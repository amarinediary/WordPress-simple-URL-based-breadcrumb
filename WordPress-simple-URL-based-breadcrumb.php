<?php

if ( ! defined( 'ABSPATH' ) ) {

    exit;

};

/**
 * Plugin Name: WordPress simple URL based breadcrumb
 * Text Domain: wordpress-simple-url-based-breadcrumb
 * Plugin URI: https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb
 * Description: 🍞 A non-invasive WordPress unofficial plugin, minimalist and SEO friendly. both lightweight and lightning fast, adding URL based breadcrumb support. Plug-and-play, with no required configuration.
 * Version: 1.2.0
 * Requires at least: 5.0.0
 * Requires PHP: 7.0.0
 * Tested up to: 6.0.1
 * Author: amarinediary
 * Author URI: https://github.com/amarinediary
 * License: CC0 1.0 Universal (CC0 1.0) Public Domain Dedication
 * License URI: https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb/blob/main/LICENSE
 * GitHub Plugin URI: https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb
 * GitHub Branch: main
 */

/**
 * Checks if a string ends with a given substring.
 * 
 * Backward compatibility for PHP < 8.0.0
 * 
 * @param String $haystack
 * @param String $needle
 * 
 * @return Boolean
 */
if ( ! function_exists( 'backward_compatibility_str_ends_with' ) ) {

    function backward_compatibility_str_ends_with( $haystack, $needle ) {

        $length = strlen( $needle );

        if ( ! $length ) {

            return true;

        };

        return substr( $haystack, -$length ) === $needle;

    };

};

/**
 * Determine if a string contains a given substring.
 * 
 * Backward compatibility for PHP < 8.0.0
 * 
 * @param String $haystack
 * @param String $needle
 * 
 * @return Boolean
 */
if ( ! function_exists( 'backward_compatibility_str_contains' ) ) {

    function backward_compatibility_str_contains( $haystack, $needle ) {

        if ( strpos( $haystack, $needle ) !== false ) {

            return true;

        };

    };

};

/**
 * Retrieve the crumbs.
 * 
 * @since 1.0.0
 *
 * @return Array Crumbs array.
 */
