<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jimsoft.ch
 * @since      1.0.0
 *
 * @package    Jimsoft_Qr_Bill
 * @subpackage Jimsoft_Qr_Bill/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jimsoft_Qr_Bill
 * @subpackage Jimsoft_Qr_Bill/admin
 * @author     JimSoft <info@jimsoft.ch>
 */
class Jimsoft_Qr_Bill_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;


    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Jimsoft_Qr_Bill_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Jimsoft_Qr_Bill_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/jimsoft-qr-bill-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Jimsoft_Qr_Bill_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Jimsoft_Qr_Bill_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/jimsoft-qr-bill-admin.js', array('jquery'), $this->version, false);

    }

    public function woocommerce_add_settings($settings)
    {
        $settings[] = include plugin_dir_path(dirname(__FILE__)) . 'admin/class-jimsoft-qr-bill-wc-settings.php';

        return $settings;
    }

    public function add_order_meta_box_action_download_invoice($actions)
    {
        global $theorder;

        $actions[Jimsoft_Qr_Bill::PREFIX . 'download_pdf'] = __('QR invoice download', Jimsoft_Qr_Bill::PREFIX);

        return $actions;
    }

    /**
     * @param WC_Order $order
     */
    public function process_order_meta_box_action_download_invoice($order)
    {


        $generator = new Jimsoft_Qr_Bill_Invoice_Generator($order->get_id());

        $generator->generate();
    }

    public function shop_order_columns_add_download_invoice($columns)
    {
        $columns[Jimsoft_Qr_Bill::PREFIX . 'download'] = __('QR Invoice', Jimsoft_Qr_Bill::SLUG);

        return $columns;
    }

    public function populate_hpos_orders_column($column_name, $order)
    {
        if ($column_name === Jimsoft_Qr_Bill::PREFIX . 'download') {
            //$order = wc_get_order( $post_id ); // Get the WC_Order instance Object

            $slug = Jimsoft_Qr_Bill::PREFIX . 'download';
            $url = get_admin_url('edit.php') . '?jimsoft_order_id=' . $order->get_id(); // The order Id is required in the URL

            $css_classes = "button wc-action-button wc-action-button-" . $slug . ' ' . $slug;
            // Output the button
            echo '<p><a class="' . esc_attr($css_classes) . '" href="' . esc_url($url) . '" aria-label="' . esc_attr($slug) . '" target="_blank">Download</a></p>';
        }

    }

    public function manage_shop_order_posts_custom_column_download_invoice_content($column, $post_id)
    {
        if (Jimsoft_Qr_Bill::PREFIX . 'download' === $column) {
            //$order = wc_get_order( $post_id ); // Get the WC_Order instance Object

            $slug = Jimsoft_Qr_Bill::PREFIX . 'download';
            $url = get_admin_url('edit.php') . '?jimsoft_order_id=' . $post_id; // The order Id is required in the URL

            $css_classes = "button wc-action-button wc-action-button-" . $slug . ' ' . $slug;
            // Output the button
            echo '<p><a class="' . esc_attr($css_classes) . '" href="' . esc_url($url) . '" aria-label="' . esc_attr($slug) . '" target="_blank">Download</a></p>';
        }
    }

    public function bulk_actions_register_print_invoices($actions)
    {
        $actions['jimsoft_print_invoices'] = __('Download QR Invoices', Jimsoft_Qr_Bill::PREFIX);
        return $actions;
    }

    public function bulk_actions_handle_print_invoices($redirect_to, $action, $ids)
    {
        if ($action === 'jimsoft_print_invoices') {
            print_r($ids);
            wp_die('Works!');
        }

        return $redirect_to;
    }
}
