<?php
/**
 * Woocommerce_Deactive_Notice Class File
 *
 * This file contains admin notices to show that Woocommerce is deactivated in admin panel
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Admin\Notices;


use ACBOL_Lite\Includes\Abstracts\Admin_Notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Woocommerce_Deactive_Notice Class File
 *
 * This file contains admin notices to show that Woocommerce is deactivated in admin panel
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 *
 * @see        https://code.tutsplus.com/series/persisted-wordpress-admin-notices--cms-1252
 * @see        https://code.tutsplus.com/tutorials/persisted-wordpress-admin-notices-part-1--cms-30134
 */
class Woocommerce_Deactive_Notice extends Admin_Notice {


	/**
	 * Method to show admin notice which is Woocommerce is not activated.
	 *
	 * @param array $args Arguments which are needed to show on notice
	 */
	public function show_admin_notice() {
		?>
        <div class="notice notice-error">
            <p>
				<?php _e(
					'Unfortunately Woocommerce is not activate. So you can not use feature of this plugin ',
                    ACBOL_Lite_TEXTDOMAIN
				) ?>
            </p>
        </div>

		<?php
	}

}
