# Skule™ Lockers Management System

This repository contains the full-stack PHP application for managing the Skule™ lockers.

## Deployment

Currently deployed on Plesk but it's a copy of the repo (no continuous deployment).
This repository has been updated to reflect the Plesk version of the lockers system.


CPanel via this repository.

`.config.yml` contains config info.

To Deploy via the `lockers` cpanel:
2. Click `Git Version Control`
3. Click on the repo `lockers`
4. On the nav bar, click on `Pull or Deploy`
5. Click `Update from Remote` to fetch latest repo changes
6. Click `Deploy HEAD Commit` to deploy

## Project Structure

```text
LOCKERS.SKULE.CA
├── admin/                  # Admin dashboards
│   ├── footer.php          # Doesn't display anything but has CRITICAL logic
│   ├── index.php           # Admin Dashboard Home
│   ├── rentals.php         # Full record of rentals
│   └── ...
├── css/                    # Stylesheets (Materialize & Custom)
├── img/                    # Logos
├── js/                     # JavaScript libraries
├── model/
│   └── db.php              # Database Connection (MySQLi)
├── user/                   # User dashboard
│   ├── footer.php          # Doesn't display anything but has CRITICAL logic
│   ├── index.php           # User Dashboard Home
│   └── ...
├── .cpanel.yml             # Deployment config (unused for Plesk?)
├── .htaccess               # Apache rewrite rules
├── booking.php             # User booking logic
├── footer.php
├── index.php               # Main landing page
├── paypal.php              # Payment gateway integration
└── ...                     # Other functions and UI elements
```

## Misc. Notes
### CSS

CSS powered by [Material CSS](https://materializecss.com/). View docs for info.

### MySQL Quirks

For some reason, it likes to have the column name in `` and the parameter in '' or "", i.e.
```sql
AND `locker_status`='Available'
```