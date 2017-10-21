<?php
/**
 * WooCommerce More Sorting - Custom Sorting
 *
 * @version 3.1.1
 * @since   3.1.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Alg_More_Sorting_Custom_Sorting' ) ) :

class WC_Alg_More_Sorting_Custom_Sorting {

	/**
	 * Constructor.
	 *
	 * @version 3.1.0
	 * @since   3.1.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_catalog_ordering_args',       array( $this, 'get_catalog_ordering_args' ), PHP_INT_MAX );
		add_filter( 'woocommerce_catalog_orderby',                 array( $this, 'add_custom_sorting' ),        PHP_INT_MAX );
		add_filter( 'woocommerce_default_catalog_orderby_options', array( $this, 'add_custom_sorting' ),        PHP_INT_MAX );
	}

	/*
	 * maybe_add_sorting.
	 *
	 * @version 3.1.0
	 */
	function maybe_add_sorting( $sortby, $key, $default ) {
		$option_name = 'alg_wc_more_sorting_by_' . str_replace( '-', '_', $key ) . '_text';
		if ( '' != ( $value = get_option( $option_name, $default ) ) ) {
			$sortby[ $key ] = $value;
		}
		return $sortby;
	}

	/*
	 * Add new sorting options to Front End and to Back End (in WooCommerce > Settings > Products > Default Product Sorting).
	 *
	 * @version 3.1.0
	 */
	function add_custom_sorting( $sortby ) {

		// Custom Sorting
		$custom_sorting_options = alg_wc_more_sorting_get_custom_sorting_options();
		foreach ( $custom_sorting_options as $custom_sorting_option_key => $custom_sorting_option_title ) {
			$sortby = $this->maybe_add_sorting( $sortby, $custom_sorting_option_key, $custom_sorting_option_title );
		}

		// Custom Meta Sorting
		$custom_meta_sortings = alg_wc_more_sorting_get_custom_meta_sorting_options();
		foreach ( $custom_meta_sortings as $custom_meta_sorting_key => $custom_meta_sorting_values ) {
			$sortby[ $custom_meta_sorting_key ] = $custom_meta_sorting_values['title'];
		}

		return $sortby;
	}

	/*
	 * Add new sorting options to WooCommerce sorting.
	 *
	 * @version 3.1.1
	 * @todo    (maybe) order by custom `orderby`, `order` and `meta_key`
	 * @todo    (maybe) order by `relevance` - __( 'Order by search terms', 'woocommerce-more-sorting' ) - https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
	 */
	function get_catalog_ordering_args( $args ) {

		// Get ordering from query string unless defined
		$orderby_value = isset( $_GET['orderby'] ) ?
			( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ? woocommerce_clean( $_GET['orderby'] ) : wc_clean( $_GET['orderby'] ) ) :
			apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		// Get order + orderby args from string
		$orderby_value = explode( '-', $orderby_value );
		$orderby       = esc_attr( $orderby_value[0] );
		$order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : '';

		$orderby = strtolower( $orderby );
		$order   = strtoupper( $order );

		switch ( $orderby ) :
			case 'sku':
				$args['orderby']  = ( 'no' === apply_filters( 'alg_wc_more_sorting', 'no', 'by_sku_num' ) ) ? 'meta_value ID' : 'meta_value_num ID';
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
				$args['meta_key'] = '_sku';
			break;
			case 'stock_quantity':
				$args['orderby']  = 'meta_value_num ID';
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
				$args['meta_key'] = '_stock';
			break;
			case 'total_sales':
				$args['orderby']  = 'meta_value_num ID';
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
				$args['meta_key'] = 'total_sales';
			break;
			case 'modified':
				$args['orderby']  = 'modified ID';
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';;
			break;
			case 'author':
				$args['orderby']  = 'author ID';
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
			break;
			case 'product_id':
				$args['orderby']  = 'ID';
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
			break;
			case 'name':
				$args['orderby']  = 'name ID';
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
			break;
			case 'comment_count':
				$args['orderby']  = 'comment_count ID';
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
			break;
			case 'none':
				$args['orderby']  = 'none';
			break;
		endswitch;

		// Custom Meta Sorting
		$custom_meta_sortings = alg_wc_more_sorting_get_custom_meta_sorting_options();
		foreach ( $custom_meta_sortings as $custom_meta_sorting_key => $custom_meta_sorting_values ) {
			if ( $orderby === $custom_meta_sorting_values['param'] && $order === strtoupper( $custom_meta_sorting_values['order'] ) ) {
				$args['orderby']  = $custom_meta_sorting_values['type'];
				if ( 'none' != $custom_meta_sorting_values['secondary'] ) {
					$args['orderby'] .= ' ' . $custom_meta_sorting_values['secondary'];
				}
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
				$args['meta_key'] = $custom_meta_sorting_values['meta_key'];
				break;
			}
		}

		return $args;
	}

}

endif;

return new WC_Alg_More_Sorting_Custom_Sorting();
