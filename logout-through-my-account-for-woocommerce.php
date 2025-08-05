<?php
/**
 * Plugin Name: Log Out Through My Account Page for WooCommerce
 * Version: 1.0.0
 * Plugin URI: https://github.com/pandamusrex/logout-through-my-account-for-woocommerce
 * Description: All users must exit through their WooCommerce my-account page.
 * Author: PandamusRex
 * Author URI: https://www.github.com/pandamusrex/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.4
 * Tested up to: 6.8
 *
 * Text Domain: logout-through-my-account-for-woocommerce
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author PandamusRex
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_LogOut_Through_My_Account_for_WooCommerce {
    private static $instance;

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone() {}

    public function __wakeup() {}

    public function __construct() {
        add_filter( 'logout_url', array( $this, 'logout_url' ), 10, 1 );
    }

    function logout_url( $url ) {

        // Don't call WooCommerce is_account_page if it isn't defined
        if ( ! function_exists( 'is_account_page' ) ) {
            return $url;
        }

        // Don't filter the url if we are on the account page
        if ( is_account_page() ) {
            return $url;
        }

        // Get the ID and URL of the WooCommerce account page
        $my_account_page_id = get_option( 'woocommerce_myaccount_page_id' );
        if ( ! $my_account_page_id ) {
            return $url;
        }

        $my_account_page_url = get_permalink( $my_account_page_id );
        if ( ! $my_account_page_url ) {
            return $url;
        }

        return $my_account_page_url;
    }
}

PandamusRex_LogOut_Through_My_Account_for_WooCommerce::get_instance();
