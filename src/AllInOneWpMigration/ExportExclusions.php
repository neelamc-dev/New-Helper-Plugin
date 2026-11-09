<?php
/**
 * Keeps sensitive data, live integrations, and clutter out of All-in-One WP Migration exports.
 *
 * @package MadeByAura\Helper
 */

namespace MadeByAura\Helper\AllInOneWpMigration;

defined( 'ABSPATH' ) || exit;

/**
 * Excludes a fixed list of plugins, wp-content items, and database tables
 * from every export, whatever options the person exporting chooses.
 *
 * Verified against All-in-One WP Migration 7.113.
 */
final class ExportExclusions {

	/**
	 * Plugin folder names inside wp-content/plugins.
	 * They integrate with live services and must never run on a copy.
	 *
	 * The official ActiveCampaign Postmark plugin (postmark-approved-wordpress-plugin)
	 * is deliberately not listed: copies keep it, and a safeguard swaps its
	 * live API key for a sandbox key there instead. See ADR 0005.
	 */
	private const PLUGINS = array(
		'postmarkapp-email-integrator',
		'klaviyo',
		'woocommerce-shipstation-integration',
	);

	/**
	 * Files and folders directly inside wp-content.
	 */
	private const WP_CONTENT_ITEMS = array(
		'ai1wm-backups',
		'cache',
		'jetpack-waf',
		'mu-plugins',
		'upgrade',
		'upgrade-temp-backup',
		'debug.log',
	);

	/**
	 * Database tables, without the table prefix.
	 * They hold API keys, webhooks, live sessions, and logs.
	 */
	private const TABLES = array(
		'wc_webhooks',
		'woocommerce_api_keys',
		'woocommerce_sessions',
		'user_registration_sessions',
		'woocommerce_log',
	);

	/**
	 * All-in-One WP Migration runs its export as a chain of steps on the
	 * 'ai1wm_export' filter, one priority per request, and lists the tables
	 * to export at priority 140. Unlike plugins and content, it has no filter
	 * for excluding tables, so this step runs just before that one and adds
	 * our tables to the export's own "exclude tables" option.
	 */
	private const TABLE_EXCLUSION_STEP_PRIORITY = 135;

	public function register(): void {
		add_filter( 'ai1wm_exclude_plugins_from_export', array( $this, 'exclude_plugins' ) );
		add_filter( 'ai1wm_exclude_content_from_export', array( $this, 'exclude_content' ) );
		add_filter( 'ai1wm_export', array( $this, 'exclude_tables' ), self::TABLE_EXCLUSION_STEP_PRIORITY );
	}

	/**
	 * @param mixed $filters Paths All-in-One WP Migration already excludes.
	 */
	public function exclude_plugins( $filters ): array {
		return array_merge( (array) $filters, self::PLUGINS );
	}

	/**
	 * @param mixed $filters Paths All-in-One WP Migration already excludes.
	 */
	public function exclude_content( $filters ): array {
		return array_merge( (array) $filters, self::WP_CONTENT_ITEMS );
	}

	/**
	 * Adds our tables to the export's excluded tables, keeping any the
	 * person exporting already excluded.
	 *
	 * @param mixed $params Export parameters, passed from step to step.
	 * @return mixed The parameters, unchanged if they are not an array.
	 */
	public function exclude_tables( $params ) {
		if ( ! is_array( $params ) ) {
			return $params;
		}

		$params['options']                      = isset( $params['options'] ) ? (array) $params['options'] : array();
		$params['options']['exclude_db_tables'] = 1;

		$params['excluded_db_tables'] = $this->join_table_list(
			array_merge( $this->split_table_list( $params['excluded_db_tables'] ?? '' ), $this->prefixed_tables() )
		);

		return $params;
	}

	/**
	 * @return string[] Table names with this site's prefix, e.g. 'wp_wc_webhooks'.
	 */
	private function prefixed_tables(): array {
		global $wpdb;

		return array_map(
			static function ( string $table ) use ( $wpdb ): string {
				return $wpdb->prefix . $table;
			},
			self::TABLES
		);
	}

	/**
	 * All-in-One WP Migration stores table lists as comma-separated strings.
	 *
	 * @param mixed $list Comma-separated table names, or already an array of them.
	 * @return string[]
	 */
	private function split_table_list( $list ): array {
		$tables = is_array( $list ) ? $list : explode( ',', (string) $list );

		return array_filter( array_map( 'trim', $tables ) );
	}

	/**
	 * @param string[] $tables
	 */
	private function join_table_list( array $tables ): string {
		return implode( ',', array_unique( $tables ) );
	}
}
