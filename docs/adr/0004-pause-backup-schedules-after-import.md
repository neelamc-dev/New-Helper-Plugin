# Pause backup schedules through the extension's own API after an import

After an import finishes on a non-production site, every All-in-One WP Migration backup schedule is set to Disabled using the Unlimited Extension's own `Ai1wmve_Schedule_Events::save()`, then every queued `ai1wmve_schedule_event` cron run is cleared. We pause rather than delete so a team member can deliberately turn a schedule back on, and we go through the extension's API rather than editing its stored option directly, because `save()` also clears the schedule's cron event and the option holds serialized objects that are easy to corrupt.

## Considered Options

- **Delete the schedules**: rejected; it loses their settings and cannot be undone.
- **Only unschedule the cron events**: rejected; schedules re-queue themselves, and the next save in the schedules screen reschedules anything still Enabled.
- **Edit the `ai1wmve_schedule_events` option directly**: rejected; it couples us to the extension's storage format instead of its public methods.

## Consequences

- The fix runs only when an All-in-One WP Migration import finishes (`ai1wm_status_import_done`). Copies made another way (manual database import, WP-CLI, another migration plugin) are caught by the admin notices work instead.
- It depends on the Unlimited Extension's class and method names (verified against 2.86) and does nothing if the extension is not active.
