<?php
/**
* MG Flutterwave for Give - WordPress Plugin
*
* @package           Flutterwave for Give
* @author            Mehul Gohil
* @copyright         2020 Mehul Gohil <hello@mehulgohil.com>
* @license           GPL-2.0-or-later
*
* @wordpress-plugin
*
* Plugin Name:       MG - Flutterwave for Give
* Plugin URI:        https://mehulgohil.com/plugins/flutterwave-for-give
* Description:       Accept donations for GiveWP using Flutterwave payment gateway.
* Version:           1.1.0
* Requires at least: 4.8
* Requires PHP:      5.6
* Author:            Mehul Gohil
* Author URI:        https://mehulgohil.com
* Text Domain:       mg-flutterwave-for-give
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace MG\GiveWP\Flutterwave;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load Constants.
require_once __DIR__ . '/config/constants.php';

// Automatically loads files used throughout the plugin.
require_once MGFFW_PLUGIN_DIR . 'vendor/autoload.php';

// Initialize the plugin.
$plugin = new Plugin();
$plugin->register();
