<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Dummy Payments Blocks integration
 *
 * @since 1.0.3
 */
final class Jimsoft_Qr_Bill_Invoice_Gateway_Blocks_Support  extends AbstractPaymentMethodType {

    /**
     * The gateway instance.
     *
     * @var Jimsoft_Qr_Bill_Invoice_Gateway
     */
    private $gateway;

    /**
     * Payment method name/id/slug.
     *
     * @var string
     */
    protected $name = 'invoice';

    /**
     * Initializes the payment method type.
     */
    public function initialize() {
        //$this->settings = get_option( 'woocommerce_dummy_settings', [] );
        $this->gateway  = new Jimsoft_Qr_Bill_Invoice_Gateway();
    }

    /**
     * Returns if this payment method should be active. If false, the scripts will not be enqueued.
     *
     * @return boolean
     */
    public function is_active() {
        return $this->gateway->is_available();
    }

    /**
     * Returns an array of scripts/handles to be registered for this payment method.
     *
     * @return array
     */
    public function get_payment_method_script_handles() {
        $script_path       = '/assets/js/frontend/blocks.js';
        $script_asset_path = jimsoft_qr_bill_plugin_abspath() . 'assets/js/frontend/blocks.asset.php';
        $script_asset      = file_exists( $script_asset_path )
            ? require( $script_asset_path )
            : array(
                'dependencies' => array(),
                'version'      => '1.2.0'
            );
        $script_url        = jimsoft_qr_bill_plugin_url() . $script_path;

        wp_register_script(
            'jimsoft-qr-bill-invoice-blocks',
            $script_url,
            $script_asset[ 'dependencies' ],
            $script_asset[ 'version' ],
            true
        );

        if ( function_exists( 'wp_set_script_translations' ) ) {
            wp_set_script_translations( 'jimsoft-qr-bill-invoice-blocks', 'jimsoft-qr-bill-invoice', jimsoft_qr_bill_plugin_abspath(). 'languages/' );
        }

        return [ 'jimsoft-qr-bill-invoice-blocks' ];
    }

    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     */
    public function get_payment_method_data() {
        return [
            //'title'       => $this->get_setting( 'title' ),
            'title' => get_option('woocommerce_jimsoft_qr_invoice_title'),
            'description' => get_option('woocommerce_jimsoft_qr_invoice_description'),
            //'description' => $this->get_setting( 'description' ),
            'supports'    => array_filter( $this->gateway->supports, [ $this->gateway, 'supports' ] )
        ];
    }
}