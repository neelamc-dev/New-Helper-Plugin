<?php
/**
 * Plugin entry point.
 *
 * @package MadeByAura\Helper
 */

namespace MadeByAura\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Registers every feature.
 *
 * Each feature is a class with a register() method that adds hooks (or,
 * when timing demands it, defines constants), so booting is cheap and the
 * list below shows everything the plugin does.
 */
final class Plugin {

	public static function boot(): void {
		( new AllInOneWpMigration\ExportExclusions() )->register();
		( new Postmark\SandboxSafeguard() )->register();
	}
}
