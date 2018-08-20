<?php

/**
 * Handles the persistence of associations, from IToolset_Association object
 * to a wpdb call and back.
 *
 * Like Toolset_Relationship_Definition_Persistence, this should not be used from outside
 * of the m2m API. Everything required for working with associations should be
 * implemented on IToolset_Relationship_Definition.
 *
 * @since 2.5.8
 */
class Toolset_Association_Persistence {


	/** @var Toolset_Association_Factory */
	private $association_factory;


	/** @var Toolset_Relationship_Table_Name */
	private $table_name;


	/** @var wpdb */
	private $wpdb;


	/** @var Toolset_Association_Translator */
	private $association_translator;


	/** @var null|Toolset_Association_Cleanup_Factory */
	protected $_cleanup_factory;



	/**
	 * Toolset_Association_Persistence constructor.
	 *
	 * @param Toolset_Association_Factory|null $association_factory_di
	 * @param Toolset_Relationship_Table_Name|null $table_name_di
	 * @param wpdb|null $wpdb_di
	 * @param Toolset_Association_Translator|null $association_translator_di
	 * @param Toolset_Association_Cleanup_Factory|null $cleanup_factory_di
	 */
	public function __construct(
		Toolset_Association_Factory $association_factory_di = null,
		Toolset_Relationship_Table_Name $table_name_di = null,
		wpdb $wpdb_di = null,
		Toolset_Association_Translator $association_translator_di = null,
		Toolset_Association_Cleanup_Factory $cleanup_factory_di = null
	) {
		$this->association_factory = ( null === $association_factory_di ? new Toolset_Association_Factory() : $association_factory_di );
		$this->table_name = ( null === $table_name_di ? new Toolset_Relationship_Table_Name() : $table_name_di );
		$this->association_translator = ( null === $association_translator_di ? new Toolset_Association_Translator() : $association_translator_di );
		global $wpdb;
		$this->wpdb = ( null === $wpdb_di ? $wpdb : $wpdb_di );
		$this->_cleanup_factory = $cleanup_factory_di;
	}


	/**
	 * Load a native association from the database.
	 *
	 * @param int $association_uid Association UID.
	 *
	 * @return null|IToolset_Association The association instance
	 *     or null if it couln't have been loaded.
	 * @deprecated Do not use this outside of the m2m API, instead, use the association query.
	 */
	public function load_association_by_uid( $association_uid ) {
		$associations_table = $this->table_name->association_table();

		$query = $this->wpdb->prepare(
			"SELECT * FROM {$associations_table} WHERE trid = %d",
			$association_uid
		);

		$row = $this->wpdb->get_row( $query );

		if ( ! $row ) {
			return null;
		}

		$relationship = Toolset_Relationship_Definition_Repository::get_instance()
			->get_definition_by_row_id( $row->relationship_id );

		if ( null === $relationship ) {
			return null;
		}

		try {
			return $this->association_factory->create(
				$relationship,
				(int) $row->parent_id,
				(int) $row->child_id,
				(int) $row->intermediary_id,
				(int) $row->id
			);
		} catch( Exception $e ) {
			return null;
		}
	}


	/**
	 * Insert a new association in the database.
	 *
	 * @param IToolset_Association $association
	 *
	 * @return IToolset_Association
	 * @throws Toolset_Element_Exception_Element_Doesnt_Exist
	 */
	public function insert_association( IToolset_Association $association ) {
		$row = $this->association_translator->to_database_row( $association );

		$this->wpdb->insert(
			$this->table_name->association_table(),
			$row,
			$this->association_translator->get_database_row_formats()
		);

		$row['id'] = $this->wpdb->insert_id;

		$updated_association = $this->association_translator->from_database_row( (object) $row );

		return $updated_association;
	}


	/**
	 * Delete an association from the database.
	 *
	 * Also delete an intermediary post if it exists.
	 *
	 * @param IToolset_Association $association
	 *
	 * @return Toolset_Result
	 * @since m2m
	 */
	public function delete_association( IToolset_Association $association ) {
		$cleanup = $this->get_cleanup_factory()->association();
		return $cleanup->delete( $association );
	}


	/**
	 * @return Toolset_Association_Cleanup_Factory
	 */
	private function get_cleanup_factory() {
		if( null === $this->_cleanup_factory ) {
			$this->_cleanup_factory = new Toolset_Association_Cleanup_Factory();
		}

		return $this->_cleanup_factory;
	}



}