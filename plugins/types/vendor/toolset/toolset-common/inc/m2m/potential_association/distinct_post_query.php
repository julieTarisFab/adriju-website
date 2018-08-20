<?php

/**
 * Augments WP_Query to check whether posts are associated with a particular other element ID,
 * and dismisses those posts.
 *
 * This is used in Toolset_Potential_Association_Query_Posts to handle distinct relationships.
 *
 * Both before_query() and after_query() methods need to be called as close to the actual
 * querying as possible, otherwise things will get broken.
 *
 * @since m2m
 */
class Toolset_Relationship_Distinct_Post_Query {

	/** @var IToolset_Relationship_Definition */
	private $relationship;

	/** @var IToolset_Element */
	private $for_element;

	/** @var IToolset_Relationship_Role_Parent_Child */
	private $target_role;

	/** @var null|Toolset_Relationship_Table_Name */
	private $_table_names;

	/** @var null|wpdb */
	private $_wpdb;

	/** @var Toolset_WPML_Compatibility */
	private $wpml_service;


	/**
	 * Toolset_Relationship_Distinct_Post_Query constructor.
	 *
	 * @param IToolset_Relationship_Definition $relationship
	 * @param IToolset_Relationship_Role_Parent_Child $target_role Target role of the relationships (future role of
	 *     the posts that are being queried)
	 * @param IToolset_Element $for_element ID of the element to check against.
	 * @param Toolset_Relationship_Table_Name|null $table_names_di
	 * @param wpdb|null $wpdb_di
	 * @param Toolset_WPML_Compatibility|null $wpml_service_di
	 */
	public function __construct(
		IToolset_Relationship_Definition $relationship,
		IToolset_Relationship_Role_Parent_Child $target_role,
		IToolset_Element $for_element,
		Toolset_Relationship_Table_Name $table_names_di = null,
		wpdb $wpdb_di = null,
		Toolset_WPML_Compatibility $wpml_service_di = null
	) {
		$this->relationship = $relationship;
		$this->for_element = $for_element;
		$this->target_role = $target_role;
		$this->wpml_service = $wpml_service_di ?: Toolset_WPML_Compatibility::get_instance();

		$this->_table_names = $table_names_di;
		$this->_wpdb = $wpdb_di;
	}


	private function is_actionable() {
		return $this->relationship->is_distinct();
	}


	/**
	 * Hooks to filters in order to add extra clauses to the MySQL query.
	 */
	public function before_query() {
		if( ! $this->is_actionable() ) {
			return;
		}

		add_filter( 'posts_join', array( $this, 'add_join_clauses' ) );
		add_filter( 'posts_where', array( $this, 'add_where_clauses' ) );

		// WPML in the back-end filters strictly by the current language by default,
		// but we need it to include default language posts, too, if the translation to the current language
		// doesn't exist. This needs to behave consistently in all contexts.
		add_filter( 'wpml_should_use_display_as_translated_snippet', '__return_true' );
	}


	/**
	 * Cleanup - unhooks the filters added in before_query().
	 */
	public function after_query() {
		if( ! $this->is_actionable() ) {
			return;
		}

		remove_filter( 'posts_join', array( $this, 'add_join_clauses' ) );
		remove_filter( 'posts_where', array( $this, 'add_where_clauses' ) );

		remove_filter( 'wpml_should_use_display_as_translated_snippet', '__return_true' );
	}


	private function get_table_names() {
		if( null === $this->_table_names ) {
			$this->_table_names = new Toolset_Relationship_Table_Name();
		}

		return $this->_table_names;
	}


	private function get_wpdb() {
		if( null === $this->_wpdb ) {
			global $wpdb;
			$this->_wpdb = $wpdb;
		}

		return $this->_wpdb;
	}


	/**
	 * Add a JOIN clause to the WP_Query's MySQL query string.
	 *
	 * That will connect the row from the associations table, if there is an association
	 * with the correct relationship and the $for_element.
	 *
	 * Otherwise, those columns will be NULL, because we're doing a LEFT JOIN here.
	 *
	 * If WPML is active, we also do the same comparison for the default language version of the
	 * queried post, if it exists.
	 *
	 * @param string $join
	 *
	 * @return string
	 */
	public function add_join_clauses( $join ) {
		$association_table = $this->get_table_names()->association_table();
		$posts_table_name = $this->get_wpdb()->posts;
		$target_element_column = $this->target_role->get_name() . '_id';
		$for_element_column = $this->target_role->other() . '_id';

		$join .= $this->get_wpdb()->prepare(
			" LEFT JOIN {$association_table} AS toolset_associations ON ( 
				toolset_associations.relationship_id = %d
				AND toolset_associations.{$target_element_column} = {$posts_table_name}.ID
				AND toolset_associations.{$for_element_column} = %d    
			) ",
			$this->relationship->get_row_id(),
			$this->for_element->get_default_language_id()
		);

		if( $this->wpml_service->is_wpml_active_and_configured() ) {
			$icl_translations = $this->get_wpdb()->prefix . 'icl_translations';
			$default_language = esc_sql( $this->wpml_service->get_default_language() );
			$join .= $this->get_wpdb()->prepare(
				" LEFT JOIN {$icl_translations} AS element_lang_info ON (
					{$posts_table_name}.ID = element_lang_info.element_id
					AND element_lang_info.element_type LIKE %s
				) LEFT JOIN {$icl_translations} AS default_lang_translation ON (
					element_lang_info.trid = default_lang_translation.trid
					AND default_lang_translation.language_code = %s
				) LEFT JOIN {$association_table} AS default_lang_association ON (
					default_lang_association.relationship_id = %d
					AND default_lang_translation.element_id = default_lang_association.{$target_element_column}
					AND default_lang_association.{$for_element_column} = %d
				) ",
				'post_%',
				$default_language,
				$this->relationship->get_row_id(),
				$this->for_element->get_default_language_id()
			);
		}

		return $join;
	}


	/**
	 * Add a WHERE clause to the WP_Query's MySQL query string.
	 *
	 * After adding the JOIN, we only need to check that there's not an ID of the
	 * column with $for_element: That means there's no association between the queried
	 * post and $for_element, and we can offer the post as a result.
	 *
	 * If WPML is active, we also have to check that there's no default language translation
	 * of the queried post that would be part of such an association.
	 *
	 * @param string $where
	 *
	 * @return string
	 */
	public function add_where_clauses( $where ) {
		$for_element_column = $this->target_role->other() . '_id';
		$where .= " AND ( toolset_associations.{$for_element_column} IS NULL ) ";

		if( $this->wpml_service->is_wpml_active_and_configured() ) {
			$where .= " AND ( default_lang_association.{$for_element_column} IS NULL ) ";
		}

		return $where;
	}

}