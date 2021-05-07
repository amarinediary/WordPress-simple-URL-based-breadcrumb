<?php

if ( ! defined( 'ABSPATH' ) ) {

    exit;

};

/**
 * Plugin Name: Where Is My Bread 🍞
 * Text Domain: where-is-my-bread
 * Plugin URI: https://github.com/amarinediary/Where-Is-My-Bread
 * Description: A non-invasive, lightweight WordPress plugin adding url based breadcrumb support. Where Is My Bread 🍞 is a plug-and-play plugin with no required configuration.
 * Version: 1.0.0
 * Requires at least: 5.6.0
 * Requires PHP: 8.0
 * Tested up to: 5.7.1
 * Author: amarinediary
 * Author URI: https://github.com/amarinediary
 * License: CC0 1.0 Universal (CC0 1.0) Public Domain Dedication
 * License URI: https://github.com/amarinediary/Where-Is-My-Bread/blob/main/LICENSE
 * GitHub Plugin URI: https://github.com/amarinediary/Where-Is-My-Bread
 * GitHub Branch: main
 */
if ( ! class_exists( 'Where_Is_My_Bread' ) ) {

    class Where_Is_My_Bread {

        /**
         * Hooks methods to actions.
         *
         * @since 1.0.0
         */
        public function __construct() {

            add_action( 'wp', array( $this, 'get_crumbs' ) );

        }

        /**
         * Retrieve all crumbs.
         *
         * @since 1.0.0
         * 
         * @return Array Crumbs array.
         */
        public function get_crumbs() {

            $scheme = $_SERVER['REQUEST_SCHEME'];

            $request = $_SERVER['REQUEST_URI'];
            
            $localhost = array(
                '127.0.0.1', 
                '::1'
            );

            if ( str_contains( $request, '?' ) ) {

                $request = substr( $request, 0, strpos( $request, '?' ) );
    
            };

            if ( str_ends_with( $request, '/' ) ) {

                $request = explode( '/', substr( $request, 1, -1 ) );
    
            } else {
    
                $request = explode( '/', substr( $request, 1 ) );
    
            };

            $crumbs = array();

            foreach ( $request as $crumb ) {

                $slug = esc_html( $crumb );

                $url = esc_url( $scheme . '://' . $_SERVER['HTTP_HOST'] . '/' . substr( implode( '/', $request ), 0, strpos( implode( '/', $request ), $crumb ) ) );

                array_push( $crumbs, ( object )
                    array(
                        'slug' => $slug,
                        'url' => $url . $slug,
                    )
                );

            };

            return( $crumbs );

        }

        /**
         * Retrieve the bread as a formated crumbs list.
         *
         * @since 1.0.0
         * 
         * @param Array $args
         * 
         * @return Array Formated crumbs list.
         */
        public function get_bread( 
            $args = array(
                'separator' => '>',
                'offset' => 0,
            ) 
        ) {

            $crumbs = array_slice( $this->get_crumbs(), abs( $args['offset'] ) );

            echo '<ol class="🍞 bread">';

            $i = 0;
            foreach ( $crumbs as $crumb ) {
                $i++;

                echo '<li class="crumb"><a href="' . $crumb->url . '">' . $crumb->slug . '</a></li>';

                if ( $i !== sizeof( $crumbs ) && ! empty( $args['separator'] ) ) {

                    echo '<li>' . $args['separator'] . '</li>';

                };

            };

            echo '</ol>';
        
        }
        
    };

    $where_is_my_bread = new Where_Is_My_Bread();

};