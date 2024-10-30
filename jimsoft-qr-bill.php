<?php
/**
 *
 * @link              https://jimsoft.ch
 * @since             1.0.0
 * @package           Jimsoft_Qr_Bill
 *
 * @wordpress-plugin
 * Plugin Name:       Jim Soft Swiss QR Invoice
 * Description:       With this plugin you can generate a swiss QR invoice for WooCommerce orders.
 * Version:           1.2.16
 * Author:            Jim Soft
 * Author URI:        https://jimsoft.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jimsoft-qr-bill
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}


require __DIR__ . '/includes/swiss-qr-bill/vendor/autoload.php';
require __DIR__ . '/includes/tcpdf_min/tcpdf.php';


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('JIMSOFT_QR_BILL_VERSION', '1.2.2');


/**
 * Add plugin action links.
 *
 * Add a link to the settings page on the plugins.php page.
 *
 * @since 1.0.0
 *
 * @param  array  $links List of existing plugin action links.
 * @return array         List of modified plugin action links.
 */
function jimsoft_qr_bill_plugin_action_links_add_settings_link($links)
{

	$links = array_merge(array(
		'<a href="' . esc_url(admin_url('/admin.php?page=wc-settings&tab=jimsoft-qr-bill')) . '">' . __('Settings', Jimsoft_Qr_Bill::SLUG) . '</a>'
	), $links);

	return $links;
}
add_action('plugin_action_links_' . plugin_basename(__FILE__), 'jimsoft_qr_bill_plugin_action_links_add_settings_link');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jimsoft-qr-bill-activator.php
 */
function activate_jimsoft_qr_bill()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-jimsoft-qr-bill-activator.php';
	Jimsoft_Qr_Bill_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jimsoft-qr-bill-deactivator.php
 */
function deactivate_jimsoft_qr_bill()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-jimsoft-qr-bill-deactivator.php';
	Jimsoft_Qr_Bill_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_jimsoft_qr_bill');
register_deactivation_hook(__FILE__, 'deactivate_jimsoft_qr_bill');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-jimsoft-qr-bill.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jimsoft_qr_bill()
{

	$plugin = new Jimsoft_Qr_Bill();
	$plugin->run();
}
run_jimsoft_qr_bill();

/**
 * Plugin url.
 *
 * @return string
 */
function jimsoft_qr_bill_plugin_url() {
    return untrailingslashit( plugins_url( '/', __FILE__ ) );
}

/**
 * Plugin url.
 *
 * @return string
 */
function jimsoft_qr_bill_plugin_abspath() {
    return trailingslashit( plugin_dir_path( __FILE__ ) );
}

