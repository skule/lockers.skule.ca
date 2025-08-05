# Lockers

This repository is in the process of being updated to reflect the (slightly 
newer) Plesk version of the lockers system.

## Deployment
CPanel via this repository.

`.config.yml` contains config info.

To Deploy via the `lockers` cpanel:
2. Click `Git Version Control`
3. Click on the repo `lockers`
4. On the nav bar, click on `Pull or Deploy`
5. Click `Update from Remote` to fetch latest repo changes
6. Click `Deply HEAD Commit` to deploy

## Misc. Notes
### CSS
CSS powered by [Material CSS](https://materializecss.com/). View docs for info.

### MySQL Quirks
For some reason, it likes to have the column name in `` and the parameter in '' or "", i.e.
```sql
AND `locker_status`='Available'
```
