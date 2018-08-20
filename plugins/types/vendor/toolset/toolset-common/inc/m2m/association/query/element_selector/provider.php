<?php

/**
 * Provider for the element selector.
 *
 * It creates the correct one depending on the state of WPML and the current language
 * and then keeps providing the same instance every time.
 *
 * Together with the restriction that condition classes must not use the element selector
 * in their constructor, this allows us to inject this dependency to query conditions
 * but wait until all conditions are instantiated before we decide which element selector
 * to actually use.
 *
 * @since 2.5.10
 */
class Toolset_Association_Query_Element_Selector_Provider {


	const FILTER_WPML_SELECTOR = 'toolset_association_query_use_wpml_element_selector';


	/** @var Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured */
	private $is_wpml_active;


	/** @var Toolset_Condition_Plugin_Wpml_Is_Current_Language_Default */
	private $is_current_language_default;


	/** @var IToolset_Association_Query_Element_Selector|null */
	private $selector;


	/**
	 * Toolset_Association_Query_Element_Selector_Provider constructor.
	 *
	 * @param Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured|null $is_wpml_active_di
	 * @param Toolset_Condition_Plugin_Wpml_Is_Current_Language_Default|null $is_current_language_default_di
	 */
	public function __construct(
		Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured $is_wpml_active_di = null,
		Toolset_Condition_Plugin_Wpml_Is_Current_Language_Default $is_current_language_default_di = null
	) {
		$this->is_wpml_active = ( null === $is_wpml_active_di ? new Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured() : $is_wpml_active_di );
		$this->is_current_language_default = ( null === $is_current_language_default_di ? new Toolset_Condition_Plugin_Wpml_Is_Current_Language_Default() : $is_current_language_default_di );
	}


	/**
	 * Get the selector instance once it has been created.
	 *
	 * @return IToolset_Association_Query_Element_Selector|null
	 */
	public function get_selector() {
		return $this->selector;
	}


	/**
	 * Create an appropriate element selector.
	 *
	 * This can be called only once.
	 *
	 * @param Toolset_Relationship_Database_Unique_Table_Alias $table_alias
	 * @param Toolset_Association_Query_Table_Join_Manager $join_manager
	 * @param Toolset_Association_Query_V2 $query
	 *
	 * @return IToolset_Association_Query_Element_Selector
	 *
	 * @throws InvalidArgumentException
	 */
	public function create_selector(
		Toolset_Relationship_Database_Unique_Table_Alias $table_alias,
		Toolset_Association_Query_Table_Join_Manager $join_manager,
		Toolset_Association_Query_V2 $query
	) {
		if( null !== $this->selector ) {
			throw new RuntimeException( 'Element selector for the association query has already been created.' );
		}

		$this->selector = $this->instantiate_selector( $table_alias, $join_manager, $query );

		return $this->selector;
	}


	/**
	 * @param Toolset_Relationship_Database_Unique_Table_Alias $table_alias
	 * @param Toolset_Association_Query_Table_Join_Manager $join_manager
	 * @param Toolset_Association_Query_V2 $query
	 * @return IToolset_Association_Query_Element_Selector
	 */
	private function instantiate_selector(
		Toolset_Relationship_Database_Unique_Table_Alias $table_alias,
		Toolset_Association_Query_Table_Join_Manager $join_manager,
		Toolset_Association_Query_V2 $query
	) {
		if(
			$this->is_wpml_active->is_met()
			&& ! $this->is_current_language_default->is_met()
		) {
			$use_wpml_selector = apply_filters( self::FILTER_WPML_SELECTOR, true, $query );

			if( $use_wpml_selector ) {
				return new Toolset_Association_Query_Element_Selector_Wpml( $table_alias, $join_manager );
			}
		}

		return new Toolset_Association_Query_Element_Selector_Default( $table_alias, $join_manager );
	}


}