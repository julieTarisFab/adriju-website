<?php

/**
 * Public-facing m2m API.
 *
 * Note: This file is included only when m2m is active, so there's no point in checking that anymore.
 */

/**
 * Query related post if many-to-many relationship functionality is enabled.
 *
 * @param int|\WP_Post $query_by_element Post to query by. All results will be posts connected to this one.
 * @param string|string[] $relationship Slug of the relationship to query by or an array with the parent and the child post type.
 *     The array variant can be used only to identify relationships that have been migrated from the legacy implementation.
 * @param string $query_by_role_name Name of the element role to query by. Accepted values: 'parent'|'child'|'intermediary'
 * @param int $limit Maximum number of returned results ("posts per page").
 * @param int $offset Result offset ("page number")
 * @param array $args Additional query arguments. Accepted arguments:
 *      - meta_key, meta_value and meta_compare: Works exactly like in WP_Query. Only limited values are supported for meta_compare ('='|'LIKE').
 *      - s: Text search in the posts.
 * @param string $return Determines return type. 'post_id' for array of post IDs, 'post_object' for an array of \WP_Post objects.
 * @param string $role_name_to_return Which posts from the relationship should be returned. Accepted values
 *     are 'parent'|'child'|'intermediary', but the value must be different from $query_by_role_name.
 *     If $query_by_role_name is 'parent' or 'child', it is also possible to pass 'other' here.
 * @param null|string $orderby Determine how the results will be ordered. Accepted values: null, 'title', 'meta_value',
 *     'meta_value_num'. If the latter two are used, there also needs to be a 'meta_key' argument in $args.
 *     Passing null means no ordering.
 * @param string $order Accepted values: 'ASC' or 'DESC'.
 * @param bool $need_found_rows Signal if the query should also determine the total number of results (disregarding pagination).
 * @param null|&int $found_rows If $need_found_rows is set to true, the total number of results will be set
 *     into the variable passed to this parameter.
 *
 * @return int[]|\WP_Post[]
 */
