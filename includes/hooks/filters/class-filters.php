<?php
/**
 * Filters Class File
 *
 * This file contains actions and filter overrides for core Wordpress en core Woocommerce functions.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @since      1.0.2
 */

namespace ACBOL_Lite\Includes\Hooks\Filters;
use ACBOL_Lite\Includes\Api\Offers\Offer_Handler;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Filters.
 * This file contains actions and filter overrides for core Wordpress en core Woocommerce functions.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
class Filters {
    public function __construct() {
        // Add woocommerce functionalities to our settings page
        add_filter( 'woocommerce_screen_ids', [ $this, 'acbol_lite_woocommerce_to_bol_screen_id'] );
        // Woocommerce Bulk addons
        add_filter( 'bulk_actions-edit-product', [$this, 'acbol_lite_add_bulk_actions'] );
        add_filter( 'handle_bulk_actions-edit-product', [$this, 'acbol_lite_bulk_action_handler'], 10, 3 );
        // Admin notices
        add_action( 'admin_notices', [$this, 'acbol_lite_bulk_action_notices'] );
        add_action('admin_notices', [$this, 'acbol_lite_stock_notice']);
        add_action( 'admin_notices', [$this, 'maybe_show_acbol_lite_upgrade_notice'] );
        add_action( 'admin_notices', [$this, 'maybe_show_acbol_lite_sku_notice'] );
        // Woocommerce Bulk addons
        add_filter( 'manage_edit-product_columns', [$this, 'acbol_lite_add_product_column'],15 );
        add_action( 'manage_product_posts_custom_column', [$this, 'acbol_lite_product_column_offercode'], 10, 2 );
        add_filter( 'woocommerce_product_data_tabs', [$this, 'acbol_lite_custom_product_data_tab'] , 15 , 1 );
        add_action( 'woocommerce_product_data_panels', [$this, 'acbol_lite_add_custom_product_data_fields'] );
        add_action( 'woocommerce_process_product_meta', [$this, 'acbol_lite_woocommerce_process_product_meta_fields_save'] );
        // Woocommerce product properties updated
        add_action('woocommerce_product_set_stock', [$this, 'acbol_lite_wc_stock_updated']);
        add_action('woocommerce_update_product', [$this, 'on_update_product'], 10, 2);
        // Woocommerce table nav btn
        add_action( 'manage_posts_extra_tablenav', [$this, 'acbol_lite_table_nav_btn'], 20, 1 );
        add_action( 'restrict_manage_posts', [$this, 'acbol_lite_table_nav_btn_action'] );
        
    }

    /**
     * 
     * IFNO NOTICE
    */
    function maybe_show_acbol_lite_upgrade_notice() {
        global $pagenow;
        if ($pagenow == 'admin.php'){
            if (isset($_GET['page'])){
                $page = $_GET['page'];
            } else {
                return;
            }
    
            if ($page == "acbol-lite-settings"){
                ?>
                <div class="notice notice-info alt">
                    <h3><strong>👋 We hebben opgemerkt dat je de Allround Connect Woo To Bol.com plugin hebt geinstalleerd</strong></h3>
                    <p><?php _e( 'De opties van de gratis plugin zijn beperkt. ', 'ACBOL_LITE' );?></p>
                    <p><?php _e( 'Wil je graag het maximale halen uit de koppeling tussen Woocommerce en Bol.com? Start dan vandaag nog met de PRO plugin! Klik hier voor meer ', 'ACBOL_LITE' );?><a href="https://allroundconnect.com/nl/woocommerce-koppelen-met-bol-com/">informatie</a>.</p>
                </div>
                <?php 
            } 
        } else {
            return;
        }
    }

