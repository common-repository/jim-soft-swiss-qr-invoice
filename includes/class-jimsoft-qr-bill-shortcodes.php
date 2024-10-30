<?php

/**
 * Register all shortcodes
 *
 * @link       https://jimsoft.ch
 * @since      1.2.2
 *
 * @package    Jimsoft_Qr_Bill
 * @subpackage Jimsoft_Qr_Bill/includes
 */


/**
 * Register all shortcodes
 *
 * @package    Jimsoft_Qr_Bill
 * @subpackage Jimsoft_Qr_Bill/includes
 * @author     JimSoft <info@jimsoft.ch>
 */
class Jimsoft_Qr_Bill_Shortcodes
{
  public function __construct()
  {
    add_shortcode('jimsoft_display_qr_payslip', [$this, 'shortcode_display_qr_payslip']);
  }

  public function shortcode_display_qr_payslip($atts)
  {
    $atts = shortcode_atts(array(
      'order_id' => get_the_ID()
    ), $atts, 'jimsoft_display_qr_payslip');

    if(get_post_type($atts['order_id']) !== 'shop_order') {
      return 'no valid order id';
    }

    $generator = new Jimsoft_Qr_Bill_Invoice_Generator($atts['order_id']);
    $html = $generator->generate(true, true);

    return $html;
  }
}
