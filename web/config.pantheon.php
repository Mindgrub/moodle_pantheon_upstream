<?php

/**
 * @file
 * Pantheon configuration file.
 *
 * IMPORTANT NOTE:
 * Do not modify this file. This file is maintained by Pantheon.
 *
 * Site-specific modifications belong in settings.php, not this file. This file
 * may change in future releases and modifications would cause conflicts when
 * attempting to apply upstream updates.
 */

/**
 * Version of Pantheon files.
 *
 * This is a monotonically-increasing sequence number that is
 * incremented whenever a change is made to any Pantheon file.
 * Not changed if Moodle core is updated without any change to
 * any Pantheon file.
 *
 * The Pantheon version is included in the git tag only if a
 * release is made that includes changes to Pantheon files, but
 * not to any Drupal files.
 */
if (!defined("PANTHEON_VERSION")) {
  define("PANTHEON_VERSION", "1");
}

/**
 * Override the $databases variable to pass the correct Database credentials
 * directly from Pantheon to Drupal.
 *
 * Issue: https://github.com/pantheon-systems/drops-8/issues/8
 *
 */
if (isset($_SERVER['PRESSFLOW_SETTINGS'])) {
  $pressflow_settings = json_decode($_SERVER['PRESSFLOW_SETTINGS'], TRUE);
  foreach ($pressflow_settings as $key => $value) {
    // One level of depth should be enough for $conf and $database.
    if ($key == 'conf') {
      foreach($value as $conf_key => $conf_value) {
        $conf[$conf_key] = $conf_value;
      }
    }
    elseif ($key == 'databases') {
      // We set the database here first, so it can be overriden later for local dev.
      $CFG->dbtype    = 'mariadb';      // 'pgsql', 'mariadb', 'mysqli', 'sqlsrv' or 'oci'
      $CFG->dblibrary = 'native';     // 'native' only at the moment
      $CFG->dbhost    = $value['default']['default']['host'];  // eg 'localhost' or 'db.isp.com' or IP
      $CFG->dbname    = $value['default']['default']['database'];     // database name, eg moodle
      $CFG->dbuser    = $value['default']['default']['username'];   // your database username
      $CFG->dbpass    = $value['default']['default']['username'];   // your database password
      $CFG->prefix    = '';       // prefix to use for all table names
      $CFG->dboptions = array(
        'dbpersist' => false,       // should persistent database connections be
        //  used? set to 'false' for the most stable
        //  setting, 'true' can improve performance
        //  sometimes
        'dbsocket'  => false,       // should connection via UNIX socket be used?
        //  if you set it to 'true' or custom path
        //  here set dbhost to 'localhost',
        //  (please note mysql is always using socket
        //  if dbhost is 'localhost' - if you need
        //  local port connection use '127.0.0.1')
        'dbport'    => $value['default']['default']['port'],          // the TCP port number to use when connecting
        //  to the server. keep empty string for the
        //  default port
        'dbhandlesoptions' => false,// On PostgreSQL poolers like pgbouncer don't
        // support advanced options on connection.
        // If you set those in the database then
        // the advanced settings will not be sent.
        'dbcollation' => 'utf8mb4_unicode_ci', // MySQL has partial and full UTF-8
        // support. If you wish to use partial UTF-8
        // (three bytes) then set this option to
        // 'utf8_unicode_ci', otherwise this option
        // can be removed for MySQL (by default it will
        // use 'utf8mb4_unicode_ci'. This option should
        // be removed for all other databases.
        // 'fetchbuffersize' => 100000, // On PostgreSQL, this option sets a limit
        // on the number of rows that are fetched into
        // memory when doing a large recordset query
        // (e.g. search indexing). Default is 100000.
        // Uncomment and set to a value to change it,
        // or zero to turn off the limit. You need to
        // set to zero if you are using pg_bouncer in
        // 'transaction' mode (it is fine in 'session'
        // mode).
      );

    }
    else {
      $$key = $value;
    }
  }
}


//=========================================================================
// 2. WEB SITE LOCATION
//=========================================================================
// Now you need to tell Moodle where it is located. Specify the full
// web address to where moodle has been installed.  If your web site
// is accessible via multiple URLs then choose the most natural one
// that your students would use.  Do not include a trailing slash
//
// If you need both intranet and Internet access please read
// http://docs.moodle.org/en/masquerading

$CFG->wwwroot   = $_SERVER['HTTP_HOST'];


//=========================================================================
// 3. DATA FILES LOCATION
//=========================================================================
// Now you need a place where Moodle can save uploaded files.  This
// directory should be readable AND WRITEABLE by the web server user
// (usually 'nobody' or 'apache'), but it should not be accessible
// directly via the web.
//
// - On hosting systems you might need to make sure that your "group" has
//   no permissions at all, but that "others" have full permissions.
//
// - On Windows systems you might specify something like 'c:\moodledata'

$CFG->dataroot  = $_ENV['HOME'] . '/files';

//=========================================================================
// 4. DATA FILES PERMISSIONS
//=========================================================================
// The following parameter sets the permissions of new directories
// created by Moodle within the data directory.  The format is in
// octal format (as used by the Unix utility chmod, for example).
// The default is usually OK, but you may want to change it to 0750
// if you are concerned about world-access to the files (you will need
// to make sure the web server process (eg Apache) can access the files.
// NOTE: the prefixed 0 is important, and don't use quotes.

$CFG->directorypermissions = 02777;


//=========================================================================
// 5. DIRECTORY LOCATION  (most people can just ignore this setting)
//=========================================================================
// A very few webhosts use /admin as a special URL for you to access a
// control panel or something.  Unfortunately this conflicts with the
// standard location for the Moodle admin pages.  You can work around this
// by renaming the admin directory in your installation, and putting that
// new name here.  eg "moodleadmin".  This should fix all admin links in Moodle.
// After any change you need to visit your new admin directory
// and purge all caches.

$CFG->admin = 'admin';
