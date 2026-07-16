<?php
/**
 * ACF fields: Testimonials block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_testimonials',
		'title'  => 'Testimonials',
		'fields' => array(
			array(
				'key'           => 'field_hlm_testimonials_overline',
				'label'         => 'Overline',
				'name'          => 'overline',
				'type'          => 'text',
				'default_value' => 'Success Stories',
			),
			array(
				'key'           => 'field_hlm_testimonials_headline',
				'label'         => 'Headline',
				'name'          => 'headline',
				'type'          => 'textarea',
				'rows'          => 2,
				'instructions'  => 'Limited HTML: &lt;br&gt;, &lt;em&gt;.',
				'default_value' => "What Our Partners<br><em>Say</em>",
			),
			array(
				'key'           => 'field_hlm_testimonials_description',
				'label'         => 'Description',
				'name'          => 'description',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => 'Institutions and students trust us to deliver results.',
			),
			array(
				'key'          => 'field_hlm_testimonials_items',
				'label'        => 'Testimonials',
				'name'         => 'testimonials',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add Testimonial',
				'sub_fields'   => array(
					array(
						'key'   => 'field_hlm_testimonial_quote',
						'label' => 'Quote',
						'name'  => 'quote',
						'type'  => 'textarea',
						'rows'  => 4,
					),
					array(
						'key'   => 'field_hlm_testimonial_author',
						'label' => 'Author Name',
						'name'  => 'author',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_testimonial_meta',
						'label' => 'Author Role / Meta',
						'name'  => 'meta',
						'type'  => 'text',
					),
					array(
						'key'           => 'field_hlm_testimonial_photo',
						'label'         => 'Photo',
						'name'          => 'photo',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'thumbnail',
					),
					array(
						'key'           => 'field_hlm_testimonial_stars',
						'label'         => 'Star Rating',
						'name'          => 'stars',
						'type'          => 'number',
						'min'           => 1,
						'max'           => 5,
						'default_value' => 5,
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-testimonials',
				),
			),
		),
	)
);
