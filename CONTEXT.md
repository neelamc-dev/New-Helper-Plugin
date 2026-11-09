# MadeByAura Helper

An internal WordPress plugin installed on every MadeByAura site. It keeps copies of production sites from behaving like production, and makes the team's development workflow safer.

## Language

### Sites and environments

**Environment**:
The kind of site something is running on: local, staging, or production. A site declares it; the plugin never guesses.
_Avoid_: Mode, stage, server type

**Local site**:
A site running on a team member's own machine, such as a LocalWP site on a `.local` domain.
_Avoid_: Dev site, localhost

**Staging site**:
A shared, non-production server, such as one on a `madebyaura.dev` domain. Sites that declare themselves "development" count as staging.
_Avoid_: Dev server, test site

**Production site**:
The live site that customers use. A site that declares no environment counts as production.
_Avoid_: Live site, original site

**Copy**:
A local or staging site made from a production site's backup.
_Avoid_: Clone, target site, duplicate

### Moving sites

**Import**:
All-in-One WP Migration restoring a backup onto a site.
_Avoid_: Restore, migration

**Import fix**:
A change the plugin applies to a copy right after an import so it cannot act like production, such as pausing backup schedules.
_Avoid_: Post-import task, cleanup

**Backup schedule**:
A recurring backup configured in All-in-One WP Migration that runs automatically and can send backups to remote storage.
_Avoid_: Cron, scheduled export

### People

**Team member**:
A logged-in user whose email address ends in `@madebyaura.com`.
_Avoid_: Admin, developer, staff
