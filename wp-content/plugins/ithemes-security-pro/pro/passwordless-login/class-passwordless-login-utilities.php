<?php

class ITSEC_Passwordless_Login_Utilities {

	const META_ENABLED = '_itsec_passwordless_login_enabled';
	const META_USE_2FA = '_itsec_passwordless_login_use_2fa';
	const META_REMIND_2FA = '_itsec_passwordless_login_remind_2fa';
	const META_USES = '_itsec_passwordless_login_uses';

	/**
	 * Can the given user use Magic Login.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function can_user_use( WP_User $user ) {
		if ( ! self::is_enabled_for_user( $user ) ) {
			return false;
		}

		return self::is_available_for_user( $user );
	}

	/**
	 * Is magic login available for the user.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function is_available_for_user( WP_User $user ) {
		switch ( ITSEC_Modules::get_setting( 'passwordless-login', 'login' ) ) {
			case 'all':
				return true;
			case 'disabled':
				return false;
			case 'non_privileged':
				ITSEC_Lib::load( 'canonical-roles' );

				return ! ITSEC_Lib_Canonical_Roles::is_user_at_least( 'contributor', $user );
			case 'custom':
				foreach ( ITSEC_Modules::get_setting( 'passwordless-login', 'roles' ) as $role ) {
					if ( in_array( $role, $user->roles, true ) ) {
						return true;
					}
				}

				return false;
			default:
				return false;
		}
	}

	/**
	 * Does the user still have to complete Two Factor when using Magic Login.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function is_2fa_used_by_user( WP_User $user ) {
		if ( self::is_2fa_enforced_for_user( $user ) ) {
			return true;
		}

		if ( self::is_2fa_enabled_for_user( $user ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Is using 2fa during a magic login enforced for the user due to the ITSEC settings.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function is_2fa_enforced_for_user( WP_User $user ) {
		switch ( ITSEC_Modules::get_setting( 'passwordless-login', '2fa_bypass' ) ) {
			case 'all':
				return false;
			case 'non_privileged':
				ITSEC_Lib::load( 'canonical-roles' );

				return ITSEC_Lib_Canonical_Roles::is_user_at_least( 'contributor', $user );
			case 'custom':
				$bypass_roles = ITSEC_Modules::get_setting( 'passwordless-login', '2fa_bypass_roles' );

				foreach ( $user->roles as $role ) {
					if ( ! in_array( $role, $bypass_roles, true ) ) {
						return true;
					}
				}

				return false;
			case 'none':
			default:
				return true;
		}
	}

	/**
	 * Is magic login enabled for the user.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function is_enabled_for_user( WP_User $user ) {
		switch ( get_user_meta( $user->ID, self::META_ENABLED, true ) ) {
			case 'enabled':
				return true;
			case 'disabled':
				return false;
			default:
				return 'enabled' === ITSEC_Modules::get_setting( 'passwordless-login', 'availability' );
		}
	}

	/**
	 * Set whether magic login is enabled for the user.
	 *
	 * @param WP_User $user
	 * @param bool    $enabled
	 */
	public static function set_enabled_for_user( WP_User $user, $enabled ) {
		update_user_meta( $user->ID, self::META_ENABLED, $enabled ? 'enabled' : 'disabled' );
	}

	/**
	 * Is magic login 2fa enabled for the user.
	 *
	 * A user can specifically opt-out of 2fa if it isn't required for their account.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function is_2fa_enabled_for_user( WP_User $user ) {
		return 'disabled' !== get_user_meta( $user->ID, self::META_USE_2FA, true );
	}

	/**
	 * Set whether magic login 2fa is enabled for the user.
	 *
	 * @param WP_User $user
	 * @param bool    $enabled
	 */
	public static function set_2fa_enabled_for_user( WP_User $user, $enabled ) {
		update_user_meta( $user->ID, self::META_USE_2FA, $enabled ? 'enabled' : 'disabled' );
	}

	/**
	 * Should a user be reminded about configuring whether they want to use 2fa during a magic login.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function should_remind_user_about_2fa( WP_User $user ) {
		if ( self::is_2fa_enforced_for_user( $user ) ) {
			return false;
		}

		return (bool) get_user_meta( $user->ID, self::META_REMIND_2FA, true );
	}

	/**
	 * Set whether to remind a user about configuring 2fa.
	 *
	 * @param WP_User $user
	 * @param bool    $remind
	 */
	public static function set_remind_user_about_2fa( WP_User $user, $remind ) {
		if ( $remind ) {
			update_user_meta( $user->ID, self::META_REMIND_2FA, true );
		} else {
			delete_user_meta( $user->ID, self::META_REMIND_2FA );
		}
	}

	/**
	 * Record that magic login has been used by a user.
	 *
	 * @param WP_User $user
	 */
	public static function record_use( WP_User $user ) {
		// I don't care about this possible race condition
		$uses = self::get_uses( $user );
		$uses ++;

		if ( 1 === $uses ) {
			self::set_remind_user_about_2fa( $user, true );
		}

		update_user_meta( $user->ID, self::META_USES, $uses );
	}

	/**
	 * Get the number of times a magic login has been used.
	 *
	 * @param WP_User $user
	 *
	 * @return int
	 */
	public static function get_uses( WP_User $user ) {
		return (int) get_user_meta( $user->ID, self::META_USES, true );
	}
}
