<?php
/**
 * Class file for registering Translation Stats settings widgets.
 *
 * @since 0.9.0
 *
 * @package Translation Stats
 */

namespace Translation_Stats;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Settings_Widgets' ) ) {

	/**
	 * Class Settings_Widgets.
	 */
	class Settings_Widgets {


		/**
		 * Globals.
		 *
		 * @var object
		 */
		protected $globals;


		/**
		 * Constructor.
		 */
		public function __construct() {

			// Instantiate Translation Stats Globals.
			$this->globals = new Globals();

			// Add Sidebar before Translation Stats settings.
			add_action( 'tstats_settings__sidebar__content', array( $this, 'tstats_settings_widget__about' ) );

		}


		/**
		 * Show plugin info widget.
		 *
		 * @since 0.9.0
		 *
		 * @return void
		 */
		public function tstats_settings_widget__about() {

			$external_link_url  = 'https://translationstats.com';
			$external_link_icon = '<i aria-hidden="true" class="dashicons dashicons-external"></i>';
			?>

			<div class="postbox paper-shadow" id="tstats_settings_metabox__about">
				<div class="inside">

					<div class="tstats-logo">
						<a class="tstats-logo-link" href="<?php echo esc_url( $this->globals->tstats_link( $external_link_url, 'tstats', 'link', 'tstats_plugin_logo' ) ); ?>" target="_blank">
							<div class="tstats-logo-image"></div>
						</a>
					</div>
					<p><?php esc_html_e( 'Show plugins translation stats on your WordPress install.', 'translation-stats' ); ?></p>

					<div class="tstats-resources">
						<h3><?php esc_html_e( 'Resources', 'translation-stats' ); ?></h3>
						<ul>
							<li><a href="<?php echo esc_url( $this->globals->tstats_link( $external_link_url, 'tstats', 'link', 'tstats_link_site' ) ); ?>" target="_blank"><?php echo wp_kses_post( $external_link_icon ); ?> <?php esc_html_e( 'Site', 'translation-stats' ); ?></a></li>
							<li><a href="<?php echo esc_url( $this->globals->tstats_link( $external_link_url . '/faq/', 'tstats', 'link', 'tstats_link_faq' ) ); ?>" target="_blank"><?php echo wp_kses_post( $external_link_icon ); ?> <?php esc_html_e( 'FAQ', 'translation-stats' ); ?></a></li>
							<li><a href="<?php echo esc_url( $this->globals->tstats_link( $external_link_url . '/changelog/', 'tstats', 'link', 'tstats_link_changelog' ) ); ?>" target="_blank"><?php echo wp_kses_post( $external_link_icon ); ?> <?php esc_html_e( 'Changelog', 'translation-stats' ); ?></a></li>
							<li><a href="https://wordpress.org/support/plugin/translation-stats/" target="_blank"><?php echo wp_kses_post( $external_link_icon ); ?> <?php esc_html_e( 'Support', 'translation-stats' ); ?></a></li>
						</ul>
					</div>

					<div class="tstats-contact">
						<h3><?php esc_html_e( 'Contact', 'translation-stats' ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'Found an issue, have a feature suggestion or just want to send some feedback?', 'translation-stats' ); ?></li>
							<li><a href="<?php echo esc_url( $this->globals->tstats_link( $external_link_url . '/contact/', 'tstats', 'link', 'tstats_link_contact' ) ); ?>" target="_blank"><?php echo wp_kses_post( $external_link_icon ); ?> <?php esc_html_e( 'Write me!', 'translation-stats' ); ?></a></li>
						</ul>
					</div>

					<div class="tstats-sponsor">
						<h3><?php esc_html_e( 'Coffee', 'translation-stats' ); ?></h3>
						<ul>
							<li>
								<?php esc_html_e( 'Do you like this plugin?', 'translation-stats' ); ?>
								<br>
								<?php esc_html_e( 'Support its further development by becoming a sponsor!', 'translation-stats' ); ?>
							</li>
							<form action="https://github.com/sponsors/pedro-mendonca" target="_blank">
								<button type="submit" class="button button-secondary tstats-github-sponsor"><span class="dashicons dashicons-heart"></span> <?php esc_html_e( 'GitHub Sponsors', 'translation-stats' ); ?> <?php echo wp_kses_post( $external_link_icon ); ?></button>
							</form>
						</ul>
					</div>

				</div>

				<div class="footer">
					<p>
						<?php
						printf(
							/* translators: Plugin name and version - Do not translate! */
							esc_html__( 'Translation Stats %s', 'translation-stats' ),
							'<small>v.' . esc_html( TSTATS_VERSION ) . '</small>'
						);
						?>
					</p>
				</div>
			</div>
			<?php
		}

	}

}