<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch()
{
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

function universitySearchResults($data)
{
    $mainQuery = new WP_Query(array(
        // specify post types to query
        'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
        /*
            search parameter:
            $data is passed as part of action; uses 'term' param for search query.
            sanitize_text_field is used for security against injection. 
        */
        's' => sanitize_text_field($data['term'])
    ));

    // Uses a nested array to group results by type
    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    // Loop through results and add to categorized results to be returned
    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() == 'post' or get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if (get_post_type() == 'program') {
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if (get_post_type() == 'campus') {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if (get_post_type() == 'event') {
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
    }

    return $results;
}
