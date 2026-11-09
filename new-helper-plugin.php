<?php
/**
 * Plugin Name:       MadeByAura Helper
 * Description:       Internal helper plugin for the MadeByAura team. Keeps local, staging, and production sites safe and consistent.
 * Version:           0.1.0
 * Requires at least: 5.5
 * Requires PHP:      7.4
 * Author:            MadeByAura
 * Text Domain:       new-helper-plugin
 *
 * @package MadeByAura\Helper
 */

namespace MadeByAura\Helper;

defined( 'ABSPATH' ) || exit;

const PLUGIN_FILE = __FILE__;
const PLUGIN_DIR  = __DIR__;

/*
 * Map classes in the MadeByAura\Helper namespace to files in src/.
 * Example: MadeByAura\Helper\Environment -> src/Environment.php
 */
spl_autoload_register(
	static function ( string $class ): void {
		$prefix = __NAMESPACE__ . '\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative_class = substr( $class, strlen( $prefix ) );
		$file           = PLUGIN_DIR . '/src/' . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( is_readable( $file ) ) {
			require $file;
		}
	}
);

Plugin::boot();
