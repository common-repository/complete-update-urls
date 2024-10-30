#!/usr/bin/php
<?php
/**
 * Complete Update URLs
 * 
 * Utility script to update URLs outside of the WordPress admin interface
 * 
 * usage: php cuu.php http://oldurl.com http://newurl.com
 */

require_once dirname( __FILE__ ) . '/CompleteUpdateURLs.php';

// We assume we're installed in the standard plugin location
require_once dirname( __FILE__ ) . '/../../../wp-load.php';

if ($argc != 2) {
    print "Usage: php cuu.php http://newlocation.com\n";
    exit(1);
}

$old = get_bloginfo('siteurl');
$new = $argv[1];
 
$cuu = new CompleteUpdateURLs($old, $new);

print "Performing update from " . $old . " to " . $new . "...\n";

try {
    $count = $cuu->update();
    print "Complete Update URLS completed successfully (" . $count . " database entries changed)!\n";
}
catch (Exception $e) {
    print "An error has occurred! Restore your database from your backup and try again.\n";
}

exit(0);
?>