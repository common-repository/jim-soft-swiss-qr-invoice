=== Jim Soft Swiss QR Invoice ===
Contributors: jimsoft
Donate link: https://jimsoft.ch
Tags: woocommerce, invoice, qr
Requires at least: 4.7
Tested up to: 6.5
Stable tag: 1.2.16
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create Swiss QR Invoice in WooCommerce. You can customize the appearance of your invoices using various settings.

== Description ==

You want to create the new payment slips with QR code?
With Jim Soft QR Invoice you can do it in no time.
We offer you a simple, easy-to-use WooCommerce extension that lets you create the new payment slips quickly and easily.

= Features =
* Very easy to set up: You just need to store your (QR-)IBAN and start right away.
* No QR IBAN? You can also work with a normal IBAN without ESR, the invoices are still future-proof for the upcoming changes in 2022.
* Many setting options: Logo & color scheme, as well as positioning of the individual elements you can customize yourself. In addition, there are free text fields, with which you can supplement the invoices.
* Order items & total: Optionally, you can output various additional information on the invoice, including order items, current date, information about the creditor (eg VAT number), etc.
* Invoices are directly ready for printing: After entering the IBAN, the invoices are already configured so that they are directly ready for printing.
* Compatible for window letters: If you send the invoices by mail or enclose them in the package, you can freely position the customer's address so that it fits directly into the address window.
* Additional payment option "Pay with QR-bill".
* Attach QR invoice to order confirmation for existing payment options
* Attach QR invoice to specific email types
* Available in English & German

== Frequently Asked Questions ==

= Until when can the old payment slips (red and orange) still be used? =

The old payment slips can only be used until 30.9.2022.

= What is the QR IBAN? =

This is a unique bank reference number for your account. This is assigned by your house bank for your bank account so that incoming payments can be processed automatically.

= Can the QR Invoice also be used for deposits at the post office counter? =

Yes, as with red and orange payment slips, the QR-bill (payment part with receipt) can also be used for payment at the post office counter. In addition, a receipt is still available for confirming deposits.

= Can additional information such as VAT number, alternative payment methods, etc. be inserted in the PDF? =

Yes, through free text fields additional information can be easily inserted in the PDF directly in the settings.

== Template hooks ==

`do_action("jimsoft_qrpdf_before_title", WC_Order $order)`
`do_action("jimsoft_qrpdf_after_title", WC_Order $order)`

`do_action("jimsoft_qrpdf_after_item_title",  WC_Order_Item $item)`
`do_action("jimsoft_qrpdf_after_item_meta", WC_Order_Item $item)`

`do_action("jimsoft_qrpdf_before_item_quantity",  WC_Order_Item $item)`
`do_action("jimsoft_qrpdf_after_item_quantity", WC_Order_Item $item)`

`do_action("jimsoft_qrpdf_before_item_subtotal",  WC_Order_Item $item)`
`do_action("jimsoft_qrpdf_after_item_subtotal",  WC_Order_Item $item)`

`do_action("jimsoft_qrpdf_after_order_details_text", WC_Order $order)`
`do_action("jimsoft_qrpdf_after_table", WC_Order $order)`


if you need more hooks, just contact us by mail :)

== Screenshots ==

1. Settings 1: The debtor details for the invoice.
2. Settings 2: Text size, colors, logo (size & positioning).
3. Settings 3: Customer, vendor and date/location configuration/positioning.
4. preview of a PDF invoice
5. Settings 4: Payment option "Pay with QR-bill".
6. Settings 5: Attach QR invoice to order confirmation for existing payment options.

== Upgrade Notice ==
= 1.0.0 =
First release

== Changelog ==
= 1.0.0 =
* First release.
= 1.0.1 =
* Auto-Pagebreak with large Orders
= 1.1.0 =
* Additional payment option "Pay with QR-bill"
* Attach QR invoice to order confirmation for existing payment options
= 1.1.1 =
* Bugfix: for PHP 8.0 and higher
* Bugfix: check if class "WC_Payment_Gateway" exists
= 1.1.2 =
* New Feature: Display order item meta in PDF invoice
= 1.1.3 =
* Change: Append invoice slip in the last page in the pdf, not the first page
= 1.1.5 =
* New Feature: Margin Left & Margin Right settings for pdf
* Bugfix: Show also billing company on pdf
= 1.1.6 =
* Bugfix: PDF Template Order comment in Payment Slip
= 1.1.7 =
* Change: Change Position of customer Notice in pdf and add new template variables
= 1.1.8 =
* Bugfix: remove table offset in pdf
= 1.1.9 =
* Add [shipping_method] and [payment_method_title] to template variables
= 1.1.10 =
* Feature: Add border to summary table in pdf & add new placeholder [customer_note]
= 1.1.11 =
* Clean up translation strings
= 1.2.0 =
* Feature: change different fontsizes
= 1.2.1 =
* Bugfix: Order list text indentation
= 1.2.2 =
* add placeholder: [current_date], [order_date_created], [order_date_completed]
* add shortcode [jimsoft_display_qr_payslip order_id="XX"]
= 1.2.3 =
* choose email types for invoice attachment
= 1.2.4 =
* add customer_invoice email type to default email types
= 1.2.5 =
* fix pdf download link for special cases
= 1.2.6 =
* fix display company and name in debtor address on pdf
= 1.2.7 =
* show PDF generate Button in new high performance storage WooCommerce order table
= 1.2.8 =
* add hooks to template files
= 1.2.9 =
* format fix readme file
= 1.2.10 =
* format fix readme file again
= 1.2.11 =
* format readme code
= 1.2.12 =
* bugfix template get_sku on boolean
= 1.2.13 =
* bugfix template table alignment
* allow multiple pdf export in bulk edit on orders page
= 1.2.14 =
* bulk print for legacy orders
= 1.2.15 =
* version fix
= 1.2.16 =
* fix display logo on multiple page pdf
