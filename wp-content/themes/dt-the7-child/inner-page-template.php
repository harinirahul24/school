<?php /* Template Name: Inner Page */ ?>

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

presscore_config()->set( 'template', 'innerpage' );
get_header(); ?>

                <?php if ( presscore_is_content_visible() ): ?>

                        <div id="content" class="content" role="main">

                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                        <?php do_action('presscore_before_loop'); ?>

                                        <?php the_content(); ?>

                                        <?php presscore_display_share_buttons_for_post( 'page' ); ?>

                                        <?php comments_template( '', true ); ?>

                                <?php endwhile; ?>

                        <?php else : ?>

                                <?php get_template_part( 'no-results', 'microsite' ); ?>

                        <?php endif; ?>

                        </div><!-- #content -->

                        <?php do_action('presscore_after_content'); ?>

                <?php endif; // if content visible ?>

<?php get_footer(); ?>
