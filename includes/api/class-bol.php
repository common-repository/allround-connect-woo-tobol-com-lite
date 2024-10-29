<?php
/**
 * Bol API Class File
 * This file contains all the core requests to the bol.com API service
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Api;
use GuzzleHttp\Client;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Bol_API.
 * This file contains all the core requests to the bol.com API service
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
class Bol {
  /**
   * Retrieves the JWT token and puts it in the db for reusability
   *
   * @var clientId 
   * @var clientSecret
   */
  public function retrieve_token($clientId, $clientSecret){
      global $wpdb;  
      $key = base64_encode($clientId . ":" . $clientSecret);
      $table_name = $wpdb->prefix . 'acbol_lite';     
      $time_end = date('Y-m-d H:i:s', strtotime("+2 Hours +5 minutes"));
      
      $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE id = 1"));
      if($count == 1){
        $result = $wpdb->get_results( "SELECT * FROM  $table_name WHERE id = 1" );
        if(date("H:i:s", strtotime($result[0]->time_end)) > date('H:i:s', strtotime('+2 Hours')) && date("d", strtotime($result[0]->time_end)) == date('d') ){
          return $result[0]->token;
          wp_die();
        } 
        else {
          $client = new Client(['headers' => 
          [
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $key,
          ]]);

          $response = $client->request('POST', 'https://login.bol.com/token?grant_type=client_credentials');
          $body = $response->getBody();
          $response = json_decode($body);

          $where = array('id' => 1);
          $data = array('token' => $response->access_token, 'time_end' => $time_end);
          $wpdb->update($table_name, $data, $where);

          return $response->access_token;
          wp_die();
        }
      }else{
        $client = new Client(['headers' => 
        [
          'Accept' => 'application/json',
          'Authorization' => 'Basic ' . $key,
        ]]);

        $response = $client->request('POST', 'https://login.bol.com/token?grant_type=client_credentials');
        $body = $response->getBody();
        $response = json_decode($body);

        $wpdb->insert($table_name, array('id' => 1,'token' => $response->access_token, 'time_end' => $time_end,));
        return $response->access_token;
        wp_die();
      }
  }

  /**
   * Tests the connection of the api
   *
   * @var clientId Uses the clientId provided through the ajax call
   * @var clientSecret Uses the clientSecret provided through the ajax call
  */
  public function test_connection($clientId, $clientSecret){
      $token = $this->retrieve_token($clientId, $clientSecret);

      $client = new Client(['headers' => 
      [
        'Accept' => 'application/vnd.retailer.v5+json',
        'Authorization' => 'Bearer ' . $token,
      ]]);
      $response = $client->request('GET', 'https://api.bol.com/retailer-demo/orders');

      return $response->getStatusCode();
      // return $token;
      wp_die();
  }

  /**
   * Publish Offer to the bol.com API service
   *
   * @var sku
   * @var title
   * @var stock
   * @var price
   * @var product_id
   * 
   * @see includes\api\offers\class-offer-handler.php
   */
  public function acbol_lite_bulk_upload_bol($sku, $title, $stock, $price, $product_id){
    $product = wc_get_product( $product_id );

    $options = get_option('ACBOL_Lite_section1');
    $client_Id = $options['bol_client_id'];
    $client_Secret = $options['bol_client_secret'];

    $condition_override = $product->get_meta("bol_condition_override");
    $fulfilment_override = $product->get_meta("bol_fullfilment_override");
    $ean_override = $options['bol_ean'];
    $price_override = $options['bol_price_margin'];

    if ($fulfilment_override == "yes") {
      $fulfilment_method = $product->get_meta("bol_fullfilment_method");
    } else {
      $fulfilment_method = $options['bol_fullfilment_method'];
    }
    
    switch ($fulfilment_method) {
      case "Zelf verzenden":
        $fulfilment_method = "FBR";
        break;
      case "Bol.com":
        $fulfilment_method = "LVB";
        break;
    }

    $fulfilment_delivery_day = explode(" ", $options['bol_transport_time']);
    if (isset($options['bol_transport_time_orderd_before']) && $fulfilment_delivery_day[1] == "24"){
      $fulfilment_delivery_time = explode(":", $options['bol_transport_time_orderd_before']);
      $fulfilment_time = $fulfilment_delivery_day[1] . "uurs-" . $fulfilment_delivery_time[0];
    }
    else {
      $fulfilment_time = $fulfilment_delivery_day[0] ."d";
    }
    
    if ($condition_override == "yes") {
      $condition = $product->get_meta("bol_product_condition");
    } else {
      $condition = $options['bol_product_condition'];
    }

    switch ($condition) {
      case "Nieuw":
        $condition = "NEW";
        break;
      case "Als nieuw":
        $condition = "AS_NEW";
        break;
      case "Goed":
        $condition = "GOOD";
        break;
      case "Redelijk":
        $condition = "REASONABLE";
        break;
      case "Matig":
        $condition = "MODERATE";
        break;
    }

    switch ($ean_override) {
      case "Product Custom Veld":
        $custom_field = get_post_meta($product_id, "bol_ean_custom_field", true);
        $sku = get_post_meta($product_id, $custom_field);
        break;
      case "Product Attribuut":
        $attribute = get_post_meta($product_id, 'bol_ean_product_attribute', true);
        $custom_attribute = get_post_meta($product->id, 'pa_'. $attribute, true);
        $sku = get_post_meta($product_id, $custom_field);
        break;
    }

    if ($price_override == "on") {
      $custom_price = $options['bol_price_margin_input'];
      if (strpos($custom_price, "%")){
        $custom_percent = $custom_price / 100 + 1;
        $price = (int)$price * (int)$custom_percent;
      } else {
        $price = (int)$price + (int)$custom_price;
      }
    }

    $token = $this->retrieve_token($client_Id, $client_Secret);
    $payload = [
      "ean" => $sku,
      "condition" => array(
          "name" => $condition,
      ),
      "reference" => "",
      "onHoldByRetailer" => false,
      "unknownProductTitle" => $title,
      "pricing" => array(
          "bundlePrices" => array(
            array(
                "quantity" => 1,
                "unitPrice" => $price
            )
          )
      ),
      "stock" => array(
          "amount" => $stock,
          "managedByRetailer" => false
      ),
      "fulfilment" => array(
        "method" => "FBR",
        "deliveryCode" => $fulfilment_time
      )
    ];
    $client = new Client(['headers' => 
    [
      'Accept' => 'application/vnd.retailer.v5+json',
      'Content-Type' => 'application/vnd.retailer.v5+json',
      'Authorization' => 'Bearer ' . $token,
    ]]);
    $response = $client->request('POST', 'https://api.bol.com/retailer/offers', ['body' => json_encode($payload), 'http_errors' => false ]);
    $body = $response->getBody();
    $code = $response->getStatusCode();
    $response = json_decode($body);
    error_log(print_r($response, true));

    if ($code == 202){
      $data = [
        'href' => $response->links[0]->href,
        'method' => $response->links[0]->method
      ];
  
      return $data;
    } else {
      return 400;
    }
  }

  /**
   * Check the Publish Offer calls for duplicates
   *
   * @var stock
   * @var price
   * @var product_id
   * @var href
   * @var method
   * 
   * @see includes\api\offers\class-offer-handler.php
   */
  public function acbol_lite_bulk_upload_dupe_check_bol($stock, $price, $product_id, $href, $method){
    $options = get_option('ACBOL_Lite_section1');
    $client_Id = $options['bol_client_id'];
    $client_Secret = $options['bol_client_secret'];

    $token = $this->retrieve_token($client_Id, $client_Secret);
    $client = new Client(['headers' => 
    [
      'Accept' => 'application/vnd.retailer.v5+json',
      'Content-Type' => 'application/vnd.retailer.v5+json',
      'Authorization' => 'Bearer ' . $token,
    ]]);

    $response = $client->request($method, $href, ['http_errors' => false ]);
    $body = $response->getBody();
    $response = json_decode($body);
    error_log(print_r($response, true));

    if ($response->status == "FAILURE"){
      $errMessage = explode("'", $response->errorMessage);
      $offerId = $errMessage[1];

      $this->acbol_lite_bulk_upload_price_bol($offerId, $price);
      sleep(1);
      $this->acbol_lite_bulk_upload_stock_bol($offerId, $stock);

      return $offerId;
      wp_die();
    } else {
      $offerId = $response->entityId;
      return $offerId;
      wp_die();
    }
  }

  /**
   * Update Offer stock to the bol.com API service
   *
   * @var stock
   * @var price
   * @var product_id
   * @var href
   * @var method
   * 
   * @see includes\api\offers\class-offer-handler.php
   */
  public function acbol_lite_bulk_upload_stock_bol($offerId, $stock){
    $options = get_option('ACBOL_Lite_section1');
    $client_Id = $options['bol_client_id'];
    $client_Secret = $options['bol_client_secret'];

    $token = $this->retrieve_token($client_Id, $client_Secret);

    $payload = [
      "amount" => $stock,
      "managedByRetailer" => false
    ];
    $client = new Client(['headers' => 
    [
      'Accept' => 'application/vnd.retailer.v5+json',
      'Content-Type' => 'application/vnd.retailer.v5+json',
      'Authorization' => 'Bearer ' . $token,
    ]]);

    $response = $client->request('PUT', 'https://api.bol.com/retailer/offers/' . $offerId .'/stock', ['body' => json_encode($payload), 'http_errors' => false ]);
    $body = $response->getBody();
    $response = json_decode($body);
    error_log(print_r($response, true));
  }

  /**
   * Update Offer price to the bol.com API service
   *
   * @var offerId
   * @var price
   * 
   * @see includes\api\offers\class-offer-handler.php
   */
  public function acbol_lite_bulk_upload_price_bol($offerId, $price){
    $options = get_option('ACBOL_Lite_section1');
    $client_Id = $options['bol_client_id'];
    $client_Secret = $options['bol_client_secret'];
    $price_override = $options['bol_price_margin'];

    if ($price_override == "on") {
      $custom_price = $options['bol_price_margin_input'];
      if (strpos($custom_price, "%")){
        $custom_price = str_replace("%", "", $custom_price);
        $custom_percent = (int)$custom_price / 100 + 1;
        $price = (int)$price * (int)$custom_percent;
      } elseif(strpos($custom_price, ".")) {
        $price = (int)$price + (int)$custom_price;
      }
    }

    $token = $this->retrieve_token($client_Id, $client_Secret);

    $payload = [
      "pricing" => [
        "bundlePrices" => array(
          [
            "quantity" => 1,
            "unitPrice" => $price
          ]
        )
      ],
    ];
    $client = new Client(['headers' => 
    [
      'Accept' => 'application/vnd.retailer.v5+json',
      'Content-Type' => 'application/vnd.retailer.v5+json',
      'Authorization' => 'Bearer ' . $token,
    ]]);

    $response = $client->request('PUT', 'https://api.bol.com/retailer/offers/' . $offerId .'/price', ['body' => json_encode($payload), 'http_errors' => false ]);
    $body = $response->getBody();
    $response = json_decode($body);
    error_log(print_r($response, true));
  }

  /**
   * Upate Offer status pauze to the bol.com API service
   *
   * @var offerId
   * 
   * @see includes\api\offers\class-offer-handler.php
   */
  public function acbol_lite_bulk_resume_offer_bol($offerId){
    $options = get_option('ACBOL_Lite_section1');
    $client_Id = $options['bol_client_id'];
    $client_Secret = $options['bol_client_secret'];

    $fulfilment_delivery_day = explode(" ", $options['bol_transport_time']);
    if (isset($options['bol_transport_time_orderd_before']) && $fulfilment_delivery_day[1] == "24"){
      $fulfilment_delivery_time = explode(":", $options['bol_transport_time_orderd_before']);
      $fulfilment_time = $fulfilment_delivery_day[1] . "uurs-" . $fulfilment_delivery_time[0];
    }
    else {
      $fulfilment_time = $fulfilment_delivery_day[0] ."d";
    }
    
    $token = $this->retrieve_token($client_Id, $client_Secret);

    $payload = [
      "onHoldByRetailer" => false,
      "fulfilment" => [
        "method" => "FBR",
        "deliveryCode" => $fulfilment_time,
      ]
    ];
    $client = new Client(['headers' => 
    [
      'Accept' => 'application/vnd.retailer.v5+json',
      'Content-Type' => 'application/vnd.retailer.v5+json',
      'Authorization' => 'Bearer ' . $token,
    ]]);

    $response = $client->request('PUT', 'https://api.bol.com/retailer/offers/' . $offerId, ['body' => json_encode($payload), 'http_errors' => false ]);
    $body = $response->getBody();
    $response = json_decode($body);
    error_log(print_r($response, true));
  }
  
  /**
   * Upate Offer status resume to the bol.com API service
   *
   * @var offerId
   * 
   * @see includes\api\offers\class-offer-handler.php
   */
  public function acbol_lite_bulk_pause_offer_bol($offerId){
    $options = get_option('ACBOL_Lite_section1');
    $client_Id = $options['bol_client_id'];
    $client_Secret = $options['bol_client_secret'];

    $fulfilment_delivery_day = explode(" ", $options['bol_transport_time']);
    if (isset($options['bol_transport_time_orderd_before']) && $fulfilment_delivery_day[1] == "24"){
      $fulfilment_delivery_time = explode(":", $options['bol_transport_time_orderd_before']);
      $fulfilment_time = $fulfilment_delivery_day[1] . "uurs-" . $fulfilment_delivery_time[0];
    }
    else {
      $fulfilment_time = $fulfilment_delivery_day[0] ."d";
    }

    $token = $this->retrieve_token($client_Id, $client_Secret);

    $payload = [
      "onHoldByRetailer" => true,
      "fulfilment" => [
        "method" => "FBR",
        "deliveryCode" => $fulfilment_time,
      ]
    ];
    $client = new Client(['headers' => 
    [
      'Accept' => 'application/vnd.retailer.v5+json',
      'Content-Type' => 'application/vnd.retailer.v5+json',
      'Authorization' => 'Bearer ' . $token,
    ]]);

    $response = $client->request('PUT', 'https://api.bol.com/retailer/offers/' . $offerId, ['body' => json_encode($payload), 'http_errors' => false ]);
    $body = $response->getBody();
    $response = json_decode($body);
    error_log(print_r($response, true));
  }
  
  /**
   * Delete Offer from the bol.com API service
   *
   * @var offerId
   * 
   * @see includes\api\offers\class-offer-handler.php
   */
  public function acbol_lite_bulk_delete_offer_bol($offerId){
    $options = get_option('ACBOL_Lite_section1');
    $client_Id = $options['bol_client_id'];
    $client_Secret = $options['bol_client_secret'];

    $token = $this->retrieve_token($client_Id, $client_Secret);

    $client = new Client(['headers' => 
    [
      'Accept' => 'application/vnd.retailer.v5+json',
      'Content-Type' => 'application/vnd.retailer.v5+json',
      'Authorization' => 'Bearer ' . $token,
    ]]);
    
    $response = $client->request('DELETE', 'https://api.bol.com/retailer/offers/' . $offerId, ['http_errors' => false ]);
    $body = $response->getBody();
    $response = json_decode($body);
    error_log(print_r($response, true));
  }
}