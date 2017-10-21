<?php
/**
 * WooCommerce More Sorting
 *
 * @version 3.1.2
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Alg_More_Sorting' ) ) :

class WC_Alg_More_Sorting {

	/**
	 * Constructor.
	 *
	 * @version 3.1.2
	 */
	function __construct() {

		if ( 'yes' === get_option( 'alg_wc_more_sorting_enabled', 'yes' ) ) {

			if ( 'yes' === get_option( 'alg_wc_more_sorting_restore_wc_default_enabled', 'no' ) ) {
				// Restore default WooCommerce sorting
				require_once( 'class-alg-wc-more-sorting-restore-default.php' );
			}

			// Remove All Sorting
			if ( 'yes' === apply_filters( 'alg_wc_more_sorting', 'no', 'remove_all' ) ) {
				add_action( 'wp_loaded',       array( $this, 'remove_sorting' ),          PHP_INT_MAX );
				add_filter( 'wc_get_template', array( $this, 'remove_sorting_template' ), PHP_INT_MAX, 5 );
			}

			// Add Custom Sorting
			if ( 'yes' === get_option( 'alg_wc_more_sorting_custom_sorting_enabled', 'yes' ) ) {
				require_once( 'class-alg-wc-more-sorting-custom-sorting.php' );
			}

			// Remove or Rename Default Sorting
			if ( 'yes' === apply_filters( 'alg_wc_more_sorting', 'no', 'default_sorting' ) ) {
				add_filter( 'woocommerce_catalog_orderby',                 array( $this, 'remove_default_sortings' ), PHP_INT_MAX );
				add_filter( 'woocommerce_catalog_orderby',                 array( $this, 'rename_default_sortings' ), PHP_INT_MAX );
				add_filter( 'woocommerce_default_catalog_orderby_options', array( $this, 'remove_default_sortings' ), PHP_INT_MAX );
			}

			// Rearrange All Sorting
			if ( 'yes' === get_option( 'alg_wc_more_sorting_rearrange_enabled', 'no' ) ) {
				add_filter( 'woocommerce_catalog_orderby',                 array( $this, 'rearrange_sorting' ), PHP_INT_MAX );
				add_filter( 'woocommerce_default_catalog_orderby_options', array( $this, 'rearrange_sorting' ), PHP_INT_MAX );
			}

		}
	}

	/**
	 * remove_sorting_template.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function remove_sorting_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( 'loop/orderby.php' === $template_name ) {
			$located = untrailingslashit( realpath( plugin_dir_path( __FILE__ ) . '/..' ) ) . '/templates/alg-loop-orderby.php';
		}
		return $located;
	}

	/**
	 * remove_sorting.
	 *
	 * @version 3.1.0
	 */
	function remove_sorting() {
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		remove_action( 'mpcth_before_shop_loop',       'woocommerce_catalog_ordering', 40 );       // Blaszok theme
		remove_action( 'woocommerce_after_shop_loop',  'woocommerce_catalog_ordering', 10 );       // Storefront
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );       // Storefront
		remove_action( 'woocommerce_before_shop_loop', 'avada_woocommerce_catalog_ordering', 30 ); // Avada
		remove_action( 'woocommerce_before_shop_loop', 'revo_woocommerce_catalog_ordering', 30 );  // Revo
		remove_action( 'woocommerce_after_shop_loop',  'revo_woocommerce_catalog_ordering', 7 );   // Revo
	}

	/*
	 * rearrange_sorting.
	 *
	 * @version 3.1.2
	 * @since   3.0.0
	 */
	function rearrange_sorting( $sortby ) {
		$rearranged_sorting = get_option( 'alg_wc_more_sorting_rearrange_sorting', false );
		if ( false === $rearranged_sorting ) {
			$rearranged_sorting = alg_get_woocommerce_sortings_order();
		} else {
			$rearranged_sorting = array_map( 'trim', explode( PHP_EOL, $rearranged_sorting ) );
		}
		$rearranged_sortby = array();
		foreach ( $rearranged_sorting as $sorting ) {
			if ( isset( $sortby[ $sorting ] ) ) {
				$rearranged_sortby[ $sorting ] = $sortby[ $sorting ];
				unset( $sortby[ $sorting ] );
			}
		}
		return array_merge( $rearranged_sortby, $sortby );
	}

	/*
	 * remove_default_sortings.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function remove_default_sortings( $sortby ) {
		$default_sortings = alg_get_woocommerce_default_sortings();
		foreach ( $default_sortings as $sorting_key => $sorting_desc ) {
			$option_key = str_replace( '-', '_', $sorting_key );
			if ( 'yes' === apply_filters( 'alg_wc_more_sorting', 'no', 'default_sorting_disable', $option_key ) ) {
				unset( $sortby[ $sorting_key ] );
			}
		}
		return $sortby;
	}

	/*
	 * rename_default_sortings.
	 *
	 * @version 3.0.0
	 * @since   3.0.0
	 */
	function rename_default_sortings( $sortby ) {
		$default_sortings = alg_get_woocommerce_default_sortings();
		foreach ( $default_sortings as $sorting_key => $sorting_desc ) {
			$option_key = str_replace( '-', '_', $sorting_key );
			if ( isset( $sortby[ $sorting_key ] ) ) {
				$sortby[ $sorting_key ] = apply_filters( 'alg_wc_more_sorting', $sorting_desc, 'default_sorting_text', $option_key );
			}
		}
		return $sortby;
	}

}

endif;

return new WC_Alg_More_Sorting();
