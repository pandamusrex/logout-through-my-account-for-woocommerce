<?php
/**
 * Plugin Name: LogInOut Redirect for WooCommerce
 * Version: 1.0.0
 * Plugin URI: https://github.com/pandamusrex/loginout-redirect-for-woocommerce
 * Description: All users must exit through their WooCommerce my-account page.
 * Author: PandamusRex
 * Author URI: https://www.github.com/pandamusrex/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.4
 * Tested up to: 6.8
 *
 * Text Domain: loginout-redirect-for-woocommerce
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author PandamusRex
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_LogInOut_Redirect_for_WooCommerce {
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
        add_filter( 'loginout', array( $this, 'loginout' ), 10, 1 );
    }

    function loginout( $link ) {

        // Filter the link only for logged in users
        if ( ! is_user_logged_in() ) {
            return $link;
        }

        // Don't call WooCommerce is_account_page if it isn't defined
        if ( ! function_exists( 'is_account_page' ) ) {
            return $link;
        }

        // Don't filter the link if we are on the account page
        if ( is_account_page() ) {
            return $link;
        }

        // Don't filter the link if it lacks an href
        $href_string_pos = strpos( $link, 'href' );
        if ( $href_string_pos === false ) {
            return $link;
        }

        // Get the ID and URL of the WooCommerce account page
        $my_account_page_id = get_option( 'woocommerce_myaccount_page_id' );
        if ( ! $my_account_page_id ) {
            return $link;
        }

        $my_account_page_url = get_permalink( $my_account_page_id );
        if ( ! $my_account_page_url ) {
            return $link;
        }

        // If we've gotten this far, replace the href with the URL of the account page
        $link = preg_replace(
            '/<a(.*)href="([^"]*)"(.*)>/',
            '<a$1href="' . $my_account_page_url . '"$3>',
            $link
        );

        return $link;
    }
}

PandamusRex_LogInOut_Redirect_for_WooCommerce::get_instance();
