<?php

/**
 * Shared functionality for all element selector implementations.
 *
 * @since 2.5.10
 */
abstract class Toolset_Association_Query_Element_Selector_Abstract
	implements IToolset_Association_Query_Element_Selector
{

	/** @var Toolset_Relationship_Database_Operations */
	protected $database_operations;


	/** @var Toolset_Relationship_Database_Unique_Table_Alias */
	protected $table_alias;


	/** @var Toolset_Association_Query_Table_Join_Manager */
	protected $join_manager;


	/** @var wpdb */
	protected $wpdb;


	/** @var Toolset_WPML_Compatibility */
	protected $wpml_service;


	/** @var IToolset_Relationship_Role[] */
	protected $requested_roles = array();


	/**
	 * Toolset_Association_Query_Element_Selector_Abstract constructor.
	 *
	 * @param Toolset_Relationship_Database_Unique_Table_Alias $table_alias
	 * @param Toolset_Association_Query_Table_Join_Manager $join_manager
	 * @param wpdb|null $wpdb_di
	 * @param Toolset_Relationship_Database_Operations|null $database_operations_di
	 * @param Toolset_WPML_Compatibility|null $wpml_compatibility_di
	 */
	public function __construct(
		Toolset_Relationship_Database_Unique_Table_Alias $table_alias,
		Toolset_Association_Query_Table_Join_Manager $join_manager,
		wpdb $wpdb_di = null,
		Toolset_Relationship_Database_Operations $database_operations_di = null,
		Toolset_WPML_Compatibility $wpml_compatibility_di = null
	) {
		$this->table_alias = $table_alias;
		$this->join_manager = $join_manager;

		$this->database_operations = ( null === $database_operations_di ? new Toolset_Relationship_Database_Operations() : $database_operations_di );
		$this->wpml_service = ( null === $wpml_compatibility_di ? Toolset_WPML_Compatibility::get_instance() : $wpml_compatibility_di );

		global $wpdb;
		$this->wpdb = ( null === $wpdb_di ? $wpdb : $wpdb_di );
	}


	/**
	 * @inheritdoc
	 */
	public function initialize() {
		// Nothing to do here.
	}


	/**
	 * Get the element ID column name of the associations table.
	 *
	 * @param IToolset_Relationship_Role $for_role
	 *
	 * @return string
	 */
	protected function get_id_column( IToolset_Relationship_Role $for_role ) {
		return $this->database_operations->role_to_column(
			$for_role, Toolset_Relationship_Database_Operations::COLUMN_ID
		);
	}


	/**
	 * @inheritdoc
	 *
	 * @param IToolset_Relationship_Role $role
	 */
	public function request_element_in_results( IToolset_Relationship_Role $role ) {
		$this->requested_roles[ $role->get_name() ] = $role;
	}


}