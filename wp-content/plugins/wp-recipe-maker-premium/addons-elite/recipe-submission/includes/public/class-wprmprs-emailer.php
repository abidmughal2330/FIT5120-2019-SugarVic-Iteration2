<?php
/**
 * Send out emails on recipe submission.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/includes/public
 */

/**
 * Send out emails on recipe submission.
 *
 * @since      2.1.0
 * @package    WP_Recipe_Maker_Premium/addons-elite/recipe-submission
 * @subpackage WP_Recipe_Maker_Premium/addons-elite/recipe-submission/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRMPRS_Emailer {
	/**
	 * Send the after recipe submission email notification.
	 *
	 * @since	2.1.0
	 */
	public static function after_submission_notification() {
		$to = WPRM_Settings::get( 'recipe_submission_admin_email' );

		if ( $to ) {
			$manage_link = admin_url( 'admin.php?page=wprecipemaker&sub=recipe_submissions' );

			$subject = __( 'New Recipe Submission', 'wp-recipe-maker-premium' );
			$message = __( 'There is a new Recipe Submission on your website!', 'wp-recipe-maker-premium' );
			$message .= '<br/><a href="' . $manage_link . '">' . __( 'Manage now', 'wp-recipe-maker-premium' ) . '</a>.';

			wp_mail( $to, $subject, $message );
		}
	}
}
