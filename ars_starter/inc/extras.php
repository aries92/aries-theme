<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package ars
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function ars_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

    if ( ! wp_is_mobile() ) {
        $classes[] = 'desktop';
    }

    if ( wp_is_mobile() ) {
        $classes[] = 'mobile';
    }

    if ( !is_front_page() ) {
        $classes[] = 'other-page';
    }


    return $classes;
}
add_filter( 'body_class', 'ars_body_classes' );

//function ars_excerpt($a){
//    $excerpt = get_the_content();
//    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
//    $excerpt = strip_shortcodes($excerpt);
//    $excerpt = strip_tags($excerpt);
//    $excerpt = substr($excerpt, 0, $a);
//    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
//    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
//    echo $excerpt;
//}

function ars_excerpt($limit) {
    $excerpt = explode(' ', get_the_content(), $limit);
    if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
    } else {
        $excerpt = implode(" ",$excerpt);
    }
    $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
    echo $excerpt;
}