if ( ! function_exists( 'get_the_crumbs' ) ) {

    function get_the_crumbs() {

        /**
         * $_SERVER["REQUEST_SCHEME"] seems to be UNRELIABLE.
         * 
         * Article "Is $_SERVER['REQUEST_SCHEME'] reliable?".
         * @see https://stackoverflow.com/a/18008178/3645650
         * 
         * $_SERVER['REQUEST_SCHEME'] is a native variable of Apache web server since its version 2.4.
         * Naturally, if a variable is not set by the server, PHP will not include it in its global array $_SERVER.
         * 
         * An alternative to $_SERVER['REQUEST_SCHEME'] is $_SERVER['HTTPS'] which set to a non-empty value if the script was queried through the HTTPS protocol.
         * 
         * Article "How to find out if you're using HTTPS without $_SERVER['HTTPS']".
         * @see https://stackoverflow.com/a/16076965/3645650
         */

        if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {

            $server_scheme = 'https';

        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || ! empty( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) {
            
            $server_scheme = 'https';

        } else {

            $server_scheme = 'http';

        };
        
        /**
         * $_SERVER["REQUEST_URI"] seems to be RELIABLE.
         * $_SERVER['REQUEST_URI'] will not be empty in WordPress, because it is filled in wp_fix_server_vars() (file wp-includes/load.php).
         * 
         * Article "Is it safe to use $_SERVER['REQUEST_URI']?".
         * @see https://wordpress.stackexchange.com/a/110541/190376
         */
        $server_uri = $_SERVER['REQUEST_URI'];

        /**
         * $_SERVER["HTTP_HOST"] seems to be RELIABLE.
         * 
         * Article "How reliable is HTTP_HOST?".
         * @see https://stackoverflow.com/a/4096246/3645650
         */
        $server_host = $_SERVER["HTTP_HOST"];

        if ( backward_compatibility_str_contains( $server_uri, '?' ) ) {

            $server_uri = substr( $server_uri, 0, strpos( $server_uri, '?' ) );

        };

        if ( backward_compatibility_str_ends_with( $server_uri, '/' ) ) {

            $server_uri = explode( '/', substr( $server_uri, 1, -1 ) );

        } else {

            $server_uri = explode( '/', substr( $server_uri, 1 ) );

        };

        $crumbs = array();

        foreach ( $server_uri as $crumb ) {

            $slug = esc_html( urldecode( $crumb ) );

            $url = esc_url( $server_scheme . '://' . $server_host . '/' . substr( implode( '/', $server_uri ), 0, strpos( implode( '/', $server_uri ), $crumb ) ) . $crumb. '/' );

            array_push( $crumbs, 
                array(
                    'slug' => $slug,
                    'url' => $url,
                )
            );

        };

        $banned_slugs = array();
        
        $post_types = get_post_types( 
            array(
                'public' => true,
            ),
            'objects'
        );

        foreach ( $post_types as $post_type ) {

            array_push( $banned_slugs, $post_type->name );

            if ( isset( $post_type->rewrite['slug'] ) ) {
            
                array_push( $banned_slugs, $post_type->rewrite['slug'] );
            
            };

        };

        $taxonomies = get_taxonomies( 
            array(
                'public' => true,
            ),
            'objects'
        );
        
        foreach ( $taxonomies as $taxonomy ) {

            array_push( $banned_slugs, $taxonomy->name );
            
            if ( isset( $taxonomy->rewrite['slug'] ) ) {
            
                array_push( $banned_slugs, $taxonomy->rewrite['slug'] );
            
            };

        };

        $banned_crumbs = array();

        foreach ( $banned_slugs as $banned_slug ) {

            $slug = esc_html( $banned_slug );

            $url = esc_url( $server_scheme . '://' . $server_host . '/' . substr( implode( '/', $server_uri ), 0, strpos( implode( '/', $server_uri ), $banned_slug ) ) . $banned_slug. '/' );

            array_push( $banned_crumbs, 
                array(
                    'slug' => $slug,
                    'url' => $url,
                )
            );

        };

        $crumbs = array_filter( $crumbs, function( $crumb ) use ( $banned_slugs ) {

            if ( ! in_array( $crumb['slug'], $banned_slugs ) && ! in_array( $crumb['url'], $banned_slugs ) ) {

                return ! in_array( $crumb['slug'], $banned_slugs );

            };

        } );

        return $crumbs;

    };

};

/**
 * Display the bread, a formatted crumbs list.
 * 
 * @since 1.0.0
 * 
 * @param   Array   $ingredients                    The bread arguments.
 * @param   Array   $ingredients['root']            Root crumb. Default to null.
 * @param   String  $ingredients['root']['slug']    Root crumb slug.
 * @param   String  $ingredients['root']['url']     Root crumb url.
 * @param   String  $ingredients['separator']       The crumb's separator. The separator is not escaped.
 * @param   Integer $ingredients['offset']          Crumbs offset. Accept positive/negative Integer. Default to "0". Refer to array_slice, https://www.php.net/manual/en/function.array-slice.php.
 * @param   Integer $ingredients['length']          Crumbs length. Accept positive/negative Integer. Default to "null". Refer to array_slice, https://www.php.net/manual/en/function.array-slice.php.
 * 
 * @return  Array   The formatted crumbs list.
 */
if ( ! function_exists( 'the_bread' ) ) {

    function the_bread( $ingredients = array() ) {
        
        if ( empty( $ingredients['root'] ) ) {
        
            $root = null;
            
        } else {
        
            $root = $ingredients['root'];
            
        };
        
        if ( empty( $ingredients['offset'] ) ) {
        
            $offset = 0;
            
        } else {
        
            $offset = $ingredients['offset'];
            
        };
               
        if ( empty( $ingredients['length'] ) ) {
        
            $length = null;
            
        } else {
        
            $length = $ingredients['length'];
            
        };
        
        $crumbs = get_the_crumbs();

        if ( ! empty( $root ) ) {

            array_unshift( $crumbs, $ingredients['root'] );

        };
        
        $crumbs = array_slice( $crumbs, $offset, $length );

        if ( ! empty( $crumbs ) ) {

            echo '<ol class="🍞 bread" itemscope itemtype="https://schema.org/BreadcrumbList">';

            $i = 0;
            
            foreach ( $crumbs as $crumb ) {

                $i++;

                if ( url_to_postid( $crumb['url'] ) ) {

                    $title = get_the_title( url_to_postid( $crumb['url'] ) );

                } elseif ( get_page_by_path( $crumb['slug'] ) ) {

                    $title = get_the_title( get_page_by_path( $crumb['slug'] ) );

                } else {
  
                    $title = ucfirst( str_replace( '-', ' ', $crumb['slug'] ) );

                };

                echo '<li class="crumb" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="' . $crumb['url'] . '">
                        <span itemprop="name">' . $title . '</span>
                    </a>
                    <meta itemprop="position" content="' . $i . '">
                </li>';

                if ( $i !== sizeof( $crumbs ) && ! empty( $ingredients['separator'] ) ) {

                    echo $ingredients['separator'];

                };
    
            };
    
            echo '</ol>';

        };

    };

};
