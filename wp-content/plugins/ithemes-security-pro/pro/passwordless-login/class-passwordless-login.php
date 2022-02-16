<?php

use iThemesSecurity\Lib\Lockout\Host_Context;
use ITSEC_Passwordless_Login_Interstitial as Interstitial;
use ITSEC_Passwordless_Login_2fa_Setting_Interstitial as Setting_Interstitial;

require_once( dirname( __FILE__ ) . '/class-passwordless-login-interstitial.php' );
require_once( dirname( __FILE__ ) . '/class-passwordless-login-2Fa-setting-interstitial.php' );
require_once( dirname( __FILE__ ) . '/class-passwordless-login-utilities.php' );

class ITSEC_Passwordless_Login {

	const AJAX_ACTION_LOGIN_METHODS = 'itsec-get-login-methods';

	const ACTION = 'itsec-passwordless-login-prompt';
	const HIDE = 'itsec-pwls-hide';
	const NOTIFICATION = 'passwordless-login';

	const E_NOT_ALLOWED = 'itsec-passwordless-login-not-allowed';

	const FLOW_USER_FIRST = 'user-first';
	const FLOW_METHOD_FIRST = 'method-first';

	/** @var WP_Error */
	private $error;

	/** @var string */
	private $flow_type;

	/**
	 * ITSEC_Magic_Links constructor.
	 */
	public function __construct() {
		$this->error     = new WP_Error();
		$this->flow_type = ITSEC_Modules::get_setting( 'passwordless-login', 'flow' );
	}

	public function run() {
		ITSEC_Lib::load( 'login' );

		add_action( 'itsec_login_interstitial_init', array( $this, 'register_interstitial' ) );
		add_filter( 'wp_login_errors', array( $this, 'modify_login_errors' ) );
		add_action( 'wp_authenticate', array( $this, 'strip_credentials_from_wp_signon' ), 10, 2 );
		add_action( 'wp_ajax_' . self::AJAX_ACTION_LOGIN_METHODS, array( $this, 'ajax_get_login_methods' ) );
		add_action( 'wp_ajax_nopriv_' . self::AJAX_ACTION_LOGIN_METHODS, array( $this, 'ajax_get_login_methods' ) );

		add_action( 'personal_options', array( $this, 'render_profile_fields' ) );
		add_action( 'personal_options_update', array( $this, 'save_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_profile_fields' ) );

		add_filter( 'itsec_notifications', array( $this, 'register_notification' ) );
		add_filter( 'itsec_' . self::NOTIFICATION . '_notification_strings', array( $this, 'notification_strings' ) );

		if ( ! empty( $_GET[ self::E_NOT_ALLOWED ] ) ) {
			return;
		}

		add_action( 'login_enqueue_scripts', array( $this, 'enqueue' ) );
		add_filter( 'login_body_class', array( $this, 'login_body_class' ), 10, 2 );
		add_action( 'login_form', array( $this, 'add_ui' ) );
		add_action( 'login_form_' . self::ACTION, array( $this, 'render_magic_link_action_page' ) );
		add_action( 'login_form_' . self::ACTION, array( $this, 'maybe_send_magic_link' ), 9 );
	}

	/**
	 * Register the login interstitial.
	 *
	 * @param ITSEC_Lib_Login_Interstitial $lib
	 */
	public function register_interstitial( ITSEC_Lib_Login_Interstitial $lib ) {
		$lib->register( Interstitial::SLUG, new Interstitial() );
		$lib->register( Setting_Interstitial::SLUG, new Setting_Interstitial() );
	}

	/**
	 * Enqueue the necessary JS and CSS.
	 */
	public function enqueue() {
		global $action;

		if ( $this->should_include_ui( $action ) || self::ACTION === $action || 'itsec-passwordless-login' === $action ) {
			wp_enqueue_style( 'itsec-passwordless-login', plugin_dir_url( __FILE__ ) . 'css/login.css' );
		}

		if ( $this->should_include_ui( $action ) && empty( $_GET[ self::HIDE ] ) ) {
			wp_enqueue_script( 'itsec-passwordless-login', plugin_dir_url( __FILE__ ) . 'js/login.js', array( 'jquery' ) );
			wp_localize_script( 'itsec-passwordless-login', 'ITSECMagicLogin', array(
				'flow'           => $this->flow_type,
				'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
				'ajaxAction'     => self::AJAX_ACTION_LOGIN_METHODS,
				'magicAction'    => add_query_arg( 'action', self::ACTION, wp_login_url() ),
				'passwordAction' => wp_login_url(),
				'i18n'           => array(
					'login' => esc_html__( 'Log In', 'it-l10n-ithemes-security-pro' ),
				)
			) );

			if ( self::FLOW_USER_FIRST === $this->flow_type ) {
				ITSEC_Lib::render( __DIR__ . '/templates/user-first/user-form-template.php', array(
					'user_lookup_fields_label' => ITSEC_Lib_Login::get_user_lookup_fields_label(),
				) );
			}
		}
	}

