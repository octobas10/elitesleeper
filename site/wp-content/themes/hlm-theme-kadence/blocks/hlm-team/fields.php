<?php
/**
 * ACF fields: Team block.
 *
 * @package HLM_Theme
 */

acf_add_local_field_group(
	array(
		'key'    => 'group_hlm_team',
		'title'  => 'Team Profiles',
		'fields' => array(
			array(
				'key'   => 'field_hlm_team_overline',
				'label' => 'Overline',
				'name'  => 'overline',
				'type'  => 'text',
				'default_value' => 'Our Team',
			),
			array(
				'key'   => 'field_hlm_team_headline',
				'label' => 'Headline',
				'name'  => 'headline',
				'type'  => 'text',
				'default_value' => 'Meet Our Experts',
			),
			array(
				'key'          => 'field_hlm_team_members',
				'label'        => 'Team Members',
				'name'         => 'members',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add Member',
				'sub_fields'   => array(
					array(
						'key'           => 'field_hlm_team_photo',
						'label'         => 'Photo',
						'name'          => 'photo',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
					),
					array(
						'key'   => 'field_hlm_team_name',
						'label' => 'Name',
						'name'  => 'name',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_team_role',
						'label' => 'Role',
						'name'  => 'role',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_hlm_team_bio',
						'label' => 'Bio',
						'name'  => 'bio',
						'type'  => 'textarea',
						'rows'  => 4,
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/hlm-team',
				),
			),
		),
	)
);
