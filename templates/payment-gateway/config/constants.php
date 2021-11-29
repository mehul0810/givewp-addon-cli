<?php
// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'MGFFW_VERSION' ) ) {
	define( 'MGFFW_VERSION', '1.1.0' );
}

if ( ! defined( 'MGFFW_MIN_GIVE_VER' ) ) {
	define( 'MGFFW_MIN_GIVE_VER', '2.5.0' );
}

if ( ! defined( 'MGFFW_PLUGIN_FILE' ) ) {
	define( 'MGFFW_PLUGIN_FILE', dirname( dirname( __FILE__ ) ) . '/{{name}}.php' );
}

if ( ! defined( 'MGFFW_PLUGIN_BASENAME' ) ) {
	define( 'MGFFW_PLUGIN_BASENAME', plugin_basename( MGFFW_PLUGIN_FILE ) );
}

if ( ! defined( 'MGFFW_PLUGIN_DIR' ) ) {
	define( 'MGFFW_PLUGIN_DIR', plugin_dir_path( MGFFW_PLUGIN_FILE ) );
}

if ( ! defined( 'MGFFW_PLUGIN_URL' ) ) {
	define( 'MGFFW_PLUGIN_URL', plugin_dir_url( MGFFW_PLUGIN_FILE ) );
}
