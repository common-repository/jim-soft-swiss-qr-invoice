<?php

/**
 * Fired during plugin activation
 *
 * @link       https://jimsoft.ch
 * @since      1.0.0
 *
 * @package    Jimsoft_Qr_Bill
 * @subpackage Jimsoft_Qr_Bill/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Jimsoft_Qr_Bill
 * @subpackage Jimsoft_Qr_Bill/includes
 * @author     JimSoft <info@jimsoft.ch>
 */
class Jimsoft_Qr_Bill_Activator {

	/**
	 * Load default options in database on Plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$default_options = [
			'invoice_additional_information' => __('Order #[order_number]', Jimsoft_Qr_Bill::SLUG),
			'creditor_company' => get_bloginfo( 'name' ),
			'creditor_street' => get_option('woocommerce_store_address'),
			'creditor_zip' => get_option('woocommerce_store_postcode'),
			'creditor_city' => get_option('woocommerce_store_city'),
			'pdf_font_size' => 10,
			'pdf_order_details_title' => __('Order #[order_number]', Jimsoft_Qr_Bill::SLUG),
			'pdf_order_details' => 'yes',
			'pdf_table_cellpadding' => 3,
			'pdf_color_primary' => '#333',
			'pdf_color_table_odd' => '#FFFFFF',
			'pdf_color_table_even' => '#EFEFEF',
			'pdf_logo_w' => 30,
			'pdf_address' => 'yes',
			'pdf_address_x' => 135,
			'pdf_address_y' => 51,
			'pdf_creditor_x' => 135,
			'pdf_date' => 'yes',
			'pdf_date_city' => get_option('woocommerce_store_city'),
			'pdf_date_format' => 'd.m.Y',
			'pdf_date_x' => 135
		];

		foreach ($default_options as $key => $value) {
			add_option(Jimsoft_Qr_Bill::PREFIX . $key, $value);
		}
	}

}
