<?php
require get_stylesheet_directory().'/inc/google-sheets/vendor/autoload.php';

if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'School Tour Settings',
        'menu_title'    => 'School Tour Settings',
        'menu_slug'     => 'school-tour-settings',
        'redirect'      => false
    ));
}

add_action('wp_ajax_save_stour_submission', 'save_stour_submission');
add_action('wp_ajax_nopriv_save_stour_submission', 'save_stour_submission');

function save_stour_submission()
{
	
	$prefix = 'stour_';
	$post_title = esc_html($_POST['parent_name']);
	$sheet_id = get_field('google_sheet_id', 'option');
	$sheet_range = get_field('google_sheet_name', 'option');
	$col_index = get_field('google_sheet_column_index', 'option');
	
	if($_POST['sub_id'] != ''){
		//Update Submissions		
		$id = $_POST['sub_id'];
		foreach($_POST as $key => $value){
			update_post_meta($id, $prefix.$key, $value);		
		}
		
		$parent_name = get_post_meta($id, $prefix.'parent_name', true);
		$email = get_post_meta($id, $prefix.'email', true);
		$phone = get_post_meta($id, $prefix.'phone', true);
		$student_name = get_post_meta($id, $prefix.'student_name', true);
		$standard = get_post_meta($id, $prefix.'standard', true);
		$address = get_post_meta($id, $prefix.'address', true);
		$date = get_post_meta($id, $prefix.'date', true);
		$slot = get_post_meta($id, $prefix.'slot', true);
		$transport = get_post_meta($id, $prefix.'transport', true);
		$count = get_post_meta($id, $prefix.'count', true);
		$vehicle_number = get_post_meta($id, $prefix.'vehicle_number', true);
		$updated_date = date("d/m/Y H:i");
		//Remove updatekey on one-time update
		update_post_meta($id, 'update_key','');
		
		$dataForSheet = array();
		$dataForSheet[] = array($parent_name, $email, $phone, $student_name, $standard, $address, $date, $slot, $transport, $count, $vehicle_number, $updated_date);
		
		//var_dump($dataForSheet);exit;
		
		$row_index = get_google_sheet_row_index($id, $sheet_id, $sheet_name);
		update_sheet($dataForSheet, $row_index, $col_index, $sheet_id, $sheet_range);
		$mail_content = get_field('update_mail_content', 'option');	
		send_email($id,$mail_content);
		echo true;
	}else{
		//Save Submissions			
		$meta_input = array();
		foreach($_POST as $key => $value){
			$meta_input[$prefix.$key] = $value;		
		}
		
		$post_arr = array(
			'post_title'   => $post_title,
			'post_status'  => 'publish',
			'post_type'    => 'stour_submissions',
			'meta_input'   => $meta_input
		);		
		$id = wp_insert_post($post_arr);
		update_post_meta($id, 'update_key', rand(999999,9999999).$id);
		
		$created_date = date("d/m/Y H:i");
		$parent_name = get_post_meta($id, $prefix.'parent_name', true);
		$email = get_post_meta($id, $prefix.'email', true);
		$phone = get_post_meta($id, $prefix.'phone', true);
		$student_name = get_post_meta($id, $prefix.'student_name', true);
		$standard = get_post_meta($id, $prefix.'standard', true);
		$address = get_post_meta($id, $prefix.'address', true);
		$date = get_post_meta($id, $prefix.'date', true);
		$slot = get_post_meta($id, $prefix.'slot', true);
		$transport = get_post_meta($id, $prefix.'transport', true);
		$count = get_post_meta($id, $prefix.'count', true);
		$vehicle_number = get_post_meta($id, $prefix.'vehicle_number', true);
		$updated_date = date("d/m/Y H:i");
		
		
		
		$dataForSheet = array();
		$dataForSheet[] = array($id, $created_date, $parent_name, $email, $phone, $student_name, $standard, $address, $date, $slot, $transport, $count, $vehicle_number, $updated_date);
				
		append_sheet($dataForSheet, $sheet_id, $sheet_range);
		$mail_content = get_field('mail_content', 'option');	
		send_email($id,$mail_content);
		echo true;
	}	
	echo false;
	exit;
}

function send_email($id,$mail_content)
{
	
	$prefix = 'stour_';
	$date = get_post_meta($id, $prefix.'date', true);
	$slot = get_post_meta($id, $prefix.'slot', true);
	$email = get_post_meta($id, $prefix.'email', true);
	$transport = get_post_meta($id, $prefix.'transport', true);
	$count = get_post_meta($id, $prefix.'count', true);
	$vehicle_number = get_post_meta($id, $prefix.'vehicle_number', true);
	$updated_date = date("d/m/Y H:i");
	$updateKey = get_post_meta($id,'update_key', true);
	
	$update_link = get_field('update_link', 'option');
	$update_link = str_replace("[ID]",urlencode($updateKey), $update_link);
	
	$subject = get_field('subject', 'option');
	$from_name = get_field('from_name', 'option');
	$from_email = get_field('from_email', 'option');

	$headers = array('Content-Type: text/html; charset=UTF-8');
	$headers[]= "From: ".$from_name." <".$from_email.">";

	// Mail Template 

	$mail_content = str_replace("[STOUR_DATE]",$date, $mail_content);
	$mail_content = str_replace("[STOUR_SLOT]",$slot, $mail_content);
	$mail_content = str_replace("[STOUR_ID]",$id, $mail_content);
	$mail_content = str_replace("[UPDATE_LINK]",$update_link, $mail_content);
	
	wp_mail($email,$subject,$mail_content,$headers);
	return true;
	
}


