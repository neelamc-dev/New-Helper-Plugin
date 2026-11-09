# Detect the environment only from WP_ENVIRONMENT_TYPE

The plugin decides whether a site is local, staging, or production solely from WordPress core's `wp_get_environment_type()`, which reads the `WP_ENVIRONMENT_TYPE` constant. We do not guess from the hostname, even though our hosts follow patterns (LocalWP sites on `*.local`, staging sites on `*.madebyaura.dev`), because a guess that misfires on a real site is worse than a missing setting, and the constant is the standard hosts and other plugins already honor.

WordPress knows four types and we use three: `development` counts as staging, because it means a shared non-production server rather than a team member's own machine, and the stricter group keeps team-only behavior away from clients on those servers.

## Consequences

- A site without the constant is treated as production, so every protection meant for non-production sites stays off there. Setting `WP_ENVIRONMENT_TYPE` is therefore part of the staging setup checklist; LocalWP sets `local` automatically.
- Because staging hostnames are predictable, a warning for a "production" site on a `*.madebyaura.dev` host is a natural follow-up for the admin notices work, without changing detection itself.
