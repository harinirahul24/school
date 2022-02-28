<?php
require get_theme_file_path('/inc/google-sheets/vendor/autoload.php');

function register_customfonts_style() {
wp_enqueue_style( 'wpb-gotham-fonts', get_stylesheet_directory_uri() . '/css/gotham.css' );
    wp_enqueue_style( 'wpb-custom-css', get_stylesheet_directory_uri() . '/css/heartfulness.css' );
        wp_enqueue_style( 'wpb-custom', get_stylesheet_directory_uri() . '/lib/font-style.css' );
wp_enqueue_style( 'wpb-custom-bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.css' );   
 wp_enqueue_style( 'wpb-custom-material-icon', get_stylesheet_directory_uri() . '/lib/material-icons/material-icon-style.css' );
      wp_enqueue_script( 'wpb-custom-js', get_stylesheet_directory_uri() . '/js/isotope.pkgd.min.js' );
    wp_enqueue_script( 'hfn-custom-js', get_stylesheet_directory_uri() . '/js/heartfulness.js' );
    wp_enqueue_script( 'lib-custom-js', get_stylesheet_directory_uri() . '/js/TweenMax.min.js' );
}
add_action( 'wp_enqueue_scripts', 'register_customfonts_style' );
// remove wp version param from any enqueued scripts
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
function add_custom_font( $fonts ) {
 
$fonts['gotham-light'] = 'Gotham Light';
$fonts['gotham-book'] = 'Gotham Book';
$fonts['gotham-medium'] = 'Gotham Medium';
$fonts['gotham-bold'] = 'Gotham Bold';
$fonts['gotham-xlight'] = 'Gotham xLight';
$fonts['gotham-black'] = 'Gotham Black';
$fonts['gotham-xlightitalic'] = 'Gotham xLight Italic';
$fonts['gotham-thinitalic'] = 'Gotham Thin Italic';
$fonts['gotham-bookitalic'] = 'Gotham Book Italic';
$fonts['gotham-lightitalic'] = 'Gotham Light Italic';

return $fonts;
}
 
add_filter( 'presscore_options_get_safe_fonts', 'add_custom_font' ,30 , 1 );
	function presscore_render_header_elements( $field_name, $class = '' ) {
		$field_elements = presscore_get_header_elements_list( $field_name );

		if ( $field_elements ) {
			$classes = presscore_split_classes( $class );
			$classes[] = 'mini-widgets';

			// wrap open
			echo '<div class="' . implode( ' ', presscore_sanitize_classes( $classes ) ) . '">';

			// render elements
			foreach ( $field_elements as $element ) {
				switch ( $element ) {
					case 'search':
						presscore_top_bar_search_element();
						break;
					case 'social_icons':
						echo presscore_get_topbar_social_icons();
						break;
					case 'custom_menu':
						presscore_top_bar_menu_element();
						break;
					case 'menu2':
						presscore_top_bar_menu2_element();
						break;
					case 'login':
						pressocore_render_login_form();
						break;
					case 'text_area':
	$top_text = of_get_option('header-elements-text', '');
	$top_text = apply_filters('the_content', $top_text ); //additional line
	if ( $top_text ) {
		echo '<div class="text-area">' . wpautop($top_text) . '</div>';
	}
	break;
					case 'text2_area':
						presscore_top_bar_text_element( 'header-elements-text-2' );
						break;
					case 'text3_area':
						presscore_top_bar_text_element( 'header-elements-text-3' );
						break;
					case 'text4_area':
						presscore_top_bar_text_element( 'header-elements-text-4' );
						break;
					case 'text5_area':
						presscore_top_bar_text_element( 'header-elements-text-5' );
						break;
					case 'skype':
						presscore_top_bar_contact_element('skype');
						break;
					case 'email':
						presscore_top_bar_contact_element('email');
						break;
					case 'address':
						presscore_top_bar_contact_element('address');
						break;
					case 'phone':
						presscore_top_bar_contact_element('phone');
						break;
					case 'working_hours':
						presscore_top_bar_contact_element('clock');
						break;
					case 'info':
						presscore_top_bar_contact_element('info');
						break;
				}

				do_action( "presscore_render_header_element-{$element}" );
			}

			// wrap close
			echo '</div>';
		}
	}



/*Custom Footer Widget Block*/
if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Social Widget Area',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

/* Custom: Generate Enrollment ID*/
add_action( 'gform_post_payment_completed', 'access_entry_via_field', 10, 2 );
function access_entry_via_field( $entry, $action ) {
	
 

			if($entry['form_id'] == 8){
					//payment_status
					if($entry['payment_status'] == 'Paid'){

					$file 		= 'count.txt';
					$uniq 		= file_get_contents($file);
					$unique 	= $uniq + 1 ;
					$meta_value = "HLC" . $unique;	
					file_put_contents($file, $unique);
					gform_update_meta( $entry['id'], 12 , $meta_value );

						if(!empty(trim($entry[16]," "))){
						$file 		= 'count.txt';
						$uniq 		= file_get_contents($file);
						$unique 	= $uniq + 1 ;
						$meta_value = "HLC" . $unique;	
						file_put_contents($file, $unique);
						gform_update_meta( $entry['id'], 17 , $meta_value );
						}


					}
			}
}

add_action( 'gform_after_submission', 'update_google_sheet', 10, 2 );

function update_google_sheet($entry, $form)
{
	 
	 if($form['title'] == 'Work with us'){
	//Reading data from spreadsheet.
	$client = new \Google_Client();
	$client->setApplicationName('Google Sheets and PHP');
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(get_theme_file_path('/inc/google-sheets/credential.json'));
	$service = new Google_Service_Sheets($client);
	global $post;
	$spreadsheetId = get_post_meta($post->ID, "spreadsheet-id", true); //It is present in your URL
    $update_value = array();
	foreach ( $form['fields'] as $field ) {
	$body = new Google_Service_Sheets_ValueRange([
		  'values' => $update_value
		]);
		
		if($field->label!=''){

		if($entry[$field->id]!=''){

   		$update_value[0][] = $entry[$field->id];

   	}else{

   		$update_value[0][] = '';

   	}
   	}
   	}
    $update_value[0][] = date('Y-m-d H:i');
    $update_range = "Sheet1!A2:DM2"; 
	$body = new Google_Service_Sheets_ValueRange([
		  'values' => $update_value
		]);
	$params = ['valueInputOption' => 'RAW'];
	$insert = ["insertDataOption" => "INSERT_ROWS"];
	$update_sheet = $service->spreadsheets_values->append($spreadsheetId, $update_range, $body, $params,$insert);
}
}
function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Google Spreadsheet", "custom_meta_box_markup", "page", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function custom_meta_box_markup($object)
{

    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <label for="spreadsheet-id">ID</label>
            <input name="spreadsheet-id" type="text" value="<?php echo get_post_meta($object->ID, "spreadsheet-id", true); ?>">

            <br>
        </div>
    <?php  
}

function save_custom_meta_box($post_id, $post, $update)
{
	
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "page";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    

    if(isset($_POST["spreadsheet-id"]))
    {
        $meta_box_text_value = $_POST["spreadsheet-id"];
    }   
    update_post_meta($post_id, "spreadsheet-id", $meta_box_text_value);
}

add_action("save_post", "save_custom_meta_box", 10, 3);

