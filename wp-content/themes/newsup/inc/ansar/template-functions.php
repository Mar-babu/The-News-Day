<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Newsup
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function newsup_body_classes($classes)
{
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }


    $global_site_mode_setting = newsup_get_option('global_site_mode_setting');
    $classes[] = $global_site_mode_setting;


    $single_post_featured_image_view = newsup_get_option('single_post_featured_image_view');
    if ($single_post_featured_image_view == 'full') {
        $classes[] = 'ta-single-full-header';
    }

    $global_hide_post_date_author_in_list = newsup_get_option('global_hide_post_date_author_in_list');
    if ($global_hide_post_date_author_in_list == true) {
        $classes[] = 'ta-hide-date-author-in-list';
    }

    global $post;

    


    $global_alignment = newsup_get_option('newsup_content_layout');
    $page_layout = $global_alignment;
    $disable_class = '';
    $frontpage_content_status = newsup_get_option('frontpage_content_status');
    if (1 != $frontpage_content_status) {
        $disable_class = 'disable-default-home-content';
    }

    // Check if single.
    if ($post && is_singular()) {
        $post_options = get_post_meta($post->ID, 'newsup-meta-content-alignment', true);
        if (!empty($post_options)) {
            $page_layout = $post_options;
        } else {
            $page_layout = $global_alignment;
        }
    }


    return $classes;


}

add_filter('body_class', 'newsup_body_classes');


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function newsup_pingback_header()
{
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
    }
}

add_action('wp_head', 'newsup_pingback_header');


/**
 * Returns posts.
 *
 * @since Newsup 1.0.0
 */
if (!function_exists('newsup_get_posts')):
    function newsup_get_posts($number_of_posts, $category = '0')
    {

        $ins_args = array(
            'post_type' => 'post',
            'posts_per_page' => absint($number_of_posts),
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'ignore_sticky_posts' => true
        );

        $category = isset($category) ? $category : '0';
        if (absint($category) > 0) {
            $ins_args['cat'] = absint($category);
        }

        $all_posts = new WP_Query($ins_args);

        return $all_posts;
    }

endif;

if (!function_exists('newsup_get_block')) :
    /**
     *
     * @param null
     *
     * @return null
     *
     * @since newsup 1.0.0
     *
     */
    function newsup_get_block($block = 'grid', $section = 'post')
    {

        get_template_part('inc/ansar/hooks/blocks/block-' . $section, $block);

    }
endif;





/**
 * Check if given term has child terms
 *
 */
function newsup_list_popular_taxonomies($taxonomy = 'post_tag', $title = "Top Tags", $number = 5)
{
    

      $show_popular_tags_section = esc_attr(get_theme_mod('show_popular_tags_section','true'));
      $show_popular_tags_title = get_theme_mod('show_popular_tags_title', esc_html('Top Tags'));
      if($show_popular_tags_section == true){
      $popular_taxonomies = get_terms(array(
        'taxonomy' => $taxonomy,
        'number' => absint($number),
        'orderby' => 'count',
        'order' => 'DESC',
        'hide_empty' => true,
    ));

    $html = '';

    if (isset($popular_taxonomies) && !empty($popular_taxonomies)):
        $html .= '<div class="mg-tpt-txnlst clearfix">';
        if (!empty($title)):
            $html .= '<strong>';
            $html .= esc_html($title);
            $html .= '</strong>';
        endif;
        $html .= '<ul>';
        foreach ($popular_taxonomies as $tax_term):
            $html .= '<li>';
            $html .= '<a href="' . esc_url(get_term_link($tax_term)) . '">';
            $html .= $tax_term->name;
            $html .= '</a>';
            $html .= '</li>';
        endforeach;
        $html .= '</ul>';
        $html .= '</div>';
    endif;

    echo $html;
}
}


/**
 * @param $post_id
 * @param string $size
 *
 * @return mixed|string
 */
function newsup_get_freatured_image_url($post_id, $size = 'newsup-featured')
{
    if (has_post_thumbnail($post_id)) {
        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
        $url = $thumb['0'];
    } else {
        $url = '';
    }

    return $url;
}
?>
