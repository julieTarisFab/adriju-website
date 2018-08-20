<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.squareonemd.co.uk
 * @since             1.2.2
 * @package           Insta_Grab
 *
 * @wordpress-plugin
 * Plugin Name:       Insta Grabagram
 * Plugin URI:        http://www.squareonemd.co.uk/
 * Description:       This plugin grabs a tagged image from instagram via the instragram API.
<<<<<<< .mine
 * Version:           1.2.2
||||||| .r1400274
 * Version:           1.2.0
=======
 * Version:           1.2.1
>>>>>>> .r1810498
 * Author:            Elliott Richmond
 * Author URI:        http://www.squareonemd.co.uk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       insta-grab
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-insta-grab-activator.php
 */
function activate_insta_grab() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-insta-grab-activator.php';
	Insta_Grab_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-insta-grab-deactivator.php
 */
function deactivate_insta_grab() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-insta-grab-deactivator.php';
	Insta_Grab_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_insta_grab' );
register_deactivation_hook( __FILE__, 'deactivate_insta_grab' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-insta-grab.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.9
 */
function run_insta_grab() {

	$plugin = new Insta_Grab();
	$plugin->run();

}
run_insta_grab();
