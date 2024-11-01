<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.webdesign-ugurcu/dhl_plugin/
 * @since      1.0.0
 *
 * @package    WU_DHL
 * @subpackage WU_DHL/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WU_DHL
 * @subpackage WU_DHL/includes
 * @author     Webdesign-UGurcu <service@webdesign-ugurcu.de>
 */
class WU_DHL_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
            self::jal_install();
	}
        function jal_install () {
            global $wpdb;
          
            $wu_dhl_table_name = $wpdb->prefix . "wu_dhl_labels"; 
            $wu_dhl_table_name_2 = $wpdb->prefix . "wu_dhl"; 
            $charset_collate = $wpdb->get_charset_collate();

            $wu_dhl_sql = "CREATE TABLE $wu_dhl_table_name (
              id        mediumint(9) NOT NULL AUTO_INCREMENT,
              created    datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
              kid       int(10) NOT NULL,
              name      varchar(55) NOT NULL,
              vorname   varchar(55) NOT NULL,
              str       varchar(100) NOT NULL,
              nr        varchar(55) NOT NULL,
              plz       int(5) NOT NULL,
              ort       varchar(100) NOT NULL,
              art       int(5)  NOT NULL,
              path      varchar(255) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $wu_dhl_sql );
            $wu_dhl_sql_1 = "CREATE TABLE $wu_dhl_table_name_2 (
              id                mediumint(9) NOT NULL AUTO_INCREMENT,
              company_name      varchar(55) NOT NULL,
              name              varchar(55) NOT NULL,
              vorname           varchar(55) NOT NULL,
              str               varchar(100) NOT NULL,
              nr                varchar(55) NOT NULL,
              plz               int(5) NOT NULL,
              ort               varchar(100) NOT NULL,
              homepage          text  NOT NULL,
              user              varchar(55) NOT NULL,
              signature         varchar(100) NOT NULL,
              ekp               varchar(100) NOT NULL,
              mode              int(1)  NOT NULL,
              contact_person    varchar(55) NOT NULL,
              email             varchar(100) NOT NULL,
              tel               varchar(100) NOT NULL
              PRIMARY KEY  (id)
            ) $charset_collate;";
             
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $wu_dhl_sql_1 );
            
             $wpdb->insert( 
                        $wpdb->prefix . "wu_dhl", 
                        array( 
                                
                                'id' => 0 
                        ), 
                        array( 
                                
                                '%d' 
                        ) 
                );
            
        }
}