<div class="wrap woocommerce">
    <h1>Settings</h1>
    <div class="wrap">
            <?php
                $settings = get_registered_settings();
                $html = '<h2 class="nav-tab-wrapper">';

                foreach($settings as $setting){
                    if(strpos($setting['group'], 'ACBOL_Lite') !== false){
                        
                        if(strpos($setting['group'], 'section') !== false){
                            $cracked = accessProtected($setting['sanitize_callback'][0], 'settings_sections');
                            // print("<pre>".print_r($setting,true)."</pre>");

                            foreach ( $cracked as $section ) {
                                $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $section['id'], $section['header_title'] );
                            }
                            break;
                        }
                    }
                }

                $html .= '</h2>';
                
                function accessProtected($obj, $prop) {
                    $reflection = new ReflectionClass($obj);
                    $property = $reflection->getProperty($prop);
                    $property->setAccessible(true);
                    return $property->getValue($obj);
                }

                echo $html;
            ?>

       

            <div style="padding: 20px 10px;">
                <h4>Reset status</h4>
                <p>Reset de status voor alle producten in proccessing</p>
                <button id="acbol_lite_reset_proces_status" class="button button-primary">Reset proccessing</button> <p class="acbol_lite_reset_proces_response"></p>
            </div>

        <div class="metabox-holder">
            <?php foreach($settings as $setting){
                    if(strpos($setting['group'], 'ACBOL_Lite') !== false){
                        // var_dump($setting);
                        if(strpos($setting['group'], 'section') !== false){
                            ?>
                            <!-- style="display: none;" -->
                            <div id="<?php echo esc_attr( $setting['group'] ); ?>" class="group">
                                <form method="post" action="options.php">
                                <?php
                                    settings_fields( $setting['group'] );
                                    do_settings_sections( $setting['group'] );
                                    submit_button( 'Sla op', 'primary' );
                                ?>
                                </form>
                            </div>
                            <?php
                        }
                    }
                }
            ?>
        </div>
    </div>
</div>


