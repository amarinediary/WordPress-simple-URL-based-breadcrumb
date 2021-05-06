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

            add_action( 'wp', array( $this, 'get_past_crumb' ) );

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
            
            $host = $_SERVER['HTTP_HOST'];

            if ( str_contains( $request, '?' ) ) {

                $request = substr( $request, 0, strpos( $request, '?' ) );
    
            };

            if ( str_ends_with( $request, '/' ) ) {

                $request = explode( '/', substr( $request, 1, -1 ) );
    
            } else {
    
                $request = explode( '/', substr( $request, 1 ) );
    
            }

            var_dump( $request );

            /*
            $crumbs = array();

            array_push( $crumbs, ( object )
                array(
                    'crumb' => $slug,
                    'url' => $url,
                )
            );
            
            return $crumbs;
            */

        }

        /**
         * Retrieve the bread as a formated crumbs list.
         *
         * @since 1.0.0
         * 
         * @return Array Crumbs list.
         */
        public function get_bread() {
        
            return '<ol class="🍞 bread">';

            $crumbs = $this->get_crumbs();

            foreach ( $crumbs as $crumb ) {

                return '<li class="crumb"><a href="' . $crumb->url . '">' . $crumb->slug . '</a></li>';

            };

            return '</ol>' . $args->after_ol . '';
        
        }
        
        /**
         * Retrieve past crumb slug|URL.
         *
         * @since 1.0.0
         * 
         * @param String Accept either slug|URL.
         * 
         * @return String Previous crumb slug|URL.
         */
        public function get_past_crumb() {
        

        
        }
        
    };

    $where_is_my_bread = new Where_Is_My_Bread();

};