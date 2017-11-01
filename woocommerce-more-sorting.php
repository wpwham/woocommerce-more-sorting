<?php
/*
Plugin Name: More Sorting Options for WooCommerce
Plugin URI: https://wpcodefactory.com/item/more-sorting-options-for-woocommerce-wordpress-plugin/
Description: Add new custom, rearrange, remove or rename WooCommerce sorting options.
Version: 3.1.3
Author: Algoritmika Ltd
Author URI: http://www.algoritmika.com
Text Domain: woocommerce-more-sorting
Domain Path: /langs
Copyright: © 2017 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'alg_is_plugin_active' ) ) {
	/**
	 * alg_is_plugin_active - Check if plugin is active.
	 *
	 * @return  bool
	 * @version 3.0.2
	 * @since   2.1.0
	 */
	function alg_is_plugin_active( $plugin_file ) {
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) );
		}
		foreach ( $active_plugins as $active_plugin ) {
			$active_plugin = explode( '/', $active_plugin );
			if ( isset( $active_plugin[1] ) && $plugin_file === $active_plugin[1] ) {
				return true;
			}
		}
		return false;
	}
}

if ( ! alg_is_plugin_active( 'woocommerce.php' ) ) {
	return;
}

// Disables free version if PRO is enabled
register_activation_hook( __FILE__, function () {	
	if ( 'woocommerce-more-sorting.php' === basename( __FILE__ ) && alg_is_plugin_active( 'woocommerce-more-sorting-pro.php' ) ) {		
		die(sprintf(__('<strong>%1$s</strong> could not be enabled as <a href="%2$s" target="blank">Premium version</a> is enabled','woocommerce-more-sorting'),__('More Sorting Options for WooCommerce','woocommerce-more-sorting'),'https://wpcodefactory.com/item/more-sorting-options-for-woocommerce-wordpress-plugin/'));	
	}
} );

if ( ! class_exists( 'Alg_Woocommerce_More_Sorting' ) ) :

/**
 * Main Alg_Woocommerce_More_Sorting Class
 *
 * @class   Alg_Woocommerce_More_Sorting
 * @version 3.1.3
 * @since   1.0.0
 */
final class Alg_Woocommerce_More_Sorting {

	/**
	 * Plugin version
	 */
	public $version = '3.1.3';

