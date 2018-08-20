<?php

/**
 * Repository for templates in Toolset Common.
 *
 * See Toolset_Renderer for a detailed usage instructions.
 *
 * @since 2.5.9
 */
class Toolset_Output_Template_Repository extends Toolset_Output_Template_Repository_Abstract {

	// Names of the templates go here and to $templates
	//
	//

	const FAUX_TEMPLATE = 'faux_template.twig';


	/**
	 * @var array Template definitions.
	 */
	private $templates = array(
		self::FAUX_TEMPLATE => array(
			'base_path' => null,
			'namespaces' => array()
		)
	);


	/** @var Toolset_Output_Template_Repository */
	private static $instance;


	/**
	 * @return Toolset_Output_Template_Repository
	 */
	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @inheritdoc
	 * @return string
	 */
	protected function get_default_base_path() {
		return $this->constants->constant( 'TOOLSET_COMMON_PATH' ) . '/utility/gui-base/twig-templates';
	}


	/**
	 * Get the array with template definitions.
	 *
	 * @return array
	 */
	protected function get_templates() {
		return $this->templates;
	}
}