    function maybe_show_acbol_lite_sku_notice() {
        global $pagenow;
        global $post;
        if ($pagenow == 'post.php'){
            if (isset($_GET['action']) && $_GET['action'] == "edit"){
                $post_type = get_post_type($post);
                if ($post_type == "product"){
                    $err = get_post_meta(get_the_ID(), 'bol_status', true);
                    if (strpos(strtolower($err), 'error:') !== false) {
                        if (strpos(strtolower($err), 'sku') !== false) {
                            ?>
                                <div class="notice notice-error is-dismissible">
                                    <p><strong>EAN veld niet geldig</strong></p>
                                    <p><?php _e( 'Wil je graag ander veld koppelen dan de standaard SKU? Met de PRO plugin kun je elk custom field of attribuut selecteren als EAN. Klik hier voor meer ', 'ACBOL_LITE' );?><a href="https://allroundconnect.com/nl/woocommerce-koppelen-met-bol-com/">informatie</a>.</p>
                                </div>
                                <?php 
                        }  
                    }
                }
            }
        } else {
            return;
        }
    }
    
    /**
    * Register plugin settings screen to Woocommerce
    *
    * @param array $screen
    * @return void
    */
    function acbol_lite_woocommerce_to_bol_screen_id( $screen ) {
        $screen[] = 'allround-connect_page_acbol-lite-settings';
        return $screen;
    }
    
    function acbol_lite_table_nav_btn( $which ) {
        ?>
            <div class="alignleft actions custom">
                <button type="submit" name="acbol_lite_publish_all" style="height:32px;" class="button" value="publish"><?php
                    echo __( 'Publiceer alle producten naar Bol.com', 'acbol_lite' ); ?></button>
            </div>
            <?php
    }

    
    function acbol_lite_table_nav_btn_action() {
        global $pagenow, $typenow, $wpdb;
        $table_name = $wpdb->prefix."acbol_lite_pending_requests";
        $count = 0;

        if ( 'product' === $typenow && 'edit.php' === $pagenow && isset($_GET['acbol_lite_publish_all']) && $_GET['acbol_lite_publish_all'] === 'publish' ) {
            $args = array(
                'post_type' => 'product',
                'post_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'offerId',
                        'compare' => '=',
                        'value'   => '',
                    ),
                    array(
                        'key'     => 'offerId',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            );
            $products = wc_get_products($args);

            foreach ($products as $product) {
                $post_id = $product->id;

                if ($product->is_type('simple')){
                    $wpdb->insert($table_name, array(
                        'post_id' => $post_id,
                        'process' => 'PENDING',
                        'request' => 'publish',
                    ));

                    $this->acbol_lite_set_proccessing($post_id, "Product publiceren");
                    $count = $count + 1;
                }
            }

            $url = get_site_url() . "/wp-admin/edit.php?post_type=product";
            $redirect = add_query_arg(
                'acbol_lite_publish_done',
                $count,
            $url );           
            wp_redirect( $redirect );
        }
    }

    /**
     * Add plugin tab to Woocommerce admin edit product page
     *
     * @var product_data_tabs 
     */
    function acbol_lite_custom_product_data_tab( $product_data_tabs ) {
        $product_data_tabs['acbol_lite'] = array(
            'label' => __( 'Allround Connect Bol.com', 'acbol_lite' ),
            'target' => 'acbol_lite_product_settings',
        );
        return $product_data_tabs;
    }