	/**
	 * Add classes to the login body depending on if magic link is active or not.
	 *
	 * @param array  $classes
	 * @param string $action
	 *
	 * @return array
	 */
	public function login_body_class( $classes, $action ) {
		if ( $this->should_include_ui( $action ) ) {
			$classes[] = 'no-js itsec-pwls-login itsec-pwls-login--flow-' . $this->flow_type;

			if ( self::FLOW_METHOD_FIRST === $this->flow_type ) {
				$classes[] = empty( $_GET[ self::HIDE ] ) ? 'itsec-pwls-login--show' : 'itsec-pwls-login--hide';
			} elseif ( self::FLOW_USER_FIRST === $this->flow_type ) {
				if ( ! $this->has_user() ) {
					$classes[] = 'itsec-pwls-login--no-user';
				} else {
					$classes[] = 'itsec-pwls-login--has-user';

					if ( ( $user = ITSEC_Lib_Login::get_user( $_POST['log'] ) ) && ITSEC_Passwordless_Login_Utilities::can_user_use( $user ) ) {
						$classes[] = 'itsec-pwls-login--is-available';
					}
				}
			}
		}

		return $classes;
	}

	/**
	 * Add Passwordless Login UI to the login form.
	 */
	public function add_ui() {
		if ( ! empty( $_GET[ self::HIDE ] ) && self::FLOW_METHOD_FIRST === $this->flow_type ) {
			ITSEC_Lib::render( __DIR__ . '/templates/fallback.php' );

			return;
		}

		if ( self::FLOW_METHOD_FIRST === $this->flow_type ) {
			ITSEC_Lib::render( __DIR__ . '/templates/method-first/login.php', array(
				'user_lookup_fields_label' => ITSEC_Lib_Login::get_user_lookup_fields_label(),
				'prompt_link'              => add_query_arg( 'action', self::ACTION, wp_login_url() ),
			) );
		}

		if ( self::FLOW_USER_FIRST === $this->flow_type ) {
			$user = empty( $_POST['log'] ) ? null : ITSEC_Lib_Login::get_user( $_POST['log'] );

			if ( ! $this->has_user() ) {
				echo '<input type="hidden" name="itsec_pwls_login_user_first" value="1">';
				add_filter( 'gettext', function ( $translation, $text, $domain ) {
					if ( 'default' === $domain && 'Log In' === $text ) {
						$translation = __( 'Continue', 'it-l10n-ithemes-security-pro' );
					}

					return $translation;
				}, 10, 3 );
			} else {
				$is_available = $user && ITSEC_Passwordless_Login_Utilities::can_user_use( $user );
				ITSEC_Lib::render( __DIR__ . '/templates/user-first/login.php', array(
					'is_available' => $is_available,
					'prompt_link'  => add_query_arg( array( 'action' => self::ACTION, 'username' => $_POST['log'] ), wp_login_url() ),
					'username'     => $_POST['log'],
				) );
			}
		}
	}

