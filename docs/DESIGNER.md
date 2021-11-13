For Designers
=============

Installing first time
---------------------

```bash
composer install
npm install
```

Starting environment
--------------------

```bash
make
```

Editing templates
-----------------

- `content/_layouts/main.blade.php` contains a shared layout file included by all other templates.
- In `content/_layouts/pages/` there are templates for each collection object e.g. every organization.
- `*.blade.php` files in `content/` root directory are subpages that will be rendered as e.g. `subpage/index.html` and visible under url http://.../subpage

Variables
---------

- `$organizations`
- `$activities`
- `$badges`
- `$locations`
