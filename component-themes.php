<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://automattic.com
 * @since             1.0.0
 * @package           Component_Themes
 *
 * @wordpress-plugin
 * Plugin Name:       Component Themes
 * Plugin URI:        https://github.com/Automattic/component-themes
 * Description:       Component-based themes for WordPress
 * Version:           1.0.0
 * Author:            Automattic
 * Author URI:        http://automattic.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       component-themes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-component-themes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_component_themes() {

	$plugin = new Component_Themes_Plugin();
	$plugin->run();

}
run_component_themes();
