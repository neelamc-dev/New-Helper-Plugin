<?php
/**
 * Plugin entry point.
 *
 * @package MadeByAura\Helper
 */

namespace MadeByAura\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Registers every feature's hooks.
 *
 * Each feature is a class with a register() method that only adds hooks,
 * so booting is cheap and the list below shows everything the plugin does.
 */
final class Plugin {

	public static function boot(): void {
		( new AllInOneWpMigration\ExportExclusions() )->register();
	}
}