    /**
     * Add plugin product options to created product data tab
     */
    function acbol_lite_add_custom_product_data_fields() {
        global $woocommerce, $post;
        ?>
        <!-- id below must match target registered in above add_my_custom_product_data_tab function -->
        <div id="acbol_lite_product_settings" class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php
                    woocommerce_wp_checkbox( array( 
                        'id'            => 'bol_fullfilment_override', 
                        'label'         => __( 'Gebruik eigen fulfilment methode', 'acbol_lite' ),
                        'default'       => '0',
                        'desc_tip'      => false,
                    ) );

                    $product = wc_get_product( get_the_ID() );
                    $fullfilment = $product->get_meta("bol_fullfilment_override");
                ?>
                <p class="form-field">
                    <label for="bol_fullfilment_method"><?php echo _e( 'Fulfilment methode', 'acbol_lite' ) ?></label>
                    <select name="bol_fullfilment_method" id="bol_fullfilment_method">
                        <?php 
                            $product = wc_get_product( get_the_ID() );
                            $selected = $product->get_meta("bol_fullfilment_method");
                            $data = [
                                __( 'Zelf verzenden', 'acbol_lite' ),
                                'Bol.com',
                            ];
                            foreach ($data as $condition) {
                                if ($selected == $condition) {
                                    echo "<option value='". esc_attr($condition) ."' selected>". esc_html($condition) ."</option>";
                                } else {
                                    echo "<option value='". esc_attr($condition) ."'>". esc_html($condition) ."</option>";
                                }
                            }  
                        ?>
                    </select>
                </p>  
            </div>
            <div class="options_group">
                <?php
                    woocommerce_wp_checkbox( array( 
                        'id'            => 'bol_condition_override', 
                        'label'         => __( 'Gebruik eigen condition', 'acbol_lite' ),
                        'default'       => '0',
                        'desc_tip'      => false,
                    ) );

                    $product = wc_get_product( get_the_ID() );
                    $condition_override = $product->get_meta("bol_condition_override");
                ?>
                <p class="form-field">
                    <label for="bol_product_condition"><?php echo _e( 'Product conditie', 'acbol_lite' ) ?></label>
                    <select name="bol_product_condition" id="bol_product_condition">
                        <?php 
                            $product = wc_get_product( get_the_ID() );
                            $selected = $product->get_meta("bol_product_condition");
                            $data = [
                                __( 'Nieuw', 'acbol_lite' ),
                                __( 'Als nieuw', 'acbol_lite' ),
                                __( 'Goed', 'acbol_lite' ),
                                __( 'Redelijk', 'acbol_lite' ),
                                __( 'Matig', 'acbol_lite' ),
                            ];
                            foreach ($data as $condition) {
                                if ($selected == $condition) {
                                    echo "<option value='". esc_attr($condition) ."' selected>". esc_html($condition) ."</option>";
                                } else {
                                    echo "<option value='". esc_attr($condition) ."'>". esc_html($condition) ."</option>";
                                }
                            }  
                        ?>
                    </select>
                </p>
            </div>
            <script>
                jQuery(document).ready(function ($) {
                    if(jQuery('#bol_condition_override').is(':checked')){
                        jQuery("#bol_product_condition").parent().show();
                    } else {
                        jQuery("#bol_product_condition").parent().hide();
                    }

                    jQuery('#bol_condition_override').click(function(){
                        if(jQuery(this).is(':checked')){
                            jQuery("#bol_product_condition").parent().show();
                        } else {
                            jQuery("#bol_product_condition").parent().hide();
                        }
                    });

                    if(jQuery('#bol_fullfilment_override').is(':checked')){
                        jQuery("#bol_fullfilment_method").parent().show();
                    } else {
                        jQuery("#bol_fullfilment_method").parent().hide();
                    }

                    jQuery('#bol_fullfilment_override').click(function(){
                        if(jQuery(this).is(':checked')){
                            jQuery("#bol_fullfilment_method").parent().show();
                        } else {
                            jQuery("#bol_fullfilment_method").parent().hide();
                        }
                    });
                });
            </script>
        </div>
        <?php
    }
   
    /**
     * Save options form created product data tab
     * 
     * @var post_id
     */
    function acbol_lite_woocommerce_process_product_meta_fields_save( $post_id ){
        $condition_override = sanitize_text_field( $_POST['bol_condition_override'] );
        $fullfilment_override = sanitize_text_field( $_POST['bol_fullfilment_override'] );

        $fullfilment_override_option = sanitize_text_field( $_POST['bol_fullfilment_method'] );
        $condition_override_option = sanitize_text_field( $_POST['bol_product_condition'] );

        $bol_condition_override = isset( $condition_override ) ? 'yes' : 'no';
        update_post_meta( $post_id, 'bol_condition_override', $bol_condition_override );

        $bol_fullfilment_override = isset( $fullfilment_override ) ? 'yes' : 'no';
        update_post_meta( $post_id, 'bol_fullfilment_override', $bol_fullfilment_override );
        
        if (isset( $fullfilment_override_option )) {
            update_post_meta( $post_id, 'bol_fullfilment_method', esc_html( $fullfilment_override_option ) );
        }
        if (isset( $condition_override_option )) {
            update_post_meta( $post_id, 'bol_product_condition',  esc_html( $condition_override_option ) );
        }
    }

