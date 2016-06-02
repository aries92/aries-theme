<?php
/**
 * Created by aries92.
 *
 * @dev: Taras Tymovskyi
 * @date: 28/01/16
 * @package ars_starter
 */

/**
 * Sets the post excerpt length to 40 words.
 * Adopted from Coraline
 */
function responsive_excerpt_length($length) {
	return 30;
}

add_filter('excerpt_length', 'responsive_excerpt_length');

/**
 * Returns a "Read more" link for excerpts
 */
function responsive_read_more() {
	return '<div class="read-more"><a href="' . get_permalink() . '">' . __('Read more &#8250;', 'responsive') . '</a></div><!-- end of .read-more -->';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and responsive_read_more_link().
 */
function responsive_auto_excerpt_more($more) {
	return '<span class="ellipsis">&hellip;</span>' . responsive_read_more();
}

add_filter('excerpt_more', 'responsive_auto_excerpt_more');

/**
 * Adds a pretty "Read more" link to custom post excerpts.
 */
function responsive_custom_excerpt_more($output) {
	if (has_excerpt() && !is_attachment()) {
		$output .= responsive_read_more();
	}
	return $output;
}

add_filter('get_the_excerpt', 'responsive_custom_excerpt_more');

/**
 * Breadcrumb Lists
 * Allows visitors to quickly navigate back to a previous section or the root page.
 *
 * Adopted from Dimox
 *
 */
if (!function_exists('responsive_breadcrumb_lists')) :

	function responsive_breadcrumb_lists() {

		/* === OPTIONS === */
		$text['home']     = __('Home','responsive'); // text for the 'Home' link
		$text['category'] = __('Archive for %s','responsive'); // text for a category page
		$text['search']   = __('Search results for: %s','responsive'); // text for a search results page
		$text['tag']      = __('Posts tagged %s','responsive'); // text for a tag page
		$text['author']   = __('View all posts by %s','responsive'); // text for an author page
		$text['404']      = __('Error 404','responsive'); // text for the 404 page

		$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
		$showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$delimiter   = ' <span class="chevron">&#8250;</span> '; // delimiter between crumbs
		$before      = '<span class="breadcrumb-current">'; // tag before the current crumb
		$after       = '</span>'; // tag after the current crumb
		/* === END OF OPTIONS === */

		global $post, $paged, $page;
		$homeLink = home_url('/');
		$linkBefore = '<span class="breadcrumb" typeof="v:Breadcrumb">';
		$linkAfter = '</span>';
		$linkAttr = ' rel="v:url" property="v:title"';
		$link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

		if ( is_front_page()) {

			if ($showOnHome == 1) echo '<div class="breadcrumb-list"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';

		} else {

			echo '<div class="breadcrumb-list" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf($link, $homeLink, $text['home']) . $delimiter;

			if ( is_home() ) {
				if ($showCurrent == 1) echo $before . get_the_title( get_option('page_for_posts', true) ) . $after;

			} elseif ( is_category() ) {
				$thisCat = get_category(get_query_var('cat'), false);
				if ($thisCat->parent != 0) {
					$cats = get_category_parents($thisCat->parent, true, $delimiter);
					$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
					$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
					echo $cats;
				}
				echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

			} elseif ( is_search() ) {
				echo $before . sprintf($text['search'], get_search_query()) . $after;

			} elseif ( is_day() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
				echo $before . get_the_time('d') . $after;

			} elseif ( is_month() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo $before . get_the_time('F') . $after;

			} elseif ( is_year() ) {
				echo $before . get_the_time('Y') . $after;

			} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
					if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, true, $delimiter);
					if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
					$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
					echo $cats;
					if ($showCurrent == 1) echo $before . get_the_title() . $after;
				}

			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				echo $before . $post_type->labels->singular_name . $after;

			} elseif ( is_attachment() ) {
				$parent = get_post($post->post_parent);
				$cat = get_the_category($parent->ID);

				if ( isset( $cat[0] ) ) {
					$cat = $cat[0];
				}

				if( $cat ) {
					$cats = get_category_parents($cat, true, $delimiter);
					$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
					$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
					echo $cats;
				}

				printf($link, get_permalink($parent), $parent->post_title);
				if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

			} elseif ( is_page() && !$post->post_parent ) {
				if ($showCurrent == 1) echo $before . get_the_title() . $after;

			} elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page_child = get_page($parent_id);
					$breadcrumbs[] = sprintf($link, get_permalink($page_child->ID), get_the_title($page_child->ID));
					$parent_id  = $page_child->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				for ($i = 0; $i < count($breadcrumbs); $i++) {
					echo $breadcrumbs[$i];
					if ($i != count($breadcrumbs)-1) echo $delimiter;
				}
				if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

			} elseif ( is_tag() ) {
				echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				echo $before . sprintf($text['author'], $userdata->display_name) . $after;

			} elseif ( is_404() ) {
				echo $before . $text['404'] . $after;

			}if ( get_query_var('paged') ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
				echo $delimiter . sprintf( __( 'Page %s', 'responsive' ), max( $paged, $page ) );
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
			}

			echo '</div>';

		}
	} // end responsive_breadcrumb_lists

