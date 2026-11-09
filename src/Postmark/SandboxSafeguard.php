<?php
/**
 * Points the official Postmark plugin at a sandbox on local and staging sites.
 *
 * @package MadeByAura\Helper
 */

namespace MadeByAura\Helper\Postmark;

use MadeByAura\Helper\Environment;

defined( 'ABSPATH' ) || exit;

/**
 * A safeguard: on every non-production site, defines the constants the
 * ActiveCampaign Postmark plugin (postmark-approved-wordpress-plugin) reads
 * before each send, so copies of production send through the sandbox
 * instead of the live server. It works whether or not Postmark is active,
 * and survives imports and reactivation, because nothing is stored.
 *
 * Postmark lets these constants take precedence over its saved settings.
 * Its settings page notes this only if this plugin loads before Postmark,
 * but sending uses them either way. Verified against Postmark 1.19.1.
 *
 * It applies only while this plugin is active. See ADR 0006.
 */
final class SandboxSafeguard {

	/**
	 * Postmark's test token: requests are accepted but never delivered.
	 * Placeholder until the team's sandbox server token replaces it.
	 */
	private const API_KEY = 'POSTMARK_API_TEST';

	/**
	 * Message stream on the sandbox server.
	 */
	private const STREAM_NAME = 'outbound';

	/**
	 * Defines the constants straight away rather than on a hook: Postmark
	 * reads them when its own file loads (for its settings page) and again
	 * on every send.
	 */
	public function register(): void {
		if ( Environment::is_production() ) {
			return;
		}

		// A site that already defines one in wp-config.php keeps its own value.
		if ( ! defined( 'POSTMARK_API_KEY' ) ) {
			define( 'POSTMARK_API_KEY', self::API_KEY );
		}

		if ( ! defined( 'POSTMARK_STREAM_NAME' ) ) {
			define( 'POSTMARK_STREAM_NAME', self::STREAM_NAME );
		}
	}
}
