<?php
add_action( 'admin_menu', 'cuu_config_page' );

function cuu_admin_init() {
    global $wp_version;
	wp_register_style('complete-update-urls.css', plugin_dir_url( __FILE__ ) . 'complete-update-urls.css');
	wp_enqueue_style('complete-update-urls.css');
}
add_action('admin_init', 'cuu_admin_init');

function cuu_config_page() {
	if ( function_exists('add_submenu_page') )
		add_menu_page(__( 'Complete Update URLs'), __('Complete Update URLs'), 'manage_options', 'complete-update-urls', 'cuu_conf',
			plugin_dir_url( __FILE__ ) . '/icon16.png' );
}

function cuu_conf() {
	?>
	<div class="wrap">
		<h2>Complete Update URLs</h2>
	 	by <strong>Cameron Lerch</strong> of <strong>Brightflock</strong>
	 	<br /><br />

        <div>
			<a target="_blank" title="<?php print __('FAQs') ?>" href="http://brightflock.com/complete-update-urls-faqs"><?php print __('FAQs') ?></a>
			| <a target="_blank" title="<?php print __('Complete Update URLs Support') ?>" href="http://brightflock.com/complete-update-urls-support"><?php print __('Support') ?></a>
		</div>
	<?php
	
	$current_url = get_bloginfo('siteurl');
	$new_url = null;

	if (isset($_POST['submit']) && isset($_POST['new_location'])) {
		$str = trim(strtolower($_POST['new_location']));
		
		if ($str != 'http://newlocation.com' && preg_match('/^http(s)?:\/\/.+/', $str)) {
			$new_url = $str;
		}
	}
	
	if ($new_url) {
		$cuu = new CompleteUpdateURLs($current_url, $new_url);
		?>
		<p class="cuu-status">
			Performing update from <em><?php print $current_url; ?></em> to <em><?php print $new_url; ?></em>...
		</p>
		
		<p class="cuu-status">
		<?php
		    try {
		        $count = $cuu->update();
		        print "Complete Update URLS completed successfully (" . $count . " database entries changed)! Remember that your " .
		          "WordPress database now no longer works at <em>" . $current_url . "</em>. Your WordPress database will now work when " .
                  "it is installed at <em>" . $new_url . "</em>.";
            }
            catch (Exception $e) {
                print "An error has occurred! Your WordPress database is now in an unstable state. Restore your database from " .
                    "your backup and try again.";
            }
        ?>
        </p>
		
		<?php
	}
	else {
		?>
		<p class="cuu-instructions">
			The URLs in your WordPress database will be updated from the current site location to the new site location you specify. Updating 
			the URLs in your WordPress database is necessary when transferring your WordPress site from one location to another. Once you
			click the <em>Update URLs</em> button, your WordPress database will no longer work at the current url.
			<br /><br />
			If you prefer, you can use the <em>cuu.php</em> script found in the plugin directory instead of this page to update your URLs from the command line.
			<br /><br/>
			See <a target="_blank" title="<?php print __('FAQs') ?>" href="http://brightflock.com/complete-update-urls-faqs"><?php print __('FAQs') ?></a> for more information.
		</p>
		<p class="cuu-warning">
			Back up your WordPress database before performing this action! If an error happens to occur your database will be left in an unusable state!
		</p>
		<form id="target" method="post" action="" class="cuu-conf" >
	
	    <table class="form-table">
	    	<tr><th scope="row"><strong>Current site location:</strong></th>
	    		<td><p><?php print get_bloginfo('siteurl'); ?></p></td> 
	        <tr><th scope="row"><strong>New site location:</strong></th>
	           <td><p><input id="new_location" name="new_location" type="text" value="http://newlocation.com" /> (<?php _e('<a target="_blank" href="http://brightflock.com/complete-update-urls-faqs#new_site_location">What is this?</a>'); ?>)</p></td>
	        </tr>
	    </table>
	    <p class="submit"><input type="submit" name="submit" value="<?php _e('Update URLs &raquo;'); ?>" /></p>
	    </form>
	<?php
	}
	?>
		<p>Sponsored by <a href="http://brightflock.com"><img class="cuu-bflogo" src="<?php print plugin_dir_url( __FILE__ ) . 'bf.png'; ?>" alt="Brightflock"/></a></p>
	</div>
	<?php
}
?>