add_action('wp_ajax_get_submission_count', 'get_submission_count');
add_action('wp_ajax_nopriv_get_submission_count', 'get_submission_count');

function get_submission_count(){
	
	$p_type = 'stour_submissions';
	$prefix = 'stour_';
	$date = $_POST['date'];
	$slot = $_POST['slot'];
    //check for slot exist
    
    if($_POST['sub_id'] != ''){
        //Update Submissions        
    $id = $_POST['sub_id'];
    $bk_date = get_post_meta($id, $prefix.'date', true);
    $bk_slot = get_post_meta($id, $prefix.'slot', true);

    if(($date == $bk_date) && ($slot == $bk_slot))      
    {   echo "booked";
        die(0); 
    }else{
        $args = array( 'post_type' => $p_type,
	'post_status' => 'publish',	
	'posts_per_page' => -1,	
	'orderby' => 'id',
	'order'	=> 'DESC',
	'meta_query' => array(
		'relation' => 'AND',				
		array(
			'key' => $prefix.'date',
			'value' => $date,
			'compare' => '='
		),
		array(
			'key' => $prefix.'slot',
			'value' => $slot,
			'compare' => '='
		)
	)
	);

	$the_query = new WP_Query( $args ); 
	if ( $the_query->have_posts() ){
		echo $the_query->found_posts;		
	}else{
		echo '0';
	}
	
    die(0);
    }
      }

    //check for slot exist
	$args = array( 'post_type' => $p_type,
	'post_status' => 'publish',	
	'posts_per_page' => -1,	
	'orderby' => 'id',
	'order'	=> 'DESC',
	'meta_query' => array(
		'relation' => 'AND',				
		array(
			'key' => $prefix.'date',
			'value' => $date,
			'compare' => '='
		),
		array(
			'key' => $prefix.'slot',
			'value' => $slot,
			'compare' => '='
		)
	)
	);

	$the_query = new WP_Query( $args ); 
	if ( $the_query->have_posts() ){
		echo $the_query->found_posts;		
	}else{
		echo '0';
	}
	
    die(0);

}

add_action('wp_ajax_get_stour_submission', 'get_stour_submission');
add_action('wp_ajax_nopriv_get_stour_submission', 'get_stour_submission');

function get_stour_submission(){
	
	$p_type = 'stour_submissions';
	$update_key = $_POST['update_key'];
	
	$args = array( 'post_type' => $p_type,
	'post_status' => 'publish',	
	'posts_per_page' => -1,	
	'orderby' => 'id',
	'order'	=> 'DESC',
	'meta_query' => array(
		'relation' => 'AND',				
		array(
			'key' => 'update_key',
			'value' => $update_key,
			'compare' => '='
		)
	)
	);
	$the_query = new WP_Query( $args ); 
	
	if ( $the_query->have_posts() ){
		while ( $the_query->have_posts() ){
			$the_query->the_post();
			$stour_fields = get_post_meta(get_the_ID());
			$stour_fields['id']=get_the_ID();
			echo json_encode($stour_fields);
			die(0);
		}
	}else{
		echo false;
	}
	
    die(0);

}


function append_sheet($post_data, $sheet_id, $sheet_name){
	//echo '<pre>';print_r($post_data);exit;
	$client = new \Google_Client();
	$client->setApplicationName('Google Sheets and PHP');
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(get_stylesheet_directory().'/inc/google-sheets/credential.json');
	$service = new Google_Service_Sheets($client);
	$spreadsheetId = $sheet_id;	
	$update_range = $sheet_name; 	
    $rangeForExcel = "A:A";
	$body = new Google_Service_Sheets_ValueRange([
	      'range' => $rangeForExcel,
		  'values' => $post_data
		]);	
	$params = ['valueInputOption' => 'RAW', 'insertDataOption' => 'INSERT_ROWS'];
	$update_sheet = $service->spreadsheets_values->append($spreadsheetId, $rangeForExcel, $body, $params);
	
}

function update_sheet($post_data, $row_index, $col_index, $sheet_id, $sheet_range){
	$client = new \Google_Client();
	$client->setApplicationName('Google Sheets and PHP');
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(get_stylesheet_directory().'/inc/google-sheets/credential.json');	
	$service = new Google_Service_Sheets($client);

	$spreadsheetId = $sheet_id;	
	$update_range = $sheet_range.'!'.$col_index.$row_index.':N'.$row_index; 
	$body = new Google_Service_Sheets_ValueRange([
		 'values' => $post_data
	]);	
	$params = ['valueInputOption' => 'RAW'];
	$update_sheet = $service->spreadsheets_values->update($spreadsheetId, $update_range, $body, $params);
	
}


function get_google_sheet_row_index($sub_id, $sheet_id, $sheet_name)
{
	$client = new \Google_Client();
	$client->setApplicationName('Google Sheets and PHP');
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(get_stylesheet_directory().'/inc/google-sheets/credential.json');
	
	$service = new Google_Service_Sheets($client);
	$spreadsheetId = $sheet_id;	
	$update_range =  $sheet_name.'!A:A'; 	
	$response = $service->spreadsheets_values->get($spreadsheetId, $update_range);	
	$col = array();	
	foreach($response->values as $val){
		$col[] =  $val[0];
	}
	$ind = array_search($sub_id, $col);
	return $ind+1;
}

?>
