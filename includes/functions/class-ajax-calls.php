<?php
/**
 * Ajax class file
 *
 * This file contains Info class. If you want to add default settings or meta
 * for your plugin (when it's activated) you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Functions;
use ACBOL_Lite\Includes\Api\Bol;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * acbol_lite_ajax
 * If you want to add default settings or some value for options
 * for your plugin (when it's activated) you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
class Ajax_Calls {

    public function __construct() {
        add_action( 'wp_ajax_acbol_lite_test_api_conn', [ $this, 'acbol_lite_test_api_conn'] );
        add_action( 'wp_ajax_acbol_lite_reset_proces', [ $this, 'acbol_lite_reset_proces_status'] );
    }

    /**
     * Tests the bol.com API connection
     */
    function acbol_lite_test_api_conn(){
        $client_id = sanitize_text_field( $_POST['data']['client_id'] );
        $client_secret = sanitize_text_field( $_POST['data']['client_secret'] );

        $conn = new Bol();
        $status = $conn->test_connection($client_id, $client_secret);

        echo esc_html($status);
        wp_die();
    }

    function acbol_lite_reset_proces_status(){
        global $wpdb;
        $count = 0;
        $table_name = $wpdb->prefix."acbol_lite_pending_requests";
        
        $args = array(
            'post_type' => 'product',
            'post_per_page' => -1,
            'meta_key' => 'bol_status',
            'meta_value' => 'Proccessing', 
            'meta_compare' => 'IN'
        );
        $products = wc_get_products($args);

        foreach ($products as $product) {
            update_post_meta($product->id, 'bol_status', 'Process onderbroken');
            
            $action = get_post_meta($product->id, 'bol_action', true);
            if (isset($action) && !empty($action)){
                if (strpos($action, 'publiceren')){
                    $request = 'publish';
                    $wpdb->delete( $table_name, array( 'post_id' => $product->id, 'request' => $request ) );
                } 
                elseif (strpos($action, 'prijs')){
                    $request = 'update_price';
                    $wpdb->delete( $table_name, array( 'post_id' => $product->id, 'request' => $request ) );
                } 
                elseif (strpos($action, 'stock')){
                    $request = 'update_stock';
                    $wpdb->delete( $table_name, array( 'post_id' => $product->id, 'request' => $request ) );
                } 
                elseif (strpos($action, 'pauzeren')){
                    $request = 'pauze';
                    $wpdb->delete( $table_name, array( 'post_id' => $product->id, 'request' => $request ) );
                } 
                elseif (strpos($action, 'hervatten')){
                    $request = 'resume';
                    $wpdb->delete( $table_name, array( 'post_id' => $product->id, 'request' => $request ) );
                } 
                elseif (strpos($action, 'verwijderen')){
                    $request = 'remove';
                    $wpdb->delete( $table_name, array( 'post_id' => $product->id, 'request' => $request ) );
                }
            }

            $count = $count + 1;
        }
        echo esc_html($count);
        wp_die();
    }
}