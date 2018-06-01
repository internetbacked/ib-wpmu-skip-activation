<?php
/**
 * Plugin Name:     WPMU Admin Skip Activation
 * Plugin URI:      https://github.com/internetbacked/ib-wpmu-skip-activation
 * Description:     Skip the default WPMU activation step for admin
 * Author:          ihsanberahim
 * Author URI:      https://ihsanberahim.my
 * Text Domain:     ib-wpmu-skip-activation
 * Domain Path:     /languages
 * Version:         1.1.0
 *
 * @package         Ib_Wpmu_Skip_Activation
 */


function ib_wpmu_skip_activation( $user, $user_email, $key, $meta ) {
	if ( is_admin() ) {
		remove_all_filters( 'wp_new_user_notification_email' );

		// Manually activate the newly created user account
		$result = wpmu_activate_signup( $key );

		if ( ! is_wp_error( $result ) ) {
			if ( $user = new WP_User( $result['user_id'] ) ) {
				wp_set_password( $user->user_login, $user->ID );

				wp_redirect( admin_url( '/user-new.php?user_login=' . $user->user_login ) );
				exit;
			}
		}

		// Return false to prevent WordPress from sending the user signup email (which includes the account activation link)
		return false;
	}

	return true;
}

add_filter( 'wpmu_signup_user_notification', 'ib_wpmu_skip_activation', 10, 4 );

if ( is_admin() ) {
	function ib_wpmu_pwd_changed_notice() {
		global $wpdb;

		$user_login = null;

		if ( isset( $_GET['user_login'] ) ) {
			if ( ! $user_login = $_GET['user_login'] ) {
				return;
			}
		}

		$user_email = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM {$wpdb->users} WHERE user_login = %s", [ $user_login ] ), 0 );

		?>
		<div class="notice notice-success is-dismissible">
			<p><strong>User added succesfully</strong></p>
			<p>Temporary password for user <code><?php echo $user_email; ?></code> is <code><?php echo $user_login; ?></code></p>
		</div>
		<?php
	}

	if ( isset( $_GET['user_login'] ) ) {
		add_action( 'admin_notices', 'ib_wpmu_pwd_changed_notice' );

	}
}