endif;

/**
 * Shorthand of directions
 * @use <?dr()?>
 */
function dr() {
    $direction = bloginfo('stylesheet_directory');
    echo $direction;
}

///**
// * Funtion to add CSS class to body
// */
//function responsive_add_class( $classes ) {
//
//	// Get Responsive theme option.
//	global $responsive_options;
//	if( $responsive_options['front_page'] == 1 && is_front_page() ) {
//		$classes[] = 'front-page';
//	}
//
//	return $classes;
//}
//
//add_filter( 'body_class','responsive_add_class' );

/**
 * Create admin section
 */
function ars_add_post_type(){
	$name = 'Collection';
	$slug = 'collection-post';
	$support = array('title', 'editor', 'thumbnail');
	$post_labels = array(
		'name' => _x($name, 'taxonomy general name', ''),
		'singular_name' => _x($name, ''),
		'search_items' => __('Search '.$name, ''),
		'all_items' => __($name, ''),
		'parent_item' => __('Parent '.$name, ''),
		'edit_item' => __('Edit '.$name, ''),
		'update_item' => __('Update '.$name, ''),
		'add_new_item' => __('Add New '.$name, '')
	);
	$args = array(
		'labels' => $post_labels,
		'rewrite' => array('slug' => $slug, 'with_front' => false),
		'singular_label' => __($name, ''),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'hierarchical' => false,
		'supports' => $support
	);

	register_post_type($slug, $args);

    $name = 'Portfolio';
    $slug = 'portfolio';
    $support = array('title', 'editor', 'thumbnail');
    $post_labels = array(
        'name' => _x($name, 'taxonomy general name', ''),
        'singular_name' => _x($name, ''),
        'search_items' => __('Search '.$name, ''),
        'all_items' => __($name, ''),
        'parent_item' => __('Parent '.$name, ''),
        'edit_item' => __('Edit '.$name, ''),
        'update_item' => __('Update '.$name, ''),
        'add_new_item' => __('Add New '.$name, '')
    );
    $args = array(
        'labels' => $post_labels,
        'rewrite' => array('slug' => $slug, 'with_front' => false),
        'singular_label' => __($name, ''),
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_ui' => true,
        'hierarchical' => false,
        'supports' => $support
    );

    register_post_type($slug, $args);

}
add_action('init', 'ars_add_post_type');


function ars_add_taxonomy() {

    $name = 'Type';
    $slug = 'type';
    $post_type_to_connect = 'portfolio';
//    or 'post'

    $labels = array(
        'name'              => _x( $name, 'taxonomy general name' ),
        'singular_name'     => _x( $name, 'taxonomy singular name' ),
        'search_items'      => __( 'Search '.$name ),
        'all_items'         => __( 'All '.$name ),
        'parent_item'       => __( 'Parent '.$name ),
        'parent_item_colon' => __( 'Parent '.$name.':' ),
        'edit_item'         => __( 'Edit '.$name ),
        'update_item'       => __( 'Update '.$name ),
        'add_new_item'      => __( 'Add New '.$name ),
        'new_item_name'     => __( 'New '.$name.' Name' ),
        'menu_name'         => __( $name ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => $slug )
    );

    register_taxonomy( $slug, array( $post_type_to_connect ), $args );

}

add_action( 'init', 'ars_add_taxonomy', 0 );

/**
 * Adds custom taxonomy class to post_class() function
 *
 * @param $classes
 *
 * @return array
 */
function add_tax_classes($classes) {
    global $post;

    $tax_terms = get_the_terms($post->ID, array('type'));
    if($tax_terms) {
        $tax_terms = wp_list_pluck($tax_terms,'slug');
        $classes = array_merge($classes, $tax_terms);
    }
    return $classes;
}
add_filter('post_class','add_tax_classes');


