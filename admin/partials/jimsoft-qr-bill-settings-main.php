<?php

/** @var string $prefix */


$settings_payment_gateways = [
	[
		'name' => __( 'Attach invoice to other payment methods', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'title',
		'id'   => $prefix . 'system_settings'
	],
];

$gateways = WC()->payment_gateways->get_available_payment_gateways();

$available = false;
if( $gateways ) {
	foreach( $gateways as $gateway ) {
		if($gateway->id === 'jimsoft_qr_invoice') continue;
		$settings_payment_gateways[] = 	[
			'id'   => $prefix . 'attach_to_mail_' . $gateway->id,
			'name' => __( 'Attach to ', Jimsoft_Qr_Bill::SLUG ) . ' '.$gateway->title,
			'type' => 'checkbox'
		];
        $available = true;

	}
}
if (!$available) {
    $settings_payment_gateways[] = 	[
        'id'   => $prefix . 'attach_to_mail_no_gateway',
        'name' => '-- ' . __( 'No other payment gateways available', Jimsoft_Qr_Bill::SLUG ) . ' --',
        'type' => 'title'
    ];
}

$emails = WC()->mailer()->get_emails();
$emailOptions = [];
foreach($emails as $email) {
    $emailOptions[$email->id] = $email->title;
}
$settings_payment_gateways[] = 	[
    'id'   => $prefix . 'attach_to_mail_types',
    'name' => __('Choose email types for invoice attachment'),
    'type' => 'multiselect',
    'default' => Jimsoft_Qr_Bill_Invoice_Attachment_Handler::$default_email_types,
    'css' => 'height: 250px',
    'options' => $emailOptions
];

$settings_payment_gateways[] =
	[
		'type' => 'sectionend',
		'desc' => '',
		'id'   => $prefix . 'system_invoice_gateway'
	];


$normal_settings = [


	[
		'name' => __( 'Bank data', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'title',
		'id'   => $prefix . 'banking_config_settings'
	],
	[
		'id'       => $prefix . 'normal_iban',
		'name'     => __( 'Default IBAN', Jimsoft_Qr_Bill::SLUG ),
		'type'     => 'text',
		'desc_tip' => __( 'Normal IBAN number (if no QR-Iban available).', Jimsoft_Qr_Bill::SLUG )
	],
	[
		'id'       => $prefix . 'qr_iban',
		'name'     => __( 'QR-IBAN', Jimsoft_Qr_Bill::SLUG ),
		'type'     => 'text',
		'desc_tip' => __( 'The invoicing system with QR-IBAN is intended for those who want to have an invoicing and payment capture system compatible with the orange (ISR) and red (ES) payment slips of the ISR system. Your bank must assign you a QR-IBAN account number for this purpose. The QR-IBAN number starting with the 5 number begins with 3. Your ISO20022 digital account statement (camt053) will only show the total of all payments for the day.', Jimsoft_Qr_Bill::SLUG )
	],
	[
		'id'       => $prefix . 'customer_identification_number',
		'name'     => __( 'Customer Identification Number', Jimsoft_Qr_Bill::SLUG ),
		'type'     => 'text',
		'desc_tip' => __( 'The customer Identification number acts as this unique number that helps banks identify which customer is being referred to with regards to any query or service request. Every bank has its way of creating Customer ID numbers.', Jimsoft_Qr_Bill::SLUG )
	],
	[
		'id'       => $prefix . 'invoice_additional_information',
		'name'     => __( 'Invoice additional information', Jimsoft_Qr_Bill::SLUG ),
		'type'     => 'text',
	],
	[
		'type' => 'sectionend',
		'desc' => '',
		'id'   => $prefix . 'banking_config_settings'
	],
	[
		'name'  => __( 'Creditor', Jimsoft_Qr_Bill::SLUG ),
		'type'  => 'title',
		'id'    => $prefix . 'creditor_config_settings',
		'value' => 'test'
	],
	[
		'id'   => $prefix . 'creditor_company',
		'name' => __( 'Company', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text',
	],
	[
		'id'   => $prefix . 'creditor_salutation',
		'name' => __( 'Salutation', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text',
	],
	[
		'id'   => $prefix . 'creditor_first_name',
		'name' => __( 'First name', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text',
	],
	[
		'id'   => $prefix . 'creditor_last_name',
		'name' => __( 'Last name', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text',
	],
	[
		'id'   => $prefix . 'creditor_street',
		'name' => __( 'Street', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text',
	],
	[
		'id'   => $prefix . 'creditor_zip',
		'name' => __( 'Postcode', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text',
	],
	[
		'id'   => $prefix . 'creditor_city',
		'name' => __( 'City', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text',
	],
	[
		'type' => 'sectionend',
		'id'   => $prefix . 'creditor_config_settings'
	],

	[
		'name'  => __( 'PDF Configuration', Jimsoft_Qr_Bill::SLUG ),
		'type'  => 'title',
		'id'    => $prefix . 'pdf_config_settings',
		'value' => ''
	],
	[
		'id'          => $prefix . 'pdf_font_size',
		'name'        => __( 'Fontsize', Jimsoft_Qr_Bill::SLUG ),
		'type'        => 'number',
	],
	[
		'id'          => $prefix . 'pdf_margin_left',
		'name'        => __( 'Margin left', Jimsoft_Qr_Bill::SLUG ),
		'type'        => 'number',
        'value' =>  get_option($prefix. 'pdf_margin_left') ? get_option($prefix. 'pdf_margin_left') : 10
	],
	[
		'id'          => $prefix . 'pdf_margin_right',
		'name'        => __( 'Margin right', Jimsoft_Qr_Bill::SLUG ),
		'type'        => 'number',
        'value' => get_option($prefix. 'pdf_margin_right') ? get_option($prefix. 'pdf_margin_right') : 10
	],
	[
		'id'          => $prefix . 'pdf_order_details_title',
		'name'        => __( 'Order details title', Jimsoft_Qr_Bill::SLUG ),
		'type'        => 'text',
		'value'       => get_option( $prefix . 'pdf_order_details_title' ) ? get_option( $prefix . 'pdf_order_details_title' ) : 'Bestellung #[order_number]'
	],
    [
        'id'   => $prefix . 'pdf_order_details_title_fontsize',
        'name' => __( 'Order details title fontsize', Jimsoft_Qr_Bill::SLUG ),
        'type' => 'number',
        'desc_tip' => __( 'Default value', Jimsoft_Qr_Bill::SLUG ). ': ' . Jimsoft_Qr_Bill_Invoice_Generator::TITLE_DEFAULT_FONT_SIZE,
    ],
	[
		'id'       => $prefix . 'pdf_order_details_text',
		'name'     => __( 'Order details text', Jimsoft_Qr_Bill::SLUG ),
		'type'     => 'textarea',
	],
    [
        'id'   => $prefix . 'pdf_order_details_text_fontsize',
        'name' => __( 'Order details text fontsize', Jimsoft_Qr_Bill::SLUG ),
        'type' => 'number',
    ],
	[
		'id'   => $prefix . 'pdf_order_details',
		'name' => __( 'Show order positions', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'checkbox'
	],
	[
		'id'   => $prefix . 'pdf_order_details_meta_hide',
		'name' => __( 'Order positions hide meta', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'checkbox'
	],
	[
		'id'   => $prefix . 'pdf_table_fontsize',
		'name' => __( 'Table fontsize', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'number',
	],
	[
		'id'   => $prefix . 'pdf_table_cellpadding',
		'name' => __( 'Table cell padding', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'number'
	],
	[
		'id'       => $prefix . 'pdf_color_primary',
		'name'     => __( 'Table primary color', Jimsoft_Qr_Bill::SLUG ),
		'type'     => 'color',
		'desc_tip' => 'Primäre Farbe für die Rechnung',
	],
	[
		'id'   => $prefix . 'pdf_color_table_odd',
		'name' => __( 'Table odd row color', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'color'
	],
	[
		'id'   => $prefix . 'pdf_color_table_even',
		'name' => __( 'Table even row color', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'color'
	],
	[
		'id'   => $prefix . 'pdf_color_table_border',
		'name' => __( 'Table summary: border color', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'color'
	],
	[
		'id'   => $prefix . 'pdf_show_customer_notice',
		'name' => __( 'Show customer notice', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'checkbox'
	],
    [
        'id'   => $prefix . 'pdf_customer_notice_fontsize',
        'name' => __( 'Customer notice fontsize', Jimsoft_Qr_Bill::SLUG ),
        'type' => 'number',
    ],
	[
		'id'   => $prefix . 'pdf_logo',
		'name' => __( 'Show logo', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'checkbox'
	],
	[
		'id'   => $prefix . 'pdf_logo_url',
		'name' => __( 'Logo URL', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text'
	],
	[
		'id'   => $prefix . 'pdf_logo_x',
		'name' => __( 'Logo position (horizontal)', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'id'   => $prefix . 'pdf_logo_y',
		'name' => __( 'Logo position (vertical)', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'id'   => $prefix . 'pdf_logo_w',
		'name' => __( 'Logo width', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'id'   => $prefix . 'pdf_logo_h',
		'name' => __( 'Logo height', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'id'   => $prefix . 'pdf_address',
		'name' => __( 'Show debitor address', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'checkbox'
	],
    [
        'id'   => $prefix . 'pdf_address_fontsize',
        'name' => __( 'Debitor address text fontsize', Jimsoft_Qr_Bill::SLUG ),
        'type' => 'number',
    ],
	[
		'id'          => $prefix . 'pdf_address_x',
		'name'        => __( 'Debitor address position (horizontal)', Jimsoft_Qr_Bill::SLUG ),
		'type'        => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'id'          => $prefix . 'pdf_address_y',
		'name'        => __( 'Debitor address position (vertical)', Jimsoft_Qr_Bill::SLUG ),
		'type'        => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],

	[
		'id'   => $prefix . 'pdf_creditor',
		'name' => __( 'Creditor textarea', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'textarea'
	],
    [
        'id'   => $prefix . 'pdf_creditor_fontsize',
        'name' => __( 'Creditor fontsize', Jimsoft_Qr_Bill::SLUG ),
        'type' => 'number',
    ],
	[
		'id'          => $prefix . 'pdf_creditor_x',
		'name'        => __( 'Creditor textarea position (horizontal)', Jimsoft_Qr_Bill::SLUG ),
		'type'        => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'id'          => $prefix . 'pdf_creditor_y',
		'name'        => __( 'Creditor textarea position (vertical)', Jimsoft_Qr_Bill::SLUG ),
		'type'        => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'id'   => $prefix . 'pdf_date',
		'name' => __( 'Show date/location', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'checkbox'
	],
    [
        'id'   => $prefix . 'pdf_date_fontsize',
        'name' => __( 'Date/Location fontsize', Jimsoft_Qr_Bill::SLUG ),
        'type' => 'number',
    ],
	[
		'id'   => $prefix . 'pdf_date_city',
		'name' => __( 'Date location', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'text',
	],
	[
		'id'    => $prefix . 'pdf_date_format',
		'name'  => __( 'Date output format', Jimsoft_Qr_Bill::SLUG ),
		'type'  => 'text',
		'desc_tip' => __('Format for date() function e.g. "d.m.Y"')
	],
	[
		'id'   => $prefix . 'pdf_date_x',
		'name' => __( 'Date position (horizontal)', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'id'   => $prefix . 'pdf_date_y',
		'name' => __( 'Date position (vertical)', Jimsoft_Qr_Bill::SLUG ),
		'type' => 'number',
		'desc_tip'    => __('in millimeters', Jimsoft_Qr_Bill::SLUG),
	],
	[
		'type' => 'sectionend',
		'id'   => $prefix . 'pdf_config_settings'
	],
];

$settings = array_merge($normal_settings, $settings_payment_gateways);