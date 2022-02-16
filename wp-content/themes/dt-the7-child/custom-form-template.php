<?php /* Template Name: Custom Form */ ?>

<?php get_header(); ?>

<div class="clear"></div>
<div class="fl-content-full">
	<div class="row content-row">
		<div class="col-md-12">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				<article <?php post_class( 'fl-post' ); ?> id="fl-post-<?php the_ID(); ?>">

	<?php if ( FLTheme::show_post_header() ) : ?>
	<header class="fl-post-header hfn-page-header padd-tb50" style="margin:0px;">
        <div class="container">
            <h1 class="fl-post-title text-center" itemprop="headline"><?php the_title(); ?></h1>
        </div>
	</header>
    <!-- Heartfulness Post Header -->
	<?php endif; ?>

	<div class="fl-post-content clearfix" itemprop="text">
		<?php
			the_content();

			wp_link_pages( array(
				'before'         => '<div class="fl-post-page-nav">' . _x( 'Pages:', 'Text before page links on paginated post.', 'fl-automator' ),
				'after'          => '</div>',
				'next_or_number' => 'number'
			) );
		?>
	</div><!-- .fl-post-content -->

	<?php comments_template(); ?>

</article>
<!-- .fl-post -->
			<?php endwhile; endif; ?>
		</div>
	</div>
</div>
<?php edit_post_link( _x( 'Edit', 'Edit page link text.', 'fl-automator' ) ); ?>

 <?php wp_dequeue_script('bootstrap', FL_THEME_URL . '/js/bootstrap.min.js', array(), FL_THEME_VERSION, true);?>
<?php get_footer(); ?>