/**
 * Adds custom pagination
 * @usage  custom_pagination($custom_query->max_num_pages,"",$paged);
 */
function custom_pagination($numpages = '', $pagerange = '', $paged='') {

    if (empty($pagerange)) {
        $pagerange = 2;
    }

    /**
     * This first part of our function is a fallback
     * for custom pagination inside a regular loop that
     * uses the global $paged and global $wp_query variables.
     *
     * It's good because we can now override default pagination
     * in our theme, and use this function in default quries
     * and custom queries.
     */
    global $paged;
    if (empty($paged)) {
        $paged = 1;
    }
    if ($numpages == '') {
        global $wp_query;
        $numpages = $wp_query->max_num_pages;
        if(!$numpages) {
            $numpages = 1;
        }
    }

    /**
     * We construct the pagination arguments to enter into our paginate_links
     * function.
     */
    $pagination_args = array(
        'base'            => get_pagenum_link(1) . '%_%',
        'format'          => 'page/%#%',
        'total'           => $numpages,
        'current'         => $paged,
        'show_all'        => False,
        'end_size'        => 1,
        'mid_size'        => $pagerange,
        'prev_next'       => True,
        'prev_text'       => __('< Previous'),
        'next_text'       => __('Next >'),
        'type'            => 'list',
        'add_args'        => false,
        'add_fragment'    => ''
    );

    $paginate_links = paginate_links($pagination_args);

    if ($paginate_links) {
        echo "<nav class='pagination'>";
        echo "<span class='page-numbers page-num'>Page " . $paged . " of " . $numpages . "</span> ";
        echo $paginate_links;
        echo "</nav>";
    }

}




/**
 * Get instagram feed(back-end)
 * Display https://gist.github.com/aries92/b8e833070f43288d18e2
**/

function scrape_instagram( $username, $slice = 9 ) {
    $username = strtolower( $username );


    if ( false === ( $instagram = get_transient( 'instagram-media-2-' . sanitize_title_with_dashes( $username ) ) ) ) {

        $args = array(
            'timeout'     => 5,
            'redirection' => 5,
            'httpversion' => '1.0',
            'user-agent'  => 'WordPress/4.3; ' . get_bloginfo( 'url' ),
            'blocking'    => true,
            'headers'     => array(),
            'cookies'     => array(),
            'body'        => null,
            'compress'    => false,
            'decompress'  => true,
            'sslverify'   => false,
            'stream'      => false,
            'filename'    => null
        );
        $remote = wp_remote_get( 'https://instagram.com/' . trim( $username ), $args );



        if ( is_wp_error( $remote ) ) {
            return new WP_Error( 'site_down', __( 'Unable to communicate with Instagram.', $this->wpiwdomain ) );
        }

        if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
            return new WP_Error( 'invalid_response', __( 'Instagram did not return a 200.', $this->wpiwdomain ) );
        }


        $shards      = explode( 'window._sharedData = ', $remote[ 'body' ] );
        $insta_json  = explode( ';</script>', $shards[ 1 ] );
        $insta_array = json_decode( $insta_json[ 0 ], true );


        if ( ! $insta_array ) {
            return new WP_Error( 'bad_json', __( 'Instagram has returned invalid data.', $this->wpiwdomain ) );
        }

        $images = $insta_array[ 'entry_data' ][ 'ProfilePage' ][0]['user']['media']['nodes'];
        $instagram = array ();

        foreach ( $images as $image ) {

            $instagram[ ] = array (
                'description' => $image[ 'caption' ],
                'link'        => 'https://instagram.com/p/'.$image[ 'code' ],
                'time'        => $image[ 'date' ],
                'comments'    => $image[ 'comments' ][ 'count' ],
                'likes'       => $image[ 'likes' ][ 'count' ],
                'image'      => $image[ 'display_src' ],
                'type'        => ((bool)$image[ 'is_video' ]) ? 'video' : 'image'
            );
        }

        $instagram = base64_encode( serialize( $instagram ) );
        set_transient( 'instagram-media-2-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
    }

    $instagram = unserialize( base64_decode( $instagram ) );

    return array_slice( $instagram, 0, $slice );
}

function images_only( $media_item ) {

    if ( $media_item[ 'type' ] == 'image' ) {
        return true;
    }

    return false;
}


