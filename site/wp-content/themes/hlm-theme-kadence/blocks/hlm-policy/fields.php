<?php
/**
 * ACF fields: Policy block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_policy',
		'title'  => 'Policy Content',
		'fields' => array(
			array(
				'key'   => 'field_hlm_policy_headline',
				'label' => 'Headline',
				'name'  => 'headline',
				'type'  => 'text',
				'default_value' => 'Privacy Policy',
			),
			array(
				'key'   => 'field_hlm_policy_updated',
				'label' => 'Last Updated',
				'name'  => 'last_updated',
				'type'  => 'date_picker',
				'display_format' => 'F j, Y',
				'return_format'  => 'F j, Y',
			),
			array(
				'key'   => 'field_hlm_policy_intro',
				'label' => 'Introduction',
				'name'  => 'intro',
				'type'  => 'wysiwyg',
				'tabs'  => 'visual',
				'toolbar' => 'basic',
			),
			array(
				'key'          => 'field_hlm_policy_sections',
				'label'        => 'Policy Sections',
				'name'         => 'sections',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add Section',
				'sub_fields'   => array(
					array(
						'key'   => 'field_hlm_policy_section_title',
						'label' => 'Section Title',
						'name'  => 'title',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_policy_section_content',
						'label' => 'Content',
						'name'  => 'content',
						'type'  => 'wysiwyg',
						'tabs'  => 'visual',
						'toolbar' => 'basic',
					),
				),
			),
			array(
				'key'   => 'field_hlm_policy_contact',
				'label' => 'Contact Information',
				'name'  => 'contact_info',
				'type'  => 'wysiwyg',
				'tabs'  => 'visual',
				'toolbar' => 'basic',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-policy',
				),
			),
		),
	)
);
