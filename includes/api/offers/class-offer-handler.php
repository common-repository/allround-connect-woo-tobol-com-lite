<?php
/**
 * Offer Handler Class File
 * This file handles all the offer requests
 *
 */
namespace ACBOL_Lite\Includes\Api\Offers;
use ACBOL_Lite\Includes\Api\Bol;
use GuzzleHttp\Client;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Offer_Handler.
 * This file handles all the offer requests and gets the required data needed for the functions later on.
 * 
 */
class Offer_Handler {
    /**
     * Executes the publish request from the waiting list and returns a dupe_check request back to the waiting list
     *
     * @var post_id 
     * @see includes\hooks\filters\class-filtes.php
     */
    public function acbol_lite_bulk_publish($post_id){
        $product = wc_get_product( $post_id );
        if( $product->is_type( 'simple' ) ){

            $sku = $product->get_sku();
            $title = $product->get_name();
            $stock = $product->get_stock_quantity();
            if( wc_prices_include_tax() ) {
                $price = $product->get_regular_price();
            } else {
                $price = wc_get_price_including_tax( $product, array('price' => $product->get_regular_price() ) );
            }
            

            if (!empty($sku) && !empty($stock) && !empty($price)){
                $conn = new Bol();
                $status = $conn->acbol_lite_bulk_upload_bol($sku, $title, $stock, $price, $post_id);

                if ($status == 400){
                    $product->update_meta_data("bol_status", "Error: ongeldige sku");
                    $product->save(); 
                } else {
                    global $wpdb;
                    $table_name = $wpdb->prefix."acbol_lite_pending_requests";
                    $wpdb->insert($table_name, array(
                        'post_id' => $post_id,
                        'process' => 'PENDING',
                        'request' => 'publish_dupe_check',
                        'href'	  => $status['href'],
                        'method'  => $status['method']
                    ));
                }
                
            } else {
                if (empty($sku)){
                    $product->update_meta_data("bol_status", "Error: ongeldige sku");
                }
                if (empty($stock)){
                    $product->update_meta_data("bol_status", "Error: ongeldige voorraad");
                }
                if (empty($price)){
                    $product->update_meta_data("bol_status", "Error: ongeldige prijs");
                }
                $product->save();
            }
            return $status;
        
        }
    }

    /**
     * Executes the dupe_check request from the waiting list
     *
     * @var post_id 
     * @var href
     * @var method
     * @see includes\hooks\filters\class-filtes.php
     */
    public function acbol_lite_bulk_publish_dupe_check($post_id, $href, $method){
        $product = wc_get_product($post_id);
        if( $product->is_type( 'simple' ) ){

            $sku = get_post_meta( $post_id, '_sku', true );
            $stock = get_post_meta( $post_id, '_stock', true );
    
            if( wc_prices_include_tax() ) {
                $price = get_post_meta( $post_id, '_price', true );
            } else {
                $price = wc_get_price_including_tax( $product, array('price' => get_post_meta( $post_id, '_price', true ) ) );
            }
            
    
            if (!empty($sku) && !empty($stock) && !empty($price)){
                $conn = new Bol();
                $status = $conn->acbol_lite_bulk_upload_dupe_check_bol($stock, $price, $post_id, $href, $method);
    
                if (!empty($status)){
                    update_post_meta($post_id, 'offerId', $status);
                    update_post_meta($post_id, 'bol_status', "Gepubliceerd");
                }
            }
        }
    }

    /**
     * Executes the update offer price request from the waiting list
     *
     * @var post_id 
     * @see includes\hooks\filters\class-filtes.php
     */
    public function acbol_lite_bulk_update_price($post_id){
        $product = wc_get_product( $post_id );
        $offerId = get_post_meta($post_id, "offerId", true);
        if( wc_prices_include_tax() ) {
            $price = $product->get_regular_price();
        } else {
            $price = wc_get_price_including_tax( $product, array('price' => $product->get_regular_price() ) );
        }
        
        if (!empty($offerId)){
            if( $product->is_type( 'simple' ) ){ 
                if (!empty($offerId)){
                    $conn = new Bol();
                    $status = $conn->acbol_lite_bulk_upload_price_bol($offerId, $price);
    
                    update_post_meta($post_id, "bol_status", "Gepubliceerd");
                } else {
                    $product->update_meta_data("bol_status", "Error: Product is nog niet gepubliceerd");
                    $product->save();
                }
            }
        }
    }

