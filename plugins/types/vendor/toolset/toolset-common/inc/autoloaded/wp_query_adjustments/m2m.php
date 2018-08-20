<?php

/**
 * Adjust the WP_Query functionality for m2m relationships.
 *
 * This assumes m2m is enabled.
 *
 * See the superclass for details.
 *
 * @since 2.6.1
 */
class Toolset_Wp_Query_Adjustments_M2m extends Toolset_Wp_Query_Adjustments {


	/** @var Toolset_Relationship_Database_Operations|null */
	private $_database_operations;


	/** @var null|Toolset_Element_Factory */
	private $_element_factory;


	/**
	 * Toolset_Wp_Query_Adjustments_M2m constructor.
	 *
	 * @param wpdb|null $wpdb_di
	 * @param Toolset_Relationship_Database_Operations|null $database_operations_di
	 * @param Toolset_Element_Factory|null $element_factory_di
	 */
	public function __construct(
		wpdb $wpdb_di = null,
		Toolset_Relationship_Database_Operations $database_operations_di = null,
		Toolset_Element_Factory $element_factory_di = null

	) {
		parent::__construct( $wpdb_di );
		$this->_database_operations = $database_operations_di;
		$this->_element_factory = $element_factory_di;
	}


	/**
	 * @inheritdoc
	 */
	public function initialize() {
		parent::initialize();

		do_action( 'toolset_do_m2m_full_init' );

		add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );
		add_filter( 'posts_join', array( $this, 'posts_join' ), 10, 2 );
	}


	/**
	 * Add conditions to the WHERE clause.
	 *
	 * @param string $where
	 * @param WP_Query $wp_query
	 *
	 * @return string
	 * @throws Toolset_Element_Exception_Element_Doesnt_Exist
	 */
	public function posts_where( $where, $wp_query ) {
		if( property_exists( $wp_query, self::RELATIONSHIP_QUERY_ARG ) ) {
			$where = $this->add_relationship_query_where( $where, $wp_query->{self::RELATIONSHIP_QUERY_ARG}, $wp_query );
		}
		return $where;
	}


	/**
	 * Add tables to the JOIN clause.
	 *
	 * @param string $join
	 * @param WP_Query $wp_query
	 *
	 * @return string
	 */
	public function posts_join( $join, $wp_query ) {
		if( property_exists( $wp_query, self::RELATIONSHIP_QUERY_ARG ) ) {
			$join = $this->add_relationship_query_join( $join, $wp_query );
		}
		return $join;
	}


	/**
	 * @param string $where
	 * @param array $relationship_query
	 * @param WP_Query $wp_query
	 *
	 * @return string
	 * @throws Toolset_Element_Exception_Element_Doesnt_Exist
	 */
	private function add_relationship_query_where( $where, $relationship_query, WP_Query $wp_query ) {
		$relationship_query = $this->normalize_relationship_query_args( $relationship_query );

		foreach( $relationship_query as $query_condition ) {
			$where .= ' ' . $this->add_relationship_query_condition( $query_condition, $wp_query );
		}

		return $where;
	}


	/**
	 * @param $query_condition
	 * @param WP_Query $wp_query
	 *
	 * @return string
	 */
	private function add_relationship_query_condition( $query_condition, WP_Query $wp_query ) {
		$relationship_slug = $this->get_relationship_slug( $query_condition );
		$related_to_post = $this->get_post( $query_condition );

		if( null === $relationship_slug || null === $related_to_post ) {
			// The relationship or the post doesn't exist but it is not a misconfiguration of the
			// wp_query argument - we just return no results.
			return ' AND 0 = 1 ';
		}

		$role_to_return = $this->get_role( $query_condition, 'role' );
		$associations_table = $this->get_table_join_manager( $wp_query )->associations_table( $relationship_slug, $role_to_return );

		$role_to_query_by = $this->get_role( $query_condition, 'role_to_query_by', $role_to_return );
		$role_to_query_by_column = $this->get_database_operations()->role_to_column( $role_to_query_by );

		$clause = $this->wpdb->prepare(
			" AND $associations_table.$role_to_query_by_column = %d ",
			$related_to_post->get_default_language_id()
		);

		return $clause;
	}


	private function add_relationship_query_join( $join, $wp_query ) {
		// Just add the tables from the JOIN manager which has been filled by data during the processing
		// of the posts_where filter (it comes before posts_join)
		return $join . ' ' . $this->get_table_join_manager( $wp_query )->get_join_clauses() . ' ';
	}


	/**
	 * Resolve the relationship slug from a given query condition.
	 *
	 * Also supports an array with a pair of post types that identify a legacy relationship.
	 *
	 * @param array $query_condition
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	private function get_relationship_slug( $query_condition ) {
		$relationship = toolset_getarr( $query_condition, 'relationship' );

		if( is_array( $relationship ) ) {
			if( count( $relationship ) !== 2 ) {
				throw new InvalidArgumentException( 'Invalid relationship query argument.' );
			}

			$relationship_definition = Toolset_Relationship_Definition_Repository::get_instance()->get_legacy_definition(
				$relationship[0], $relationship[1]
			);
		} elseif( ! is_string( $relationship ) || empty( $relationship ) ) {
			throw new InvalidArgumentException( 'Invalid relationship query argument.' );
		} else {
			$relationship_definition = Toolset_Relationship_Definition_Repository::get_instance()->get_definition( $relationship );
		}

		if( null === $relationship_definition ) {
			return null;
		}

		return $relationship_definition->get_slug();
	}


	/**
	 * Resolve the role object from the query condition.
	 *
	 * @param string[] $query_condition
	 * @param string $key Key of the element in the query condition that contains the role name.
	 * @param IToolset_Relationship_Role|null $other_role If this is provided and is a parent or child,
	 *    the role in $key can be empty/other - the opposite of $other_role will be used in that case.
	 *
	 * @return IToolset_Relationship_Role
	 */
	private function get_role( $query_condition, $key, IToolset_Relationship_Role $other_role = null ) {
		$role_name = toolset_getarr( $query_condition, $key, 'other' );

		if( 'other' === $role_name && $other_role instanceof IToolset_Relationship_Role_Parent_Child ) {
			return $other_role->other();
		}

		return Toolset_Relationship_Role::role_from_name( $role_name );
	}


	/**
	 * @return Toolset_Relationship_Database_Operations
	 */
	private function get_database_operations() {
		if( null === $this->_database_operations ) {
			$this->_database_operations = Toolset_Relationship_Database_Operations::get_instance();
		}

		return $this->_database_operations;
	}


	/**
	 * @return Toolset_Element_Factory
	 */
	private function get_element_factory() {
		if( null === $this->_element_factory ) {
			$this->_element_factory = new Toolset_Element_Factory();
		}

		return $this->_element_factory;
	}


	/**
	 * Get the "related_to" post from the query condition array.
	 *
	 * @param $query_condition
	 *
	 * @return IToolset_Post
	 */
	private function get_post( $query_condition ) {
		$related_to_post_id = toolset_getarr( $query_condition, 'related_to' );
		if( $related_to_post_id instanceof WP_Post ) {
			$related_to_post_id = $related_to_post_id->ID;
		} elseif( ! Toolset_Utils::is_natural_numeric( $related_to_post_id ) ) {
			throw new InvalidArgumentException( 'Invalid relationship query argument.' );
		} else {
			$related_to_post_id = (int) $related_to_post_id;
		}

		try {
			$post = $this->get_element_factory()->get_post( $related_to_post_id );
		} catch ( Toolset_Element_Exception_Element_Doesnt_Exist $e ) {
			return null;
		}

		return $post;
	}

}