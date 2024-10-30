<?php
/**
 * Extends the WC_Settings_Page class
 *
 * @link        https://paulmiller3000.com
 * @since       1.0.0
 *
 * @package     P3k_Galactica
 * @subpackage  P3k_Galactica/admin
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Jimsoft_Qr_Bill_WC_Settings' ) ) {

    /**
     * Settings class
     *
     * @since 1.0.0
     */
    class Jimsoft_Qr_Bill_WC_Settings extends WC_Settings_Page {

        /**
         * Constructor
         * @since  1.0
         */



        public function __construct() {



            $this->id    = Jimsoft_Qr_Bill::SLUG;
            $this->label = __( 'QR Invoice', Jimsoft_Qr_Bill::SLUG );

            // Define all hooks instead of inheriting from parent
            add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
            add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
            add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
            add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );

        }


        /**
         * Get sections.
         *
         * @return array
         */
        public function get_sections() {
            $sections = array(
                '' => __( 'Settings', Jimsoft_Qr_Bill::SLUG )
            );

            return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
        }


        /**
         * Get settings array
         *
         * @return array
         */
        public function get_settings() {

            global $current_section;
            $prefix = Jimsoft_Qr_Bill::PREFIX;
            $settings = array();

            switch ($current_section) {

                default:
                    include 'partials/jimsoft-qr-bill-settings-main.php';
            }
            return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
        }

        /**
         * Output the settings
         */
        public function output() {
            global $current_section;

            switch ($current_section) {
                case 'results':
                    include 'partials/p3k-galactica-settings-results.php';
                    break;
                default:
                    $settings = $this->get_settings();
                    WC_Admin_Settings::output_fields( $settings );
            }

        }

        /**
         * Save settings
         *
         * @since 1.0
         */
        public function save() {
            $settings = $this->get_settings();

            WC_Admin_Settings::save_fields( $settings );
        }

    }

}


return new Jimsoft_Qr_Bill_WC_Settings();