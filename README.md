# MadeByAura Helper

Internal WordPress plugin for the MadeByAura team, installed on every site: local, staging, and production. It makes the development workflow safer and more reliable, especially when a production site is copied to a local or staging site.

It is a standard plugin (not a must-use plugin) with no admin interface.

## Requirements

- WordPress 5.5 or later
- PHP 7.4 or later

## Environment detection

Features decide how to behave with `MadeByAura\Helper\Environment`:

```php
use MadeByAura\Helper\Environment;

if ( ! Environment::is_production() ) {
	// Only runs on local and staging sites.
}
```

The environment comes from the `WP_ENVIRONMENT_TYPE` constant, read through WordPress core's `wp_get_environment_type()`:

| `WP_ENVIRONMENT_TYPE` | Detected as  |
| --------------------- | ------------ |
| `local`               | `local`      |
| `development`         | `staging`    |
| `staging`             | `staging`    |
| `production`          | `production` |
| missing or invalid    | `production` |

LocalWP sets `local` automatically. **Every staging server must define the constant in `wp-config.php`**, or it is treated as production:

```php
define( 'WP_ENVIRONMENT_TYPE', 'staging' );
```

## Features

### All-in-One WP Migration: export exclusions

Every export leaves out the items below, whatever options the person exporting picks. This runs on every environment.

- **Plugins** (folders in `wp-content/plugins`): `postmarkapp-email-integrator`, `klaviyo`, `woocommerce-shipstation-integration`. The official Postmark plugin (`postmark-approved-wordpress-plugin`) is deliberately kept: an import fix switches it to a test key on copies instead.
- **Files and folders** directly in `wp-content`: `ai1wm-backups`, `cache`, `jetpack-waf`, `mu-plugins`, `upgrade`, `upgrade-temp-backup`, `debug.log`
- **Database tables** (with the site's table prefix): `wc_webhooks`, `woocommerce_api_keys`, `woocommerce_sessions`, `user_registration_sessions`, `woocommerce_log`

To change the lists, edit the constants in `src/AllInOneWpMigration/ExportExclusions.php`.

## Structure

```
new-helper-plugin.php   Plugin header, constants, class autoloader, and boot
src/Plugin.php          Registers every feature's hooks
src/Environment.php     Environment detection
src/<Integration>/      One folder per third-party plugin we adjust
CONTEXT.md              Glossary of the terms we use (copy, import, import fix, ...)
docs/adr/               Decision records: what we decided and why
```
