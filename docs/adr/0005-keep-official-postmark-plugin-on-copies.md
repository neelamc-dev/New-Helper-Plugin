# Keep the official Postmark plugin on copies and swap its key

The export-exclusions task lists Postmark among the plugins to leave out of backups, naming PostmarkApp Email Integrator (`postmarkapp-email-integrator`). Our sites actually run the official ActiveCampaign Postmark plugin (`postmark-approved-wordpress-plugin`). We keep that plugin in exports, so copies still send email the way production does, and an import fix swaps its live API key and message stream for test ones on non-production sites. The task's named plugin stays excluded.

## Consequences

- Copies can test real email flows (forms, checkout, password resets) without reaching real inboxes, which excluding the plugin would not allow.
- Until the Postmark import fix exists, a copy imported from production keeps the live key and can send real email. That fix should land before the team relies on this.
