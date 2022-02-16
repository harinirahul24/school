<?php /* Template Name: Heartfulness Blogs */ ?>

<?php get_header(); ?>

<div class="fl-content-full">
            <div class="hfn-page-content">
                <header class="hfn-page-header padd-tb50">
                    <div class="container">
                        <h1 class="fl-post-title text-center"><?php the_title(); ?></h1>
                    </div>
                </header>
                <div class="container hfn-container">
                <div class="col-md-12">
                    <div class="padd-tb50">
                    <div class="sh-group blog-list blog-style-masonry masonry-shadow">
                    <?php
            $page = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $query = new WP_Query( array( 'post_type' => 'post', 'paged' => $page ) );

            if ( $query->have_posts() ) : ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                    <div class="post-container">
                        <div class="post-meta-thumb">
                            <?php echo the_post_thumbnail( 'post-thumbnail' ); ?>
                        </div>
                        <div class="post-content-container">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="post-title">
                                <h2><?php the_title(); ?></h2>
                            </a>
                            <div class="post-content">
                                <?php the_excerpt(); ?>
                            </div>	
                            <div class="post-meta post-meta-two">
                                <div class="text-left">
                                   <div class="hfn-blog-more">
                                    <a href="<?php echo get_permalink(); ?>">Read More</a>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="hfn-blog-share-block">
                                        <div class="hfn-blog-share-pop">
                                            <?php echo do_shortcode ('[easy-social-share buttons="facebook,twitter,google,linkedin,mail,whatsapp" sharebtn_style="icon" counters=0 style="button" template="8" point_type="simple"]'); ?>
                                        </div>
                                        <div class="hfn-social-icons">
                                            <a href="javascript:void(0)"><i class="material-icons hfn-blog-share-icon">share</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>			
                        </div>

                    </div>

                </article>
			<?php endwhile; wp_reset_postdata(); ?>
			
            <?php else : ?>
            <!-- show 404 error here -->
            <?php endif; ?>
                    </div>
 <?php hfn_pagination( $query ); ?>
            <!-- show pagination here -->
                </div>
                   
        </div>
        </div>
    </div>
</div>
<?php edit_post_link( _x( 'Edit', 'Edit page link text.', 'fl-automator' ) ); ?>
<?php get_footer(); ?>