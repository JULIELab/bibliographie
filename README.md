Intents to be a bibliography management tool and was derived from the database scheme of Aigaion v2.1.2.
You can also use it with the current database scheme version v2.2, but the project was started with v2.1.2.

Bibliographie aims to tackle some problems of Aigaion like the old code base, slowness for large databases, problems with multi-hierarchical graphs and adds some neat functionality like suggestions and maintanance tasks, that makes it easier to cope with a lot of literature.

# Get it running #
## 1. step: config file ##

You need a config file named 'config.php' that you put in the root of this app. The file should look something like that:

```php
<?php
// MySQL connection data
define('BIBLIOGRAPHIE_MYSQL_HOST', 'host');
define('BIBLIOGRAPHIE_MYSQL_USER', 'user');
define('BIBLIOGRAPHIE_MYSQL_PASSWORD', 'password');
define('BIBLIOGRAPHIE_MYSQL_DATABASE', 'database');

// Root path of bibliographie without ending slash.
define('BIBLIOGRAPHIE_WEB_ROOT', '/bibliographie');

// Minimum of chars needed to start a search. Should be the same as the minimum length of MySQL fulltext index length.
define('BIBLIOGRAPHIE_SEARCH_MIN_CHARS', 4);

// Configuration for the tag cloud.
define('BIBLIOGRAPHIE_TAG_SIZE_FACTOR', 100);
define('BIBLIOGRAPHIE_TAG_SIZE_MINIMUM', 10);
define('BIBLIOGRAPHIE_TAG_SIZE_FLATNESS', 40);

// If you have a key for ISBNDB.com put it here.
define('BIBLIOGRAPHIE_ISBNDB_KEY', '');

// One of 'errors', 'all' or false
define('BIBLIOGRAPHIE_DATABASE_DEBUG', false);

// Wether to use caching or not. Highly recommended for large databases.
define('BIBLIOGRAPHIE_CACHING', true);

// Prefix for the mysql tables.
define('BIBLIOGRAPHIE_PREFIX', 'a2');
```

## 2. Step ##

You need a server side directory authentication, e.g. via apaches .htaccess. And the appropriate authentication names in the database table `users` with the names in the `login` field.
If you have the user 'foobar' in your .htaccess file, you'll need a row in the `users` table with the login field having the value 'foobar'.
For users that get authenticated by the server bibliographie will automatically create the appropriate row in the `users` table.

## 2.1 Step ##

The following steps depend on your environment and might be unnecessary:

* You might have to give the webserver access to the installation directory. (e.g. under Ubuntu: chown www-data /var/www/bibliographie)
* You might also have to explicitly allow writes to the files and folders. (e.g. chmod -R 0775 /var/www/bibliographie/*)

## 3. Step ##

Access the app via a browser at the path you've set in the config file earlier. Follow the instructions to convert/create the database scheme.

## Reach the finish line ##

All done... You can now start using bibliographie...

# 3rd party libraries #

* jQuery http://www.jquery.com/
* jQuery UI http://www.jquery-ui.com/
* jGrowl http://plugins.jquery.com/project/jGrowl
* jQuery TokenInput http://loopj.com/jquery-tokeninput/
* Structures_BibTex http://pear.php.net/package/Structures_BibTex (heavily modified)

* If you're new to bibliographie and do not have an aigaion database, you'll have a meta-topic called "Top" that is used as the root of the topic graph.
