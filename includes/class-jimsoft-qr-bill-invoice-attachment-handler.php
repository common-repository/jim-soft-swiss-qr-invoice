<?php

class Jimsoft_Qr_Bill_Invoice_Attachment_Handler {
    static $default_email_types = ['new_order', 'customer_on_hold_order', 'customer_processing_order', 'customer_invoice'];


	function attach_qr_invoice_to_mail ( $attachments , $email_id, $order ) {


		if ( ! is_a( $order, 'WC_Order' ) || ! isset( $email_id ) ) {
			return $attachments;
		}

        $emailTypes = get_option(Jimsoft_Qr_Bill::PREFIX . 'attach_to_mail_types');
        if(!is_array($emailTypes)) {
            $emailTypes = self::$default_email_types;
        }
        if(!in_array($email_id, $emailTypes)) {
            return $attachments;
        }

		if($order->get_payment_method() === 'jimsoft_qr_invoice' || get_option( Jimsoft_Qr_Bill::PREFIX . 'attach_to_mail_' . $order->get_payment_method() ) === 'yes') {
			$generator = new Jimsoft_Qr_Bill_Invoice_Generator($order->get_id());
			$file_path = $generator->generate(false);
			$attachments[] = $file_path;
		}


		return $attachments;
	}
}