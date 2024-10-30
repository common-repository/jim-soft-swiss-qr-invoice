<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

/** @var string $pdf_color_table_even */
/** @var string $pdf_color_table_odd */
/** @var string $td_style */
if (!defined('ABSPATH')) {
    exit;
}

if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
    return;
}

$bgcolor_odd = $pdf_color_table_odd;
$bgcolor_even = $pdf_color_table_even;
?>
<tr nobr="true">

    <td bgcolor="<?php echo $i % 2 ? esc_attr($bgcolor_even) : esc_attr($bgcolor_odd) ?>"
        width="<?php echo esc_attr($td_widths[0]); ?>" >
        <?php do_action('jimsoft_qrpdf_before_item_title', $item); ?>
        <?php
        $is_visible = $product && $product->is_visible();
        $product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);

        echo wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, $is_visible));

        if ($is_visible && $product->get_sku()) {
            echo ' / ' . $product->get_sku();
        }
        ?>
        <?php do_action('jimsoft_qrpdf_after_item_title', $item); ?>

        <?php if (!$this->get_option('pdf_order_details_meta_hide') || $this->get_option('pdf_order_details_meta_hide') === 'no'): ?>
            <?php foreach ($item->get_formatted_meta_data() as $meta_id => $meta): ?>
                <br>
                <i>
                    <?php echo wp_kses_post($meta->display_key); ?>: <?php echo strip_tags($meta->display_value); ?>
                </i>

            <?php endforeach; ?>
        <?php endif; ?>
        <?php do_action('jimsoft_qrpdf_after_item_meta', $item); ?>
    </td>

    <td bgcolor="<?php echo $i % 2 ? esc_attr($bgcolor_even) : esc_attr($bgcolor_odd) ?>"
        width="<?php echo esc_attr($td_widths[1]); ?>" align="right">
        <?php do_action('jimsoft_qrpdf_before_item_quantity', $item); ?>
        <?php

        $qty = $item->get_quantity();
        $refunded_qty = $order->get_qty_refunded_for_item($item_id);

        if ($refunded_qty) {
            $qty_display = '<del>' . esc_html($qty) . '</del> <ins>' . esc_html($qty - ($refunded_qty * -1)) . '</ins>';
        } else {
            $qty_display = esc_html($qty);
        }

        echo apply_filters('woocommerce_order_item_quantity_html', ' <span class="product-quantity">' . sprintf('&times;&nbsp;%s', $qty_display) . '</span>', $item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        ?>
        <?php do_action('jimsoft_qrpdf_after_item_quantity', $item); ?>
    </td>
    <td class="woocommerce-table__product-total product-total"
        bgcolor="<?php echo $i % 2 ? esc_attr($bgcolor_even) : esc_attr($bgcolor_odd) ?>" align="right"
        width="<?php echo esc_attr($td_widths[2]) ?>">
        <?php do_action('jimsoft_qrpdf_before_item_subtotal', $item); ?>
        <?php echo $order->get_formatted_line_subtotal($item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php do_action('jimsoft_qrpdf_after_item_subtotal', $item); ?>
    </td>

</tr>

<?php if ($show_purchase_note && $purchase_note) : ?>

    <tr class="woocommerce-table__product-purchase-note product-purchase-note">

        <td colspan="3"><?php echo wpautop(do_shortcode(wp_kses_post($purchase_note))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>

    </tr>

<?php endif; ?>
