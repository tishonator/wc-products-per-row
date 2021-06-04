<?php
/*
Plugin Name: Products per Row for WooCommerce
Description: This plugin adds a Customizer section to change number of products per row in WooCommerce pages.
Author: tishonator
Version: 1.0.0
Author URI: http://tishonator.com/
Contributors: tishonator
Text Domain: wc-products-per-row
*/

if ( !class_exists('tishonator_wc_products_per_row') ) :

    /**
     * Register the plugin.
     *
     */
    class tishonator_wc_products_per_row {
        
    	/**
    	 * Instance object
    	 *
    	 * @var object
    	 * @see get_instance()
    	 */
    	protected static $instance = NULL;


        /**
         * Constructor
         */
        public function __construct() {}

        /**
         * Setup
         */
        public function setup() {

            add_action('customize_register', array(&$this, 'customize_register') );

            add_filter('loop_shop_columns', array(&$this, 'woocommerce_loop_columns' ), 999 );
        }

        public function customize_register( $wp_customize ) {

            tishonator_wc_products_per_row::wc_customize_register_woocommerce_settings( $wp_customize );
        }

        public static function customizer_add_section( $wp_customize, $sectionId, $sectionTitle ) {

            $wp_customize->add_section(
                $sectionId,
                array(
                    'title'       => $sectionTitle,
                    'capability'  => 'edit_theme_options',
                )
            );
        }

        private static function customizer_add_select_control( $wp_customize, $sectionId, $controlId, $controlLabel, $controlDefaultVar, $controlChoices ) {

            $wp_customize->add_setting(
                $controlId,
                array(
                    'default'           => $controlDefaultVar,
                    'sanitize_callback' => 'esc_attr',
                )
            );

            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $controlId,
                array(
                    'label'          => $controlLabel,
                    'section'        => $sectionId,
                    'settings'       => $controlId,
                    'type'           => 'select',
                    'choices'        => $controlChoices
                    )
                )
            );
        }

        public static function wc_customize_register_woocommerce_settings( $wp_customize ) {

            // Add WooCommerce Settings Section
            tishonator_wc_products_per_row::customizer_add_section( $wp_customize,
                    'woocommerce_prdperrow_settings',
                    __( 'WooCommerce Products per Row', 'wc-products-per-row' ) );

            tishonator_wc_products_per_row::customizer_add_select_control( $wp_customize,
                'woocommerce_prdperrow_settings',
                'woocommerce_productsperrow',
                __( 'Number of Products per Row', 'wc-products-per-row' ),
                    '4', array( '1'    => '1',
                                '2'    => '2',
                                '3'    => '3',
                                '4'    => '4',
                                '5'    => '5',
                                '6'    => '6',
                            ) );
        }

        public function woocommerce_loop_columns() {

            return tishonator_wc_products_per_row::read_customizer_option('woocommerce_productsperrow', 4);
        }

        public static function read_customizer_option($name, $default) {

            return get_theme_mod($name, $default);
        }

    	/**
    	 * Used to access the instance
         *
         * @return object - class instance
    	 */
    	public static function get_instance() {

    		if ( NULL === self::$instance ) {
                self::$instance = new self();
            }

    		return self::$instance;
    	}
    }

endif; // tishonator_wc_products_per_row

add_action('plugins_loaded', array( tishonator_wc_products_per_row::get_instance(), 'setup' ), 10);
