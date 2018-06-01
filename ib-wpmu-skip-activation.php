<?php
/**
 * Plugin Name:     WPMU Skip Activation
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Skip the default WPMU activation step
 * Author:          ihsanberahim
 * Author URI:      https://ihsanberahim.my
 * Text Domain:     ib-wpmu-skip-activation
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Ib_Wpmu_Skip_Activation
 */

/**
 * Halts the user signup process (where a user would receive an email to activate their account)
 *		and instead automatically activates the account and sends the user welcome email (template
 *		set in network settings). This was done so that site Administrators could create new user
 *		accounts, at the blog level, and bypass the need for the user to activate the account.
 *
 * @since 1.0.0
 *
 * @param string $user			The new users username.
 * @param string $user_email	The new users email address.
 * @param string $key			The user activation key, needed to create the user account from the "signup" table
 * @param array  $meta			User meta.
 * @return boolean
 */
function laubsterboy_signup_user_notification($user, $user_email, $key, $meta) {
 // Manually activate the newly created user account
 wpmu_activate_signup($key);
 
 // Return false to prevent WordPress from sending the user signup email (which includes the account activation link)
 return false;
}

add_filter('wpmu_signup_user_notification', 'laubsterboy_signup_user_notification', 10, 4);
 