<script>
    function ACBOL_Lite_setting_logic(controller, reason, setting){
        if (jQuery(controller).val() == reason){
            jQuery(setting).parent().parent().show();
        } 
        else {
            jQuery(setting).parent().parent().hide();
        }
    }

    jQuery(document).ready(function ($) {
        ACBOL_Lite_setting_logic("#ACBOL_Lite_section1\\[bol_transport_time\\]", "Binnen 24 uur", "#ACBOL_Lite_section1\\[bol_transport_time_orderd_before\\]");
        ACBOL_Lite_setting_logic("#ACBOL_Lite_section1\\[bol_ean\\]", "Product Custom Veld", "#ACBOL_Lite_section1\\[bol_ean_custom_field\\]");
        ACBOL_Lite_setting_logic("#ACBOL_Lite_section1\\[bol_ean\\]", "Product Attribuut", "#ACBOL_Lite_section1\\[bol_ean_product_attribute\\]");
        if(jQuery("#msnsmp-ACBOL_Lite_section1\\[bol_price_margin\\]").is(':checked')){
            jQuery("#ACBOL_Lite_section1\\[bol_price_margin_input\\]").parent().parent().show();
        } else {
            jQuery("#ACBOL_Lite_section1\\[bol_price_margin_input\\]").parent().parent().hide();
        }

        jQuery("label[for='ACBOL_Lite_section1\\[bol_import\\]']").css('opacity', '0.4');
        jQuery("label[for='ACBOL_Lite_section1\\[bol_import_lvb\\]']").css('opacity', '0.4');
        jQuery("label[for='ACBOL_Lite_section1\\[bol_import_retour\\]']").css('opacity', '0.4');
        jQuery("label[for='ACBOL_Lite_section1\\[bol_import_commission\\]']").css('opacity', '0.4');
        jQuery("label[for='ACBOL_Lite_section1\\[bol_transport\\]']").css('opacity', '0.4');
        jQuery("label[for='ACBOL_Lite_section1\\[bol_woocommerce_prices\\]").css('opacity', '0.4');
        jQuery("label[for='ACBOL_Lite_section1\\[bol_price_margin\\]']").css('opacity', '0.4');
        jQuery("#ACBOL_Lite_section1\\[bol_transport\\]").prop('disabled', 'disabled');

        jQuery('#ACBOL_Lite_section1\\[bol_client_secret\\]').parent().parent().after("<div class='acbol_lite-connection-container'><button id='acbol_lite-connection' class='button button-primary'>Test connectie</button><p id='acbol_lite-connection-status'></p></div>");
        jQuery("#acbol_lite-connection").on("click", function(){
            event.preventDefault();
            
            var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
            var data = {
                'action': 'acbol_lite_test_api_conn',
                'data' : {
                    'client_id': jQuery('#ACBOL_Lite_section1\\[bol_client_id\\]').val(),
                    'client_secret': jQuery("#ACBOL_Lite_section1\\[bol_client_secret\\]").val(),
                },
                
            };
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                success: function (response) {
                    if(response == "200"){
                        console.log(response)
                        jQuery("#acbol_lite-connection").prop('disabled', true);
                        jQuery("#acbol_lite-connection-status").text("Connectie succesvol").hide().fadeIn(500);
                        jQuery("#acbol_lite-connection-status").delay(1200).fadeOut(800);
                    } else {
                        console.log(response)
                        jQuery("#acbol_lite-connection-status").text("Connectie niet succesvol").hide().fadeIn(500);
                        jQuery("#acbol_lite-connection-status").delay(1200).fadeOut(800);
                    }                
                }
            });            
        });

        jQuery("#acbol_lite_reset_proces_status").on("click", function(){
            event.preventDefault();
            
            var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
            var data = {
                'action': 'acbol_lite_reset_proces',
                'data': '',
            };
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                success: function (response) {
                    console.log(response)
                    jQuery("#acbol_lite_reset_proces_status").prop('disabled', true);
                    jQuery(".acbol_lite_reset_proces_response").text(response + " Aantal producten process onderbroken").hide().fadeIn(500);
                    jQuery(".acbol_lite_reset_proces_response").delay(1200).fadeOut(800);         
                }
            });            
        });
    });

    jQuery('#msnsmp-ACBOL_Lite_section1\\[bol_price_margin\\]').click(function(){
        if(jQuery(this).is(':checked')){
            jQuery("#ACBOL_Lite_section1\\[bol_price_margin_input\\]").parent().parent().show();
        } else {
            jQuery("#ACBOL_Lite_section1\\[bol_price_margin_input\\]").parent().parent().hide();
        }
    });

    jQuery(document).on("change", "#ACBOL_Lite_section1\\[bol_transport_time\\]", function() {
        ACBOL_Lite_setting_logic("#ACBOL_Lite_section1\\[bol_transport_time\\]", "Binnen 24 uur", "#ACBOL_Lite_section1\\[bol_transport_time_orderd_before\\]");
    });

    jQuery(document).on("change", "#ACBOL_Lite_section1\\[bol_ean\\]", function() {
        ACBOL_Lite_setting_logic("#ACBOL_Lite_section1\\[bol_ean\\]", "Product Custom Veld", "#ACBOL_Lite_section1\\[bol_ean_custom_field\\]");
    });

    jQuery(document).on("change", "#ACBOL_Lite_section1\\[bol_ean\\]", function() {
        ACBOL_Lite_setting_logic("#ACBOL_Lite_section1\\[bol_ean\\]", "Product Attribuut", "#ACBOL_Lite_section1\\[bol_ean_product_attribute\\]");
    });

    jQuery(document).ready(function ($) {

        //Initiate Color Picker.
        $('.color-picker').iris();

        // Switches option sections
        $('.group').hide();
        var activetab = '';
        if ('undefined' != typeof localStorage) {
            activetab = localStorage.getItem('activetab');
        }
        if ('' != activetab && $(activetab).length) {
            $(activetab).fadeIn();
        } else {
            $('.group:first').fadeIn();
        }
        $('.group .collapsed').each(function () {
            $(this)
                .find('input:checked')
                .parent()
                .parent()
                .parent()
                .nextAll()
                .each(function () {
                    if ($(this).hasClass('last')) {
                        $(this).removeClass('hidden');
                        return false;
                    }
                    $(this)
                        .filter('.hidden')
                        .removeClass('hidden');
                });
        });

        if ('' != activetab && $(activetab + '-tab').length) {
            $(activetab + '-tab').addClass('nav-tab-active');
        } else {
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
        }
        $('.nav-tab-wrapper a').click(function (evt) {
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $(this)
                .addClass('nav-tab-active')
                .blur();
            var clicked_group = $(this).attr('href');
            if ('undefined' != typeof localStorage) {
                localStorage.setItem('activetab', $(this).attr('href'));
            }
            $('.group').hide();
            $(clicked_group).fadeIn();
            evt.preventDefault();
        });

        $( '.msnsmp-browse' ).on( 'click', function( event ) {
            event.preventDefault();

            var self = $( this );

            // Create the media frame.
            var file_frame = ( wp.media.frames.file_frame = wp.media({
                title: self.data( 'uploader_title' ),
                button: {
                    text: self.data( 'uploader_button_text' )
                },
                multiple: false
            }) );

            file_frame.on( 'select', function() {
                attachment = file_frame
                    .state()
                    .get( 'selection' )
                    .first()
                    .toJSON();

                self
                    .prev( '.msnsmp-url' )
                    .val( attachment.url )
                    .change();
            });

            // Finally, open the modal
            file_frame.open();
        });

        $( 'input.msnsmp-url' )
            .on( 'change keyup paste input', function() {
                var self = $( this );
                self
                    .next()
                    .parent()
                    .children( '.msnsmp-image-preview' )
                    .children( 'img' )
                    .attr( 'src', self.val() );
            })
            .change();

    });

</script>

<style type="text/css">
    /** WordPress 3.8 Fix **/
    .form-table th {
        padding: 20px 10px;
    }

    #wpbody-content .metabox-holder {
        padding-top: 5px;
    }

    .msnsmp-image-preview img {
        height: auto;
        max-width: 70px;
    }

    .msn-settings-separator {
        background: #ccc;
        border: 0;
        color: #ccc;
        height: 1px;
        position: absolute;
        left: 0;
        width: 99%;
    }

    .group .form-table input.color-picker {
        max-width: 100px;
    }
</style>