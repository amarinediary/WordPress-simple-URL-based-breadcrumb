<?php

if ( ! defined( 'ABSPATH' ) ) {

    exit;

};

/**
 * Plugin Name: Where's My Bread ? 🍞
 * Text Domain: where-is-my-bread
 * Plugin URI: https://github.com/amarinediary/Where-Is-My-Bread
 * Description: A URL based WordPress breadcrumb, unstyled, minimalist and SEO friendly. A non-invasive WordPress unofficial plugin, both lightweight and lightning fast, adding URL based breadcrumb support. Plug-and-play, with no required configuration.
 * Version: 1.0.2
 * Requires at least: 5.6.0
 * Requires PHP: 8.0
 * Tested up to: 5.8.2
 * Author: amarinediary
 * Author URI: https://github.com/amarinediary
 * License: CC0 1.0 Universal (CC0 1.0) Public Domain Dedication
 * License URI: https://github.com/amarinediary/Where-Is-My-Bread/blob/main/LICENSE
 * GitHub Plugin URI: https://github.com/amarinediary/Where-Is-My-Bread
 * GitHub Branch: main
 */

if ( version_compare( PHP_VERSION, '8.0.0', '<' ) ) {

    return;

};

if ( ! function_exists( 'get_the_crumbs' ) ) {

    /**
     * Retrieve the crumbs.
     * 
     * @since 1.0.0
     *
     * @return Array Crumbs array.
     */
    function get_the_crumbs() {

        $flour = $_SERVER['REQUEST_URI'];

        if ( str_contains( $flour, '?' ) ) {

            $flour = substr( $flour, 0, strpos( $flour, '?' ) );

        };

        $flour = ( str_ends_with( $flour, '/' ) ? explode( '/', substr( $flour, 1, -1 ) ) : explode( '/', substr( $flour, 1 ) ) );

        $crumbs = [];

        foreach ( $flour as $crumb ) {

            $slug = esc_html( $crumb );

            $url = esc_url( $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . substr( implode( '/', $flour ), 0, strpos( implode( '/', $flour ), $crumb ) ) . $crumb. '/' );

            array_push( $crumbs, (object) array(
                'slug' => $slug,
                'url' => $url,
            ) );    

        };

        return $crumbs;

    };

};

if ( ! function_exists( 'the_bread' ) ) {

    /**
     * Display the bread, a formatted crumbs list.
     * 
     * @since 1.0.0
     * 
     * @param Array $ingredients[separator] The crumb's separator. Default to ">".
     * @param Array $ingredients[offset] Crumbs offset. Accept positive/negative Integer. Default to "0". Refer to array_slice, https://www.php.net/manual/en/function.array-slice.php.
     * @param Array $ingredients[length] Crumbs length. Accept positive/negative Integer. Default to "null". Refer to array_slice, https://www.php.net/manual/en/function.array-slice.php.
     * 
     * @return Array The formatted crumbs list.
     */
    function the_bread(
        $ingredients = array(
            'separator' => '>',
            'offset' => 0,
            'length' => null,
        )
    ) {

        $offset = ( empty( $ingredients['offset'] ) ? 0 : $ingredients['offset'] );

        $length = ( empty( $ingredients['length'] ) ? null : $ingredients['length'] );

        $crumbs = array_slice( get_the_crumbs(), $offset, $length );

        if ( ! empty( $crumbs ) ) {

            echo '<ol class="🍞 bread" itemscope itemtype="https://schema.org/BreadcrumbList">';

            $i = 0;
            
            foreach ( $crumbs as $crumb ) {

                $i++;
                
                //get_page_by_path() usage, credit to https://github.com/mohamadnr
                echo '<li class="crumb" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="' . $crumb->url . '">
                        <span itemprop="name">' . ( url_to_postid( $crumb->url ) ? get_the_title( url_to_postid( $crumb->url ) ) : ( get_page_by_path( $crumb->slug )->ID ? get_the_title( get_page_by_path( $crumb->slug )->ID ) : ucfirst( str_replace( '-', ' ', $crumb->slug ) ) ) ) . '</span>
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
