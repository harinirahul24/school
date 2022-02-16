<?php /* Template Name: User Profile */ 

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

presscore_config()->set( 'template', 'innerpage' );
get_header(); 
$userInfo	=	wp_get_current_user();


if($userInfo->data->ID == 44){
	
	if($userInfo->data->ID != 0){
			
	$s1name 	=	get_user_meta($userInfo->data->ID, "s2name", true);
	$userMeta	=	get_user_meta( $userInfo->data->ID );
	$amount 	=	get_user_meta($userInfo->data->ID, "amount2", true);
	$class 		=	get_user_meta($userInfo->data->ID, "class2", true);
	$entryid 	=	get_user_meta($userInfo->data->ID, "entryid", true);
	$contact 	=	get_user_meta($userInfo->data->ID, "contact", true);
	$hsds   	=	get_user_meta($userInfo->data->ID, "hsds2", true);
	
	/*Student 2 Details*/
	$s2name 	=	'';
	$amount2 	=	'';
	$class2 	=	'';
	$hsds2 	    =	'';

}else{
	$url = get_site_url()."/wp-login.php/";
	wp_redirect( $url );
}


global $wpdb;
$query_entryid = "select entry_id from hos_gf_entry_meta where meta_key=13 and meta_value=".$userInfo->data->ID." ORDER BY entry_id DESC";
$result_entryid = $wpdb->get_results($query_entryid);
/*print_r($result_entryid);
exit;*/

if(isset($result_entryid[0])){
	$entryid = $result_entryid[0]->entry_id;
	$query = "select count(*) as count from hos_gf_entry_notes where entry_id=".$entryid." and note_type='success'ORDER BY entry_id DESC";
	$result = $wpdb->get_results($query);
	if(isset($result[0]) && $result[0]->count > 1){ 
		$url = get_site_url()."/profile-information/";
		wp_redirect( $url );
	} 
}
	
}


else{

if($userInfo->data->ID != 0){
	$s1name 	=	get_user_meta($userInfo->data->ID, "s1name", true);
	$userMeta	=	get_user_meta( $userInfo->data->ID );
	$amount 	=	get_user_meta($userInfo->data->ID, "amount", true);
	$class 		=	get_user_meta($userInfo->data->ID, "class", true);
	$entryid 	=	get_user_meta($userInfo->data->ID, "entryid", true);
	$contact 	=	get_user_meta($userInfo->data->ID, "contact", true);
	$hsds 	=	get_user_meta($userInfo->data->ID, "hsds", true);
	
	/*Student 2 Details*/
	$s2name 	=	get_user_meta($userInfo->data->ID, "s2name", true);
	$amount2 	=	get_user_meta($userInfo->data->ID, "amount2", true);
	$class2 	=	get_user_meta($userInfo->data->ID, "class2", true);
	$hsds2   	=	get_user_meta($userInfo->data->ID, "hsds2", true);

}else{
	$url = get_site_url()."/wp-login.php/";
	wp_redirect( $url );
}


global $wpdb;
 $query_entryid = "select entry_id from hos_gf_entry_meta where meta_key=13 and meta_value=".$userInfo->data->ID." ORDER BY entry_id DESC";
$result_entryid = $wpdb->get_results($query_entryid);

if(isset($result_entryid[0])){
	$entryid = $result_entryid[0]->entry_id;
	$query = "select count(*) as count from hos_gf_entry_notes where entry_id=".$entryid." and note_type='success'ORDER BY entry_id DESC";
	$result = $wpdb->get_results($query);
		//print_r($result);
	if(isset($result[0]) && $result[0]->count > 0){ 
		$url = get_site_url()."/profile-information/";
		wp_redirect( $url );
	} 
}

}

?>

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
						
						<input type="hidden" name="camount" id="camount" value="<?php echo $amount; ?>">
						<input type="hidden" name="camount2" id="camount2" value="<?php echo $amount2; ?>">
						<input type="hidden" name="centryid" id="centryid" value="<?php echo $entryid; ?>">
						<input type="hidden" name="cclass" id="cclass" value="<?php echo $class; ?>">						
						<input type="hidden" name="cclass2" id="cclass2" value="<?php echo $class2; ?>">						
						<input type="hidden" name="cname" id="cname" value="<?php echo $s1name; ?>">						
						<input type="hidden" name="cname2" id="cname2" value="<?php echo $s2name; ?>">						
						<input type="hidden" name="cemail" id="cemail" value="<?php echo $userInfo->data->user_email; ?>">						
						<input type="hidden" name="ccontact" id="ccontact" value="<?php echo $contact; ?>">						
						<input type="hidden" name="cuserid" id="cuserid" value="<?php echo $userInfo->data->ID; ?>">						
						<input type="hidden" name="chsds" id="chsds" value="<?php echo $hsds; ?>">						
						<input type="hidden" name="chsds2" id="chsds2" value="<?php echo $hsds2; ?>">						

                        </div><!-- #content -->

                        <?php do_action('presscore_after_content'); ?>

                <?php endif; // if content visible ?>
<?php get_footer(); ?>