<?php

/*
Plugin Name: Webdesign-Ugurcu DHL Manager
Description: Plugin to create DHL Labels and Retoure Labels
Version:     1.0.2
Author:      Webdesign Ugurcu
Author URI:  https//webdesing-ugurcu.de
Text Domain: wu-dhl
Domain Path: /language
License:     GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 
WU_DHL is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WU_DHL is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WU_DHL. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WU_DHL_VERSION', '1.0.0' );
define( 'WU_DHL_PLUGIN_NAME', plugin_basename( __DIR__ ));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_wu_dhl() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wu-dhl-activator.php';
	wu_dhl_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_wu_dhle() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wu-dhl-deactivator.php';
	wu_dhl_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'activate_wu_dhl' );
register_deactivation_hook( __FILE__, 'deactivate_wu_dhle' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wu-dhl.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wu_dhl() {
        if(class_exists('WU_DHL')){
	$wu_dhl = new WU_DHL();
	$wu_dhl->run();
        }
}
run_wu_dhl();