function toolset_get_related_posts(
	$query_by_element,
	$relationship,
	$query_by_role_name,
	$limit = 100,
	$offset = 0,
	$args = array(),
	$return = 'post_id',
	$role_name_to_return = 'other',
	$orderby = null,
	$order = 'ASC',
	$need_found_rows = false,
	&$found_rows = null
) {
	do_action( 'toolset_do_m2m_full_init' );

	// Input validation
	//
	//
	if( ! is_string( $relationship ) && ! ( is_array( $relationship ) && count( $relationship ) === 2 ) ) {
		throw new \InvalidArgumentException( 'The relationship must be a string with the relationship slug or an array with two post types.' );
	}

	if( ! in_array( $query_by_role_name, \Toolset_Relationship_Role::all_role_names() ) ) {
		throw new \InvalidArgumentException( 'The role name to query by is not valid. Allowed values are: "' . implode( '", "', \Toolset_Relationship_Role::all_role_names() ) . '".' );
	}

	if(
		! in_array( $role_name_to_return, \Toolset_Relationship_Role::all_role_names() )
		&& ( 'other' !== $role_name_to_return || \Toolset_Relationship_Role::INTERMEDIARY === $query_by_role_name )
	) {
		throw new \InvalidArgumentException(
			'The role name to return is not valid. Allowed values are: "' .
			implode( '", "', \Toolset_Relationship_Role::all_role_names() ) .
			'" or "other" if $query_by_role_name is parent or child.'
		);
	}

	if( ! \Toolset_Utils::is_natural_numeric( $query_by_element ) && ! $query_by_element instanceof \WP_Post ) {
		throw new \InvalidArgumentException( 'The provided argument for a related element must be either an ID or a WP_Post object.' );
	}

	if( ! \Toolset_Utils::is_natural_numeric( $limit ) || ! \Toolset_Utils::is_nonnegative_numeric( $offset ) ) {
		throw new \InvalidArgumentException( 'Limit and offset must be non-negative integers.' );
	}

	if( ! in_array( $return , array( 'post_id', 'post_object' ) ) ) {
		throw new \InvalidArgumentException( 'The provided argument for a return type must be either "post_id" or "post_object".' );
	}

	if( 'meta_key' === $orderby && ! array_key_exists( 'meta_key', $args ) ) {
		throw new \InvalidArgumentException( 'Cannot use ordering by a meta_key if no meta_key argument is provided.' );
	}

	if( ! in_array( strtoupper( $order ), array( 'ASC', 'DESC' ) ) ) {
		throw new \InvalidArgumentException( 'Allowed order values are only ASC and DESC.' );
	}

	// Input post-processing
	//
	//
	$element_id = (int) ( $query_by_element instanceof \WP_Post ? $query_by_element->ID : $query_by_element );
	$limit = (int) $limit;
	$offset = (int) $offset;
	$query_by_role = \Toolset_Relationship_Role::role_from_name( $query_by_role_name );
	$need_found_rows = (bool) $need_found_rows;
	$search = toolset_getarr( $args, 's' );
	$has_meta_condition = ( array_key_exists( 'meta_key', $args ) && array_key_exists( 'meta_value', $args ) );

	if( 'other' === $role_name_to_return ) {
		// This will happen only if the $query_by_role not intermediary.
		/** @var \IToolset_Relationship_Role_Parent_Child $query_by_role */
		$role_to_return = $query_by_role->other();
	} else {
		$role_to_return = \Toolset_Relationship_Role::role_from_name( $role_name_to_return );
	}

	if( is_array( $relationship ) ) {
		$definition_repository = Toolset_Relationship_Definition_Repository::get_instance();
		$relationship_definition = $definition_repository->get_legacy_definition( $relationship[0], $relationship[1] );
		if( null === $relationship_definition ) {
			//throw new \InvalidArgumentException( 'There is no relationship between the two provided post types (no migrated one from the legacy implementation).' );
			return array();
		}
		$relationship = $relationship_definition->get_slug();
	}

	// Build the query
	//
	//
	try {
		$query = new \Toolset_Association_Query_V2();

		$query->add( $query->relationship_slug( $relationship ) )
			->add( $query->element_id_and_domain( $element_id, \Toolset_Element_Domain::POSTS, $query_by_role ) )
			->limit( $limit )
			->offset( $offset )
			->order( $order )
			->need_found_rows( $need_found_rows );

		if ( ! empty( $search ) ) {
			$query->add( $query->search( $search, $role_to_return ) );
		}

		if ( $has_meta_condition ) {
			$query->add(
				$query->meta(
					toolset_getarr( $args, 'meta_key' ),
					toolset_getarr( $args, 'meta_value' ),
					\Toolset_Element_Domain::POSTS,
					$role_to_return,
					toolset_getarr( $args, 'meta_compare', \Toolset_Query_Comparison_Operator::EQUALS )
				)
			);
		}

		if ( 'post_id' === $return ) {
			$query->return_element_ids( $role_to_return );
		} else {
			$query->return_element_instances( $role_to_return );
		}

		switch ( $orderby ) {
			case 'title':
				$query->order_by_title( $role_to_return );
				break;
			case 'meta_value':
				$query->order_by_meta( toolset_getarr( $args, 'meta_key' ), \Toolset_Element_Domain::POSTS, $role_to_return );
				break;
			case 'meta_value_num':
				$query->order_by_meta( toolset_getarr( $args, 'meta_key' ), \Toolset_Element_Domain::POSTS, $role_to_return, true );
				break;
			default:
				$query->dont_order();
				break;
		}

		// Get results and post-process them
		//
		//
		$results = $query->get_results();

		if ( $need_found_rows ) {
			$found_rows = $query->get_found_rows();
		}

		if ( 'post_id' === $return ) {
			return $results;
		} else {
			$results = array_map(
				function ( $result ) {
					/** @var \IToolset_Post $result */
					return $result->get_underlying_object();
				}, $results
			);

			return $results;
		}
	} catch ( Exception $e ) {
		// This is most probably caused by an element not existing, an exception raised from the depth of
		// the association query - otherwise, there are no reasons for it to fail, all the inputs should be valid.
		return array();
	}
}


