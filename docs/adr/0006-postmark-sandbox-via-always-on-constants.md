# Send Postmark email to the sandbox with always-on constants on local and staging sites

On every local and staging site, the plugin defines `POSTMARK_API_KEY` and `POSTMARK_STREAM_NAME` with sandbox values as it loads. The official Postmark plugin lets these constants take precedence over its saved settings and reads them before every send, so a copy sends through the sandbox whether or not Postmark was active during the import, and activating Postmark later does not bring the live key back. Nothing is stored, so there is nothing for an import to overwrite and no setting someone can edit. This makes it a safeguard rather than an import fix. On production the plugin defines nothing.

The values are hard-coded in `src/Postmark/SandboxSafeguard.php`. For now the key is Postmark's test token, `POSTMARK_API_TEST`, which Postmark accepts but never delivers, and the stream is `outbound`; replacing the key with the team's sandbox server token is a single edit.

## Considered Options

- **Rewrite Postmark's saved settings after each import** (an import fix): rejected; anyone can change the values back on Postmark's settings page, it only covers All-in-One WP Migration imports, and it writes Postmark's JSON option format, which we would then depend on.
- **Constants plus rewriting the saved settings**: rejected for now; it would also remove the live key from the copy's database, but adds that same dependency for a case (our plugin deactivated) that takes a deliberate act.

## Consequences

- **The safeguard applies only while this plugin is active.** Deactivating or deleting it on a copy brings the live key and stream back immediately.
- A copy is protected only if this plugin is active on the production site it came from, because the import brings production's list of active plugins with it.
- The live key stays in the copy's database (in the `postmark_settings` option), unused while the safeguard is on.
- A site that already defines either constant, for example in `wp-config.php`, keeps its own value for that constant; the other is still defined. The Admin Notices work should warn when this happens on a copy.
- Postmark's settings page notes that the key and stream come from constants only if this plugin loads before Postmark. WordPress loads plugins in the order of the `active_plugins` option, normally alphabetical by folder, and `new-helper-plugin` comes before `postmark-approved-wordpress-plugin`. That notice always says the constants are "defined in your wp-config.php", which is misleading here; the Admin Notices work could explain where they really come from. Sending uses the sandbox either way, because Postmark reads the constants again on every send.
- **Known limitation:** when Postmark is inactive or disabled in its own settings, WordPress sends through the server's normal mail instead, which this safeguard does not cover. A separate safeguard that blocks outgoing mail on local and staging sites may be needed.
- Depends on Postmark's constant names (verified against 1.19.1).
