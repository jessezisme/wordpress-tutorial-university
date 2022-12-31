<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="<?php bloginfo('charset') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container">
            <h1 class="school-logo-text float-left">
                <a href="<?php echo site_url() ?>"><strong>Fictional</strong> University</a>
            </h1>
            <a href="<?php esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
            <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
            <div class="site-header__menu group">
                <nav class="main-navigation">
                    <ul>
                        <?php
                        function setActiveLinkClass($linkPath)
                        {
                            $isCurrentPage = is_page($linkPath);
                            $isChildPage = (get_page_by_path($linkPath) && wp_get_post_parent_id(get_the_ID()) == get_page_by_path($linkPath)->ID);
                            $isActive = $isCurrentPage || $isChildPage;
                            return $isActive ? "current-menu-item" : '';
                        }
                        ?>
                        <li class="<?php echo setActiveLinkClass('about-us') ?>">
                            <a href="<?php echo site_url('/about-us') ?>">About Us</a>
                        </li>
                        <li class="<?php if (get_post_type() == 'program') echo 'current-menu-item' ?>">
                            <a href="<?php echo get_post_type_archive_link('program') ?>">Programs</a>
                        </li>
                        <li class="<?php if (get_post_type() == 'event' || is_page('past-events')) echo 'current-menu-item' ?>">
                            <a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a>
                        </li>
                        <li class="<?php if (get_post_type() == 'campus') echo 'current-menu-item' ?>">
                            <a href="<?php echo get_post_type_archive_link('campus'); ?>">Campuses</a>
                        </li>
                        <li <?php
                            if (get_post_type() == 'post') {
                                echo 'class="current-menu-item"';
                            }
                            ?>>
                            <a href="<?php echo site_url('/blog') ?>">Blog</a>
                        </li>
                    </ul>
                </nav>
                <div class="site-header__util">
                    <?php if (is_user_logged_in()) {
                    ?>
                        <a href="<?php echo esc_url(site_url('my-notes')); ?>" class="btn btn--small btn--orange float-left push-right">My Notes</a>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small btn--dark-orange float-left btn--with-photo">
                            <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
                            <span class="btn__text">Log Out</span>
                        </a>

                    <?php } else {
                    ?>
                        <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
                    <?php }
                    ?>
                    <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </header>
</body>

</html>