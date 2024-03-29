<?php

namespace Helick\GTM;

use WP_Taxonomy;

/**
 * Get the container ID.
 *
 * @return string
 */
function container_id(): string
{
    return defined('GTM_CONTAINER_ID') ? GTM_CONTAINER_ID : '';
}

/**
 * Get the data layer variable.
 *
 * @return string
 */
function data_layer_variable(): string
{
    $dataLayerVar = 'dataLayer';

    /**
     * Control the data layer variable.
     *
     * @param string $dataLayerVar
     */
    $dataLayerVar = apply_filters('helick_gtm_data_layer_variable', $dataLayerVar);

    $dataLayerVar = preg_replace('/[^a-z0-9_\-]/i', '', (string)$dataLayerVar);

    return $dataLayerVar;
}

/**
 * Get the data layer.
 *
 * @return array
 */
function data_layer(): array
{
    $dataLayer = [];

    if (is_multisite()) {
        $dataLayer['network'] = [
            'id'  => get_main_site_id(),
            'url' => get_site_url(get_main_site_id()),
        ];
    }

    $dataLayer['site'] = [
        'id'  => is_multisite() ? get_current_blog_id() : 0,
        'url' => home_url(),
    ];

    $dataLayer['context'] = [
        'is_home'              => is_home(),
        'is_front_page'        => is_front_page(),
        'is_post_type_archive' => is_post_type_archive(),
        'is_tax'               => is_tax(),
        'is_author'            => is_author(),
        'is_date'              => is_date(),
        'is_search'            => is_search(),
        'is_singular'          => is_singular(),
        'is_404'               => is_404(),
    ];

    if (is_archive()) {
        if (is_date()) {
            $dataLayer['date'] = get_the_date();
        }

        if (is_search()) {
            $dataLayer['search'] = get_search_query();
        }

        if (is_post_type_archive()) {
            $dataLayer['post_type'] = get_post_type();
        }

        if (is_tag() || is_category() || is_tax()) {
            $term = get_queried_object();

            $dataLayer[$term->taxonomy] = [
                'id'   => $term->term_id,
                'slug' => $term->slug,
                'name' => $term->name,
            ];
        }

        if (is_author()) {
            $user = get_queried_object();

            $dataLayer['author'] = [
                'id'   => $user->ID,
                'slug' => $user->user_nicename,
                'name' => $user->display_name,
            ];
        }
    }

    if (is_singular()) {
        $dataLayer[get_post_type()] = [
            'id'           => get_the_ID(),
            'slug'         => get_post_field('post_name'),
            'title'        => get_the_title(),
            'template'     => get_page_template_slug(),
            'comments'     => (int)get_comments_number(),
            'published_at' => get_the_date('c'),
            'modified_at'  => get_the_modified_date('c'),
        ];

        $dataLayer[get_post_type()]['author'] = [
            'id'   => get_the_author_meta('ID'),
            'slug' => get_the_author_meta('user_nicename'),
            'name' => get_the_author_meta('display_name'),
        ];

        $taxonomies = get_object_taxonomies(get_post_type(), 'objects');
        $taxonomies = array_filter($taxonomies, function (WP_Taxonomy $taxonomy) {
            return $taxonomy->public;
        });

        // TODO: ugly :(
        foreach ($taxonomies as $taxonomy) {
            $terms = get_the_terms(get_the_ID(), $taxonomy->name);
            if (!$terms || is_wp_error($terms)) {
                continue;
            }

            $dataLayer[get_post_type()][$taxonomy->name] = array_column($terms, 'slug');
        }
    }

    if (is_user_logged_in()) {
        $user = wp_get_current_user();

        $dataLayer['user'] = [
            'id'           => $user->ID,
            'slug'         => $user->user_nicename,
            'name'         => $user->display_name,
            'capabilities' => array_keys($user->caps),
        ];
    }

    /**
     * Control the data layer.
     *
     * @param array $dataLayer
     */
    $dataLayer = apply_filters('helick_gtm_data_layer', $dataLayer);

    return (array)$dataLayer;
}
