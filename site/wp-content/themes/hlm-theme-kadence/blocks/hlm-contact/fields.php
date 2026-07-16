<?php
/**
 * ACF fields: Contact block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_contact',
		'title'  => 'Contact Details',
		'fields' => array(
			array(
				'key'   => 'field_hlm_contact_overline',
				'label' => 'Overline',
				'name'  => 'overline',
				'type'  => 'text',
				'default_value' => 'Get In Touch',
			),
			array(
				'key'   => 'field_hlm_contact_headline',
				'label' => 'Headline',
				'name'  => 'headline',
				'type'  => 'text',
				'default_value' => 'Contact Us',
			),
			array(
				'key'   => 'field_hlm_contact_email',
				'label' => 'Email',
				'name'  => 'email',
				'type'  => 'email',
				'default_value' => 'info@highereducationelite.com',
			),
			array(
				'key'   => 'field_hlm_contact_phone',
				'label' => 'Phone',
				'name'  => 'phone',
				'type'  => 'text',
				'default_value' => '(555) 123-4567',
			),
			array(
				'key'   => 'field_hlm_contact_address',
				'label' => 'Address',
				'name'  => 'address',
				'type'  => 'textarea',
				'rows'  => 3,
				'default_value' => "123 Education Lane\nSuite 100\nNew York, NY 10001",
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-contact',
				),
			),
		),
	)
);
