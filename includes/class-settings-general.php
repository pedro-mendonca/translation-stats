<?php
/**
 * Class file for registering Translation Stats General Settings.
 *
 * @since 0.9.9
 *
 * @package Translation Stats
 */

namespace Translation_Stats;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Settings_General' ) ) {

	/**
	 * Class Settings_General.
	 */
	class Settings_General {


		/**
		 * Settings API.
		 *
		 * @var object
		 */
		protected $settings_api;


		/**
		 * Constructor.
		 */
		public function __construct() {

			// Instantiate Translation Stats Settings API.
			$this->settings_api = new Settings_API();

		}


		/**
		 * Registers Settings General page section.
		 *
		 * @since 0.8.0
		 * @since 0.9.9   Moved from class Settings() to Settings_General().
		 *                Renamed from tstats_settings_section__general() to settings_section().
		 *
		 * @return void
		 */
		public function settings_section() {

			add_settings_section(
				'tstats_settings__general',                    // String for use in the 'id' attribute of tags.
				__( 'General Settings', 'translation-stats' ), // Title of the section.
				array( $this, 'settings__general__callback' ), // Function that fills the section with the desired content.
				'tstats_settings__general'                     // The menu page on which to display this section. Should match $menu_slug.
			);

			register_setting(
				'tstats_settings__general', // The menu page on which to display this section. Should match $menu_slug.
				TRANSLATION_STATS_WP_OPTION // The WordPress option to store Translation Stats settings.
			);

		}


		/**
		 * Callback function for section "Settings".
		 *
		 * @since 0.8.0
		 * @since 0.9.9   Moved from class Settings() to Settings_General().
		 *                Renamed from tstats_settings__general__callback() to settings__general__callback().
		 *
		 * @return void
		 */
		public function settings__general__callback() {

			$section = 'tstats_settings__general';

			/*
			Section description.
			esc_html_e( 'General settings for Translation Stats', 'translation-stats' );
			*/

			$this->settings_api->tstats_add_settings_field(
				array(
					'section'     => $section,
					'path'        => 'settings',
					'id'          => 'show_warnings',
					'type'        => 'checkbox',
					'class'       => '',
					'title'       => __( 'Warnings', 'translation-stats' ),
					'label'       => __( 'Show translation project warnings', 'translation-stats' ),
					'description' => __( 'Check this to show translation project error messages for selected plugins.', 'translation-stats' ),
					'helper'      => __( 'Need help?', 'translation-stats' ),
					'callback'    => 'tstats_render_input_checkbox',
					'default'     => true,
				)
			);

			$this->settings_api->tstats_add_settings_field(
				array(
					'section'        => $section,
					'path'           => 'settings',
					'id'             => 'translation_language',
					'type'           => 'select',
					'class'          => '',
					'title'          => __( 'Translation Language', 'translation-stats' ),
					'label'          => __( 'Select translation language', 'translation-stats' ),
					'description'    => __( 'Select the language for which you want to show the translation stats.', 'translation-stats' ),
					'helper'         => __( 'Need help?', 'translation-stats' ),
					'callback'       => 'tstats_render_input_select__language',
					'select_options' => '',
					'default'        => 'site-default',
				)
			);
		}

	}

}
