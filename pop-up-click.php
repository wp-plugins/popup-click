<?php
/**
 *
 * @package   CcPopUpClick
 * @author    Chop-Chop.org <shop@chop-chop.org>
 * @license   GPL-2.0+
 * @link      https://shop.chop-chop.org
 * @copyright 2014 
 *
 * @wordpress-plugin
 * Plugin Name:       Pop-Up CC - Click
 * Plugin URI:        http://shop.chop-chop.org
 * Description:       An elegant Pop Up in just a few clicks.
 * Version:           1.1.0
 * Author:            Chop-Chop.org
 * Author URI:        http://chop-chop.org
 * Text Domain:       cc-pop-up-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );

define( 'CHCH_PUCF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CHCH_PUCF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-chch-pop-up-click.php' );  

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'ChChPopUpClick', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ChChPopUpClick', 'deactivate' ) ); 

add_action( 'plugins_loaded', array( 'ChChPopUpClick', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * The code below is intended to to give the lightest footprint possible.
 */
 
	
if (is_admin()) {
	
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-chch-pop-up-click-admin.php' );
	add_action( 'plugins_loaded', array( 'ChChPopUpClickAdmin', 'get_instance' ) );

}
