<?php

use Sprain\SwissQrBill\DataGroup\Element\AdditionalInformation;
use Sprain\SwissQrBill\DataGroup\Element\CombinedAddress;
use Sprain\SwissQrBill\DataGroup\Element\CreditorInformation;
use Sprain\SwissQrBill\DataGroup\Element\PaymentAmountInformation;
use Sprain\SwissQrBill\DataGroup\Element\PaymentReference;
use Sprain\SwissQrBill\DataGroup\Element\StructuredAddress;
use Sprain\SwissQrBill\PaymentPart\Output\HtmlOutput\HtmlOutput;
use Sprain\SwissQrBill\QrBill;
use Sprain\SwissQrBill\PaymentPart\Output\TcPdfOutput\TcPdfOutput;
use Sprain\SwissQrBill\Reference\QrPaymentReferenceGenerator;

/**
 * The file that defines the generator class
 *
 * A class definition that generates PDF Invoices
 *
 * @link       https://jimsoft.ch
 * @since      1.0.0
 *
 * @package    Jimsoft_Qr_Bill
 * @subpackage Jimsoft_Qr_Bill/includes
 */
class Jimsoft_Qr_Bill_Invoice_Generator
{

    const DEFAULT_FONT_SIZE = 10;
    const TITLE_DEFAULT_FONT_SIZE = 16;


    private $order;
    private $multiple_orders = [];


    public function __construct($order_id)
    {

        //$this->generate();

        if(!is_array($order_id)) {
            $order_id = [$order_id];
        }

        foreach ($order_id as $id) {
            $order = wc_get_order($id);
            if (!$order) {
                throw new Exception("Order does not exist");
            }
            $this->multiple_orders[] = $order;
        }
        $this->order = $this->multiple_orders[0];
    }


    private function get_option($key)
    {
        $value = get_option(Jimsoft_Qr_Bill::PREFIX . $key);

        if (is_string($value)) {
            $replaces = [
                '[order_number]' => $this->order->get_order_number(),
                '[billing_first_name]' => $this->order->get_billing_first_name(),
                '[billing_last_name]' => $this->order->get_billing_last_name(),
                '[billing_company]' => $this->order->get_billing_company(),
                '[billing_postcode]' => $this->order->get_billing_postcode(),
                '[billing_city]' => $this->order->get_billing_city(),
                '[billing_address_1]' => $this->order->get_billing_address_1(),
                '[billing_address_2]' => $this->order->get_billing_address_2(),

                '[shipping_first_name]' => $this->order->get_shipping_first_name(),
                '[shipping_last_name]' => $this->order->get_shipping_last_name(),
                '[shipping_company]' => $this->order->get_shipping_company(),
                '[shipping_postcode]' => $this->order->get_shipping_postcode(),
                '[shipping_city]' => $this->order->get_shipping_city(),
                '[shipping_address_1]' => $this->order->get_shipping_address_1(),
                '[shipping_address_2]' => $this->order->get_shipping_address_2(),

                '[payment_method_title]' => $this->order->get_payment_method_title(),
                '[shipping_method]' => $this->order->get_shipping_method(),

                '[customer_note]' => $this->order->get_customer_note(),

                '[order_date_completed]' => $this->order->get_date_completed() ? $this->order->get_date_completed()->format('d.m.Y') : '',
                '[order_date_created]' => $this->order->get_date_created() ? $this->order->get_date_created()->format('d.m.Y') : '',
                '[current_date]' => date('d.m.Y'),
            ];
            $value = str_replace(array_keys($replaces), array_values($replaces), $value);
        }


        //default values
        if (!$value) {
            if ($key === 'pdf_order_details_title_fontsize') {
                $value = Jimsoft_Qr_Bill_Invoice_Generator::TITLE_DEFAULT_FONT_SIZE;
            }
        }

        return $value;
    }


