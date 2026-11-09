# Exclude tables through an extra All-in-One WP Migration export step

All-in-One WP Migration offers filters for excluding plugins and wp-content files, but none for database tables. We exclude tables by adding our own step to its `ai1wm_export` step chain at priority 135, just before it lists tables at 140, and writing our tables into the export's own "exclude tables" option. The alternatives were telling the team to tick the tables by hand on every export, which is the problem the task exists to remove, or filtering the SQL dump afterward, which is far more fragile.

## Consequences

- The approach depends on All-in-One WP Migration's internal step priorities (verified against 7.113 and Unlimited Extension 2.86, whose scheduled backups use the same step chain). Re-check after major updates: if the table step moves, exclusions could stop silently.
- Tables are excluded with the current site's prefix only. Multisite is out of scope: All-in-One WP Migration exports a network only with its Multisite Extension (the free plugin and the Unlimited Extension both stand aside on Multisite), and we do not use it.
- The archive's own metadata (`package.json`) is written earlier, at step 50, so it does not list our tables; the tables are still left out of the dump.