   /**
     * Executes the update stock request from the waiting list
     *
     * @var post_id 
     * @see includes\hooks\filters\class-filtes.php
     */
    public function acbol_lite_bulk_update_stock($post_id){
        $product = wc_get_product( $post_id );
        $offerId = $product->get_meta("offerId");
        $stock = get_post_meta($post_id, '_stock', true);

        $options = get_option('ACBOL_Lite_section1');
        $lvb = $options['bol_fullfilment_method'];
        $lvb_override = get_post_meta($post_id, 'bol_fullfilment_method');

        if ($lvb != "Bol.com" && !$lvb_override != "Bol.com" && !empty($offerId)){
            if( $product->is_type( 'simple' ) ){ 
                if (!empty($offerId)){
                    $conn = new Bol();
                    $status = $conn->acbol_lite_bulk_upload_stock_bol($offerId, $stock);
                    update_post_meta($post_id, "bol_status", "Gepubliceerd");
                } else {
                    $product->update_meta_data("bol_status", "Error: Product is nog niet gepubliceerd");
                    $product->save();
                }
            }
        } else {
            update_post_meta($post_id, "bol_status", "De voorraad staat op LVB (levering via Bol.com). Zet het product eerst om naar Eigen voorraad");
        }
    }

    /**
     * Executes the pauze offer request from the waiting list
     *
     * @var post_id 
     * @see includes\hooks\filters\class-filtes.php
     */
    public function acbol_lite_bulk_pauze($post_id){
        $product = wc_get_product( $post_id );
        $offerId = $product->get_meta("offerId");
        
        if (!empty($offerId)){
            if( $product->is_type( 'simple' ) ){ 
                if (!empty($offerId)){
                    $conn = new Bol();
                    $status = $conn->acbol_lite_bulk_pause_offer_bol($offerId);
                    $product->update_meta_data("bol_status", "Gepubliceerd (op pauze)");
                    $product->save();
                } else {
                    $product->update_meta_data("bol_status", "Error: Product is nog niet gepubliceerd");
                    $product->save();
                }
            }
        }
    }

    /**
     * Executes the resume offer request from the waiting list
     *
     * @var post_id 
     * @see includes\hooks\filters\class-filtes.php
     */
    public function acbol_lite_bulk_resume($post_id){
        $product = wc_get_product( $post_id );
        $offerId = $product->get_meta("offerId");
        
        if (!empty($offerId)){
            if( $product->is_type( 'simple' ) ){ 
                if (!empty($offerId)){
                    $conn = new Bol();
                    $status = $conn->acbol_lite_bulk_resume_offer_bol($offerId);
                    update_post_meta($post_id, "bol_status", "Gepubliceerd");
                } else {
                    $product->update_meta_data("bol_status", "Error: Product is nog niet gepubliceerd");
                    $product->save();
                }
            } 
        }
    }

    /**
     * Executes the remove offer request from the waiting list
     *
     * @var post_id 
     * @see includes\hooks\filters\class-filtes.php
     */
    public function acbol_lite_bulk_remove($post_id){
        $product = wc_get_product( $post_id );
        $offerId = $product->get_meta("offerId");
        
        if(!empty($offerId)){
            if( $product->is_type( 'simple' ) ){ 
                if (!empty($offerId)){
                    $conn = new Bol();
                    $status = $conn->acbol_lite_bulk_delete_offer_bol($offerId);
    
                    $product->update_meta_data("offerId", "");
                    $product->update_meta_data("bol_status", "Niet gepubliceerd");
                    $product->save();
                } else {
                    $product->update_meta_data("bol_status", "Error: Product is nog niet gepubliceerd");
                    $product->save();
                }
            }
        }
    }
}