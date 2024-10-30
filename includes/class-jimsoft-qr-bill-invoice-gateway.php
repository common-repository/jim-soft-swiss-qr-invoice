<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('WC_Payment_Gateway')) return;
/**
 * WC Invoice Gateway.
 *
 * Provides a Invoice Payment Gateway.
 */

class Jimsoft_Qr_Bill_Invoice_Gateway extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 * @since   1.0.0
	 */
	public function __construct() {
		// Setup general properties
		$this->setup_properties();

		// Load the settings
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title              = $this->get_option( 'title' );
		$this->description        = $this->get_option( 'description' );
		$this->instructions       = $this->get_option( 'instructions' );
		$this->order_status       = $this->get_option( 'order_status' );
		$this->user_roles         = $this->get_option( 'user_roles' );
		$this->enable_for_methods = $this->get_option( 'enable_for_methods', array() );
		$this->enable_for_virtual = true;

		// Actions
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_invoice', array( $this, 'thankyou_page' ) );

		// Restrict payment gateway to user roles.
		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'wc_invoice_restrict_gatway_user_roles' ) );

		// Customer Emails
		add_action('woocommerce_email_before_order_table', array( $this, 'email_instructions'), 10, 3);

	}

	/**
	 * Setup general properties for the gateway.
	 */
	protected function setup_properties() {
		$this->id                 = 'jimsoft_qr_invoice';
		$this->icon               = apply_filters('wc_invoice_gateway_icon', '');
		$this->method_title       = __( 'QR Invoice Payments', Jimsoft_Qr_Bill::SLUG );
		$this->method_description = __( 'Allows QR invoice payments.', Jimsoft_Qr_Bill::SLUG );
		$this->has_fields 	      = false;
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 * @since   1.0.0
	 * @return  void
	 */
	function init_form_fields() {

		$shipping_methods = array();

		foreach ( WC()->shipping()->load_shipping_methods() as $method ) {
			$shipping_methods[ $method->id ] = $method->get_method_title();
		}

		$this->form_fields = array(
			'enabled' => array(
				'title'       => __( 'Enable/Disable', Jimsoft_Qr_Bill::SLUG ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable QR Invoice Payment', Jimsoft_Qr_Bill::SLUG ),
				'default'     => 'yes'
			),
			'title' => array(
				'title'       => __( 'Title', Jimsoft_Qr_Bill::SLUG ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', Jimsoft_Qr_Bill::SLUG ),
				'default'     => __( 'Invoice Payment', Jimsoft_Qr_Bill::SLUG ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Description', Jimsoft_Qr_Bill::SLUG ),
				'type'        => 'textarea',
				'description' => __( 'Payment method description which the user sees during checkout.', Jimsoft_Qr_Bill::SLUG ),
				'default'     => __( 'You will find the QR invoice attached to your order confirmation.', Jimsoft_Qr_Bill::SLUG ),
				'desc_tip'    => true,
			),
			'instructions' => array(
				'title'       => __( 'Instructions', Jimsoft_Qr_Bill::SLUG ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page after checkout and included within the new order email.', Jimsoft_Qr_Bill::SLUG ),
				'default'     => __( 'You will find the invoice attached to this order confirmation.', Jimsoft_Qr_Bill::SLUG ),
				'desc_tip'    => true,
			),
			'order_status' => array(
				'title'             => __( 'Choose an order status', Jimsoft_Qr_Bill::SLUG ),
				'type'              => 'select',
				'class'             => 'wc-enhanced-select',
				'css'               => 'width: 450px;',
				'default'           => 'on-hold',
				'description'       => __( 'Choose the order status that will be set after checkout', Jimsoft_Qr_Bill::SLUG ),
				'options'           => $this->get_available_order_statuses(),
				'desc_tip'          => true,
				'custom_attributes' => array(
					'data-placeholder'  => __( 'Select order status', Jimsoft_Qr_Bill::SLUG )
				)
			)
		);

	}

	/**
	 * Get all order statuses available within WooCommerce
	 * @access  protected
	 * @since   1.0.3
	 * @return array
	 */
	protected function get_available_order_statuses() {
		$order_statuses = wc_get_order_statuses();

		$keys = array_map( function( $key ) {
			return str_replace('wc-', '', $key ); // Remove prefix
		}, array_keys( $order_statuses ) );

		$returned_statuses = array_combine( $keys, $order_statuses );

		// Remove the statuses of cancelled, refunded and failed from returning.
		unset( $returned_statuses['cancelled'] );
		unset( $returned_statuses['refunded'] );
		unset( $returned_statuses['failed'] );

		return $returned_statuses;

	}

	/**
	 * Get all user roles available within WordPress
	 * @access  protected
	 * @since   1.0.6
	 * @return array
	 */
	protected function get_available_user_roles() {
		global $wp_roles;

		$roles = $wp_roles->get_names();

		return $roles;
	}

	/**
	 * Restrict invoice gateway access selected user roles
	 * @access  public
	 * @since   1.0.6
	 */
	public function wc_invoice_restrict_gatway_user_roles( $available_gateways ) {

		$user = wp_get_current_user();
		$enabled_roles = $this->user_roles;

		if ( ! empty( $enabled_roles ) && array_diff( $enabled_roles, (array) $user->roles ) === $enabled_roles ) {
			unset( $available_gateways['invoice'] );
		}

		return $available_gateways;

	}

	/**
	 * Check If The Gateway Is Available For Use.
	 * @access  public
	 * @since   1.0.0
	 * @return bool
	 */
	public function is_available() {


		return parent::is_available();

	}

	/**
	 * Process the payment and return the result.
	 * @access  public
	 * @since   1.0.0
	 * @param int $order_id
	 * @return array
	 */
	function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );

		// Mark as on-hold (we're awaiting the invoice)
		$order->update_status( apply_filters( 'wc_invoice_gateway_process_payment_order_status', $this->order_status ), __('Awaiting invoice payment', Jimsoft_Qr_Bill::SLUG) );

		// Reduce stock levels
		wc_reduce_stock_levels( $order_id );

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' 	  => 'success',
			'redirect'	=> $this->get_return_url( $order )
		);

	}

	/**
	 * Output for the order received page.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function thankyou_page() {
		if ( $this->instructions ) {
			echo wpautop( wptexturize( $this->instructions ) );
		}
	}

	/**
	 * Add content to the WC emails.
	 * @access  public
	 * @since   1.0.0
	 * @param WC_Order $order
	 * @param bool $sent_to_admin
	 * @param bool $plain_text
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
        global $jimsoft_instructions_sent;
        if(!$jimsoft_instructions_sent) {
            $jimsoft_instructions_sent = false;
        }
		if ( !$jimsoft_instructions_sent && $this->instructions && ! $sent_to_admin && 'jimsoft_qr_invoice' === $order->get_payment_method() && apply_filters( 'wc_invoice_gateway_process_payment_order_status', $this->order_status ) !== 'wc-' . $order->get_status() ) {
			echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;

            $jimsoft_instructions_sent = true;
		}
	}

}
