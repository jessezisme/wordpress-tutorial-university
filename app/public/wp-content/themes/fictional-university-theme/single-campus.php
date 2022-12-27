<?php get_header() ?>

<?php
while (have_posts()) {
    the_post();
    pageBanner();
?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses
                </a> <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content() ?>
        </div>

        <?php
        $latitude = get_field('latitude');
        $longitude = get_field('longitude');

        if ($latitude && $longitude) {
        ?>
            <div class="gmap_canvas"><iframe width="600" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $latitude . ',' . $longitude ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://www.whatismyip-address.com"></a><br>
                <style>
                    .gmap_canvas {
                        position: relative;
                        display: block;
                        max-width: 800px;
                        aspect-ratio: 16/9;
                        overflow: hidden;
                        background: none !important;
                    }

                    .gmap_canvas iframe {
                        position: absolute;
                        top: 0;
                        right: 0;
                        bottom: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                    }
                </style>
            </div>
        <?php
        }
        ?>

        <?php
        $relatedPrograms = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'program',
            'orderby' => 'title',
            'order' => 'asc',
            'meta_query' => array(
                array(
                    'key' => 'related_campuses',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_id() . '"'
                )
            )
        ));

        if ($relatedPrograms->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium"> Programs Available at this Campus </h2>';
            echo '<ul class="min-list link-list">';

            while ($relatedPrograms->have_posts()) {
                $relatedPrograms->the_post();
        ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
        <?php
            }
            echo '<ul>';
        }
        ?>

        <?php wp_reset_postdata(); ?>

    </div>
<?php }
?>

<?php get_footer() ?>