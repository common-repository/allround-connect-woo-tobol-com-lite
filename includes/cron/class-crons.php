<?php
/**
 * Custom_Cron_Schedule Class File
 *
 * This file contains custom recurrence schedules for cron jobs in WordPress.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Cron;
use ACBOL_Lite\Includes\Api\Offers\Offer_Handler;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Custom_Cron_Schedule.
 * This file contains custom recurrence schedules for cron jobs in WordPress.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 *
 * @see        https://developer.wordpress.org/reference/functions/wp_get_schedules/
 */
class Crons {

	public function __construct() {
		add_filter( 'cron_schedules', [$this, 'acbol_lite_add_cron_schedule'] );

		if ( ! wp_next_scheduled( 'acbol_lite_handle_requests' ) ) {
			wp_schedule_event( time(), 'every_minute', 'acbol_lite_handle_requests' );
		}
		
		
		add_action( 'acbol_lite_handle_requests', [$this, 'acbol_lite_get_all_pending'] );
	}

	function acbol_lite_add_cron_schedule( $schedules ) {
		$schedules['every_minute'] = array(
			'interval' => 60, // 1 week in seconds
			'display'  => __( 'Once every minute' ),
		);
	
		return $schedules;
	}

	public function acbol_lite_get_all_pending(){
		global $wpdb;
		$table_name = $wpdb->prefix."acbol_lite_pending_requests";
		$options = get_option('ACBOL_Lite_section1');
		$limit = $options['bol_api_limit'];
		$proccessed = 0;

		$results = $wpdb->get_results ( "SELECT * FROM $table_name" );

		foreach($results as $result){
			switch ($result->request) {
				case 'publish':
					$offer = new Offer_Handler();
					$publish = $offer->acbol_lite_bulk_publish($result->post_id);
					
					$wpdb->delete( $table_name, array( 'id' => $result->id ) );
				break;

				case 'publish_dupe_check':
					$href = $wpdb->get_var( "SELECT href FROM $table_name WHERE id = $result->id" );
					$method = $wpdb->get_var( "SELECT method FROM $table_name WHERE id = $result->id" );

					$offer = new Offer_Handler();
					$publish_dupe = $offer->acbol_lite_bulk_publish_dupe_check($result->post_id, $href, $method);

					$wpdb->delete( $table_name, array( 'id' => $result->id ) );
				break;
				
				case 'update_price':
					$offer = new Offer_Handler();
					$update_price = $offer->acbol_lite_bulk_update_price($result->post_id);
					
					$wpdb->delete( $table_name, array( 'id' => $result->id ) );
				break;
				
				case 'update_stock':
					$offer = new Offer_Handler();
					$update_stock = $offer->acbol_lite_bulk_update_stock($result->post_id);
					
					$wpdb->delete( $table_name, array( 'id' => $result->id ) );
				break;

				case 'pauze':
					$offer = new Offer_Handler();
					$pauze = $offer->acbol_lite_bulk_pauze($result->post_id);
					
					$wpdb->delete( $table_name, array( 'id' => $result->id ) );
				break;

				case 'resume':
					$offer = new Offer_Handler();
					$resume = $offer->acbol_lite_bulk_resume($result->post_id);
					
					$wpdb->delete( $table_name, array( 'id' => $result->id ) );
				break;

				case 'remove':
					$offer = new Offer_Handler();
					$remove = $offer->acbol_lite_bulk_remove($result->post_id);
					
					$wpdb->delete( $table_name, array( 'id' => $result->id ) );
				break;
			}
			
			if ($proccessed >= $limit){
				break;
			}

			$proccessed = $proccessed + 1;
		}
	}
}