<?php /* Template Name: Profile Information */ 

/*error_reporting(1);
ini_set('display_errors', 1);*/

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

presscore_config()->set( 'template', 'innerpage' );
get_header(); 
$userInfo	=	wp_get_current_user();


if($userInfo->data->ID != 0){
	$userMeta	=	get_user_meta( $userInfo->data->ID );
	$amount 	=	get_user_meta($userInfo->data->ID, "amount", true);
	$class 		=	get_user_meta($userInfo->data->ID, "class", true);
	$entryid 	=	get_user_meta($userInfo->data->ID, "entryid", true);
	$contact 	=	get_user_meta($userInfo->data->ID, "contact", true);
	
	
	/* Getting Student 2 Details */

	$s2name 	=	get_user_meta($userInfo->data->ID, "s2name", true);
	$amount2 	=	get_user_meta($userInfo->data->ID, "amount2", true);
	$class2 	=	get_user_meta($userInfo->data->ID, "class2", true);
	$hsds2  	=	get_user_meta($userInfo->data->ID, "hsds2", true);
	
	
}else{
	$url = get_site_url()."/wp-login.php/";
	wp_redirect( $url );
}


global $wpdb;


$query_entryid = "select entry_id from  hos_gf_entry_meta where meta_key=13 and meta_value=".$userInfo->data->ID;
$result_entryid = $wpdb->get_results($query_entryid);
$entryid = $result_entryid[0]->entry_id;
$entryid2 = $result_entryid[1]->entry_id;



$query_enrolment_id = "select meta_value from  hos_gf_entry_meta where meta_key=12 and entry_id=".$entryid;
$result_enrolment_id = $wpdb->get_results($query_enrolment_id);
$enrolment_id = $result_enrolment_id[0]->meta_value;


if($userInfo->data->ID == 44){
$query_enrolment_id_2 = "select meta_value from  hos_gf_entry_meta where meta_key=12 and entry_id=".$entryid2;
$result_enrolment_id_2 = $wpdb->get_results($query_enrolment_id_2);
$enrolment_id_2 = $result_enrolment_id_2[0]->meta_value;

$query2 = "select * from hos_gf_entry_notes where entry_id=".$entryid2." and note_type='success'";
$result2 = $wpdb->get_results($query2);
}else{

/*Getting Student 2 Enrollment ID*/
$query_enrolment_id_2 = "select meta_value from  hos_gf_entry_meta where meta_key=17 and entry_id=".$entryid;
$result_enrolment_id_2 = $wpdb->get_results($query_enrolment_id_2);
$enrolment_id_2 = $result_enrolment_id_2[0]->meta_value;

}


$query = "select * from hos_gf_entry_notes where entry_id=".$entryid." and note_type='success'";
$result = $wpdb->get_results($query);

/* echo '<pre>';
print_r($query);
print_r($result);
exit; */

?>

                <?php if ( presscore_is_content_visible() ): ?>

                        <div id="content" class="content" role="main">

                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                        <?php do_action('presscore_before_loop'); ?>

                                        <?php the_content(); ?>
										<div class="container">
										
											<div class="form-row" style="display: block;">
											<div class="alert alert-success" role="alert">
											<div class="col">
											<strong>Email: </strong> <?php echo $userInfo->data->user_email; ?><br/>	
											<strong>Contact No: </strong>  <?php echo $contact; ?><br/>
											<strong>Payment Details: </strong>  <?php echo $result[0]->value; ?><br/>
											<?php if(!empty($result2)) {
												
												 echo '<strong>Payment Details: </strong> '.$result2[0]->value.'<br/>';
											}
												?>
											
											
											<strong>Date: </strong>  <?php echo $result[0]->date_created; ?>
											</div>
											</div>
											</div>
											
											<div>
											<strong>Student Payment Details: </strong>
											<div class="form-row" style="background:#e5e5e5;">	
											<div class="col-sm-2"></div>
											<div class="col-sm-4">
											<strong>Your Enrolment ID: </strong>  <?php echo $enrolment_id; ?>
											</div>
											<div class="col-sm-4">
											<strong>Name: </strong>  <?php echo $userInfo->data->display_name; ?>
											</div>
											<div class="col-sm-2"></div>
											</div>
											
											<div class="form-row" style="background:#e5e5e5;">	
											<div class="col-sm-2"></div>
											<div class="col-sm-4">
										    <strong>Amount: </strong>  <?php echo $amount; ?>
											</div>
										     <div class="col-sm-4">
											<strong>Class: </strong>  <?php echo $class; ?>
											</div>
											<div class="col-sm-2"></div>
											</div>
											 <div class="form-row" style="background:#e5e5e5;">	
											<div class="col-sm-2"></div>
											</div>
											</div>
											</div>
											</div>
												<?php 
											
											if(!empty($s2name) ){
											
											?>
											<div class="container">
											
										
											<div>
											<strong>Student 2 Payment Details: </strong>
											<div class="form-row" style="background:#e5e5e5;">	
											<div class="col-sm-2"></div>
											<div class="col-sm-4">
											<strong>Your Enrolment ID: </strong>  <?php echo $enrolment_id_2; ?>
											</div>
											<div class="col-sm-4">
											<strong>Name: </strong>  <?php echo $s2name; ?>
											</div>
											<div class="col-sm-2"></div>
											</div>
											
											<div class="form-row" style="background:#e5e5e5;">	
											<div class="col-sm-2"></div>
											<div class="col-sm-4">
										    <strong>Amount: </strong>  <?php echo $amount2; ?>
											</div>
											<div class="col-sm-4">
											<strong>Class: </strong>  <?php echo $class2; ?>
											</div>
											<div class="col-sm-2"></div>
											</div>
							                <div class="form-row" style="background:#e5e5e5;">	
											<div class="col-sm-2"></div>
											</div>
											</div>
											</div>
											<?php } ?>
									
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