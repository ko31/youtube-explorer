<?php

namespace Gosign;

use Gosign\YoutubeExplorer\Admin\Admin;
use Gosign\YoutubeExplorer\Pattern\Singleton;

/**
 * Run this plugin.
 *
 * @package Youtube_Explorer
 */
class YoutubeExplorer extends Singleton {

	private $slug = 'youtube-explorer';

	/**
	 * Register
	 */
	public function register() {
		if ( is_admin() ) {
			Admin::get_instance();
		}
//		Rules::get_instance();
	}

	/**
	 * Deactivation
	 */
	public function deactivation() {
		delete_option( $this->get_slug() );
	}

	/**
	 * Get plugin slug.
	 */
	public function get_slug() {
		return $this->slug;
	}
}