/**
 * Retrieve an ID of a single related post.
 *
 * Note: For more complex cases, use toolset_get_related_posts().
 *
 * @param WP_Post|int $post Post whose related post should be returned.
 * @param string|string[] $relationship Slug of the relationship to query by or an array with the parent and the child post type.
 *     The array variant can be used only to identify relationships that have been migrated from the legacy implementation.
 * @param string $role_name_to_return Which posts from the relationship should be returned. Accepted values
 *     are 'parent' and 'child'. The relationship needs to have only one possible result in this role,
 *     otherwise an exception will be thrown.
 *
 * @return int Post ID or zero if no related post was found.
 */
function toolset_get_related_post( $post, $relationship, $role_name_to_return = 'parent' ) {

	do_action( 'toolset_do_m2m_full_init' );

	// Input validation and pre-processing
	//
	//
	if( ! is_string( $relationship ) && ! ( is_array( $relationship ) && count( $relationship ) === 2 ) ) {
		throw new \InvalidArgumentException( 'The relationship must be a string with the relationship slug or an array with two post types.' );
	}

	$post = get_post( $post );

	if( ! $post instanceof WP_Post ) {
		return 0;
	}

	$definition_repository = Toolset_Relationship_Definition_Repository::get_instance();

	if( is_array( $relationship ) ) {
		$relationship_definition = $definition_repository->get_legacy_definition( $relationship[0], $relationship[1] );
	} else {
		$relationship_definition = $definition_repository->get_definition( $relationship );
	}

	if( null === $relationship_definition ) {
		return 0;
	}

	if( $relationship_definition->get_cardinality()->get_limit( $role_name_to_return ) > Toolset_Relationship_Cardinality::ONE_ELEMENT ) {
		return 0;
	}

	if( ! in_array( $role_name_to_return, \Toolset_Relationship_Role::parent_child_role_names() ) ) {
		throw new \InvalidArgumentException(
			'The role name to return is not valid. Allowed values are: "' .
			implode( '", "', \Toolset_Relationship_Role::parent_child_role_names() ) .
			'".'
		);
	}

	/** @var IToolset_Relationship_Role_Parent_Child $role_to_return */
	$role_to_return = \Toolset_Relationship_Role::role_from_name( $role_name_to_return );

	// Query the single result
	//
	//

	try {
		$query = new Toolset_Association_Query_V2();

		$results = $query->add( $query->relationship( $relationship_definition ) )
			->add( $query->element_id_and_domain( $post->ID, Toolset_Element_Domain::POSTS, $role_to_return->other() ) )
			->limit( 1 )
			->return_element_ids( $role_to_return )
			->get_results();

	} catch ( Exception $e ) {
		// This is most probably caused by an element not existing, an exception raised from the depth of
		// the association query - otherwise, there are no reasons for it to fail, all the inputs should be valid.
		return 0;
	}

	if( empty( $results ) ) {
		return 0; // No result.
	}

	$result = (int) array_pop( $results );

	return $result;
}


/**
 * Retrieve an ID of the parent post, using a legacy post relationship (migrated from the legacy implementation).
 *
 * For this to work, there needs to be a relationship between $target_type and the provided post's type.
 *
 * Note: For more complex cases, use toolset_get_related_post() or toolset_get_related_posts().
 *
 * @param WP_Post|int $post Post whose parent should be returned.
 * @param string $target_type Parent post type.
 *
 * @return int Post ID or zero if no related post was found.
 */
function toolset_get_parent_post_by_type( $post, $target_type ) {

	$post = get_post( $post );

	if( ! $post instanceof WP_Post ) {
		return 0;
	}

	return toolset_get_related_post( $post, array( $target_type, $post->post_type ) );
}