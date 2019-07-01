<?php

namespace Gosign\YoutubeExplorer\Admin;

use Gosign\YoutubeExplorer\Pattern\Singleton;

/**
 * Setting admin screen.
 *
 * @package Youtube_Explorer
 */
class Admin extends Singleton {

	/**
	 * Constructor
	 */
	protected function init() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );
	}

	/**
	 * Register admin menu.
	 */
	public function admin_menu() {
		add_options_page(
			__( 'Youtube Explorer', 'youtube-explorer' ),
			__( 'Youtube Explorer', 'youtube-explorer' ),
			'manage_options',
			$this->slug,
			[ $this, 'display' ]
		);
	}

	/**
	 * Register settings.
	 */
	public function admin_init() {
		register_setting( $this->slug, $this->slug );

		add_settings_section( 'api_settings', __( 'Youtube Explorer Settings', 'youtube-explorer' ), function () {
			printf(
				'<p class="description">%s</p>',
				esc_html__( 'Please set up settings for Youtube Explorer.', 'youtube-explorer' )
			);
		}, $this->slug );

		add_settings_field(
			'google_api_key',
			__( 'API Key', 'youtube-explorer' ),
			[ $this, 'google_api_key_callback' ],
			$this->slug,
			'api_settings'
		);

	}

	/**
	 * Render callback for news publication name.
	 */
	public function google_api_key_callback() {
		$google_api_key = isset( $this->options['google_api_key'] ) ? $this->options['google_api_key'] : '';
		?>
        <input name="<?php echo $this->slug; ?>[google_api_key]" type="text" id="google_api_key"
               value="<?php echo esc_attr( $google_api_key ); ?>" class="regular-text">
        <p class="description"><?php _e( 'Get API Key from <a href="https://console.cloud.google.com/">Google Cloud Plathome</a>', 'youtube-explorer' ); ?></p>
		<?php
	}

	/**
	 * Admin menu render callback.
	 */
	public function display() {
		$action = untrailingslashit( admin_url() ) . '/options.php';
		?>
        <div class="wrap media-xml-sitemap-settings">
            <h1 class="wp-heading-inline"><?php _e( 'Youtube Explorer Settings', 'youtube-explorer' ); ?></h1>
            <form action="<?php echo esc_url( $action ); ?>" method="post">
				<?php
				settings_fields( $this->slug );
				do_settings_sections( $this->slug );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}
}