    /**
     * Generate the a QR Bill as a PDF
     *
     */
    public function generate($inline = true, $returnPaySlipHtml = false)
    {

        $pdf_margin_left = $this->get_option('pdf_margin_left') ? $this->get_option('pdf_margin_left') : 10;
        $pdf_margin_right = $this->get_option('pdf_margin_right') ? $this->get_option('pdf_margin_right') : 10;

        $tcPdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
        $tcPdf->setPrintHeader(false);
        $tcPdf->setPrintFooter(false);
        $tcPdf->SetAutoPageBreak(true, 10);
        $tcPdf->SetLeftMargin($pdf_margin_left);
        $tcPdf->SetRightMargin($pdf_margin_right);

        foreach ($this->multiple_orders as $order) {
            $this->order = $order;

            $normal_iban = $this->get_option('normal_iban');
            $qr_iban = $this->get_option('qr_iban');
            $customer_identification_number = $this->get_option('customer_identification_number');
            $creditor_company = $this->get_option('creditor_company');
            $creditor_salutation = $this->get_option('creditor_salutation');
            $creditor_first_name = $this->get_option('creditor_first_name');
            $creditor_last_name = $this->get_option('creditor_last_name');
            $creditor_street = $this->get_option('creditor_street');
            $creditor_zip = $this->get_option('creditor_zip');
            $creditor_city = $this->get_option('creditor_city');

            $order_id = $this->order->get_id();
            $qrBill = QrBill::create();


            $creditor_name = $creditor_company;

            if (!$creditor_company) {
                $creditor_name = $creditor_first_name . ' ' . $creditor_last_name;
            }

            $qrBill->setCreditor(
                CombinedAddress::create(
                    $creditor_name,
                    $creditor_street,
                    $creditor_zip . ' ' . $creditor_city,
                    'CH'
                )
            );

            if ($qr_iban) {

                if (!$customer_identification_number) {
                    $customer_identification_number = null;
                }

                $referenceNumber = QrPaymentReferenceGenerator::generate(
                    $customer_identification_number,  // You receive this number from your bank (BESR-ID). Unless your bank is PostFinance, in that case use NULL.
                    $order->get_order_number()
                );

                $qrBill->setPaymentReference(
                    PaymentReference::create(
                        PaymentReference::TYPE_QR,
                        $referenceNumber
                    )
                );
                $qrBill->setCreditorInformation(
                    CreditorInformation::create($qr_iban)
                );
            } else {
                $qrBill->setPaymentReference(
                    PaymentReference::create(
                        PaymentReference::TYPE_NON
                    )
                );
                $qrBill->setCreditorInformation(
                    CreditorInformation::create($normal_iban)
                );
            }


            if ($value = $this->get_option('invoice_additional_information')) {

                $qrBill->setAdditionalInformation(
                    AdditionalInformation::create(
                        $value
                    )
                );
            }


            $qrBill->setUltimateDebtor(
                StructuredAddress::createWithStreet(
                    $order->get_billing_company() ? $this->order->get_billing_company() : $this->order->get_formatted_billing_full_name(),
                    $order->get_billing_address_1(),
                    $order->get_billing_address_2(),
                    $order->get_billing_postcode(),
                   $order->get_billing_city(),
                    'CH'
                )
            );

            $qrBill->setPaymentAmountInformation(
                PaymentAmountInformation::create('CHF', $this->order->get_total())
            );
            /*
                $violations = $qrBill->getViolations();

                if ( $violations->count() ) {
                    ?>
                    <h3>Error List</h3>
                    <table>

                        <?php foreach ( $violations as $violation ): ?>
                            <?php
                            ?>
                            <tr>
                                <th><?php echo wp_kses_post($violation->getPropertyPath()); ?></th>
                                <td><?php echo wp_kses_post($violation->getMessage()); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                    exit;
                }
                */

            //$tcPdf->SetFooterMargin(400);
            $tcPdf->AddPage();


            $locale = 'en';

            if (get_bloginfo('language')) {
                $locale = explode('-', get_bloginfo('language'))[0];
            }


            $tcPdf->SetFontSize(Jimsoft_Qr_Bill_Invoice_Generator::DEFAULT_FONT_SIZE);
            if (is_numeric($this->get_option('pdf_font_size'))) {
                $tcPdf->SetFontSize($this->get_option('pdf_font_size'));
            }

            if ($this->get_option('pdf_logo') === 'yes') {
                $this->printLogo($tcPdf, $this->order);
            }
            if ($this->get_option('pdf_creditor')) {

                $fs_reset = $tcPdf->getFontSizePt();
                if ($fs = $this->get_option('pdf_creditor_fontsize'))
                    $tcPdf->SetFontSize($fs);
                $this->printCreditor($tcPdf, $this->order);

                $tcPdf->SetFontSize($fs_reset);
            }
            if ($this->get_option('pdf_address') === 'yes') {
                $fs_reset = $tcPdf->getFontSizePt();
                if ($fs = $this->get_option('pdf_address_fontsize'))
                    $tcPdf->SetFontSize($fs);

                $this->printAddress($tcPdf, $this->order);

                $tcPdf->SetFontSize($fs_reset);
            }
            if ($this->get_option('pdf_date') === 'yes') {
                $fs_reset = $tcPdf->getFontSizePt();
                if ($fs = $this->get_option('pdf_date_fontsize'))
                    $tcPdf->SetFontSize($fs);

                $this->printDate($tcPdf, $this->order);

                $tcPdf->SetFontSize($fs_reset);
            }


            $pdf_color_primary = '#333333';
            $pdf_color_table_even = '#EFEFEF';
            $pdf_color_table_odd = '#FFFFFF';

            if ($value = $this->get_option('pdf_color_primary')) {
                $pdf_color_primary = $value;
            }
            if ($value = $this->get_option('pdf_color_table_odd')) {
                $pdf_color_table_odd = $value;
            }
            if ($value = $this->get_option('pdf_color_table_even')) {
                $pdf_color_table_even = $value;
            }


            if ($this->get_option('pdf_order_details') === 'yes') {
                ob_start();
                include __DIR__ . '/../wc-templates/order/order-details.php';
                $html_order = ob_get_clean();
                $tcPdf->writeHTML($html_order);
            }


            if ($tcPdf->GetY() > 192) {
                $tcPdf->AddPage();
            }

            if ($returnPaySlipHtml) {

                $output = new HtmlOutput($qrBill, $locale);

                $html = $output
                    ->setPrintable(true)
                    ->getPaymentPart();

                return $html;
            }

            //$tcPdf->setPage( 1 );
            $output = new TcPdfOutput($qrBill, $locale, $tcPdf);
            $output
                ->setPrintable(false)
                ->getPaymentPart();

            echo $order_id . '<br>';
        }

        $temp_dir = WP_CONTENT_DIR . '/jimsoft_invoices_temp';

        if (!is_dir($temp_dir)) {
            @mkdir($temp_dir);
        }
        if (!file_exists($temp_dir . '/.htaccess')) {
            file_put_contents($temp_dir . '/.htaccess', 'Deny from all');
        }

        $filename = $temp_dir . '/Invoice-' . $order->get_order_number() . '.pdf';

        $tcPdf->Output($filename, 'F');
        if ($inline) {
            header("Content-type:application/pdf");
            header("Content-Disposition:attachment;filename=" . basename($filename));
            readfile($filename);
            exit;
        }

        return $filename;
    }

