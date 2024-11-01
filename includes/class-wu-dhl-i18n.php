<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.webdesign-ugurcu/dhl_plugin/
 * @since      1.0.0
 *
 * @package    WU_DHL
 * @subpackage WU_DHL/includes
 
 */
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WU_DHL
 * @subpackage WU_DHL/includes
 * @author     Webdesign-UGurcu <service@webdesign-ugurcu.de>
 */
class WU_DHL_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
         	load_plugin_textdomain(
			'wu-dhl',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
              
             
               
	}
}

