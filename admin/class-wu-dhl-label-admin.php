<?php
class wu_dhl_label {
    /**
	 * DHL API User.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $user    DHL API User.
	 */
    public $user;
     /**
	 * DHL API User Signature.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sig    DHL API User Signature.
	 */
    public $sig;
      /**
	 * DHL API Kundennummer.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sig    DHL API User Signature.
	 */
    public $ekp;
      /**
	 * Array of receiver datas.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $receiver   receiver datas.
	 */
    public $receiver;
      /**
	 * Store the $wpdb database connection into a class variable.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      databse    $db    Databse connection.
	 */
    public $db;
    
      /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $user      DHL api user.
	 * @param      string    $sig       DHL api user signature.
         * @param      string    $ekp       dhl kundennummer.
	 * @param      array    $receiver    array of receiver datas.
	 */
       
    public function __construct( $user,  $sig,  $ekp, $receiver) {
          global $wpdb;
          $this->db = $wpdb;
        $this->user = $user;
        $this->sig = $sig;
        $this->ekp = $ekp;
        $this->receiver = $receiver;
    }
    
    
    function initSender(){
        $data = $this->db->get_row('Select * from '.$this->db->prefix.'wu_dhl where id="1"');
        /*
         * We sort the array to store it into info array to initilize the Sender information
         * 
         */
        $sortArray = array(
                'company_name'    => $data->company_name,
                'street_name'     => $data->str,
                'street_number'   => $data->nr,
                'zip'             => $data->plz,
                'country'         => "garmany",
                'city'            => $data->ort,
                'email'           => $data->email,
                'phone'           => $data->tel,
                'internet'        => $data->homepage,
                'contact_person'  => $data->contact_person
        );
                return $sortArray;
    }
    
    function run(){
      
        /*
         * This Array is use to tell the buisness shipment class the DHL API Data
         * 
         * 
         */
        $credentials = array(
	'user'                  => $this->user, 
	'signature'             => $this->sig, 
	'ekp'                   => $this->ekp."0101", // first two extra digits for process number and the second extra digit for the Participant number 
        'sandbox_user'          => '2222222222_01',
        'sandbox_signature'     => 'pass',
        'sandbox_ekp'           => '22222222220101' ,   
	'api_user'              => 'Muwu_1',
	'api_password'          => 'XXygpWFPcmjcwfvOu4pYfssOMddlG9',
        'api_user_sandbox'      => 'mugurcu',
        'api_password_sandbox' => 'Dbzdbgt1#',    
	'log' => true
    );
    

        // your company info
        $info =   $this->initSender();
        /**
	* The class to communictae with dhl server 
        * of the plugin.
	*/
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wu-dhl-buisness-shipment.php';
        $dhl = new WU_DHL_BUISNESS_SHIPMENT($credentials, $info,true);
       
        // receiver details
        $customer_details = $this->receiver;
        // Response from the dhl api server
        $response = $dhl->createNationalShipment($customer_details);
        
        if($response !== false) {
               
        // save the response pdf into plugin folder to review    
        $file =  file_get_contents($response['label_url']);
        $file_path = 'labels/'.$this->receiver['oid'].'.pdf';
        file_put_contents(plugin_dir_path( dirname( __FILE__ ) ) . $file_path, $file);
    
        //Store the Shipmentnumber to save it into the Database
        $packnumber = $response['shipnumber'];
      
          //Insert all data to the DB for further access
             $this->db->insert( 
                        $this->db->prefix . "wu_dhl_labels", 
                        array( 
                                
                                'created'       =>  date("Y-m-d h:i:S"),
                                'kid'           =>  $this->receiver['kid'],
                                'name'          =>  $this->receiver['first_name'],
                                'vorname'       =>  $this->receiver['last_name'],
                                'str'           =>  $this->receiver['street_name'],
                                'nr'            =>  $this->receiver['street_number'],
                                'plz'           =>  $this->receiver['zip'],
                                'ort'           =>  $this->receiver['city'],
                                'art'           =>  "Label",
                                'path'          =>  $file_path,
                                'sendnumber'    =>  $packnumber,
                                'order_id'           =>  $this->receiver['oid']
                                    
                        )
                );
      
        
        } else {
                // If the soap api call trigger and response and error , we show it here for the user
                var_dump($dhl->errors);

        }
            }
}