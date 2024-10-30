<?php
/**
 * @package Complete Update URLs
 */
/*
Plugin Name: Complete Update URLs
Plugin URI: http://brightflock.com/complete-update-urls
Description: Update your URLs from the current site location to a new site location. <a href="admin.php?page=complete-update-urls">Complete Update URLs panel</a> | <a href="http://brightflock.com/complete-update-urls-faqs">FAQs</a> | <a href="http://brightflock.com/complete-update-urls-support">Support</a>
Version: 1.0
Author: Cameron Lerch (Brightflock)
Author URI: http://brightflock.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('CUU_VERSION', '1.0');
define('CUU_PLUGIN_URL', plugin_dir_url( __FILE__ ));

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

if ( is_admin() ) {
	require_once dirname( __FILE__ ) . '/admin.php';
}

require_once dirname( __FILE__ ) . '/CompleteUpdateURLs.php';

function cuu_init() {
}

add_action('init', 'cuu_init');
?>