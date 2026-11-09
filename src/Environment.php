<?php
/**
 * Detects whether the site is running locally, on staging, or in production.
 *
 * @package MadeByAura\Helper
 */

namespace MadeByAura\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Environment detection built on WordPress core's wp_get_environment_type().
 *
 * Core reads the WP_ENVIRONMENT_TYPE constant (or environment variable) and
 * caches the result for the request, so every call here is a cheap lookup.
 * LocalWP sets the constant to 'local' in wp-config.php on every new site.
 *
 * Core knows four types; this plugin works with three. 'development' is
 * treated as staging: it is a shared, non-production server, not a
 * developer's own machine.
 *
 * Core falls back to 'production' when the constant is missing or invalid,
 * so any staging server must define WP_ENVIRONMENT_TYPE explicitly.
 */
final class Environment {

	public const LOCAL      = 'local';
	public const STAGING    = 'staging';
	public const PRODUCTION = 'production';

	/**
	 * Returns the current environment: one of the class constants.
	 */
	public static function get(): string {
		switch ( wp_get_environment_type() ) {
			case 'local':
				return self::LOCAL;

			case 'development':
			case 'staging':
				return self::STAGING;

			default:
				return self::PRODUCTION;
		}
	}

	public static function is_local(): bool {
		return self::LOCAL === self::get();
	}

	public static function is_staging(): bool {
		return self::STAGING === self::get();
	}

	public static function is_production(): bool {
		return self::PRODUCTION === self::get();
	}
}
