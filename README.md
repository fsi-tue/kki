# Kneipen- und Kulturinterface (PHP files)
## Introduction
This PHP script uses an SQL-based RDBMS like MariaDB to manage bars, restaurants, fastfood places etc. across Tübingen.
New items can be supplied by users and have to be activated by an admin or moderator before they are displayed.
Items can be fully modified prior to activation or deleted.

## Installation
Clone the entire repository and upload the contents to a PHP-enabled directory of your choice.
Then, enter the credentials to MariaDB inside the file credentials.in in the following format:

```ini
[db]
mysql_user=<mysql-username>
mysql_server=<mysql-server>
mysql_db=<database-name>
mysql_pw=<mysql-password>
mysql_table=<table-name>
```

To create the database schema, navigate your browser to the file `_initdb.php`.

All activated items are shown in a table in `index.php`. New entries can be created with `create.php` or by clicking the button "Neuen Eintrag anlagen".

## Creation
The information for a location can be entered via a simple form. The user can supply the following information:
- name of the location
- address
- category (bar, fast food, restaurant, club/disco)
- price for a "Halbe"
- price for a large softdrink
- URL
- phone number
- a short description of the location

Additionally, the user can answer the following questions with (yes/no/don't know):
- Is there food on the menu?
- Is there a takeaway option?
- Is beer on the menu?
- Are cocktails on the menu?
- Is there a room for smokers? 
- Is there a room for non-smokers?
- Is there free wifi?

These fields are shown as small icons and marked with either "✔", "×" or "?".


## Administration
To review new entries supplied by users or to edit details of a specific location, navigate your browser to `index_admin.php`. Here you can view all entries as well as edit and delete specific entries.

## CSV Export
The information inside the database schema can be dumped into a CSV file to be used inside the FSI Anfiheft (or for other purposes). The export can be triggered by clicking "CSV-Export" inside `admin.php`, which dumps a CSV output into the browser. This is the same as calling `dump.php` or `dump.php?type=echo`.
If the CSV file should be downloaded instead, call `dump.php?type=file` and your browser will download the CSV file instead.

### Delimiter
The CSV delimiter is set to `|` per default. To use another delimiter, edit the file `dump.php`:
```php
$db->dumpToCSV('|'); // change `|` to the delimiter you want to use
[…]
$db->dumpToCSV('|', $filename); // change `|` to the delimiter you want to use
```

## WIP
This project explicitly is a *work in progress*. Issues that still need to be adressed:
- Access control to `index_admin.php, dump.php, delete.php, edit.php`, maybe using PHP sessions
- Encapsulate handling of POST parameters to avoid code duplications inside `create.php` and `store.php`
