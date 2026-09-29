<?php
/**
 * RankinAI local WordPress config. Copy to wp-config.php and fill in the
 * database details and fresh salts (https://api.wordpress.org/secret-key/1.1/salt/).
 * wp-config.php is git-ignored: every environment keeps its own copy.
 */
define( 'DB_NAME', 'rankinai_local' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY',         'fill-in' );
define( 'SECURE_AUTH_KEY',  'fill-in' );
define( 'LOGGED_IN_KEY',    'fill-in' );
define( 'NONCE_KEY',        'fill-in' );
define( 'AUTH_SALT',        'fill-in' );
define( 'SECURE_AUTH_SALT', 'fill-in' );
define( 'LOGGED_IN_SALT',   'fill-in' );
define( 'NONCE_SALT',       'fill-in' );

$table_prefix = 'rankinai_';

/* Local: on. Live server: both false. */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

/* Keep search engines out of every copy that is not the real site. The theme
   reads this, the same switch as SITE_NOINDEX in the static build. */
define( 'RANKINAI_NOINDEX', true );

/* Local only: form submissions are written to debug.log instead of being
   emailed, because there is no mail server here. Leave it out on the live
   site, where Contact Form 7 must send real email. See the theme's
   inc/forms.php. */
define( 'RANKINAI_FORMS_SKIP_MAIL', true );

/* Local only: the flat build, which the seed reads its content from (see the
   theme's inc/model-engine.php). This folder is rankinai-wp/rankinai-m, and
   the flat build is rankinAI, beside rankinai-wp. Leave it out on the live
   site, which has no flat build and never runs the seed. */
define( 'RANKINAI_FLAT_DIR', dirname( __DIR__, 2 ) . '/rankinAI' );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
