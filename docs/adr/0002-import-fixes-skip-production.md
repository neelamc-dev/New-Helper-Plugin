# Import fixes run only on non-production sites

Every import fix (pausing backup schedules, deactivating Live Only snippets) runs only when the site receiving the import is not production. Imports also happen on production itself, for example when recovering from a problem with the site's own backup, and running the fixes there would silently turn off production's backups or snippets. Postmark's sandbox switch is a safeguard rather than an import fix, but follows the same rule (see ADR 0006).

## Consequences

- The decision uses the receiving site's environment. All-in-One WP Migration backups contain wp-content and the database, not `wp-config.php`, so the receiving site keeps its own `WP_ENVIRONMENT_TYPE` through the import.
- A staging site that does not declare its environment counts as production (see ADR 0001), so it gets no import fixes. Setting the constant on staging is what makes these protections work.
