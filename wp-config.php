<?php

#define('WP_HOME','http://www.hfnschools.org');

#define('WP_SITEURL','http://www.hfnschools.org');


/**

 * The base configuration for WordPress

 *

 * The wp-config.php creation script uses this file during the

 * installation. You don't have to use the web site, you can

 * copy this file to "wp-config.php" and fill in the values.

 *

 * This file contains the following configurations:

 *

 * * MySQL settings

 * * Secret keys

 * * Database table prefix

 * * ABSPATH

 *

 * @link https://codex.wordpress.org/Editing_wp-config.php

 *

 * @package WordPress

 */


// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define('DB_NAME', "hfnschool");


/** MySQL database username */

#define('DB_USER', 'stghfnschools');

define('DB_USER', "root");


/** MySQL database password */

#define('DB_PASSWORD', 'access4stghfnschools');

define('DB_PASSWORD', "");


/** MySQL hostname */

define('DB_HOST', "localhost");


/** Database Charset to use in creating database tables. */

define('DB_CHARSET', 'utf8mb4');


/** The Database Collate type. Don't change this if in doubt. */

define('DB_COLLATE', '');


/**#@+

 * Authentication Unique Keys and Salts.

 *

 * Change these to different unique phrases!

 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}

 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define('AUTH_KEY',         'G[Fn3%}<&#U^{mWCef-> m2Y6vzwnyB[rkr2&gT/TzeWV;o?|`EPq#=r#7MlAd2a');

define('SECURE_AUTH_KEY',  ')~Nw#Nu+kv_](Uy:E>rfjI}8-9h|J71vy(pJ!]|bAp: >uo{Mm(7l: +ck[mn2?F');

define('LOGGED_IN_KEY',    'tWKW(#6R2?@@B]6R#_eJ<hU{<0|tB@m?o6ckg3I+<4>|=s-Bfzm[vxY-#5];y}u[');

define('NONCE_KEY',        'B*s]Cc{.U@B= CNS0#huy3Z_2d.IqwfmOcTBo_mH7gkJ TZfy?0jUM~;nPcR?QRa');

define('AUTH_SALT',        '}/)_IUhbTeSHj.9uw<TaA-5#%9NO0+VJt(7i?!tGo2aq#AfjG-SasyL~wg6p6}Fp');

define('SECURE_AUTH_SALT', 'GXB,Dz^YoK+g!NEqutGG,*F>Qf+2Y0q8TRXpW ^)&`0jDv[|H~|gTg?tfYx$rBzm');

define('LOGGED_IN_SALT',   ' 42/jcn/:f_IsZ7s _X]B/&77nNQX,c?L;;ONSf(h+#:-k/}T&vA{Un}w7lD1QH&');

define('NONCE_SALT',       '^Di9lMva)CM`)xJefC1JXAGl)jEAJXSW9$ykGK^oq`eFJx/Kx>rT_{8iKreYE^n1');


/**#@-*/


/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix  = 'hos_';


/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 *

 * For information on other constants that can be used for debugging,

 * visit the Codex.

 *

 * @link https://codex.wordpress.org/Debugging_in_WordPress

 */

define('WP_DEBUG', false);


/* That's all, stop editing! Happy blogging. */


/** Absolute path to the WordPress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');


/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');

