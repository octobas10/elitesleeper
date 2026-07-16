<?php
/**
 * Institution campus and program catalog (Elite Biz Panel codes).
 *
 * @package HLM_Lead_Capture
 */

defined( 'ABSPATH' ) || exit;

/**
 * Provides campus/program JSON for cascading selects.
 */
class HLM_Institution_Data {

	/**
	 * Cached decoded data.
	 *
	 * @var array|null
	 */
	private static $data = null;

	/**
	 * Load institution data from JSON file.
	 *
	 * @return array
	 */
	private static function load() {
		if ( null !== self::$data ) {
			return self::$data;
		}

		$file = HLM_LC_PLUGIN_DIR . 'includes/institution-data.json';

		if ( ! file_exists( $file ) ) {
			self::$data = array(
				'institutions' => array(),
				'campus'       => array(),
				'programs'     => array(),
			);
			return self::$data;
		}

		$decoded = json_decode( file_get_contents( $file ), true );

		self::$data = is_array( $decoded ) ? $decoded : array(
			'institutions' => array(),
			'campus'       => array(),
			'programs'     => array(),
		);

		return self::$data;
	}

	/**
	 * Institution category labels.
	 *
	 * @return array<string, string>
	 */
	public static function get_institutions() {
		$data = self::load();
		return $data['institutions'] ?? array();
	}

	/**
	 * Campus options keyed by institution category.
	 *
	 * @return array
	 */
	public static function get_campus_data() {
		$data = self::load();
		return $data['campus'] ?? array();
	}

	/**
	 * Program options keyed by institution category and campus code.
	 * Values are { program_code: label } maps.
	 *
	 * @return array
	 */
	public static function get_program_data() {
		$data = self::load();
		return $data['programs'] ?? array();
	}

	/**
	 * Validate a campus code for an institution category.
	 *
	 * @param string $category Institution key (e.g. BERKELEY).
	 * @param string $campus   Campus code.
	 * @return bool
	 */
	public static function is_valid_campus( $category, $campus ) {
		$campuses = self::get_campus_data();
		return isset( $campuses[ $category ][ $campus ] );
	}

	/**
	 * Validate a program code for institution + campus.
	 *
	 * @param string $category Institution key.
	 * @param string $campus   Campus code.
	 * @param string $program  Program code.
	 * @return bool
	 */
	public static function is_valid_program( $category, $campus, $program ) {
		$programs = self::get_program_data();
		return isset( $programs[ $category ][ $campus ][ $program ] );
	}

	/**
	 * Resolve institution category for Berkeley-only submissions.
	 *
	 * @return string
	 */
	public static function berkeley_category() {
		return 'BERKELEY';
	}
}
