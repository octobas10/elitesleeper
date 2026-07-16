<?php
/**
 * ACF fields: Stats block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_stats',
		'title'  => 'Stats Bar',
		'fields' => array(
			array(
				'key'          => 'field_hlm_stats_items',
				'label'        => 'Statistics',
				'name'         => 'stats',
				'type'         => 'repeater',
				'layout'       => 'block',
				'min'          => 1,
				'max'          => 6,
				'button_label' => 'Add Stat',
				'sub_fields'   => array(
					array(
						'key'   => 'field_hlm_stats_number',
						'label' => 'Number',
						'name'  => 'number',
						'type'  => 'number',
					),
					array(
						'key'   => 'field_hlm_stats_prefix',
						'label' => 'Prefix',
						'name'  => 'prefix',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_stats_suffix',
						'label' => 'Suffix',
						'name'  => 'suffix',
						'type'  => 'text',
						'default_value' => '+',
					),
					array(
						'key'   => 'field_hlm_stats_label',
						'label' => 'Label',
						'name'  => 'label',
						'type'  => 'text',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-stats',
				),
			),
		),
	)
);
