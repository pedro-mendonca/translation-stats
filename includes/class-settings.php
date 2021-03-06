<?php
/**
 * Class file for registering Translation Stats Settings.
 *
 * @since 0.8.0
 *
 * @package Translation Stats
 */

namespace Translation_Stats;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Settings' ) ) {

	/**
	 * Class Settings.
	 */
	class Settings {


		/**
		 * Notices.
		 *
		 * @var object
		 */
		protected $notices;

		/**
		 * Transients.
		 *
		 * @var object
		 */
		protected $transients;

		/**
		 * Plugins Settings.
		 *
		 * @var object
		 */
		protected $settings_plugins;

		/**
		 * General Settings.
		 *
		 * @var object
		 */
		protected $settings_general;

		/**
		 * Tools Settings.
		 *
		 * @var object
		 */
		protected $settings_tools;

		/**
		 * Hidden Settings.
		 *
		 * @var object
		 */
		protected $settings_hidden;


		/**
		 * Constructor.
		 */
		public function __construct() {

			// Instantiate Translation Stats Notices.
			$this->notices = new Notices();

			// Instantiate Translation Stats Transients.
			$this->transients = new Transients();

			// Instantiate Translation Stats Plugins Settings.
			$this->settings_plugins = new Settings_Plugins();

			// Instantiate Translation Stats General Settings.
			$this->settings_general = new Settings_General();

			// Instantiate Translation Stats Tools Settings.
			$this->settings_tools = new Settings_Tools();

			// Instantiate Translation Stats Hidden Settings.
			$this->settings_hidden = new Settings_Hidden();

			// Add admin menu item.
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );

			// Add plugin settings sections.
			add_action( 'admin_init', array( $this, 'settings_sections' ) );

			// Initialize Settings Sidebar.
			new Settings_Sidebar();

			// Initialize Settings Widgets.
			new Settings_Widgets();

			// Initialize Settings Footer.
			new Settings_Footer();

		}


		/**
		 * Registers a new Translation Stats Settings Page.
		 *
		 * @since 0.8.0
		 * @since 0.9.9   Renamed from tstats_admin_menu() to admin_menu().
		 *
		 * @return void
		 */
		public function admin_menu() {
			// Add submenu page to the Settings main menu.
			add_options_page(
				esc_html_x( 'Translation Stats', 'Options Page Title', 'translation-stats' ), // The text to be displayed in the title tag.
				esc_html_x( 'Translation Stats', 'Options Page Title', 'translation-stats' ), // The text to be used for the menu.
				'manage_options',                                                             // The capability required to display this menu.
				TRANSLATION_STATS_SETTINGS_PAGE,                                              // The unique slug name to refer to this menu.
				array( $this, 'options_page' )                                                // The function to output the page content.
			);
		}


		/**
		 * Add Settings Sections.
		 *
		 * @since 0.9.0
		 * @since 0.9.9   Renamed from tstats_settings_sections() to settings_sections().
		 *
		 * @return void
		 */
		public function settings_sections() {

			// Plugins settings section.
			$this->settings_plugins->settings_section();

			// General settings section.
			$this->settings_general->settings_section();

			// Tools settings section.
			$this->settings_tools->settings_section();

			// Hidden settings section.
			$this->settings_hidden->settings_section();

			// Add section after Translation settings sections.
			do_action( 'tstats_settings_section__after' );

		}


		/**
		 * Callback function for Reset Settings.
		 *
		 * @since 0.8.0
		 * @since 0.9.9   Renamed from tstats_settings_reset_callback() to settings_reset_callback().
		 *
		 * @return void
		 */
		public function settings_reset_callback() {
			$action = 'reset_settings';
			if ( isset( $_POST[ $action ] ) ) {
				// Check nonce.
				if ( ! isset( $_POST['tstats_nonce_check'] ) || ! wp_verify_nonce( sanitize_key( $_POST['tstats_nonce_check'] ), 'tstats_action' ) ) {
					$this->nonce_fail();
				}

				// Update to default settings.
				update_option( TRANSLATION_STATS_WP_OPTION, $this->settings_defaults() );

				$admin_notice = array(
					'type'        => 'success',
					'notice-alt'  => false,
					'inline'      => false,
					'dismissible' => true,
					'force_show'  => true,
					'message'     => '<strong>' . esc_html__( 'Settings restored successfully.', 'translation-stats' ) . '</strong>',
				);
				$this->notices->notice_message( $admin_notice );
			}
		}


		/**
		 * Callback function for Delete Transients.
		 *
		 * @since 0.8.0
		 * @since 0.9.9   Renamed from tstats_transients_delete_callback() to transients_delete_callback().
		 *
		 * @return void
		 */
		public function transients_delete_callback() {
			$action = 'delete_transients';
			if ( isset( $_POST[ $action ] ) ) {
				// Check nonce.
				if ( ! isset( $_POST['tstats_nonce_check'] ) || ! wp_verify_nonce( sanitize_key( $_POST['tstats_nonce_check'] ), 'tstats_action' ) ) {
					$this->nonce_fail();
				}
				// Delete translations stats and available languages transients.
				// The transient 'translation_stats_plugin_available_translations' will be immediatly rebuilt on tstats_render_settings__plugins_list() loading.
				$this->transients->delete_transients( TRANSLATION_STATS_TRANSIENTS_PREFIX );
				$admin_notice = array(
					'type'        => 'success',
					'notice-alt'  => false,
					'inline'      => false,
					'dismissible' => true,
					'force_show'  => true,
					'message'     => '<strong>' . esc_html__( 'Cache cleaned successfully.', 'translation-stats' ) . '</strong>',
				);
				$this->notices->notice_message( $admin_notice );
			}
		}


		/**
		 * Callback function for Nonce fail.
		 *
		 * @since 0.9.5
		 * @since 0.9.9   Renamed from tstats_nonce_fail() to nonce_fail().
		 *
		 * @return void
		 */
		public function nonce_fail() {
			esc_html_e( 'Sorry, your nonce did not verify.', 'translation-stats' );
			exit;
		}


		/**
		 * Default Translation Stats Settings.
		 *
		 * @since 0.8.0
		 * @since 0.9.9   Renamed from tstats_settings_defaults() to settings_defaults().
		 *
		 * @return array  Array of default settings.
		 */
		public function settings_defaults() {
			$defaults = array(
				'settings' => array(
					'show_warnings'            => true,
					'translation_language'     => 'site-default',
					'delete_data_on_uninstall' => true,
					'transients_expiration'    => TRANSLATION_STATS_TRANSIENTS_TRANSLATIONS_EXPIRATION,
					'settings_version'         => TRANSLATION_STATS_SETTINGS_VERSION,
				),
			);
			return $defaults;
		}


		/**
		 * Callback function for the options page.
		 *
		 * @since 0.8.0
		 * @since 0.9.9   Renamed from tstats_options_page() to options_page().
		 *
		 * @return void
		 */
		public function options_page() {
			// Check required user capability.
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'translation-stats' ) );
			}

			// Add action on Translation Stats settings init.
			do_action( 'tstats_settings_init' );
			?>

			<div class="wrap">
				<?php

				// Settings Reset Callback.
				$this->settings_reset_callback();

				// Delete Transients Callback.
				$this->transients_delete_callback();

				?>
				<h1><?php echo esc_html_x( 'Translation Stats', 'Options Page Title', 'translation-stats' ); ?></h1>
				<p><?php esc_html_e( 'Customize the translation stats you want to show.', 'translation-stats' ); ?></p>

				<div class="tstats-settings-wrapper">

					<?php
					// Add before Translation Stats settings.
					do_action( 'tstats_settings__before' );
					?>

					<div class="tstats-settings__content">

						<h2 class="nav-tab-wrapper">
							<a class="nav-tab" href="#plugins"><span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'Plugins', 'translation-stats' ); ?></a>
							<a class="nav-tab" href="#settings"><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'Settings', 'translation-stats' ); ?></a>
							<a class="nav-tab" href="#tools"><span class="dashicons dashicons-admin-tools"></span> <?php esc_html_e( 'Tools', 'translation-stats' ); ?></a>

							<?php
							// Add after Translation Stats settings tabs items.
							do_action( 'tstats_settings_tab__after' );
							?>

						</h2>

						<div class="tabs-content">
							<form action='options.php' method='post'>

								<div id="tab-plugins" class="tab-content hidden">
									<?php
									$section = 'tstats_settings__plugins';
									do_settings_sections( $section );
									settings_fields( $section );
									?>
								</div>
								<div id="tab-settings" class="tab-content hidden">
									<?php
									$section = 'tstats_settings__general';
									do_settings_sections( $section );
									settings_fields( $section );
									?>
								</div>
								<div id="tab-tools" class="tab-content hidden">
									<?php
									$section = 'tstats_settings__tools__settings';
									do_settings_sections( $section );
									settings_fields( $section );
									$section = 'tstats_settings__tools__transients';
									do_settings_sections( $section );
									settings_fields( $section );
									?>
								</div>
								<div class="hidden">
									<?php
									$section = 'tstats_settings__hidden';
									do_settings_sections( $section );
									?>
								</div>

								<?php
								// Add after Translation Stats settings content items.
								do_action( 'tstats_settings_content__after' );
								?>

								<?php wp_nonce_field( 'tstats_action', 'tstats_nonce_check' ); ?>

								<p class="submit">
									<?php
									submit_button( __( 'Save Changes', 'translation-stats' ), 'primary', 'submit', false );
									?>
								</p>
							</form>
						</div>
					</div>

					<?php
					// Add after Translation Stats settings.
					do_action( 'tstats_settings__after' );
					?>

				</div>
			</div>
			<?php
		}

	}

}
