A bibliography management app relying on PHP + php-mbstring extension, a bit of Javascript and MySQL/mariaDB.


# Get it running #
## First step: config file ##

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

## Second step ##

You need a server side directory authentication, e.g. via apaches .htaccess. And the appropriate authentication names in the database table `users` with the names in the `login` field.
If you have the user 'foobar' in your .htaccess file, you'll need a row in the `users` table with the login field having the value 'foobar'.
For users that get authenticated by the server bibliographie will automatically create the appropriate row in the `users` table.

### The following steps depend on your environment and might be unnecessary ###

* You might have to give the webserver access to the installation directory. (e.g. under Ubuntu: chown www-data /var/www/bibliographie)
* You might also have to explicitly allow writes to the files and folders. (e.g. chmod -R 0775 /var/www/bibliographie/*)

## Third and final step ##

Access the app via a browser at the path you've set in the config file earlier. Follow the instructions to either convert an existing database or create a new, empty one.


# 3rd party libraries #
This is a list of stuff that i didn't handcraft myself but took from other nice people because their software suits my needs.

* jQuery http://www.jquery.com/
* jQuery UI http://www.jquery-ui.com/
* jGrowl http://plugins.jquery.com/project/jGrowl
* jQuery TokenInput http://loopj.com/jquery-tokeninput/
* Structures_BibTex http://pear.php.net/package/Structures_BibTex (heavily modified)

## Adjust 3rd party libraries ##
From file `resources/javascript/jquery.tokeninput.js` remove all lines where it says `cache.add(SOMETHING)`. This is already done in the file that is redistributed with bibliographie.

Additionally at the block (lines 198 to 201)

```js
.blur(function () {
	hide_dropdown();
	$(this).val("");
})
```

remove the line `$(this.val(""));`. This is also already done in the redistributed file.

# Credits, history #
Rooted in the database scheme of the bibliography management app Aigaion v2.1.2. by Dennis Reidsma. That all happened before the currently available forks of Aigaion.
