# Keep the official Postmark plugin on copies and swap its key

The export-exclusions task lists Postmark among the plugins to leave out of backups, naming PostmarkApp Email Integrator (`postmarkapp-email-integrator`). Our sites actually run the official ActiveCampaign Postmark plugin (`postmark-approved-wordpress-plugin`). We keep that plugin in exports, so copies still send email the way production does, and a safeguard swaps its live API key and message stream for sandbox ones on every non-production site (see ADR 0006). The task's named plugin stays excluded.

## Consequences

- Copies can test real email flows (forms, checkout, password resets) without reaching real inboxes, which excluding the plugin would not allow.
- Copies rely on the Postmark safeguard: without it, a copy imported from production would keep the live key and could send real email.