    /**
     * Add product columns to product view
     * 
     * @var columns
     */
    function acbol_lite_add_product_column($columns){
        return array_slice( $columns, 0, 6, true )
        + array( 'acbol_bol_status' => 'Bol.com status' )
        + array_slice( $columns, 3, NULL, true );
    }    
    
    /**
     * Handle data in created product columns
     * 
     * @var column
     * @var postid
     */
    function acbol_lite_product_column_offercode( $column, $postid ) {
        if ( $column == 'acbol_bol_status' ) {
            $product = wc_get_product( $postid );
            $status = $product->get_meta("bol_status");
            $offerId = $product->get_meta("offerId");

            if ($product->is_type('simple')){
                if (strpos($status, 'pauze') !== false) {
                    echo "<p style='color:#ffb361;font-weight:bold;'>
                            " . esc_html($status) . "
                        </p>";
                }
                elseif (strpos($status, 'Error') !== false || strpos($status, 'LVB') !== false || strpos($status, 'onderbroken') !== false) {
                    echo "<p style='color:#d03a3a;font-weight:bold;'>
                        " . esc_html($status) . "
                        </p>";
                }
                elseif (strpos($status, 'Proccessing') !== false) {
                    $action = $product->get_meta("bol_action");
                    $tip = wc_help_tip(esc_html($action));

                    echo "<p style='color:#3a8ad0;font-weight:bold;'>
                        " . esc_html($status) . "
                        ". $tip ."</p>";
                }
                elseif (!empty($status) && !empty($offerId) && !$product->is_type( 'variable' )){
                    echo "<p style='color:#7ad03a;font-weight:bold;'>
                        " . esc_html($status) . "
                        </p>";
                } 
                else {
                    echo "<p style='color:#a8a8a8;font-weight:bold;'>
                        Niet gepubliceerd
                    </p>";
                }
            }
        }
    }

   /**
     * Add bulk actions to product bulk action view
     * 
     * @var bulk_array
     */
    function acbol_lite_add_bulk_actions( $bulk_array ) {

        $bulk_array['acbol_lite_publish_bol'] = __( 'Publiceren naar Bol.com', 'acbol_lite' );
        $bulk_array['acbol_lite_update_price_bol'] = __( 'Prijs updaten naar Bol.com', 'acbol_lite' );
        $bulk_array['acbol_lite_update_stock_bol'] = __( 'Voorraad updaten naar Bol.com', 'acbol_lite' );
        $bulk_array['acbol_lite_pauze_bol'] = __( 'Pauzeren op Bol.com', 'acbol_lite' );
        $bulk_array['acbol_lite_resume_bol'] = __( 'Hervatten op Bol.com', 'acbol_lite' );
        $bulk_array['acbol_lite_remove_bol'] = __( 'Verwijderen van Bol.com', 'acbol_lite' );

        return $bulk_array;
    }
    
