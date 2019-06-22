<?php
/**
 * WooCommerce More Sorting - Default WooCommerce Sorting Section Settings
 *
 * @version 3.1.2
 * @since   3.1.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Alg_WC_More_Sorting_Settings_Default_WC_Sorting' ) ) :

class Alg_WC_More_Sorting_Settings_Default_WC_Sorting extends Alg_WC_More_Sorting_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 3.1.0
	 * @since   3.1.0
	 */
	function __construct() {
		$this->id   = 'default_wc_sorting';
		$this->desc = __( 'Default WooCommerce Sorting', 'woocommerce-more-sorting' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 3.1.2
	 * @since   3.1.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'     => __( 'Default WooCommerce Sorting', 'woocommerce-more-sorting' ),
				'type'      => 'title',
				'id'        => 'alg_wc_more_sorting_default_sorting_options',
			),
			array(
				'title'     => __( 'Default Sorting Options', 'woocommerce-more-sorting' ),
				'desc'      => '<strong>' . __( 'Enable Section', 'woocommerce-more-sorting' ) . '</strong>',
				'id'        => 'alg_wc_more_sorting_default_sorting_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_more_sorting', array( 'disabled' => 'disabled' ), 'settings' ),
				'desc_tip'  => apply_filters( 'alg_wc_more_sorting',
					sprintf( __( 'You will need %s plugin to enable this section.', 'woocommerce-more-sorting' ),
						'<a target="_blank" href="https://wpcodefactory.com/item/more-sorting-options-for-woocommerce-wordpress-plugin/">' .
							__( 'More Sorting Options for WooCommerce Pro', 'woocommerce-more-sorting' ) . '</a>'
					),
					'settings'
				),
			),
		);
		foreach ( alg_get_woocommerce_default_sortings() as $sorting_key => $sorting_desc ) {
			$option_key = str_replace( '-', '_', $sorting_key );
			$settings[] = array(
				'title'     => $sorting_desc,
				'id'        => 'alg_wc_more_sorting_default_sorting_' . $option_key,
				'default'   => $sorting_desc,
				'type'      => 'text',
				'css'       => 'min-width:300px;',
			);
			if ( 'menu_order' === $sorting_key ) {
				continue;
			}
			$settings[] = array(
				'desc'      => __( 'Remove', 'woocommerce-more-sorting' ) . ' "' . $sorting_desc . '"',
				'id'        => 'alg_wc_more_sorting_default_sorting_' . $option_key . '_disable',
				'default'   => 'no',
				'type'      => 'checkbox',
			);
		}
		$settings = array_merge( $settings, array(
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_more_sorting_default_sorting_options',
			),
		) );
		return $settings;
	}

}

endif;

return new Alg_WC_More_Sorting_Settings_Default_WC_Sorting();
