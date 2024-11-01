<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
        define( 'WU_DHL_API_URL', plugin_dir_url(__file__).'../admin/assets/wsdl/geschaeftskundenversand-api-2.2.wsdl' );
        define( 'WU_DHL_DHL_SANDBOX_URL', 'https://cig.dhl.de/services/sandbox/soap' );
        define( 'WU_DHL_DHL_PRODUCTION_URL', 'https://cig.dhl.de/services/production/soap' );
class WU_DHL_BUISNESS_SHIPMENT {
        
	private $credentials;
	private $info;
	private $client;
	public $errors;
	protected $sandbox;
	/**
	 * Constructor for Shipment SDK
	 *
	 * @param type $api_credentials
	 * @param type $customer_info
	 * @param boolean $sandbox use sandbox or production environment
	 */
	function __construct( $api_credentials, $customer_info, $sandbox = FALSE ) {
		$this->credentials = $api_credentials;
		$this->info        = $customer_info;
		$this->sandbox = $sandbox;
                
		$this->errors = array();
	}
	private function log( $message ) {
		if ( isset( $this->credentials['log'] ) ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
	private function buildClient() {
		$header = $this->buildAuthHeader();
		if ($this->sandbox) {
			$location = WU_DHL_DHL_SANDBOX_URL;
		} else {
			$location = WU_DHL_DHL_PRODUCTION_URL;
		}
		$auth_params = array(
			'login'    => ($this->sandbox == true) ? $this->credentials['api_user_sandbox'] :  $this->credentials['api_user'],
			'password' =>  ($this->sandbox == true) ? $this->credentials['api_password_sandbox']  : $this->credentials['api_password'],
			'location' => $location,
			'trace'    => 1,
                        'stream_context' => $context,
                        'cache_wsdl' => WSDL_CACHE_NONE
		);
                
		$this->log( $auth_params );
                $opts = array(
                    'http'=>array(
                        'user_agent' => 'PHPSoapClient'
                        )
                    );
 
                $context = stream_context_create($opts);
		$this->client = new SoapClient( WU_DHL_API_URL, $auth_params );
		$this->client->__setSoapHeaders( $header );
              
		$this->log( $this->client );
	}
	function createNationalShipment( $customer_details, $shipment_details = null ) {
		$this->buildClient();
             
		$shipment = array();
		// Version
		$shipment['Version'] = array( 'majorRelease' => '2', 'minorRelease' => '0' );
		// Order
		$shipment['ShipmentOrder'] = array();
		// Fixme
		$shipment['ShipmentOrder']['sequenceNumber'] = '1';
		// Shipment
		$s                 = array();
		$s['product']  = 'V01PAK';
		$s['shipmentDate'] = date( 'Y-m-d' );
		$s['accountNumber']          = ($this->sandbox) ? $this->credentials['sandbox_ekp'] :$this->credentials['ekp'];
		$s['Attendance']              = array();
		$s['Attendance']['partnerID'] = '01';
		if ( $shipment_details == null ) {
			$s['ShipmentItem']               = array();
			$s['ShipmentItem']['weightInKG'] = "2";
			$s['ShipmentItem']['lengthInCM'] = "2";
			$s['ShipmentItem']['widthInCM']  = "2";
			$s['ShipmentItem']['heightInCM'] = "2";
			// FIXME: What is this
			$s['ShipmentItem']['PackageType'] = 'PL';
		}
		$shipment['ShipmentOrder']['Shipment']['ShipmentDetails'] = $s;
		$shipper                                = array();
		$shipper['Company']                     = array();
		$shipper['Company']['Company']          = array();
                $shipper['Name']                        = array();
		$shipper['Company']['Company']['name1'] = $this->info['company_name'];
                $shipper['Name']['name1']               = $this->info['company_name'];
		$shipper['Address']                                                = array();
		$shipper['Address']['streetName']                                  = $this->info['street_name'];
		$shipper['Address']['streetNumber']                                = $this->info['street_number'];
		$shipper['Address']['zip']                                         = $this->info['zip'];
//		$shipper['Address']['zip'][ strtolower( $this->info['country'] ) ] = $this->info['zip'];
		$shipper['Address']['city']                                        = $this->info['city'];
		$shipper['Address']['Origin'] = array( 'countryISOCode' => 'DE' );
		$shipper['Communication']                  = array();
		$shipper['Communication']['email']         = $this->info['email'];
		$shipper['Communication']['phone']         = $this->info['phone'];
		$shipper['Communication']['internet']      = $this->info['internet'];
		$shipper['Communication']['contactPerson'] = $this->info['contact_person'];
		$shipment['ShipmentOrder']['Shipment']['Shipper'] = $shipper;
		$receiver = array();
                
		$receiver['Company']                        = array();
		$receiver['Company']['Person']              = array();
                $shipper['Company']['Company']          = array();
                $shipper['Name']                        = array();
                
                $receiver['name1']                          = $customer_details['first_name'] . ' ' .$customer_details['last_name'];
		$receiver['Company']['Person']['firstname'] = $customer_details['first_name'];
		$receiver['Company']['Person']['lastname']  = $customer_details['last_name'];
                
                
		$receiver['Address']                                                      = array();
		$receiver['Address']['streetName']                                        = $customer_details['street_name'];
		$receiver['Address']['streetNumber']                                      = $customer_details['street_number'];
		$receiver['Address']['zip']                                               = $customer_details['zip'];
             
//		$receiver['Address']['zip'][ strtolower( $customer_details['country'] ) ] = $customer_details['zip'];
		$receiver['Address']['city']                                              = $customer_details['city'];
                $receiver['Address']['name3']                                               =$customer_details['company_name'];   
                if($customer_details['postNumber'] != ""){
                $receiver['Address']['name2']                                               = $customer_details['postNumber'];
                $receiver['Packstation']                                                      = array();
                $receiver['Packstation']['postNumber']                                        = $customer_details['postNumber'];
                $receiver['Packstation']['packstationNumber']                                 = $customer_details['packstationNumber'];
                $receiver['Packstation']['zip']                                               = $customer_details['zip'];
                $receiver['Packstation']['city']                                              = $customer_details['city'];
                }
		$receiver['Communication']                                                = array();
		$receiver['Address']['Origin'] = array( 'countryISOCode' => 'DE' );
		$shipment['ShipmentOrder']['Shipment']['Receiver'] = $receiver;
               // print_r($shipment);
		$response = $this->client->createShipmentOrder( $shipment );
//                print_r($receiver);
       //        print_r($response);
		if ( is_soap_fault( $response ) || $response->status->StatusCode != 0 ) {
			if ( is_soap_fault( $response ) ) {
				$this->errors[] = $response->faultstring;
			} else {
				$this->errors[] = $response->status->StatusMessage;
			}
			return false;
		} else {
			$r                    = array();
			$r['shipment_number'] = (String) $response->CreationState->ShipmentNumber->shipmentNumber;
			$r['piece_number']    = (String) $response->CreationState->PieceInformation->PieceNumber->licensePlate;
			$r['label_url']       = (String) $response->CreationState->LabelData->labelUrl;
                        $r['shipnumber']      = (String) $response->CreationState->LabelData->shipmentNumber;
			return $r;
		}
           
               
	}
	/*
	  function getVersion() {
		$this->buildClient();
		$this->log("Response: \n");
		$response = $this->client->getVersion(array('majorRelease' => '1', 'minorRelease' => '0'));
		$this->log($response);
	  }
	  */
	private function buildAuthHeader() {
           
		$head = $this->credentials;
		$auth_params = array(
			'user'      => ($this->sandbox) ? $this->credentials['sandbox_user'] :$this->credentials['user'],
			'signature' => ($this->sandbox) ? $this->credentials['sandbox_signature'] : $this->credentials['signature'],
			'type'      => 0
		);
		return new SoapHeader( 'http://dhl.de/webservice/cisbase', 'Authentification', $auth_params );
	}
}
    