	/**
	 * @var Alg_Woocommerce_More_Sorting The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_Woocommerce_More_Sorting Instance
	 *
	 * Ensures only one instance of Alg_Woocommerce_More_Sorting is loaded or can be loaded.
	 *
	 * @static
	 * @return Alg_Woocommerce_More_Sorting - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_Woocommerce_More_Sorting Constructor.
	 *
	 * @access  public
	 * @version 3.0.0
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'woocommerce-more-sorting', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Settings
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param   mixed $links
	 * @return  array
	 * @version 3.0.0
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_more_sorting' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'woocommerce-more-sorting.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="https://wpcodefactory.com/item/more-sorting-options-for-woocommerce-wordpress-plugin/">' .
				__( 'Unlock all', 'woocommerce-more-sorting' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 3.1.2
	 */
	function includes() {

		// Functions
		require_once( 'includes/alg-wc-more-sorting-functions.php' );

		// Settings
		require_once( 'includes/admin/class-alg-wc-more-sorting-settings-section.php' );
		$this->settings = array();
		$this->settings['general']             = require_once( 'includes/admin/class-alg-wc-more-sorting-settings-general.php' );
		$this->settings['custom-sorting']      = require_once( 'includes/admin/class-alg-wc-more-sorting-settings-custom-sorting.php' );
		$this->settings['custom-meta-sorting'] = require_once( 'includes/admin/class-alg-wc-more-sorting-settings-custom-meta-sorting.php' );
		$this->settings['default-wc-sorting']  = require_once( 'includes/admin/class-alg-wc-more-sorting-settings-default-wc-sorting.php' );
		$this->settings['rearrange-sorting']   = require_once( 'includes/admin/class-alg-wc-more-sorting-settings-rearrange-sorting.php' );
		$this->settings['remove-sorting']      = require_once( 'includes/admin/class-alg-wc-more-sorting-settings-remove-sorting.php' );
		$this->settings['advanced']            = require_once( 'includes/admin/class-alg-wc-more-sorting-settings-advanced.php' );
		if ( is_admin() && $this->version != get_option( 'alg_wc_more_sorting_version', '' ) ) {
			foreach ( $this->settings as $section ) {
				foreach ( $section->get_settings() as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
			$this->handle_deprecated_options();
			update_option( 'alg_wc_more_sorting_version', $this->version );
		}

		// Core
		require_once( 'includes/class-alg-wc-more-sorting.php' );
	}

	/**
	 * handle_deprecated_options.
	 *
	 * @version 3.1.2
	 * @since   3.0.0
	 */
	function handle_deprecated_options() {
		$deprecated_settings = array(
			// v3.0.0
			'woocommerce_more_sorting_enabled'                         => 'alg_wc_more_sorting_enabled',
			'woocommerce_more_sorting_by_name_asc_text'                => 'alg_wc_more_sorting_by_title_asc_text',
			'woocommerce_more_sorting_by_name_desc_text'               => 'alg_wc_more_sorting_by_title_desc_text',
			'woocommerce_more_sorting_by_sku_asc_text'                 => 'alg_wc_more_sorting_by_sku_asc_text',
			'woocommerce_more_sorting_by_sku_desc_text'                => 'alg_wc_more_sorting_by_sku_desc_text',
			'woocommerce_more_sorting_by_sku_num_enabled'              => 'alg_wc_more_sorting_by_sku_num_enabled',
			'woocommerce_more_sorting_by_stock_quantity_asc_text'      => 'alg_wc_more_sorting_by_stock_quantity_asc_text',
			'woocommerce_more_sorting_by_stock_quantity_desc_text'     => 'alg_wc_more_sorting_by_stock_quantity_desc_text',
			'woocommerce_more_sorting_remove_all_enabled'              => 'alg_wc_more_sorting_remove_all_enabled',
			'woocommerce_more_sorting_pro_enabled'                     => 'alg_wc_more_sorting_enabled',
			'woocommerce_more_sorting_pro_by_name_asc_text'            => 'alg_wc_more_sorting_by_title_asc_text',
			'woocommerce_more_sorting_pro_by_name_desc_text'           => 'alg_wc_more_sorting_by_title_desc_text',
			'woocommerce_more_sorting_pro_by_sku_asc_text'             => 'alg_wc_more_sorting_by_sku_asc_text',
			'woocommerce_more_sorting_pro_by_sku_desc_text'            => 'alg_wc_more_sorting_by_sku_desc_text',
			'woocommerce_more_sorting_pro_by_sku_num_enabled'          => 'alg_wc_more_sorting_by_sku_num_enabled',
			'woocommerce_more_sorting_pro_by_stock_quantity_asc_text'  => 'alg_wc_more_sorting_by_stock_quantity_asc_text',
			'woocommerce_more_sorting_pro_by_stock_quantity_desc_text' => 'alg_wc_more_sorting_by_stock_quantity_desc_text',
			'woocommerce_more_sorting_pro_remove_all_enabled'          => 'alg_wc_more_sorting_remove_all_enabled',
		);
		foreach ( $deprecated_settings as $old => $new ) {
			if ( false !== ( $old_value = get_option( $old ) ) ) {
				update_option( $new, $old_value );
				delete_option( $old );
			}
		}
		// v3.1.0
		$rearranged_sorting = get_option( 'alg_wc_more_sorting_rearrange', false );
		if ( false !== $rearranged_sorting ) {
			$replacement_values = array(
				'title_asc'           => 'title-asc',
				'title_desc'          => 'title-desc',
				'sku_asc'             => 'sku-asc',
				'sku_desc'            => 'sku-desc',
				'stock_quantity_asc'  => 'stock_quantity-asc',
				'stock_quantity_desc' => 'stock_quantity-desc',
			);
			$rearranged_sorting = array_map( 'trim', explode( PHP_EOL, $rearranged_sorting ) );
			$rearranged_sorting_modified = array();
			foreach ( $rearranged_sorting as $sorting ) {
				$rearranged_sorting_modified[] = str_replace( array_keys( $replacement_values ), array_values( $replacement_values ), $sorting );
			}
			$rearranged_sorting_modified = implode( PHP_EOL, $rearranged_sorting_modified );
			update_option( 'alg_wc_more_sorting_rearrange_sorting', $rearranged_sorting_modified );
			delete_option( 'alg_wc_more_sorting_rearrange' );
		}
	}

	/**
	 * Add Woocommerce settings tab to WooCommerce settings.
	 *
	 * @version 3.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = include( 'includes/admin/class-alg-wc-settings-more-sorting.php' );
		return $settings;
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
}

endif;

if ( ! function_exists( 'alg_woocommerce_more_sorting' ) ) {
	/**
	 * Returns the main instance of Alg_Woocommerce_More_Sorting to prevent the need to use globals.
	 *
	 * @return  Alg_Woocommerce_More_Sorting
	 * @version 3.0.0
	 */
	function alg_woocommerce_more_sorting() {
		return Alg_Woocommerce_More_Sorting::instance();
	}
}

alg_woocommerce_more_sorting();