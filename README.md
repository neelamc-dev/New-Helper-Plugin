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

## Structure

```
new-helper-plugin.php   Plugin header, constants, and class autoloader
src/                    Classes in the MadeByAura\Helper namespace, one per file
```