    /**
     * Handle custom bulk action executions
     * 
     * @var redirect
     * @var doaction
     * @var object_ids
     */
    function acbol_lite_bulk_action_handler( $redirect, $doaction, $object_ids ) {
        global $wpdb;
        $table_name = $wpdb->prefix."acbol_lite_pending_requests";
        $redirect = remove_query_arg( array( 
            'acbol_lite_publish_bol', 'acbol_lite_update_price_bol_done',
            'acbol_lite_update_stock_bol_done', 'acbol_lite_pauze_bol_done',
            'acbol_lite_resume_bol_done', 'acbol_lite_remove_bol_done',
            'acbol_lite_proccessing_bol', 'acbol_lite_lvb_bol',
            'acbol_lite_variations_bol'
        ), $redirect );

        if ( $doaction == 'acbol_lite_publish_bol' ) {
            $error_ids = "";
            $success_amount = 0;
            $total = 0;

            foreach ( $object_ids as $post_id ) {
                $product = wc_get_product($post_id);

                if ($product->is_type('simple')){
                    $proccessing = get_post_meta($post_id, 'bol_status', true);

                    if ($proccessing === "Proccessing"){
                        $redirect = add_query_arg(
                            'acbol_lite_proccessing_bol',
                            count( $object_ids ),
                        $redirect );
                        
                    } else {
                        $wpdb->insert($table_name, array(
                            'post_id' => $post_id,
                            'process' => 'PENDING',
                            'request' => 'publish',
                        ));

                        $this->acbol_lite_set_proccessing($post_id, "Product publiceren");
                        
                        $redirect = add_query_arg(
                            'acbol_lite_publish_done',
                            count( $object_ids ),
                        $redirect );

                    }
                } else {
                    $redirect = add_query_arg(
                        'acbol_lite_variations_bol',
                        count( $object_ids ),
                    $redirect );

                }
            }

            
        }
        if ( $doaction == 'acbol_lite_update_price_bol' ) {
            foreach ( $object_ids as $post_id ) {
                $product = wc_get_product($post_id);

                if ($product->is_type('simple')){
                    $options = get_option('ACBOL_Lite_section1');
                    $allow_price_update = $options['bol_woocommerce_prices'];
                    
                    $proccessing = get_post_meta($post_id, 'bol_status', true);
                    if ($proccessing === "Proccessing"){
                        $redirect = add_query_arg(
                            'acbol_lite_proccessing_bol',
                            count( $object_ids ),
                        $redirect );

                    } else {
                        if ($allow_price_update == "on"){
                            $wpdb->insert($table_name, array(
                                'post_id' => $post_id,
                                'process' => 'PENDING',
                                'request' => 'update_price',
                            ));

                            $this->acbol_lite_set_proccessing($post_id, "Product prijs updaten");
        
                            $redirect = add_query_arg(
                                'acbol_lite_update_price_bol_done',
                                count( $object_ids ),
                            $redirect );

                        }
                    }
                } else {
                    $redirect = add_query_arg(
                        'acbol_lite_variations_bol',
                        count( $object_ids ),
                    $redirect );

                }
            }

            
        }
        if ( $doaction == 'acbol_lite_update_stock_bol' ) {
            foreach ( $object_ids as $post_id ) {
                $product = wc_get_product($post_id);

                if ($product->is_type('simple')){
                    $proccessing = get_post_meta($post_id, 'bol_status', true);

                    if ($proccessing === "Proccessing"){
                        $redirect = add_query_arg(
                            'acbol_lite_proccessing_bol',
                            count( $object_ids ),
                        $redirect );
                    
                    } else {
                        $options = get_option('ACBOL_Lite_section1');
                        $allow_stock_update = $options['bol_fullfilment_method'];
                        $allow_stock_update_override = get_post_meta($post_id, 'bol_fullfilment_method', true);

                        if ($allow_stock_update == "Bol.com" || $allow_stock_update_override == "Bol.com"){
                            $redirect = add_query_arg(
                                'acbol_lite_lvb_bol',
                                count( $object_ids ),
                            $redirect );
                            update_post_meta($post_id, "bol_status", "De voorraad staat op LVB (levering via Bol.com). Zet het product eerst om naar Eigen voorraad");
                            
                            return $redirect;
                        }
                        $wpdb->insert($table_name, array(
                            'post_id' => $post_id,
                            'process' => 'PENDING',
                            'request' => 'update_stock',
                        ));

                        $this->acbol_lite_set_proccessing($post_id, "Product stock updaten");
        
                        $redirect = add_query_arg(
                            'acbol_lite_update_stock_bol_done',
                            count( $object_ids ),
                        $redirect );

                    }
                } else {
                    $redirect = add_query_arg(
                        'acbol_lite_variations_bol',
                        count( $object_ids ),
                    $redirect );
                
                }
            }
        }
        if ( $doaction == 'acbol_lite_pauze_bol' ) {
            foreach ( $object_ids as $post_id ) {
                $product = wc_get_product($post_id);

                if ($product->is_type('simple')){
                    $proccessing = get_post_meta($post_id, 'bol_status', true);

                    if ($proccessing === "Proccessing"){
                        $redirect = add_query_arg(
                            'acbol_lite_proccessing_bol',
                            count( $object_ids ),
                        $redirect );
    
                    } else {
                        $wpdb->insert($table_name, array(
                            'post_id' => $post_id,
                            'process' => 'PENDING',
                            'request' => 'pauze',
                        ));

                        $this->acbol_lite_set_proccessing($post_id, "Product pauzeren");
        
                        $redirect = add_query_arg(
                            'acbol_lite_pauze_bol_done',
                            count( $object_ids ),
                        $redirect );

                    }
                } else {
                    $redirect = add_query_arg(
                        'acbol_lite_variations_bol',
                        count( $object_ids ),
                    $redirect );
                
                }
                
            }
        }
        if ( $doaction == 'acbol_lite_resume_bol' ) {
            foreach ( $object_ids as $post_id ) {
                $product = wc_get_product($post_id);

                if ($product->is_type('simple')){
                    $proccessing = get_post_meta($post_id, 'bol_status', true);

                    if ($proccessing === "Proccessing"){
                        $redirect = add_query_arg(
                            'acbol_lite_proccessing_bol',
                            count( $object_ids ),
                        $redirect );
    
                    } else {
                        $wpdb->insert($table_name, array(
                            'post_id' => $post_id,
                            'process' => 'PENDING',
                            'request' => 'resume',
                        ));
    
                        $this->acbol_lite_set_proccessing($post_id, "Product hervatten");
        
                        $redirect = add_query_arg(
                            'acbol_lite_resume_bol_done',
                            count( $object_ids ),
                        $redirect );

                    }
                } else {
                    $redirect = add_query_arg(
                        'acbol_lite_variations_bol',
                        count( $object_ids ),
                    $redirect );
                
                }
            }
        }
        if ( $doaction == 'acbol_lite_remove_bol' ) {
            foreach ( $object_ids as $post_id ) {
                $product = wc_get_product($post_id);

                if ($product->is_type('simple')){
                    $proccessing = get_post_meta($post_id, 'bol_status', true);

                    if ($proccessing === "Proccessing"){
                        $redirect = add_query_arg(
                            'acbol_lite_proccessing_bol',
                            count( $object_ids ),
                        $redirect );
    
                    } else {
                        $wpdb->insert($table_name, array(
                            'post_id' => $post_id,
                            'process' => 'PENDING',
                            'request' => 'remove',
                        ));
    
                        $this->acbol_lite_set_proccessing($post_id, "Product verwijderen");
        
                        $redirect = add_query_arg(
                            'acbol_lite_remove_bol_done',
                            count( $object_ids ),
                        $redirect );

                    }
                } else {
                    $redirect = add_query_arg(
                        'acbol_lite_variations_bol',
                        count( $object_ids ),
                    $redirect );
                
                }
            }
        }

        return $redirect;
    }

