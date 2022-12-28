<?php

function pageBanner($args = NULL)
{
    $customBackgroundImage = get_field('page_banner_background_image');
    $getBackgroundImage = $customBackgroundImage ? $customBackgroundImage['sizes']['pageBanner'] : get_template_directory_uri() . '/images/ocean.jpg';
    $getTitle = isset($args['title']) ? $args['title'] : get_the_title();
    $getSubtitle = isset($args['subtitle']) ? $args['subtitle'] : (get_field('page_banner_subtitle') ?: '');
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $getBackgroundImage; ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $getTitle; ?> </h1>
            <div class="page-banner__intro">
                <p> <?php echo $getSubtitle; ?></p>
            </div>
        </div>
    </div>
<?php }

function university_files()
{
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-google', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query)
{
    if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query) {
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'asc');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'type' => 'numeric',
                'value' => $today
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries');
