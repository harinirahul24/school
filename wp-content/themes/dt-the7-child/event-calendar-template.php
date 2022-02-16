<?php /* Template Name: Event Calendar Template */ ?>
<?php wp_deregister_script( 'google_map_api', 'https://maps.googleapis.com/maps/api/js?sensor=false' ); ?>
<?php get_header(); ?>
<style>
#event_tribe_organizer table tr:nth-child(4){display:none;}
#event_datepickers table tr:nth-child(3){display:none;}
.sharify-btn-twitter .sharify-icon {    padding-top: 8px;}
.sharify-btn-facebook .sharify-icon {     padding-top: 8px;}
#tribe-community-events .tribe-events-community-footer, #tribe-community-events p.login-submit {
     text-align: left !important; 
}
[type=checkbox]:checked, [type=checkbox]:not(:checked) {
    position: initial!important;
    left: 0px!important;
    opacity: 1!important;
}

</style>
<div class="fl-content-full">
	<div class="row content-row">
		<div class="col-md-12">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				<article <?php post_class( 'fl-post' ); ?> id="fl-post-<?php the_ID(); ?>">

	<?php if ( FLTheme::show_post_header() ) : ?>
	<header class="fl-post-header hfn-page-header padd-tb50" style="margin:0px;">
        <div class="container">
            <h1 class="fl-post-title text-center">Events</h1>
        </div>
	</header>
                    <!-- .fl-post-header -->
	<?php endif; ?>

	<div class="fl-post-content clearfix padd-tb30">
	   <div class="container hfn-container">
		<?php
			the_content();
			wp_link_pages( array(
				'before'         => '<div class="fl-post-page-nav">' . _x( 'Pages:', 'Text before page links on paginated post.', 'fl-automator' ),
				'after'          => '</div>',
				'next_or_number' => 'number'
			) );
		?>
        <p class="post-calendar-events text-center">To have your event featured on the website, please <a href="<?php echo get_bloginfo('url'); ?>/events/submit-your-events/add">complete the form</a>.</p>
       
	   </div>
    </div><!-- .fl-post-content -->

	<?php comments_template(); ?>

</article>
<!-- .fl-post -->
			<?php endwhile; endif; ?>
		</div>
	</div>
</div>
 
<?php get_footer(); ?>
