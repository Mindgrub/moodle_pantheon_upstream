<?php

// Moodle configuration file

unset($CFG);  // Ignore this line
global $CFG;  // This is necessary here for PHPUnit execution
$CFG = new stdClass();

include __DIR__ . "/config.pantheon.php";

/**
 * If there is a local settings file, then include it.
 */
$local_settings = __DIR__ . "/config.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

require_once(__DIR__ . '/lib/setup.php'); // Do not edit

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!