    /**
     * Print debitor address on pdf
     *
     * @param TCPDF $tcPdf
     * @param $order
     */
    private function printAddress($tcPdf, $order)
    {

        $address = '';
        if ($this->order->get_billing_company()) {
            $address .= $this->order->get_billing_company() . PHP_EOL;
        }
        if ($this->order->get_formatted_billing_full_name()) {
            $address .= $this->order->get_formatted_billing_full_name() . PHP_EOL;
        }
        $address .= $order->get_billing_address_1() . PHP_EOL;
        if ($order->get_billing_address_2()) {
            $address .= $order->get_billing_address_2() . PHP_EOL;
        }
        $address .= $order->get_billing_postcode() . ' ' . $order->get_billing_city() . PHP_EOL;


        $x = '';
        $y = '';

        if ($value = $this->get_option('pdf_address_x')) {
            $x = $value;
        }
        if ($value = $this->get_option('pdf_address_y')) {
            $y = $value;
        }

        $tcPdf->MultiCell(0, 30, $address, 0, 'L', false, 1, $x, $y);
    }

    /**
     * Print logo on PDF
     *
     * @param TCPDF $tcPdf
     * @param $order
     */
    private function printLogo($tcPdf, $order)
    {

        $url = $this->get_option('pdf_logo_url');

        $w = 0;
        $h = 0;

        // get width and height from options
        if (is_numeric($this->get_option('pdf_logo_w'))) {
            $w = $this->get_option('pdf_logo_w');
        }
        if (is_numeric($this->get_option('pdf_logo_h'))) {
            $h = $this->get_option('pdf_logo_h');
        }

        $x = '';
        $y = '';

        // get position from options
        if (is_numeric($this->get_option('pdf_logo_x'))) {
            $x = $this->get_option('pdf_logo_x');
        }
        if (is_numeric($this->get_option('pdf_logo_y'))) {
            $y = $this->get_option('pdf_logo_y');
        }

        $image = @file_get_contents($url);
        if ($image != '') {
            $filename = uniqid().'.jpg';
            file_put_contents('/tmp/'.$filename, $image);
            $tcPdf->Image('/tmp/'.$filename, $x, $y, $w, $h, );
        }

    }

    /**
     * Print Creditor textarea on PDF
     *
     * @param TCPDF $tcPdf
     * @param $order
     */
    private function printCreditor($tcPdf, $order)
    {

        $x = '';
        $y = '';

        if (is_numeric($this->get_option('pdf_creditor_x'))) {
            $x = $this->get_option('pdf_creditor_x');
        }
        if (is_numeric($this->get_option('pdf_creditor_y'))) {
            $y = $this->get_option('pdf_creditor_y');
        }

        $tcPdf->MultiCell(0, 30, $this->get_option('pdf_creditor'), 0, 'L', false, 1, $x, $y);
    }

    /**
     * Print date and location on PDF
     *
     * @param TCPDF $tcPdf
     * @param $order
     */
    private function printDate($tcPdf, $order)
    {

        $x = '';
        $y = '';

        if (is_numeric($this->get_option('pdf_date_x'))) {
            $x = $this->get_option('pdf_date_x');
        }
        if (is_numeric($this->get_option('pdf_date_y'))) {
            $y = $this->get_option('pdf_date_y');
        }


        $string = '';

        if ($this->get_option('pdf_date_city')) {
            $string .= $this->get_option('pdf_date_city') . ', ';
        }
        $string .= date($this->get_option('pdf_date_format'));

        $tcPdf->MultiCell(0, 0, $string, 0, 'L', false, 1, $x, $y);
    }
}