	/**
	 * Should the Passwordless login UI be included.
	 *
	 * We include on the login action, or any other action that falls through
	 * to the login action. ie they aren't rendering a custom page using `login_form_`.
	 *
	 * @param string $action
	 *
	 * @return bool
	 */
	private function should_include_ui( $action ) {
		if ( 'login' === $action ) {
			return true;
		}

		if ( doing_action( 'login_form_' . $action ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Whether we have the user being logged in as.
	 *
	 * If the login request has been processed, and errored, we no longer have a user and
	 * go back to the first step of the flow.
	 *
	 * @return bool
	 */
	private function has_user() {
		global $errors;

		if ( empty( $_POST['log'] ) ) {
			return false;
		}

		if ( is_wp_error( $errors ) && ( $code = $errors->get_error_code() ) && 'empty_password' !== $code ) {
			return false;
		}

		return true;
	}

	/**
	 * Render the magic link form submission page.
	 */
	public function render_magic_link_action_page() {
		ITSEC_Lib::render( __DIR__ . '/templates/prompt-page.php', array(
			'error'                    => $this->error,
			'username'                 => isset( $_GET['username'] ) ? $_GET['username'] : '',
			'use_recaptcha'            => $this->use_recaptcha(),
			'user_lookup_fields_label' => ITSEC_Lib_Login::get_user_lookup_fields_label(),
		) );
		die;
	}

	/**
	 * Add login errors.
	 *
	 * @param WP_Error $error
	 *
	 * @return WP_Error
	 */
	public function modify_login_errors( $error ) {
		if ( ! empty( $_GET[ self::E_NOT_ALLOWED ] ) ) {
			$error->add(
				self::E_NOT_ALLOWED,
				__( 'Passwordless Login is not enabled for your account. Please login with your username and password.', 'it-l10n-ithemes-security-pro' )
			);
		}

		if ( self::FLOW_USER_FIRST === $this->flow_type ) {
			if ( ! empty( $_POST['itsec_pwls_login_user_first'] ) ) {
				$error->remove( 'empty_password' );
				$error->remove( 'empty_username' );
			} else {
				if (
					in_array( 'invalid_username', $error->get_error_codes(), true ) ||
					in_array( 'invalid_email', $error->get_error_codes(), true )
				) {
					unset( $_POST['log'] );
				}
			}
		}

		return $error;
	}

	/**
	 * Strip credentials from {@see wp_signon} when loading the non JS version of user first flow.
	 *
	 * This prevents other authentication methods from running.
	 *
	 * @param string $user_login
	 * @param string $user_password
	 */
	public function strip_credentials_from_wp_signon( &$user_login, &$user_password ) {
		if ( ! empty( $_POST['itsec_pwls_login_user_first'] ) && did_action( 'login_init' ) && self::FLOW_USER_FIRST === $this->flow_type ) {
			$user_login    = '';
			$user_password = '';
		}
	}

	/**
	 * Ajax endpoint to get the available login methods and HTML.
	 */
	public function ajax_get_login_methods() {
		/** @var ITSEC_Lockout $itsec_lockout */
		global $itsec_lockout;

		if ( empty( $_POST['log'] ) ) {
			wp_send_json_error( array(
				'message' => __( 'You must enter a username.', 'it-l10n-ithemes-security-pro' )
			) );
		}

		$methods = array( 'password' );

		if ( ( $user = ITSEC_Lib_Login::get_user( $_POST['log'] ) ) && ITSEC_Passwordless_Login_Utilities::can_user_use( $user ) ) {
			$methods[] = 'magic';
		}

		if ( ! $user ) {
			$context = new Host_Context( 'brute_force' );
			$context->set_login_username( $_POST['log'] );
			$itsec_lockout->do_lockout( $context );
		}

		wp_send_json_success( array(
			'methods' => $methods,
			'html'    => ITSEC_Lib::render( __DIR__ . '/templates/user-first/login-ajax.php', array(
				'username'           => $_POST['log'],
				'prompt_link'        => add_query_arg( array( 'action' => self::ACTION, 'username' => $_POST['log'] ), wp_login_url() ),
				'is_available'       => in_array( 'magic', $methods, true ),
				'user_lookup_fields' => ITSEC_Lib_Login::get_user_lookup_fields_label(),
			), false ),
		) );
	}

	/**
	 * Maybe send the magic link to the user.
	 */
	public function maybe_send_magic_link() {
		/** @var ITSEC_Lockout $itsec_lockout */
		global $itsec_lockout;

		if ( ! isset( $_POST['itsec_magic_link_username'], $_POST['itsec_magic_link_login'] ) ) {
			return;
		}

		$identifier = $_POST['itsec_magic_link_username'];
		$fields     = ITSEC_Lib_Login::get_user_lookup_fields();
		$user       = ITSEC_Lib_Login::get_user( $identifier );

		if ( $this->use_recaptcha() ) {
			$args = array(
				'action' => ITSEC_Recaptcha::A_LOGIN,
			);

			if ( $user ) {
				$args['user'] = $user->ID;
			} else {
				$args['username'] = $identifier;
			}

			$valid = ITSEC_Recaptcha_API::validate( $args );

			if ( is_wp_error( $valid ) ) {
				ITSEC_Lib::add_to_wp_error( $this->error, $valid );

				return;
			}
		}

		if ( ! $user ) {
			$context = new Host_Context( 'brute_force' );
			$context->set_login_username( $_POST['log'] );
			$itsec_lockout->do_lockout( $context );

			if ( array( 'email' ) === $fields || ( in_array( 'email', $fields, true ) && is_email( $identifier ) ) ) {
				$this->error->add( 'invalid_email', __( '<strong>ERROR</strong>: Invalid email address.' ) );
			} else {
				$this->error->add( 'invalid_username', __( '<strong>ERROR</strong>: Invalid username.' ) );
			}

			return;
		}

		if ( ! ITSEC_Passwordless_Login_Utilities::can_user_use( $user ) ) {
			wp_redirect( add_query_arg( self::E_NOT_ALLOWED, true, wp_login_url() ) );

			die;
		}

		$session = ITSEC_Login_Interstitial_Session::create( $user, Interstitial::SLUG );

		if ( is_wp_error( $session ) ) {
			wp_die( $session );
		}

		$session->initialize_from_global_state();
		$session->add_show_after( Interstitial::SLUG );

		if ( ! ITSEC_Passwordless_Login_Utilities::is_2fa_used_by_user( $user ) ) {
			$session->add_completed_interstitial( '2fa' );
		}

		$session->save();

		if ( ! $this->send_email( $session ) ) {
			$session->delete();
			$this->error->add( 'mail_failed', __( 'The email could not be sent. Possible reason: your host may have disabled the mail() function.', 'it-l10n-ithemes-security-pro' ) );

			return;
		}

		ITSEC_Core::get_login_interstitial()->show_interstitial( $session );
	}

	/**
	 * Send the magic login link to the user.
	 *
	 * @param ITSEC_Login_Interstitial_Session $session
	 *
	 * @return bool
	 */
	private function send_email( ITSEC_Login_Interstitial_Session $session ) {

		$user = $session->get_user();
		$link = ITSEC_Core::get_login_interstitial()->get_async_action_url( $session, Interstitial::ASYNC_ACTION );
		$nc   = ITSEC_Core::get_notification_center();

		$mail = $nc->mail();
		$mail->set_recipients( array( $user->user_email ) );

		$mail->add_header(
			esc_html__( 'Your Passwordless Login Link is Here', 'it-l10n-ithemes-security-pro' ),
			sprintf( esc_html__( 'Passwordless login link for %s', 'it-l10n-ithemes-security-pro' ), '<b>' . get_bloginfo( 'name', 'display' ) . '</b>' ),
			true
		);

		$mail->add_text( ITSEC_Lib::replace_tags( $nc->get_message( self::NOTIFICATION ), array(
			'username'     => $user->user_login,
			'display_name' => $user->display_name,
			'login_url'    => $link,
			'site_title'   => get_bloginfo( 'name', 'display' ),
			'site_url'     => $mail->get_display_url(),
		) ) );
		$mail->add_button( esc_html__( 'Login Now →', 'it-l10n-ithemes-security-pro' ), $link );

		$mail->add_image( plugin_dir_url( __FILE__ ) . 'img/icon.png', 163 );
		$mail->add_user_footer();

		return $nc->send( self::NOTIFICATION, $mail );
	}

	/**
	 * Render the profile fields for enabling/disabling magic login.
	 *
	 * @param WP_User $user
	 */
	public function render_profile_fields( $user ) {
		if ( ! ITSEC_Passwordless_Login_Utilities::is_available_for_user( $user ) ) {
			return;
		}

		?>
		<tr>
			<th scope="row">
				<label for="itsec-passwordless-login-login-enabled"><?php esc_html_e( 'Enable Passwordless Login', 'it-l10n-ithemes-security-pro' ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="itsec-passwordless-login-login-enabled" name="itsec_magic_links_login_enabled"
					<?php checked( ITSEC_Passwordless_Login_Utilities::is_enabled_for_user( $user ) ) ?>>
			</td>
		</tr>
		<?php if ( $this->is_user_using_two_factor( $user ) && ! ITSEC_Passwordless_Login_Utilities::is_2fa_enforced_for_user( $user ) ): ?>
			<tr>
				<th scope="row">
					<label for="itsec-passwordless-login-login-2fa-enabled"><?php esc_html_e( 'Use Two-Factor during Passwordless Login', 'it-l10n-ithemes-security-pro' ); ?></label>
				</th>
				<td>
					<input type="checkbox" id="itsec-passwordless-login-login-2fa-enabled" name="itsec_magic_links_login_2fa_enabled"
						<?php checked( ITSEC_Passwordless_Login_Utilities::is_2fa_enabled_for_user( $user ) ) ?>>
				</td>
			</tr>
		<?php endif; ?>
		<?php
	}

	/**
	 * Save the profile fields.
	 *
	 * @param int $user_id
	 */
	public function save_profile_fields( $user_id ) {
		if ( ! $user = get_userdata( $user_id ) ) {
			return;
		}

		if ( ! ITSEC_Passwordless_Login_Utilities::is_available_for_user( $user ) ) {
			return;
		}

		ITSEC_Passwordless_Login_Utilities::set_enabled_for_user( $user, ! empty( $_POST['itsec_magic_links_login_enabled'] ) );

		if ( $this->is_user_using_two_factor( $user ) && ! ITSEC_Passwordless_Login_Utilities::is_2fa_enforced_for_user( $user ) ) {
			ITSEC_Passwordless_Login_Utilities::set_2fa_enabled_for_user( $user, ! empty( $_POST['itsec_magic_links_login_2fa_enabled'] ) );
		}
	}

	/**
	 * Is the given user using Two-Factor.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	private function is_user_using_two_factor( WP_User $user ) {
		if ( ! class_exists( 'ITSEC_Two_Factor' ) ) {
			return false;
		}

		return (bool) ITSEC_Two_Factor::get_instance()->get_primary_provider_for_user( $user );
	}

	/**
	 * Whether Recaptcha should be used.
	 *
	 * @return bool
	 */
	private function use_recaptcha() {
		return ITSEC_Modules::is_active( 'recaptcha' ) && ITSEC_Modules::get_setting( 'recaptcha', 'login' );
	}

	/**
	 * Register the magic link notification.
	 *
	 * @param array $notifications
	 *
	 * @return array
	 */
	public function register_notification( $notifications ) {
		$notifications[ self::NOTIFICATION ] = array(
			'recipient'        => ITSEC_Notification_Center::R_USER,
			'schedule'         => ITSEC_Notification_Center::S_NONE,
			'subject_editable' => true,
			'message_editable' => true,
			'tags'             => array( 'username', 'display_name', 'login_url', 'site_title', 'site_url' ),
			'module'           => 'passwordless-login',
		);

		return $notifications;
	}

	/**
	 * Register strings for the Magic Links Login Method notification.
	 *
	 * @return array
	 */
	public function notification_strings() {
		return array(
			'label'       => esc_html__( 'Magic Links Passwordless Login', 'it-l10n-ithemes-security-pro' ),
			'description' => sprintf( esc_html__( 'The %1$sMagic Links%2$s module sends an email with a Magic Link to automatically login. Note: the default email template already includes the %3$s tag.' ), '<a href="#" data-module-link="passwordless-login">', '</a>', '<code>login_url</code>' ),
			'tags'        => array(
				'username'     => esc_html__( "The recipient's WordPress username.", 'it-l10n-ithemes-security-pro' ),
				'display_name' => esc_html__( "The recipient's WordPress display name.", 'it-l10n-ithemes-security-pro' ),
				'login_url'    => esc_html__( 'The magic login link to continue logging in.', 'it-l10n-ithemes-security-pro' ),
				'site_title'   => esc_html__( 'The WordPress Site Title. Can be changed under Settings -> General -> Site Title', 'it-l10n-ithemes-security-pro' ),
				'site_url'     => esc_html__( 'The URL to your website.', 'it-l10n-ithemes-security-pro' ),
			),
			'subject'     => esc_html__( 'Login Link', 'it-l10n-ithemes-security-pro' ),
			'message'     => esc_html__( 'Hi {{ $display_name }},

Click the button below to continue logging in.', 'it-l10n-ithemes-security-pro' ),
		);
	}
}