    /**
     * Set product status to processing
     */
    function acbol_lite_set_proccessing($post_id, $action){
        update_post_meta($post_id, "bol_status", "Proccessing");
        $current_action = get_post_meta($post_id, "bol_action");

        if (!empty($current_action)){
            update_post_meta($post_id, "bol_action", $action);
        } else {
            add_post_meta($post_id, "bol_action", $action);
        }
    }
    
    /**
     * Add admin notices after execution of actions
     */
    function acbol_lite_bulk_action_notices() {
        if( ! empty( $_REQUEST['acbol_lite_publish_done'] ) ) {
            printf( '<div id="message" class="updated notice is-dismissible"><p>' .
                _n( '%s Product(en) in de wachtrij gezet.',
                '%s Product(en) in de wachtrij gezet.',
                intval( $_REQUEST['acbol_lite_publish_done'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_publish_done'] ) );
        }

        if( ! empty( $_REQUEST['acbol_lite_update_price_bol_done'] ) ) {
            printf( '<div id="message" class="updated notice is-dismissible"><p>' .
                _n( '%s Product(en) in de wachtrij gezet om de prijs te laten updaten.',
                '%s Product(en) in de wachtrij gezet om de prijs te laten updaten.',
                intval( $_REQUEST['acbol_lite_update_price_bol_done'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_update_price_bol_done'] ) );
        }

        if( ! empty( $_REQUEST['acbol_lite_update_stock_bol_done'] ) ) {
            printf( '<div id="message" class="updated notice is-dismissible"><p>' .
                _n( '%s Product(en) in de wachtrij gezet om de stock te laten updaten.',
                '%s Product(en) in de wachtrij gezet om de stock te laten updaten.',
                intval( $_REQUEST['acbol_lite_update_stock_bol_done'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_update_stock_bol_done'] ) );
        }

        if( ! empty( $_REQUEST['acbol_lite_pauze_bol_done'] ) ) {
            printf( '<div id="message" class="updated notice is-dismissible"><p>' .
                _n( '%s Product(en) in de wachtrij gezet om de gepauzeerd te worden.',
                '%s Product(en) in de wachtrij gezet om de gepauzeerd te worden.',
                intval( $_REQUEST['acbol_lite_pauze_bol_done'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_pauze_bol_done'] ) );
        }

        if( ! empty( $_REQUEST['acbol_lite_resume_bol_done'] ) ) {
            printf( '<div id="message" class="updated notice is-dismissible"><p>' .
                _n( '%s Product(en) in de wachtrij gezet om de gehervat te worden.',
                '%s Product(en) in de wachtrij gezet om de gehervat te worden.',
                intval( $_REQUEST['acbol_lite_resume_bol_done'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_resume_bol_done'] ) );
        }
        
        if( ! empty( $_REQUEST['acbol_lite_remove_bol_done'] ) ) {
            printf( '<div id="message" class="note-error notice is-dismissible"><p>' .
                _n( '%s Product(en) in de wachtrij gezet om de verwijderd te worden.',
                '%s Product(en) in de wachtrij gezet om de verwijderd te worden.',
                intval( $_REQUEST['acbol_lite_remove_bol_done'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_remove_bol_done'] ) );
        }

        if( ! empty( $_REQUEST['acbol_lite_proccessing_bol'] ) ) {
            printf( '<div id="message" class="notice notice-error is-dismissible"><p>' .
                _n( 'Het product staat nog in de wachtrij. Wacht alstublieft met uw aanpassingen.',
                'Het product staat nog in de wachtrij. Wacht alstublieft met uw aanpassingen.',
                intval( $_REQUEST['acbol_lite_proccessing_bol'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_proccessing_bol'] ) );
        }

        if( ! empty( $_REQUEST['acbol_lite_variations_bol'] ) ) {
            printf( '<div id="message" class="notice notice-error is-dismissible"><p>' .
                _n( 'Variates worden niet ondersteund in de Lite versie. Upgrade naar PRO voor de volledige support.',
                'Variates worden niet ondersteund in de Lite versie. Upgrade naar PRO voor de volledige support.',
                intval( $_REQUEST['acbol_lite_variations_bol'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_variations_bol'] ) );
        }

        if( ! empty( $_REQUEST['acbol_lite_lvb_bol'] ) ) {
            printf( '<div id="message" class="notice notice-error is-dismissible"><p>' .
                _n( 'De voorraad staat op LVB (levering via Bol.com). Zet het product eerst om naar Eigen voorraad.',
                'De voorraad staat op LVB (levering via Bol.com). Zet het product eerst om naar Eigen voorraad.',
                intval( $_REQUEST['acbol_lite_lvb_bol'] )
            ) . '</p></div>', intval( $_REQUEST['acbol_lite_lvb_bol'] ) );
        }
    }

    /**
     * Add admin notices for permanent stock status
     */
    function acbol_lite_stock_notice(){
        global $pagenow;
        global $post_type;

        if (isset($_GET['page'])){
            $page = esc_url_raw($_GET['page']);
            if ( $pagenow == 'edit.php' && $post_type == 'product' || $pagenow == 'admin.php' && $page == 'acbol-lite-settings') {
                echo '<div class="notice notice-acbol">
                    <p>'. __('De voorraad word van Woocommerce naar Bol.com geupdate, en niet van bol.com naar Woocommerce. Zorg er dus voor dat je voorraad in Woocommerce altijd goed staat. Ook bij nieuwe bestellingen op Bol.com. Wil je dat de voorraad automatisch word aangepast bij een bestelling op Bol.com? Ga dan voor PRO met nog veel meer mogelijkheden! Klik <a href="http://allroundconnect.com/">hier</a> voor meer informatie.', 'acbol_lite') .'</p>
                </div>';
            }
        }
    }

    /**
     * On product stock change add update stock request to waiting list
     * 
     * @var product
     */
    function acbol_lite_wc_stock_updated( $product ) {
        if($product->is_type('simple')){
            global $wpdb;

            $post_id = $product->get_id();
            $offerId = $product->get_meta("offerId");

            $options = get_option('ACBOL_Lite_section1');
            $allow_stock_update = $options['bol_fullfilment_method'];
            $allow_stock_update_override = get_post_meta($post_id, 'bol_fullfilment_method', true);
        
            $table_name = $wpdb->prefix."acbol_lite_pending_requests";
            if ($allow_stock_update == "Bol.com" || $allow_stock_update_override == "Bol.com"){
                update_post_meta($post_id, "bol_status", "De voorraad staat op LVB (levering via Bol.com). Zet het product eerst om naar Eigen voorraad");
                return;
                wp_die();
            }
            elseif (!empty($offerId)){
                $wpdb->insert($table_name, array(
                    'post_id' => $post_id,
                    'process' => 'PENDING',
                    'request' => 'update_stock',
                ));
    
                $this->acbol_lite_set_proccessing($post_id, "Product stock updaten");
    
                return;
                wp_die();
            }
        }
    }

    /**
     *  On product save check if price has changed through hashes
     * 
     * @var product_id
     * @var product
     */
    function on_update_product($product_id, $product){
        global $wpdb;

        $options = get_option('ACBOL_Lite_section1');
        $allow_price_update = $options['bol_woocommerce_prices'];
        
        $table_name = $wpdb->prefix."acbol_lite_pending_requests";
        $offerId = get_post_meta( $product_id, 'offerId', true );

        if ($allow_price_update == "on" && !empty($offerId)){
            if ($product->is_type('simple')){
                $hash = md5(json_encode([
                    $product->get_regular_price(),
                ]));
        
                $hashBefore = get_post_meta( $product_id, "hashKeyPrice", true );
                if ($hash !== $hashBefore) {
                    if (empty($hashBefore)){
                        add_post_meta($product_id, "hashKeyPrice", $hash);
                    }
                    update_post_meta($product_id, "hashKeyPrice", $hash);
                    
                    $wpdb->insert($table_name, array(
                        'post_id' => $product_id,
                        'process' => 'PENDING',
                        'request' => 'update_price',
                    ));
    
                    $parent = wc_get_product( $product->get_parent_id());
                    if (isset($parent)){
                        $this->acbol_lite_set_proccessing($post_id, "Product prijs updaten");
                    }
    
                    return;
                    wp_die();
                }
            }
        }
    }
}