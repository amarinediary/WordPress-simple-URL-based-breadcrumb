<?php

if ( ! defined( 'ABSPATH' ) ) {

    exit;

};

/**
 * Plugin Name: WordPress simple URL based breadcrumb
 * Text Domain: wordpress-simple-url-based-breadcrumb
 * Plugin URI: https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb
 * Description: 🍞 A non-invasive WordPress unofficial plugin, minimalist and SEO friendly. both lightweight and lightning fast, adding URL based breadcrumb support. Plug-and-play, with no required configuration.
 * Version: 1.2.4
 * Requires at least: 5.0.0
 * Requires PHP: 7.0.0
 * Tested up to: 6.0.2
 * Author: amarinediary
 * Author URI: https://github.com/amarinediary
 * License: CC0 1.0 Universal (CC0 1.0) Public Domain Dedication
 * License URI: https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb/blob/main/LICENSE
 * GitHub Plugin URI: https://github.com/amarinediary/WordPress-simple-URL-based-breadcrumb
 * GitHub Branch: main
 */

/**
 * Fallback implementation for str_ends_with function for PHP < 8.0.0 versions.
 *
 * @param string $haystack The string to search within.
 * @param string $needle The string to search for.
 *
 * @return Bool True if $haystack ends with $needle, false otherwise.
 * 
 * @since 1.0.0
 */
function backward_compatibility_str_ends_with( $haystack, $needle ) {

    return substr( $haystack, -strlen( $needle ) ) === $needle;

}

/**
 * Fallback implementation for str_contains function for PHP < 8.0.0 versions.
 *
 * @param string $haystack The string to search within.
 * @param string $needle The string to search for.
 *
 * @return Bool True if $haystack contains $needle, false otherwise.
 * 
 * @since 1.0.0
 */
function backward_compatibility_str_contains( $haystack, $needle ) {

    return strpos( $haystack, $needle ) !== false;

}

/**
 * Wrapper function that safely uses the str_ends_with function
 * It uses the native PHP > 8.0.0 function when available, or falls back to the custom implementation.
 *
 * @param string $haystack The string to search within.
 * @param string $needle The string to search for.
 *
 * @return Bool True if $haystack ends with $needle, false otherwise.
 * 
 * @since 1.2.4
 */
function safe_str_ends_with( $haystack, $needle ) {
    
    if ( function_exists( 'str_ends_with' ) ) {
    
        return str_ends_with( $haystack, $needle );
    
    }
    
    return backward_compatibility_str_ends_with( $haystack, $needle );

}

/**
 * Wrapper function that safely uses the str_contains function
 * It uses the native PHP > 8.0.0 function when available, or falls back to the custom implementation.
 *
 * @param string $haystack The string to search within.
 * @param string $needle The string to search for.
 *
 * @return Bool True if $haystack contains $needle, false otherwise.
 * 
 * @since 1.2.4
 */
function safe_str_contains( $haystack, $needle ) {
    
    if ( function_exists( 'str_contains' ) ) {
    
        return str_contains( $haystack, $needle );
    
    }
    
    return backward_compatibility_str_contains( $haystack, $needle );

}

/**
 * Attempts to determine the server scheme (http or https) of the current request based on various server variables.
 * 
 * This function checks multiple server variables like $_SERVER['HTTPS'], $_SERVER['SERVER_PORT'],
 * $_SERVER['HTTP_X_FORWARDED_PROTO'], and $_SERVER['HTTP_X_FORWARDED_SSL'] to accurately
 * determine whether the current request is made over https or http.
 * 
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
 * 
 * @return String $server_scheme The server scheme, either 'http' or 'https'.
 * 
 * @since 1.0.0
 */
