<?php
 /* BEGIN KINSTA DEVELOPMENT ENVIRONMENT */ ?>
<?php if ( !defined('KINSTA_DEV_ENV') ) { define('KINSTA_DEV_ENV', true); /* Kinsta development - don't remove this line */ } ?>
<?php if ( !defined('JETPACK_STAGING_MODE') ) { define('JETPACK_STAGING_MODE', true); /* Kinsta development - don't remove this line */ } ?>
<?php /* END KINSTA DEVELOPMENT ENVIRONMENT */ ?>
<?php



/**

 * The base configuration for WordPress

 *

 * The wp-config.php creation script uses this file during the installation.

 * You don't have to use the web site, you can copy this file to "wp-config.php"

 * and fill in the values.

 *

 * This file contains the following configurations:

 *

 * * Database settings

 * * Secret keys

 * * Database table prefix

 * * ABSPATH

 *

 * @link https://wordpress.org/support/article/editing-wp-config-php/

 *

 * @package WordPress

 */


// ** Database settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', 'child' );


/** Database username */

define( 'DB_USER', 'root' );


/** Database password */

define( 'DB_PASSWORD', '' );


/** Database hostname */

define( 'DB_HOST', 'localhost' );


/** Database charset to use in creating database tables. */

define( 'DB_CHARSET', 'utf8mb4' );


/** The database collate type. Don't change this if in doubt. */

define( 'DB_COLLATE', '' );


/**#@+

 * Authentication unique keys and salts.

 *

 * Change these to different unique phrases! You can generate these using

 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.

 *

 * You can change these at any point in time to invalidate all existing cookies.

 * This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define( 'AUTH_KEY',         '!LMke>Pj*SK^F)<(BIg~-vAY}88f6Z-yrc7;<3&Az}Rl?6,3!g4o*h4@CX4LQ8q!' );

define( 'SECURE_AUTH_KEY',  'rfL=pO9dq6isP)X>zsl+GhZ+wP9C>=iQQiuD7_rP@E#Ax%g94GUxamOsY)7U^Za[' );

define( 'LOGGED_IN_KEY',    ']wVLWyTI?/Wd~N>3l.kaQhgL,Kd6j!(hgXG-WiV`8forO4[9OG@hFJ:fA/K?%OAu' );

define( 'NONCE_KEY',        'iQ^gulkdm(w5l(sAC%f tZ?$IvNw^66W=g;3X+CpCGS(g14z;^DERU]>L.FzzJD[' );

define( 'AUTH_SALT',        'W=Nh3Vc2Mhp1kW4Og)=b`k+I.v7WH`/,pRZRBmyWX:9g*ECcxIG6<3`,kH0/?~ZE' );

define( 'SECURE_AUTH_SALT', 'ZeJkZvTQhG*KB(Mf5^f*WF9viswhz/sAaEzm-u(rm8o8f>mnYSzI}kB1nx+ctW/J' );

define( 'LOGGED_IN_SALT',   'U^Okag54:TegAKH8M[|.*GZ^w }qRmJ~o9WG7}Si7IiMa#BrJ;tM[5}_no# =B[!' );

define( 'NONCE_SALT',       '>TVq/gVTiOhXg[i=ZzRsURP}LQRBi0m`qCq1R|`p=,$Z_@ODucpS{x[JKZ:vXUg?' );


/**#@-*/


/**

 * WordPress database table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'wp_';


/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 *

 * For information on other constants that can be used for debugging,

 * visit the documentation.

 *

 * @link https://wordpress.org/support/article/debugging-in-wordpress/

 */

define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */




define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';