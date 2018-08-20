<?php

/**
 * Collect the JOINed tables in Toolset_Wp_Query_Adjustments_M2m and generate the JOIN clause.
 *
 * @since 2.6.1
 */
class Toolset_Wp_Query_Adjustments_Table_Join_Manager extends Toolset_Wpdb_User {


	/**
	 * @var string[][] Unique aliases for the associations table, indexed by a relationship slug and a role name.
	 */
	private $joins = array();


	/** @var Toolset_Relationship_Table_Name */
	private $table_name;


	/** @var Toolset_Relationship_Database_Unique_Table_Alias */
	private $uniqe_table_alias;


	/** @var Toolset_Relationship_Database_Operations */
	private $database_operations;


	/** @var Toolset_Relationship_Definition_Repository */
	private $definition_repository;


	/** @var Toolset_WPML_Compatibility */
	private $wpml_service;


	/**
	 * Toolset_Wp_Query_Adjustments_Table_Join_Manager constructor.
	 *
	 * @param wpdb|null $wpdb_di
	 * @param Toolset_Relationship_Database_Unique_Table_Alias|null $unique_table_alias_di
	 * @param Toolset_Relationship_Table_Name|null $table_name_di
	 * @param Toolset_Relationship_Database_Operations|null $database_operations_di
	 * @param Toolset_Relationship_Definition_Repository|null $definition_repository_di
	 * @param Toolset_WPML_Compatibility|null $wpml_service_di
	 */
	public function __construct(
		wpdb $wpdb_di = null,
		Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias_di = null,
		Toolset_Relationship_Table_Name $table_name_di = null,
		Toolset_Relationship_Database_Operations $database_operations_di = null,
		Toolset_Relationship_Definition_Repository $definition_repository_di = null,
		Toolset_WPML_Compatibility $wpml_service_di = null
	) {
		parent::__construct( $wpdb_di );
		$this->uniqe_table_alias = $unique_table_alias_di ?: new Toolset_Relationship_Database_Unique_Table_Alias();
		$this->table_name = $table_name_di ?: new Toolset_Relationship_Table_Name();
		$this->database_operations = $database_operations_di ?: Toolset_Relationship_Database_Operations::get_instance();
		$this->definition_repository = $definition_repository_di ?: Toolset_Relationship_Definition_Repository::get_instance();
		$this->wpml_service = $wpml_service_di ?: Toolset_WPML_Compatibility::get_instance();
	}


	/**
	 * Generate the JOIN clause based on previously made requests for table aliases.
	 *
	 * @return string
	 */
	public function get_join_clauses() {
		$results = '';

		foreach( $this->joins as $relationship_slug => $role_joins ) {
			foreach( $role_joins as $role_name => $table_alias ) {
				$results .= $this->get_single_join_clause( $relationship_slug, $table_alias, $role_name );
			}
		}

		return $results;
	}


	private function get_single_join_clause( $relationship_slug, $associations_table_alias, $role_name ) {

		$element_id_column = $this->database_operations->role_to_column( $role_name );
		$relationship_definition = $this->definition_repository->get_definition( $relationship_slug );

		if( null === $relationship_definition ) {
			// This should have failed already during the WHERE clause processing and never get to this point.
			throw new InvalidArgumentException( 'Unknown relationship "' . sanitize_text_field( $relationship_slug ) . '".' );
		}

		$relationship_id = $relationship_definition->get_row_id();

		if( $this->wpml_service->is_wpml_active_and_configured() ) {
			return $this->get_single_join_clause_for_wpml( $relationship_id, $associations_table_alias, $element_id_column );
		}

		return $this->wpdb->prepare(
			"JOIN {$this->table_name->association_table()} AS {$associations_table_alias} ON (
				wp_posts.ID = {$associations_table_alias}.{$element_id_column}
				AND {$associations_table_alias}.relationship_id = %d
			) ",
			$relationship_id
		);
	}


	/**
	 * Join the associations table by either using the wp_posts.ID directly or by
	 * translating it to the default language first.
	 *
	 * @param int $relationship_id
	 * @param string $associations_table_alias
	 * @param string $element_id_column
	 *
	 * @return string
	 */
	private function get_single_join_clause_for_wpml( $relationship_id, $associations_table_alias, $element_id_column ) {

		$clause = $this->wpdb->prepare(
			"
			# join the icl_translations table independently from WPML's 't'
			# because that one may not be joined at all time, but we 
			# need it always - this is safer than trying to reuse the 't' one
			LEFT JOIN {$this->wpml_service->icl_translations_table_name()} AS toolset_post_t ON (
				wp_posts.ID = toolset_post_t.element_id
				AND toolset_post_t.element_type = CONCAT('post_', wp_posts.post_type)
			) LEFT JOIN {$this->wpml_service->icl_translations_table_name()} AS toolset_post_dl ON (
			    toolset_post_t.trid = toolset_post_dl.trid
			    AND toolset_post_dl.language_code = %s
			) JOIN {$this->table_name->association_table()} AS {$associations_table_alias} ON (
				(
					# join the association row if either the post ID matches the
					# proper column in the associations table or if the ID of the default
					# language version of the post matches it
					wp_posts.ID = {$associations_table_alias}.{$element_id_column}
					OR toolset_post_dl.element_id = {$associations_table_alias}.{$element_id_column}
				)
				AND {$associations_table_alias}.relationship_id = %d
			)",
			$this->wpml_service->get_default_language(),
			$relationship_id
		);

		return $clause;
	}


	/**
	 * Request an alias for the associations table.
	 *
	 * The table will be JOINed on wp_posts.ID by a given relationship slug and element role.
	 *
	 * @param string $relationship_slug
	 * @param IToolset_Relationship_Role $role
	 *
	 * @return string
	 */
	public function associations_table( $relationship_slug, IToolset_Relationship_Role $role ) {
		if( ! array_key_exists( $relationship_slug, $this->joins ) ) {
			$this->joins[ $relationship_slug ] = array();
		}

		if( ! array_key_exists( $role->get_name(), $this->joins[ $relationship_slug ] ) ) {
			$unique_alias = $this->uniqe_table_alias->generate( $this->table_name->association_table(), true );
			$this->joins[ $relationship_slug ][ $role->get_name() ] = $unique_alias;
		}

		return $this->joins[ $relationship_slug ][ $role->get_name() ];
	}

}