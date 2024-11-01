<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webdesign-ugurcu/dhl_plugin/
 * @since      1.0.0
 *
 * @package    WU_DHL
 * @subpackage WU_DHL/includes
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    WU_DHL
 * @subpackage WU_DHL/includes
 * @author     Webdesign-UGurcu <service@webdesign-ugurcu.de>
 */
class wu_dhl_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
        
         /**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $$mode    The current Mode of this plugin.
         * 0 = Sandbox , 1 = Live
	 */
        
        private $mode ;
         /**
	 * Store the Orderdetails .
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Object    $Order    The current Orderdetail.
         * 0 = Sandbox , 1 = Live
	 */
        
         private $Order;
        
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
       
       
        
	public function __construct( $plugin_name, $version, $mode ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
               
                $this->mode = $mode;
                
	}
        
        public function meta_Boxes(){
           add_meta_box( 'WU_DHL_LABEL_FIELDS', __('WU DHL Label Manager:','woocommerce'), array($this,'DHL_add_other_fields_for_packaging'), 'shop_order', 'side', 'core' );
                
          }
        
        
       
                function DHL_add_other_fields_for_packaging($order)
                {
                    global $wpdb;
                    
                    $wu_dhl_sendungsnummer = $wpdb->get_var("Select sendnumber from ".$wpdb->prefix."wu_dhl_labels  where order_id = '".$order->ID."'");
                   
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/wu-dhl-meta-box-side.php';
                    

                }
             
        
       public function add_admin_pages(){
           add_menu_page(__('Webdesign Ugurcu DHL Manager'), 'DHL Manager', 'manage_options', 
                   'wu_dhl', array($this,'admin_index'), plugins_url('/images/menu_logo.png',__FILE__),null);
       }
        
       public function admin_index(){
           global $wpdb; 
           $data = $wpdb->get_row("Select * from ".$wpdb->prefix. "wu_dhl");
          
            //require template Load
            require_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/wu-dhl-index.php';
       }
       
       public function settings_link($links){
           
           $settings_link = '<a href="admin.php?page=wu_dhl_plugin">'.__("Settings",'wu-dhl').'</a>';
           array_push($links, $settings_link);
           return $links;
           
       }
       
       public function SaveData(){
         
          
            $this->CreateLabel();
             /* ?><script>alert("post saved");</script><?php 
              die();  */
       }
       
       public function CreateLabel(){
         
            
          if(isset($_POST['l_erstellen'])){
            global $woocommerce,$wpdb;
            $order = wc_get_order();
         
            $data = $order->get_data();
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wu-dhl-label-admin.php';
            $data_api = $wpdb->get_row("Select * from ".$wpdb->prefix. "wu_dhl");
            /* Choice to get Data from billing or Shipping  */
            $prefixArray = ($data['shipping']['first_name'] == "") ? "billing" : "shipping";
            
           $testdata =  array(
                        'company_name'  =>  $data[$prefixArray]['company'],
                        'first_name'    =>  $data[$prefixArray]['first_name'],
                        'last_name'     =>  $data[$prefixArray]['last_name'],
                        'c/o'           =>  null,
                        'street_name'   =>  $data[$prefixArray]['address_1'],
                        'street_number' =>  $data[$prefixArray]['address_2'],
                        'country'       =>  $data[$prefixArray]['country'],
                        'zip'           =>  $data[$prefixArray]['postcode'],
                        'city'          =>  $data[$prefixArray]['city'],
                        'kid'           =>  $data['customer_id'],
                        'oid'           =>  $data['id']
           );
          
         // print_r($testdata);
         // print_r($data);
           $dhl_label = new wu_dhl_label($data_api->user,$data_api->signature,$data_api->ekp,$testdata);
           $dhl_label->run();
       }
           
           
           
       }
       
       public function TestDruck(){
            if(isset($_POST['testrun'])){
                global $wpdb;
                /* load the dhl label class to print the label */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wu-dhl-label-admin.php';
            $data = $wpdb->get_row("Select * from ".$wpdb->prefix. "wu_dhl");
               /*
                * Test array to use the sample print function 
                * 
                * all data are made up and not real
                * 
                */
           $testdata =  array(
                        'company_name'  => "lidl",
                        'first_name'    => "Max",
                        'last_name'     => "Muster",
                        'c/o'           =>  null,
                        'street_name'   => "Musterstrasse",
                        'street_number' => "12",
                        'country'       => "germany",
                        'zip'           => "27607",
                        'city'          => "Geestland",
                        'kid'           => "1",
                        'oid'           => '0'
           );
          /*
           * Use the constructor to send api information and testdata to the 
           * 
           * DHL label class after thats load the Startfunction to run the label print 
           * 
           * process
           * 
           * 
           */
           $dhl_label = new wu_dhl_label($data->user,$data->signature,$data->ekp,$testdata);
           $dhl_label->run();
         //echo plugin_dir_url(__FILE__).'/labels/0.pdf';
          header("Location: ".plugin_dir_url(__DIR__).'labels/0.pdf');
       }
       
       
    }
    
    /*
     * 
     * function to register the from event that change the Mode  between sandbox and live 
     * 
     * @since       1.0.0
     */
    
        public function modeChange(){
            if(isset($_POST['modeChange'])){
               global $wpdb;
             
             $wpdb->update( 
                    $wpdb->prefix. "wu_dhl", 
                    array( 
                            'mode'      => $_POST['mode'],	// string
                           
                    ), 
                    array( 'id' => 1 ),
                  
                    array( '%d' ) 
            );
               
            
        }
    }
    /**
     * 
     * Function to register the SaveSettingsApi Form Event 
     * 
     * @since       1.0.0
     */
    
       public function saveSettingApi(){
           if(isset($_POST['api_send'])){
               global $wpdb;
             $wpdb->update( 
                    $wpdb->prefix. "wu_dhl", 
                    array( 
                            'user'      => $_POST['dhl_user'],	// string
                            'signature' => $_POST['signature'],	// string 
                            'ekp'       => $_POST['ekp']	// string  
                    ), 
                    array( 'id' => 1 ), 
                    array( 
                            '%s',	// value1
                            '%s',	// value2
                            '%s'
                    ), 
                    array( '%d' ) 
            );
               
           }
           
       }
       /**
	 * Initialize the Observer to watch  form submits
	 *
	 * @since       1.0.0
	 *
	 */
       public function Observer(){
           
                $this->saveSettingApi();
                $this->saveSettingsLabel();
                $this->TestDruck();
                $this->modeChange();
               
               
       }
       /**
     * 
     * Function to register the saveSettingsLabel Form Event 
     * 
     * @since       1.0.0
     */
       public function saveSettingsLabel(){
            if(isset($_POST['l_submit'])){
               global $wpdb;
             $wpdb->update( 
                    $wpdb->prefix. "wu_dhl", 
                    array( 
                            'company_name'      => $_POST['c_name'],	// string
                            'name'              => $_POST['name'],	// string  
                            'vorname'           => $_POST['vorname'],	// string 
                            'str'               => $_POST['str'],	// string
                            'nr'                => $_POST['nr'],	// string  
                            'plz'               => $_POST['plz'],	// string 
                            'ort'               => $_POST['ort'],	// string 
                            'homepage'          => $_POST['hp'],        // string 
                            'contact_person'    => $_POST['contact'],	// string 
                            'tel'               => $_POST['tel'],	// string 
                            'email'             => $_POST['email']         // string 
                    ), 
                    array( 'id' => 1 ), 
                    array( 
                            '%s',       // value1
                            '%s',	// value1
                            '%s',	// value2
                            '%s',	// value1
                            '%s',	// value2
                            '%s',	// value1
                            '%s',	// value2
                            '%s',	// value1
                            '%s',	// value1
                            '%s',	// value2
                            '%s'	// value1
                           
                            
                    ), 
                    array( '%d' ) 
            );
               
           }
           
       }
       
        
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wu_dhl_admin.css', array(), $this->version, 'all' );
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wu-dhl-admin.js', array( 'jquery' ), $this->version, false );
	}
        
   
}