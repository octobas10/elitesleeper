<?php
/**
 * Shared select options for lead forms.
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * US states and DC for API state field (2-letter codes).
 *
 * @return array<string, string>
 */
function hlm_lead_us_states() {
	return array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	);
}

/**
 * Canadian provinces for CDI submissions.
 *
 * @return array<string, string>
 */
function hlm_lead_ca_provinces() {
	return array(
		'AB' => 'Alberta',
		'BC' => 'British Columbia',
		'MB' => 'Manitoba',
		'NB' => 'New Brunswick',
		'NL' => 'Newfoundland and Labrador',
		'NS' => 'Nova Scotia',
		'NT' => 'Northwest Territories',
		'NU' => 'Nunavut',
		'ON' => 'Ontario',
		'PE' => 'Prince Edward Island',
		'QC' => 'Quebec',
		'SK' => 'Saskatchewan',
		'YT' => 'Yukon',
	);
}

/**
 * Graduation years allowed by Elite API (2006–2026).
 *
 * @return int[]
 */
function hlm_lead_grad_years() {
	return range( 2026, 2006 );
}

/**
 * Render <option> elements for a code => label map.
 *
 * @param array  $options     Options map.
 * @param string $placeholder Placeholder label.
 */
function hlm_lead_render_select_options( array $options, $placeholder = '' ) {
	if ( $placeholder ) {
		printf( '<option value="">%s</option>', esc_html( $placeholder ) );
	}

	foreach ( $options as $value => $label ) {
		printf(
			'<option value="%s">%s</option>',
			esc_attr( $value ),
			esc_html( $label )
		);
	}
}

/**
 * Render helper text below a field.
 *
 * @param string $hint_id   Element id for aria-describedby.
 * @param string $hint_text Visible hint copy.
 */
function hlm_lead_field_hint( $hint_id, $hint_text ) {
	if ( ! $hint_text ) {
		return;
	}

	include HLM_LC_PLUGIN_DIR . 'templates/partials/field-hint.php';
}