if ( ! function_exists( 'attempt_to_retrieve_server_scheme' ) ) {

    function attempt_to_retrieve_server_scheme() {

        if ( isset( $_SERVER['HTTPS'] ) && safe_str_contains( $_SERVER['HTTPS'], 'on' ) ) {
        
            $server_scheme = 'https';
        
        } elseif ( isset($_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] == '443' ) {
            
            $server_scheme = 'https';
        
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && safe_str_contains( $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https' ) ) {
            
            $server_scheme = 'https';
        
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && safe_str_contains( $_SERVER['HTTP_X_FORWARDED_SSL'], 'on' ) ) {
            
            $server_scheme = 'https';
        
        } else {

            $server_scheme = 'http';

        }

    }

}

/**
 * Retrieve the crumbs.
 * 
 * @return Array Crumbs array.
 * 
 * @since 1.0.0
 */

if ( ! function_exists( 'get_the_crumbs' ) ) {

    function get_the_crumbs() {

        // Retrieve the server scheme ('http' or 'https')
        $server_scheme = attempt_to_retrieve_server_scheme();
        
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

        // Remove query string if present
        if ( safe_str_contains( $server_uri, '?' ) ) {

            $server_uri = substr( $server_uri, 0, strpos( $server_uri, '?' ) );

        }

        // Remove trailing slash if present
        if ( safe_str_ends_with( $server_uri, '/' ) ) {

            $server_uri = explode( '/', substr( $server_uri, 1, -1 ) );

        } else {

            $server_uri = explode( '/', substr( $server_uri, 1 ) );

        }

        // Initialize crumbs array
        $crumbs = array();

        // Populate crumbs array
        foreach ( $server_uri as $crumb ) {

            $slug = esc_html( urldecode( $crumb ) );

            $taxonomies = get_taxonomies(
                array(
                    'public' => true,
                ),
                'objects'
            );

            // Iterate through all the taxonomies.
            foreach ( $taxonomies as $taxonomy ) {

                // Check if there's a term with the given slug in the current taxonomy.
                if ( $term = get_term_by( 'slug', $crumb, $taxonomy->name ) ) {
                    
                    // If a matching term is found, update the slug with the actual term name.
                    $slug = $term->name;

                    // Break the loop since a match has been found.
                    break;

                }

            }

            $url = esc_url( $server_scheme . '://' . $server_host . '/' . substr( implode( '/', $server_uri ), 0, strpos( implode( '/', $server_uri ), $crumb ) ) . $crumb. '/' );

            array_push( $crumbs, 
                array(
                    'slug' => $slug,
                    'url' => $url,
                )
            );

        };

        /**
         * WordPress, by default, doesn't generate a taxonomy index, meaning https://.../taxonomy will redirect to a 404.
         * Any request needs to be made against a term. eg: https://.../taxonomy/term will redirect to taxonomy.php.
         * Therefore we need to remove the taxonomy slug from the crumbs array to avoid displaying a link to a 404.
         * 
         * We round up all taxonomies through get_taxonomies(). 
         * @see https://developer.wordpress.org/reference/functions/get_taxonomies/
         * 
         * Through array_filter we filter-out any matching crumbs.
         * @see https://www.php.net/manual/en/function.array-filter.php
         */
        $banned_slugs = array();

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
 * Display the breadcrumb, a formatted crumbs list.
 * 
 * @param Array $ingredients The bread arguments.
 * 
 * @return Void
 * 
 * @since 1.0.0
 */
if ( ! function_exists( 'the_bread' ) ) {

    function the_bread( $ingredients = array() ) {

        // Default values using array destructuring
        $defaults = [
            'crumbs'    => get_the_crumbs(),
            'root'      => null,
            'separator' => '',
            'offset'    => 0,
            'length'    => null
        ];

        // Merge provided ingredients with defaults
        $ingredients = array_merge( $defaults, $ingredients );

        // Extract variables from the ingredients array
        extract( $ingredients );

        // Handle the root crumb case
        if ( $root ) {

            array_unshift( $crumbs, $root );

        }
        
        // Handle the length case
        $crumbs = array_slice( $crumbs, $offset, $length );

        if ( $crumbs ) {

            echo '<nav aria-label="breadcrumb">';
            echo '<ol class="🍞 bread" itemscope itemtype="https://schema.org/BreadcrumbList">';

            $i = 0;

            foreach ( $crumbs as $crumb ) {

                $i++;

                // Unparsing the slug
                if ( url_to_postid( $crumb['url'] ) ) {

                    $title = get_the_title( url_to_postid( $crumb['url'] ) );

                } elseif ( get_page_by_path( $crumb['slug'] ) ) {

                    $title = get_the_title( get_page_by_path( $crumb['slug'] ) );

                } else {
  
                    $title = $crumb['slug'];

                };

                echo '<li class="crumb" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="' . $crumb['url'] . '"';

                if ( $i === sizeof( $crumbs ) ) {

                    echo ' aria-current="page"';

                }

                echo '>
                        <span itemprop="name">' . $title . '</span>
                    </a>
                    <meta itemprop="position" content="' . $i . '">';

                if ( $i !== sizeof( $crumbs ) && ! empty( $ingredients['separator'] ) ) {

                    echo $ingredients['separator'];

                };

                echo '</li>';


            };

            echo '</ol>';
            
            echo '</nav>';

        }

    }

}