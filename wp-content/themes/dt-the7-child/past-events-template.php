<?php /* Template Name: Past Events  */  ?>
<?php get_header(); ?>
 <style>
.row1{
background:#f5f5f5;
}
.row2{
background:#f5f5f5;
margin-bottom:15px;
padding:15px;
}
.row2 .recurringinfo{
	border: 1px solid;
    padding: 6px;
    cursor: pointer;
}
.head_style{
font-size: 18px;
font-weight: bold;
margin:0px;
}
.date_custm{
	text-align:center;
	
}
.tribe-event-date-start ,.tribe-event-time, .tribe-events-divider{
	display:none !important;
}
.centered {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.day_style{
	font-weight: bold;
    font-size: 60px;
    font-family: sans-serif;
    
}
.month_style{
	position: relative;
    top: -10px;
	font-family: sans-serif;
    font-size: 22px;
    font-weight: normal;
}

</style>
 <div class="fl-content-full"> 
 <div class="row content-row"> 
 <div class="fl-content">
 <article <?php post_class( 'fl-post' ); ?> id="fl-post-<?php the_ID(); ?>">
	<?php if ( FLTheme::show_post_header() ) : ?>
	<header class="fl-post-header hfn-page-header padd-tb50" style="margin:0px;">
        <div class="container">
            <h1 class="fl-post-title text-center" itemprop="headline"><?php the_title(); ?></h1>
        </div>
	</header>
    <!-- Heartfulness Post Header -->
	<?php endif; ?>
 <?php

global $post;
$organizer = tribe_get_organizer();
$phone = tribe_get_organizer_phone();
$email = tribe_get_organizer_email();
?>
  <div class="fl-post-content clearfix padd-tb30">
		   <div class="container hfn-container">
		   <?php
$get_posts = tribe_get_events(array('posts_per_page'=>-1, 'eventDisplay'=>'past') );
foreach($get_posts as $post) { setup_postdata($post);
        ?>  
<div class="row row1">
<div class="col-md-12">
<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>
<h2 class="tribe-events-list-event-title" style="padding:15px;">
	<a style="color:#545454;font-size:20px;" class="tribe-event-url cst_title" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
		<?php the_title(); ?>
	</a>
</h2>
<?php do_action( 'tribe_events_after_the_meta' ) ?> 

</div>

</div>

<div class="row row2">
<!-- Map Image -->
<div class="col-md-3 date_custm ss">
<img src="http://staging.heartfulness.org/us/wp-content/uploads/2018/01/date-bg.png" style="width:325px;">

<?php  
 //echo $start_dat = tribe_get_start_date($post, $display_time = false, $date_format); 
 echo "<div style='font-size:30px;font-weight:bold;' class='centered'>";
  echo  '<div class="day_style">'.tribe_get_start_date($post, $display_time = false, $date_format ='d').'</div>'; 
   echo  '<div class="month_style">'.tribe_get_start_date($post, $display_time = false, $date_format ='F').'</div>'; 
  echo "</div>";
  //echo "<br/>";
   //echo "<div style='font-size:30px;class='centered''>";
 
  //echo "</div>";
 ?>
</div>
<div class="col-md-8">
<div>
<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

		<?php if ( tribe_address_exists() ) : ?>
			<div class="tribe-venue-location">
			<h3 class="head_style">Venue</h3>
				<address class="tribe-events-address cst_addrs">
				<?php echo tribe_get_venue() ?> 
					<?php echo tribe_get_full_address(); ?>
					<?php if ( tribe_show_google_map_link() ) : ?>
						<?php echo tribe_get_map_link_html(); ?>
					<?php endif; ?>
				</address>
		<div>
		<?php endif; ?>		

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>

<?php do_action( 'tribe_events_after_the_meta' ) ?> 
</div>
<div>
<h3 class="head_style" >Time</h3>
<p><?php echo tribe_get_start_date($post, false, $format = 'g:i A' ) . '—' . tribe_get_end_date($post, false, $format = 'g:i A'); ?></p><br/>

</div>
<!-- Contact -->
<!--<div>
<h3 class="head_style">Contact</h3>
<div class="cst_addrs"><?php //echo $organizer; ?> - <?php  //echo $email; ?> - Phone No: <?php  //echo $phone; ?></div>-->

<?php
			 $eve_url = tribe_get_event_link();
			$eve_id = url_to_postid(eve_url);
		    $event_id = get_the_ID();
			//echo tribe_get_upcoming_recurring_event_id_from_url ($eve_url);
			$start_dat = tribe_get_start_date($post, $display_time = false, $date_format ='Y-m-d') ;
			
			
			?>
	
<!--</div>-->

<div>
	<a href="<?php echo esc_url( tribe_get_event_link() ); ?>#buy-tickets" class="tribe-events-read-more" style="padding:10px;text-decoration:none !important;border:1px solid;" rel="bookmark" >Know more</a>

</div>
  


</div>

</div>
</div>
</div>  		
	<?php } //endforeach ?>
    <?php wp_reset_query(); ?>
</div>
</div>
 </div>
 
 </div>
 </div>
 
 <?php get_footer(); ?>

