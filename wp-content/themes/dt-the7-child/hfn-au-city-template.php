<?php /* Template Name: Heartfulness AU City Template */ ?>
<?php get_header(); ?>

<?php
$home_url = home_url();
$home_url1 = substr($home_url,0,strlen($home_url)-2);
if(strstr($home_url,"staging"))
$mode = "dev";
else
$mode = "production";

/* echo $id = get_query_var('name');
echo $id = get_query_var('id');
echo $filter_id = get_query_var('filter_id');
exit; */
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
/* if(isset($_GET["ip"]))
{
$data = ip2location_get_vars($_GET["ip"]);
$data = json_decode($data);
$clientlocation=$data->cityName.", ".$data->regionName.", ".$data->countryName;
$loc_name = $data->cityName;


$lat=$data->latitude;
$lng=$data->longitude;
$countryCode=$data->countryCode;

}else */
//echo '<br><br><br><br><br>';
$no_cookie =0;
if (isset($GLOBALS['lat']) && isset($GLOBALS['lng']) && isset($GLOBALS['city']) && isset($GLOBALS['state'])) {
  # code...
   $country = "Australia";
    $city_name = str_replace('-',' ',$GLOBALS['city']);
   $clientlocation=$city_name.", ".$GLOBALS['state'].", ".$country;
   $loc_name = $city_name;
   $lat=$GLOBALS['lat'];
  $lng=$GLOBALS['lng'];

  setcookie('user_preference_city', $city_name, time() + (86400 * 90), "/");
  setcookie('user_preference_state', $GLOBALS['state'], time() + (86400 * 90), "/");
  setcookie('user_preference_country', $country, time() + (86400 * 90), "/");
  setcookie('user_preference_lat', $lat, time() + (86400 * 90), "/");
  setcookie('user_preference_lng', $lng, time() + (86400 * 90), "/");

}elseif(isset($_COOKIE['user_preference_city']) && isset($_COOKIE['user_preference_state']) && isset($_COOKIE['user_preference_country']) && isset($_COOKIE['user_preference_lat']) && isset($_COOKIE['user_preference_lng'])){

  $clientlocation=$_COOKIE['user_preference_city'].", ".$_COOKIE['user_preference_state'].", ".$_COOKIE['user_preference_country'];
  $loc_name = $_COOKIE['user_preference_city'];
  $lat=$_COOKIE['user_preference_lat'];
  $lng=$_COOKIE['user_preference_lng'];

}else{
$no_cookie =1;


}
// $clientlocation=$_COOKIE['user_preference_city'].", ".$_COOKIE['user_preference_state'].", ".$_COOKIE['user_preference_country'];
 // echo '<br><br><br>cookie'.$clientlocation;
 // exit;

/* else{
//$data = ip2location_get_vars();
//$data = json_decode($data);
//$clientlocation=$data->cityName.", ".$data->regionName.", ".$data->countryName;
//$loc_name = $data->cityName;
//$data = ip2location_get_vars();
$data = RulesEngineCommon::get_geo_loction_details();
$clientlocation=$data->city.", ".$data->region.", ".$data->countryName;
$loc_name = $data->city;
$lat=$data->latitude;
$lng=$data->longitude;
$countryCode=$data->countryCode;
} */



 //if(empty($loc_name) || $loc_name== "-"){
 //wp_redirect( home_url() ); exit;
 //	}

	$banner ='';
	$notext = 0;
	$registerlink = '';
	$title = '';
	$desc = '';
	$buttontext = '';
	//$file = fopen("/srv/web/wpapp01/wp-content/themes/bb-theme-child/my-templates/location/us_location.csv","r");

	$file = fopen("https://docs.google.com/spreadsheets/d/e/2PACX-1vQP1gEeJChEG8hD9gkoFC8Nvghu1InMBUy7Ufu4GfGp0EO_Wcvf7pz8aQbNBM2n3oci_Z9_SWBfYKoh/pub?gid=0&single=true&output=csv","r");

	//$file = fopen("https://docs.google.com/spreadsheets/d/e/2PACX-1vSUZgUX3HWWw1ti1K3L-tHUw0lWGMUK8R1Z4bpRaafef55cWpMO9hH1Zh5Z5G2IniMIpzYvlomr2w4r/pub?gid=1436924922&single=true&output=csv","r");
	//var_dump($file);
	while(! feof($file))
	  {
		$csv=fgetcsv($file);
		//var_dump($csv);

			if($csv[0] == "Default"){
			  $defaultbanner = $csv[1];
			}


		if($csv[0] == $loc_name)
		{
		$banner = $csv[1];
		$registerlink = $csv[2];
		$title = $csv[3];
		$desc = $csv[4];
		$buttontext = $csv[5];
		$notext = 1;
		 break;
		}

	  }
	fclose($file);

if($notext == 0)
$banner =$defaultbanner;



 ?>
<?php get_header(); ?>
<style type="text/css">

.featured-event-banner{
    background: url(<?php echo $banner; ?>) no-repeat center;
    background-size: cover;
    padding:80px 0px;
	min-height: 370px;
}
.cstm_hs{
color:#fd5a63 !important";cursor:pointer;font-family: gotham-light;font-size: 16px;width:500px;}

</style>
<div class="fl-content-full">
	<div class="row content-row">
		<div class="col-md-12">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				<article <?php post_class( 'fl-post' ); ?> id="fl-post-<?php the_ID(); ?>">

                    <div class="fl-post-content clearfix" itemprop="text">


<!--Html COdes-->
<div class="hfn-city-page-heading">
   <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
         <h1><span class="theme-black">Heartfulness </span><span class="theme-color"><?php echo $loc_name;?></span></h1>
        </div>
        <div class="text-center hfn-citypop-section" style="display:none;">If you are not in right city. Then please click <a class="citypopup">here</a></div>

      </div>
   </div>
</div>
<?php if($notext != 0){?>
<div class="featured-event-banner">

   <div class="container">
      <div class="row">
         <div class="col-md-12">

            <h2 class="featured-event-title"><?php echo $title;?></h2>
            <h3 class="featured-event-intro"><?php echo $desc;?></h3>
			<?php if(!empty($buttontext)) {
            echo '<p class="text-center"><a href="'.$registerlink.'" class="event-register-btn">'.$buttontext.'</a></p>';
			}
			?>
         </div>
      </div>
   </div>

</div>
<?php }else
{?>
<div>
	<a href="<?php echo $registerlink; ?>"><img src="<?php echo $banner; ?>"/></a>
</div>

<?php }?>
<div class="hfn-city-event-block padd-tb50 grey lighten-2">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
             <div class="hfn-city-section-heading">
                <h1 class="theme-black">Heartfulness <span class="theme-color">Calendar</span></h1>
             </div>
         </div>
      </div>
      <div class="col-md-12 padd-tb40">
	   <?php //echo do_shortcode('[custom_carousel]');  ?>
		  <?php
		   global $post;
  		    $today = date('Y-m-d');
			$city = $loc_name;
			/*$near_by_cities = RulesEngineCommon::get_near_by_cities($lat,$lng,50,'mi',20);
			$city_array = array();
			foreach($near_by_cities as $city)
			{
				$city_array[] = $city->city;
			}*/
		    //var_dump($city_array);
			//$city_array = array("Atlanta","Alpharetta","Marietta","Kennesaw");
//'value'     => join(', ', $city_array),
			//exit();
			$query = new WP_Query(
			//$city = get_post_meta( $post->ID, "custom_VenueCity", true);
			//$city = $locname;
			array(
											'post_status'=>'publish',
										    'post_type' => 'tribe_events',
										    'posts_per_page' => 500,

										'meta_query' => array(
														//comparison between the inner meta fields conditionals
														'relation'    => 'AND',
														//meta field condition one
														 array(
															'key'          => '_EventStartDate',
															'value'        => $today,
															'compare'      => '>=',
															'order_by' => '_EventStartDate'
														),

														array(
															'key'          => 'custom_VenueCity',
															'value'        => $city,
															//'value'     => join(', ', $city_array),
															'compare'      => 'IN',

														)),
														));

			//$city = $loc_name;
			$contents = "";
		  ?>
		  <div class="event-carousel">
		<?php
			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) : $query->the_post();
					//if(strtolower(trim(tribe_get_city())) == strtolower(trim($city))){
							$contents .= '<div class="outr_div"><div style="margin-top:25px;">
						<p>'.tribe_event_featured_image( get_the_ID(), 'thumbnail' ).'</p>
							<div><p class="event-title">'. get_the_title() .'</p></div>
							<div  style="margin-top:15px;"><p class="event-data"><i class="fa fa-calendar-plus-o" aria-hidden="true" style="color:#1da1f2;cursor:pointer;"></i>&nbsp;'.tribe_get_start_date(get_the_ID(),true,'d F Y').'</p></div>
							<div class="reg" style="margin-top:15px;"><a href="'.tribe_get_event_link().'">Learn more>> <span style="font-size: 13px;">  </span></a></div>
						</div></div>';
					//}

				endwhile; wp_reset_postdata();
			else :
			endif;
			// return $contents;

			if(!empty($contents)){
				print_r($contents);
			}else{
				print_r("<div class='cstm_hs' style='width:500px !important;'> <span>Sorry no events.<br/><a href='#heartspot_centers'>Click here to know nearby Meditation Sessions</a></span></div>");
			}
		?>
		</div>
		</div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <h6 class="past-event-city-link text-center">For past events <a href="<?php echo $home_url; ?>/past-events" class="link-color">click here</a></h6>
        </div>
    </div>

   </div>
</div>

<div id="heartspot_centers" class="hfn-city-heartspot-block padd-tb50">
   <div class="container">
       <div class="row">
          <div class="col-md-12">
              <div class="hfn-city-section-heading">
                    <h1 class="theme-black">Heartfulness <span class="theme-color"><?php echo $loc_name;?></span></h1>
              </div>
          </div>
       </div>
        <div class="find-your-center">
            <div class="row">
        <div class="col-md-3 first-side-section">
              <div>
                <div class="panel panel-default height-setting">
                    <div class="text-center heartspot-menu-header" id="centerLbl">Meditation Centers - <span id="centers_count"></span></div>
                    <div class="panel-body-trainer text-left">
                    <div class="bh-sl-loc-list"><ul id="center_list" class="list"></ul></div>
                        <!--<div><select id="locationSelect" style="width: 10%; visibility: hidden"></select></div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mapping-section">
            <div>
                <div class="panel panel-default">
                    <div class="panel-body text-center map_padding">
                        <div id="map" class="map  heartspot-city-map"></div>

                    </div>
                </div>
            </div>
        </div>
		<div class="col-md-3 last-side-section">
             <div>
                <div class="panel panel-default height-setting">
                    <div class="text-center heartspot-menu-header" id="trainerLbl">Trainers - <span id="trainers_count"></span></div>
                    <div class="panel-body-trainer text-left">
                    <div class="bh-sl-loc-list"><ul id="trianer_list" class="list"></ul></div>
                        <!--<div><select id="locationSelect" style="width: 10%; visibility: hidden"></select></div>-->
                    </div>
                </div>
            </div>
        </div>

    </div>
	<div class="row">
	<div class="col-md-12">
		<div class="col-md-8">

		<div class="input-field">

		<i class="material-icons prefix">language</i>
         <label for="raddressInput"></label>
         <input type="text" id="addressInput" size="15"/>
		 </div>
		</div>
		<div class="col-md-2">
        <label for="radiusSelect"></label>
        <select id="radiusSelect" label="Radius">

		  <option value="2">200 Miles</option>
		  <option value="1">100 Miles</option>
		  <option value=".50" selected>50 Miles</option>
		  <option value=".30">30 Miles</option>
          <option value=".25">25 Miles</option>
          <option value=".20">20 Miles</option>
          <option value=".15">15 Miles</option>
          <option value=".10">10 Miles</option>
        </select>
		</div>
		<div class="col-md-2">
		<label></label><br/>
        <input type="button" id="searchButton" value="Search"/>
		</div>
    </div>
	</div>
        </div>
    </div>
</div>
<div class="hfn-city-daaji-block padd-tb50 grey lighten-2">
   <div class="container">
     <div class="row">
         <div class="col-md-12">
             <div class="hfn-city-section-heading">
                <h1 class="theme-black">Learn from <span class="theme-color">Daaji</span></h1>
             </div>
         </div>
      </div>
<div class="row padd-b45">
<?php
    $response = wp_remote_get( add_query_arg( array(
	'per_page' => 3 ), 'https://www.daaji.org/wp-json/wp/v2/posts?_embed' ) );

if( !is_wp_error( $response ) && $response['response']['code'] == 200 ) {

	$remote_posts = json_decode( $response['body'] );
    $i=1;
    foreach( $remote_posts as $remote_post ) {
       $post_title = $remote_post->title->rendered;
       $post_img = $remote_post->featured_image_url;
       $post_excerpt = $remote_post->excerpt->rendered;
       $post_link =  $remote_post->link;

       if (($i-1)%3 == 0) {
         echo '<div class="row">';
       }
       echo'<div class="col-md-4 col-sm-4">
              <div class="learn-daaji-container">
                  <div class="learn-daaji-thumb">
                      <a href="' .$post_link.'" target="_blank"><img src="'.$post_img.'"></a>
                  </div>
                  <div class="learn-daaji-content">
                      <h5 class="daaji-post-title"><a href="' .$post_link.'" target="_blank" class="hfn-blog-title-link">' .$post_title.'</a></h5>
                  <div class="learn-daaji-excerpt"><p class="daaji-inshort"> '.$post_excerpt.'</p></div>
                      <div class="learn-daaji-meta">
                          <div class="row">
                              <div class="col-xs-6">
                                  <div class="read-more-block"> <a href="'.$post_link.'" target="_blank" class="link-color">Read More</a></div>
                              </div>
                              <div class="col-xs-6">
                                
                              </div>
                          </div>
                      </div>
                  </div>       
              </div>
          </div>';
        if ($i%3 == 0) {
         echo '</div>';
       }
        $i++;
	}

}
    ?>

      </div>
   </div>
</div>


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
<div id="dialog-messagepop" class="modal" style="overflow: hidden;">
 <form id="connect_trainer_detail"><div class="row"><div class="input-field"><div class="hfn-input-group"><i class="material-icons prefix">account_circle</i><input id="name" name="name" type="text" class="validate"><label for="icon_prefix">Your Name</label><span class="errmsgname error-text red-text"></span></div></div></div><div class="row"><div class="input-field"><div class="hfn-input-group"><i class="material-icons prefix">email</i><input type="email" name="userEmail" id="userEmail" class="validate"><label for="icon_prefix">Email Id</label><span class="errmsgemail error-text red-text"></span></div></div></div><div class="row"><div class="input-field"><div class="hfn-input-group"><i class="material-icons prefix">phone</i><input id="icon_telephone" name="phoneNumber" id="phoneNumber" type="tel" class="validate"><label for="icon_telephone">Your Phone No.(Optional)</label></div></div></div><div class="row"><div class="input-field"><div class="hfn-input-group"> <i class="material-icons prefix">language</i><input id="location" name="location"  type="text" class="validate"><label for="icon_country">Location</label><span class="errmsglocation error-text red-text"></span></div></div></div>
 <script type='text/javascript'>
 var verifyCallback_register = function(response) {
		if(response){
			jQuery("#captcha1_response").val(response);
		}
	};
	
	var onloadCallback = function() {
		if ( jQuery('#captchaOne').length ) {
			grecaptcha.render('captchaOne', {
			  'sitekey' : '6LeQgEYUAAAAAD22rx_i62OKQ4W6Zlv2bhXN4kKy',
			  'callback' : verifyCallback_register,
			  'theme' : 'light'
			});
		}
	};
jQuery(document).ready(function() {
	jQuery(".successmsg").hide();
	jQuery(".errormsg").hide();
	jQuery(".mandatoryerror").hide();
	jQuery(".captchamsg").hide();
	jQuery('.countryselect').formSelect();
	
});
</script>
 <div class="row">
 <div class="input-field">
	<div class="hfn-input-group">
		<i class="material-icons prefix">language</i><select class="countryselect" id="country" name="country">
			<option selected="true" default="true" value="">- Select Country -</option>
			<option value="IN">India</option><option value="US">United States</option>
			<option value="AF">Afghanistan</option>
			<option value="AL">Albania</option>
			<option value="DZ">Algeria</option>
			<option value="AS">American Samoa</option>
			<option value="AD">Andorra</option>
			<option value="AO">Angola</option>
			<option value="AI">Anguilla</option>
			<option value="AG">Antigua and Barbuda</option>
			<option value="AR">Argentina</option>
			<option value="AM">Armenia</option>
			<option value="AW">Aruba</option>
			<option value="AU">Australia</option>
			<option value="AT">Austria</option>
			<option value="AZ">Azerbaijan</option>
			<option value="BS">Bahamas</option>
			<option value="BH">Bahrain</option>
			<option value="BD">Bangladesh</option>
			<option value="BB">Barbados</option>
			<option value="BY">Belarus</option>
			<option value="BE">Belgium</option>
			<option value="BZ">Belize</option>
			<option value="BJ">Benin</option>
			<option value="BM">Bermuda</option>
			<option value="BT">Bhutan</option>
			<option value="BO">Bolivia, Plurinational State of</option>
			<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
			<option value="BA">Bosnia and Herzegovina</option>
			<option value="BW">Botswana</option>
			<option value="BV">Bouvet Island</option>
			<option value="BR">Brazil</option>
			<option value="IO">British Indian Ocean Territory</option>
			<option value="BN">Brunei Darussalam</option>
			<option value="BG">Bulgaria</option>
			<option value="BF">Burkina Faso</option>
			<option value="BI">Burundi</option>
			<option value="KH">Cambodia</option>
			<option value="CM">Cameroon</option>
			<option value="CA">Canada</option>
			<option value="CV">Cape Verde</option>
			<option value="KY">Cayman Islands</option>
			<option value="CF">Central African Republic</option>
			<option value="TD">Chad</option>
			<option value="CL">Chile</option>
			<option value="CN">China</option>
			<option value="CX">Christmas Island</option>
			<option value="CC">Cocos (Keeling) Islands</option>
			<option value="CO">Colombia</option>
			<option value="KM">Comoros</option>
			<option value="CG">Congo</option>
			<option value="CD">Congo, the Democratic Republic of the</option>
			<option value="CK">Cook Islands</option>
			<option value="CR">Costa Rica</option>
			<option value="HR">Croatia</option>
			<option value="CU">Cuba</option>
			<option value="CY">Cyprus</option>
			<option value="CZ">Czech Republic</option>
			<option value="DK">Denmark</option>
			<option value="DJ">Djibouti</option>
			<option value="DM">Dominica</option>
			<option value="DO">Dominican Republic</option>
			<option value="EC">Ecuador</option>
			<option value="EG">Egypt</option>
			<option value="SV">El Salvador</option>
			<option value="GQ">Equatorial Guinea</option>
			<option value="ER">Eritrea</option>
			<option value="EE">Estonia</option>
			<option value="ET">Ethiopia</option>
			<option value="FK">Falkland Islands (Malvinas)</option>
			<option value="FO">Faroe Islands</option>
			<option value="FJ">Fiji</option>
			<option value="FI">Finland</option>
			<option value="FR">France</option>
			<option value="GF">French Guiana</option>
			<option value="PF">French Polynesia</option>
			<option value="TF">French Southern Territories</option>
			<option value="GA">Gabon</option>
			<option value="GM">Gambia</option>
			<option value="GE">Georgia</option>
			<option value="DE">Germany</option>
			<option value="GH">Ghana</option>
			<option value="GI">Gibraltar</option>
			<option value="GR">Greece</option>
			<option value="GL">Greenland</option>
			<option value="GD">Grenada</option>
			<option value="GP">Guadeloupe</option>
			<option value="GU">Guam</option>
			<option value="GT">Guatemala</option>
			<option value="GG">Guernsey</option>
			<option value="GN">Guinea</option>
			<option value="GW">Guinea-Bissau</option>
			<option value="GY">Guyana</option>
			<option value="HT">Haiti</option>
			<option value="HM">Heard Island and McDonald Islands</option>
			<option value="VA">Holy See (Vatican City State)</option>
			<option value="HN">Honduras</option>
			<option value="HK">Hong Kong</option>
			<option value="HU">Hungary</option>
			<option value="IS">Iceland</option>
			<option value="ID">Indonesia</option>
			<option value="IR">Iran, Islamic Republic of</option>
			<option value="IQ">Iraq</option>
			<option value="IE">Ireland</option>
			<option value="IM">Isle of Man</option>
			<option value="IL">Israel</option>
			<option value="IT">Italy</option>
			<option value="JM">Jamaica</option>
			<option value="JP">Japan</option>
			<option value="JE">Jersey</option>
			<option value="JO">Jordan</option>
			<option value="KZ">Kazakhstan</option>
			<option value="KE">Kenya</option>
			<option value="KI">Kiribati</option>
			<option value="KP">Korea, Democratic People's Republic of</option>
			<option value="KR">Korea, Republic of</option>
			<option value="KW">Kuwait</option>
			<option value="KG">Kyrgyzstan</option>
			<option value="LA">Lao People's Democratic Republic</option>
			<option value="LV">Latvia</option>
			<option value="LB">Lebanon</option>
			<option value="LS">Lesotho</option>
			<option value="LR">Liberia</option>
			<option value="LY">Libya</option>
			<option value="LI">Liechtenstein</option>
			<option value="LT">Lithuania</option>
			<option value="LU">Luxembourg</option>
			<option value="MO">Macao</option>
			<option value="MK">Macedonia, the former Yugoslav Republic of</option>
			<option value="MG">Madagascar</option>
			<option value="MW">Malawi</option>
			<option value="MY">Malaysia</option>
			<option value="MV">Maldives</option>
			<option value="ML">Mali</option>
			<option value="MT">Malta</option>
			<option value="MH">Marshall Islands</option>
			<option value="MQ">Martinique</option>
			<option value="MR">Mauritania</option>
			<option value="MU">Mauritius</option>
			<option value="YT">Mayotte</option>
			<option value="MX">Mexico</option>
			<option value="FM">Micronesia, Federated States of</option>
			<option value="MD">Moldova, Republic of</option>
			<option value="MC">Monaco</option>
			<option value="MN">Mongolia</option>
			<option value="ME">Montenegro</option>
			<option value="MS">Montserrat</option>
			<option value="MA">Morocco</option>
			<option value="MZ">Mozambique</option>
			<option value="MM">Myanmar</option>
			<option value="NA">Namibia</option>
			<option value="NR">Nauru</option>
			<option value="NP">Nepal</option>
			<option value="NL">Netherlands</option>
			<option value="NC">New Caledonia</option>
			<option value="NZ">New Zealand</option>
			<option value="NI">Nicaragua</option>
			<option value="NE">Niger</option>
			<option value="NG">Nigeria</option>
			<option value="NU">Niue</option>
			<option value="NF">Norfolk Island</option>
			<option value="MP">Northern Mariana Islands</option>
			<option value="NO">Norway</option>
			<option value="OM">Oman</option>
			<option value="PK">Pakistan</option>
			<option value="PW">Palau</option>
			<option value="PS">Palestinian Territory, Occupied</option>
			<option value="PA">Panama</option>
			<option value="PG">Papua New Guinea</option>
			<option value="PY">Paraguay</option>
			<option value="PE">Peru</option>
			<option value="PH">Philippines</option>
			<option value="PN">Pitcairn</option>
			<option value="PL">Poland</option>
			<option value="PT">Portugal</option>
			<option value="PR">Puerto Rico</option>
			<option value="QA">Qatar</option>
			<option value="RO">Romania</option>
			<option value="RU">Russian Federation</option>
			<option value="RW">Rwanda</option>
			<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
			<option value="KN">Saint Kitts and Nevis</option>
			<option value="LC">Saint Lucia</option>
			<option value="MF">Saint Martin (French part)</option>
			<option value="PM">Saint Pierre and Miquelon</option>
			<option value="VC">Saint Vincent and the Grenadines</option>
			<option value="WS">Samoa</option>
			<option value="SM">San Marino</option>
			<option value="ST">Sao Tome and Principe</option>
			<option value="SA">Saudi Arabia</option>
			<option value="SN">Senegal</option>
			<option value="RS">Serbia</option>
			<option value="SC">Seychelles</option>
			<option value="SL">Sierra Leone</option>
			<option value="SG">Singapore</option>
			<option value="SX">Sint Maarten (Dutch part)</option>
			<option value="SK">Slovakia</option>
			<option value="SI">Slovenia</option>
			<option value="SB">Solomon Islands</option>
			<option value="SO">Somalia</option>
			<option value="ZA">South Africa</option>
			<option value="GS">South Georgia and the South Sandwich Islands</option>
			<option value="SS">South Sudan</option>
			<option value="ES">Spain</option>
			<option value="LK">Sri Lanka</option>
			<option value="SD">Sudan</option>
			<option value="SR">Suriname</option>
			<option value="SJ">Svalbard and Jan Mayen</option>
			<option value="SZ">Swaziland</option>
			<option value="SE">Sweden</option>
			<option value="CH">Switzerland</option>
			<option value="SY">Syrian Arab Republic</option>
			<option value="TW">Taiwan, Province of China</option>
			<option value="TJ">Tajikistan</option>
			<option value="TZ">Tanzania, United Republic of</option>
			<option value="TH">Thailand</option>
			<option value="TL">Timor-Leste</option>
			<option value="TG">Togo</option>
			<option value="TK">Tokelau</option>
			<option value="TO">Tonga</option>
			<option value="TT">Trinidad and Tobago</option>
			<option value="TN">Tunisia</option>
			<option value="TR">Turkey</option>
			<option value="TM">Turkmenistan</option>
			<option value="TC">Turks and Caicos Islands</option>
			<option value="TV">Tuvalu</option>
			<option value="UG">Uganda</option>
			<option value="UA">Ukraine</option>
			<option value="AE">United Arab Emirates</option>
			<option value="GB">United Kingdom</option>
			<option value="UM">United States Minor Outlying Islands</option>
			<option value="UY">Uruguay</option>
			<option value="UZ">Uzbekistan</option>
			<option value="VU">Vanuatu</option>
			<option value="VE">Venezuela, Bolivarian Republic of</option>
			<option value="VN">Viet Nam</option>
			<option value="VG">Virgin Islands, British</option>
			<option value="VI">Virgin Islands, U.S.</option>
			<option value="WF">Wallis and Futuna</option>
			<option value="EH">Western Sahara</option>
			<option value="YE">Yemen</option>
			<option value="ZM">Zambia</option>
			<option value="ZW">Zimbabwe</option>
		</select>
		<span class="errmsgcountry error-text red-text"></span> 
	</div>
</div>
 </div>
 
 <div class="row"><div class="input-field"><div class="hfn-input-group"> <i class="material-icons prefix">message</i><textarea id="message" name="message" class="materialize-textarea"></textarea><label for="textarea1">Your Message</label><span class="errmsgmsg error-text red-text"></span></div></div></div>
 <div class="row">
  <div class="input-field col s12">
    <div class="hfn-input-group"><input name="concern1" id="concern1" type="checkbox"><label for="concern1">I Agree to the <a href="/us/terms/" target="_blank">Terms and Conditions</a></label><span class="concern1errmsg error-text red-text"></span></div>
  </div>
</div>
<div class="row">
	<div class="input-field col s12">
		<div id="captchaOne"></div>
		<input type="hidden" id="captcha1_response"><span class="errmsgcaptcha1 error-msgs red-text"></span>
	</div>
</div>
				
 <div class="row"><div class="input-field"><div class="hfn-input-group"> 
 <div class="alert alert-success successmsg"> Registered Successfully... </div>
 <div class="alert alert-danger mandatoryerror"> Mandatory Fields Missing...</div>
 <div class="alert alert-danger errormsg"> Registration Failed...</div>
 <div class="alert alert-danger captchamsg"> Invalid Captcha...</div>
 </div></div></div>
 <div class="col s12"><i class="waves-effect waves-light btn login-button waves-input-wrapper" style=""><input type="submit" class="waves-button-input"  id= "trainers_detail" value="Submit"></i> </div><input id="dst_name" name="dst_name" type="hidden" value="Geetu Bohra"><input id="dst_hfnContactNo" name="dst_hfnContactNo" type="hidden" value="NA"><input id="dst_hfnContactId" name="dst_hfnContactId" type="hidden" value="shankar.chari@gmail.com"><input id="dst_abhyasiId" name="dst_abhyasiId" type="hidden" value="INGBAE020"><input id="mode" name="mode" type="hidden" value="<?php echo $mode; ?>"></form>
 <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer> </script>
</div>
<!--<div id="dialog-citypop">
  <div class="row">
    <div class="input-field city-input">
      <div class="hfn-input-group">
        <i class="material-icons prefix">language</i>
        <input id="city_name" name="city_name" type="text" class="validate">
        <label for="icon_prefix">Your City</label>
        <span class="errmsgcity error-text red-text"></span>
      </div>
      <div class="go_city"><i class="waves-effect waves-light btn login-city-button waves-input-wrapper" style=""><input type="submit" class="waves-button-input"  id= "city_search" value="Go"></i> </div>
    </div>
  </div>
</div>-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<!--<script src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyAGXcKO3JTAxeFBMQs27f0OmEcg5GUMiH4&libraries=visualization,drawing,places">
    </script>-->
<script type="text/javascript">
/* setTimeout(function(){
  jQuery(".hfn-citypop-section").hide();
   }, 30000); */
var map;
var markers = [];
var infoWindow;
var locationSelect;
var ul;
var ul_center;
var autocomplete;
//var typeId=document.getElementById('type');
//var trainerId=document.getElementById('trainer');
//var centerId=document.getElementById('center');
var trainerLbl=document.getElementById('trainerLbl');
var centerLbl=document.getElementById('centerLbl');
var trainers_countId=document.getElementById('trainers_count');
var centers_countId=document.getElementById('centers_count');
var addrId=document.getElementById("addressInput");
var txt_city=document.getElementById('city_name');
var setlatlng;
var searchAddress="";
var geocoder = new google.maps.Geocoder();

var latitude_rightside;
var longitude_rightside
var loc_city;
var loc_state;
var loc_country;
var loc_country_iso_code;

var options = {
types: ['(cities)'],
//componentRestrictions: {country: "us"}
};

function initMap() {

     // var hfn_atlanta = {lat: 33.753746, lng: -84.386330};
     map = new google.maps.Map(document.getElementById('map'), {
        //center: hfn_atlanta,
        zoom: 12,
        mapTypeId: 'roadmap',
        //mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
        mapTypeControl: true,
        mapTypeControlOptions: {
          style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
          position: google.maps.ControlPosition.LEFT_TOP
        },
        fullscreenControl: true
      });
     infoWindow = new google.maps.InfoWindow();

      /*Autocomplete Below Map*/
      //var geocoder = new google.maps.Geocoder();
      autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('addressInput')),
      { types: ['geocode'] });
      google.maps.event.addListener(autocomplete, 'place_changed', function() {});

      /*Right Top Autocomplete*/
      var places = new google.maps.places.Autocomplete(txt_city,options);
      google.maps.event.addListener(places, 'place_changed', function () {
        var place = places.getPlace();
        //console.log(place.address_components[0]);
        var address_rightside = place.formatted_address;
        latitude_rightside = place.geometry.location.lat();
        longitude_rightside = place.geometry.location.lng();
        /*var mesg = "Address: " + address_rightside;
        mesg += "\nLatitude: " + latitude_rightside;
        mesg += "\nLongitude: " + longitude_rightside;*/
        for (var ac = 0; ac < place.address_components.length; ac++) {
          var component = place.address_components[ac];

          switch(component.types[0]) {
              case 'locality':
                  loc_city = component.long_name;
                  loc_city = loc_city.replace(" ", "-");

                  break;
              case 'administrative_area_level_1':
                  loc_state = component.short_name;
                  break;
              case 'country':
                  loc_country = component.long_name;
                  loc_country_iso_code = component.short_name;
                  break;
          }
        }

       /* mesg += "\nstorableLocation_city: " + loc_city;
        mesg += "\nstorableLocation_state: " + loc_state;
        mesg += "\nstorableLocation_country: " + loc_country;
        mesg += "\nstorableLocation_country_iso_code: " + loc_country_iso_code;*/

        //setlatlng=latitude_rightside+","+longitude_rightside;
        //addrId.value=txt_city.value;
        //geocodeLatLng(map, setlatlng);


        //alert(mesg);
      });

        searchLocations();
        /*document.getElementById('city_search').addEventListener('click', function() {
          window.location.href="<?php //echo $home_url; ?>/meditation/"+loc_city+"/"+loc_state+"/"+latitude_rightside+"/"+longitude_rightside;
        });*/


        /*  // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            setlatlng=position.coords.latitude+","+position.coords.longitude;
            geocodeLatLng(map, setlatlng);
          }, function() {
            //handleLocationError(true, infoWindow, map.getCenter());
            searchLocations();
          });
        }*/

        //  searchButton = document.getElementById("searchButton").onclick = searchLocations;
        document.getElementById('searchButton').addEventListener('click', function() {
          searchLocations();
        });

       /* trainerId.addEventListener('click', function() {
            typeId.value="trainer";
            trainerLbl.innerHTML="Trainers";
            if(addrId.value.length>0 || searchAddress.length > 0){
              searchLocations();
            }else{
              geocodeLatLng(map, setlatlng);
            }

        });
        centerId.addEventListener('click', function() {
            typeId.value="center";
            trainerLbl.innerHTML="Meditation Centers";
            if(addrId.value.length>0 || searchAddress.length > 0){
              searchLocations();
            }else{
              geocodeLatLng(map, setlatlng);
            }
        });*/

          ul = document.getElementById("trianer_list");
          ul_center = document.getElementById("center_list");

        }


        function geocodeLatLng(map, latlng) {
          //var input = document.getElementById('latlng').value;
          var input = latlng;
          var latlngStr = input.split(',', 2);
          var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
          geocoder.geocode({'location': latlng}, function(results, status) {
            if (status === 'OK') {
               map.setZoom(13);
               var country="";
              for (var i = 0; i < results[0].address_components.length; i++) {
                var addr = results[0].address_components[i];
                // check if this entry in address_components has a type of country
                if (addr.types[0] == 'country')
                    country = addr.short_name;

              }
            searchLocationsNear(results[0].geometry.location,country);
            } else {
            window.alert('Geocoder failed due to: ' + status);
            }
          });
        }



       function searchLocations() {
         searchAddress = addrId.value;
         if(searchAddress.length == 0){
            searchAddress='<?php echo $clientlocation; ?>';
         }
         var geocoder = new google.maps.Geocoder();
         geocoder.geocode({address: searchAddress}, function(results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
            map.setZoom(13);
            var country="";
              for (var i = 0; i < results[0].address_components.length; i++) {
                var addr = results[0].address_components[i];
                // check if this entry in address_components has a type of country
                if (addr.types[0] == 'country')
                    country = addr.short_name;

              }
            searchLocationsNear(results[0].geometry.location,country);
           } else {
             alert(address + ' not found');
           }
         });
       }

       function clearLocations() {
         infoWindow.close();
         for (var i = 0; i < markers.length; i++) {
           markers[i].setMap(null);
         }
         markers.length = 0;

         /*locationSelect.innerHTML = "";
         var option = document.createElement("option");
         option.value = "none";
         option.innerHTML = "See all results:";
         locationSelect.appendChild(option);*/

         ul.innerHTML = "";
         var li = document.createElement("li");
       //  option.value = "none";
         li.innerHTML = "";
         ul.appendChild(li);

         //For center
         ul_center.innerHTML = "";
         var li_center = document.createElement("li");
         li_center.innerHTML = "";
         ul_center.appendChild(li_center);
       }

       function searchLocationsNear(center,country) {
         clearLocations();
         var searchUrl;

         var radius = document.getElementById('radiusSelect').value;
          searchUrl = '<?php echo $home_url1; ?>Heartspot/trainerslocator.php?country='+country+'&lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
           downloadUrl(searchUrl, function(data) {
           var xml = parseXml(data);
           var markerNodes = xml.documentElement.getElementsByTagName("marker");
           var bounds = new google.maps.LatLngBounds();
           for (var i = 0; i < markerNodes.length; i++) {
             var id = markerNodes[i].getAttribute("id");
             var abhyasiId = markerNodes[i].getAttribute("abhyasiId");
             var name = markerNodes[i].getAttribute("name");
             var address = markerNodes[i].getAttribute("address");
             var picture = markerNodes[i].getAttribute("picture");
             var typev = markerNodes[i].getAttribute("type");
             var timings = markerNodes[i].getAttribute("timings");
             var t_count = markerNodes[i].getAttribute("trainers_count");
             var c_count = markerNodes[i].getAttribute("centers_count");
             var privacy = markerNodes[i].getAttribute("privacy");
             var phone = markerNodes[i].getAttribute("phone");
             var distance = parseFloat(markerNodes[i].getAttribute("distance"));
             var latlng = new google.maps.LatLng(
                  parseFloat(markerNodes[i].getAttribute("lat")),
                  parseFloat(markerNodes[i].getAttribute("lng")));

             createOption(name, picture, i, address, typev, timings,t_count,c_count,privacy,phone,id,abhyasiId);
             createMarker(latlng, name, address,typev,timings,privacy,phone);
             bounds.extend(latlng);
           }
           //console.log("t_count"+t_count);
            // console.log("c_count"+c_count);
           map.fitBounds(bounds);

         });
       }

       function createMarker(latlng, name, address,typev,timings,privacy,phone) {
          var html;
          var iconBase = '/us/wp-content/uploads/2017/11/';
          var markerImg;

          if(typev=='center'){
            html = "<b>" + name + "</b> <br/>Meditation Time: " + timings+"<br/>Phone : "+phone;;
            markerImg="map-marker-variant-tool32.png";
          }else{
            if(privacy=="low"){
              html = "<b>" + name + "</b><br/>"+"Phone : "+phone;
            }else{
              html = "<b>Heartfulness Trainer</b>";
            }
            markerImg="user-shape.png";
          }
          var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            icon: iconBase + markerImg
          });
          google.maps.event.addListener(marker, 'click', function() {

            infoWindow.setContent(html);
            infoWindow.open(map, marker);
          });
          markers.push(marker);
        }

       function createOption(name, picture, num, address, typev, timings,t_count,c_count,privacy,phone,id,abhyasiId) {
         /* var option = document.createElement("option");
          option.value = num;
          option.innerHTML = name;
          locationSelect.appendChild(option);*/
          var clk="'click'";
          trainers_countId.innerHTML=t_count;
          centers_countId.innerHTML=c_count;

          var li = document.createElement("li");
          var li_center = document.createElement("li");
         // li.innerHTML = '<div class="list-label" onclick="google.maps.event.trigger(markers['+num+'], '+clk+');"><img src="'+picture+'" width="45px" height="45px"></div><div class="list-details"><div class="list-content"><div class="loc-name">'+name+'</div></div></div>';
         if(typev=='center'){
           li_center.innerHTML = '<div class="list-details"><div class="list-content"><h4 class="loc-name">'+name+'</h4><div class="loc-timings">'+timings+'</div><div class="loc-name">'+address+'</div><div class="loc-button"><a onclick="google.maps.event.trigger(markers['+num+'], '+clk+'),map.setZoom(16);">Locate</a> | <a class="center_connect" data-abhyasiId="'+abhyasiId+'" data-name="'+name+'" data-id="'+id+'" >Connect</a></div></div></div>';
           ul_center.appendChild(li_center);
         }else{
           if(privacy=="low"){
              li.innerHTML = '<div class="list-label"><img src="'+picture+'" class="trainer_img" width="45px" height="45px"></div><div class="list-details"><div class="list-content"><div class="loc-name">'+name+'</div><div class="loc-button"><a  onclick="google.maps.event.trigger(markers['+num+'], '+clk+'),map.setZoom(16);">Locate</a>  <a class="connect" data-abhyasiId="'+abhyasiId+'" data-name="'+name+'" data-id="'+id+'">Connect</a></div></div></div>';
           }else{
             li.innerHTML = '<div class="list-details"><div class="list-content"><div class="loc-name">Heartfulness Trainer</div><div class="loc-button"><a  onclick="google.maps.event.trigger(markers['+num+'], '+clk+'),map.setZoom(16);">Locate</a>  <a class="connect_pro" data-abhyasiId="'+abhyasiId+'" data-name="'+name+'" data-id="'+id+'">Connect</a></div></div></div>';
           }
           ul.appendChild(li);
         }





       }

       function downloadUrl(url, callback) {
          var request = window.ActiveXObject ?
              new ActiveXObject('Microsoft.XMLHTTP') :
              new XMLHttpRequest;

          request.onreadystatechange = function() {
            if (request.readyState == 4) {
              request.onreadystatechange = doNothing;
              callback(request.responseText, request.status);
            }
          };

          request.open('GET', url, true);
          request.send(null);
       }

       function parseXml(str) {
          if (window.ActiveXObject) {
            var doc = new ActiveXObject('Microsoft.XMLDOM');
            doc.loadXML(str);
            return doc;
          } else if (window.DOMParser) {
            return (new DOMParser).parseFromString(str, 'text/xml');
          }
       }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
      }


       function doNothing() {}

       initMap();


//Connect popup part

  jQuery( ".connect , .center_connect , .connect_pro" ).live("click",function() {
	jQuery(".alert").hide();
  var attrAbhyasiId=jQuery(this).attr("data-abhyasiId");
  var attrName=jQuery(this).attr("data-name");
  var attrId=jQuery(this).attr("data-id");
  jQuery("#dst_hfnContactId").val(attrId);
  jQuery("#dst_name").val(attrName);
  jQuery("#dst_abhyasiId").val(attrAbhyasiId);
setTimeout(function(){
  jQuery(".ui-dialog-titlebar-close").html("X");
  jQuery(".errmsgname,.errmsgemail,.errmsglocation").text("");
  }, 100);
jQuery( "#dialog-messagepop" ).dialog({
title: "Contact",
position: 'top',
width:350,
modal: true,
autoOpen: true,
resizable: false,
draggable: false,

});
});
//connect popup validation part
jQuery("#trainers_detail").on("click",function(event){
jQuery(".alert").hide();
jQuery(".successmsg").hide();
jQuery(".errormsg").hide();
jQuery(".mandatoryerror").hide();
jQuery(".captchamsg").hide();
var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var all=true;
if(jQuery("#name").val()==""){
		jQuery(".errmsgname").text("Name should not be empty");
		all=false;
	}else
	jQuery(".errmsgname").text("");


if(jQuery("#userEmail").val()==""){
		jQuery(".errmsgemail").text("Email should not be empty");
		all=false;
	}else if(!regex.test(jQuery("#userEmail").val())){
		jQuery(".errmsgemail").text("Invalid Email");
		all=false;
	}else
	jQuery(".errmsgemail").text("");

  if(jQuery("#concern1").is(":checked") == false){
      jQuery(".concern1errmsg").text("Please agree to the terms and conditions.");
      all=false;    
    }else{
      jQuery(".concern1errmsg").text("");
    }
	
	if(jQuery("#captcha1_response").val() == ""){
		all=false;
		jQuery(".errmsgcaptcha1").text("reCAPTCHA should not be empty");
	}else{
		jQuery(".errmsgcaptcha1").text("");
	}
	
	
  if(all){
    event.preventDefault();
    jQuery.ajax({
        url:'<?php echo $home_url1; ?>Heartspot/enquiries.php',
        type:'POST',
        data:jQuery("form#connect_trainer_detail").serialize(),
        success:function(result){
		jQuery("#connect_trainer_detail")[0].reset(); 
		grecaptcha.reset();
		console.log(result);
            if(result=="Registered"){
				jQuery(".successmsg").show();
			}else if(result=="Failed"){
				jQuery(".errormsg").show();
			}else if(result=="FieldsMissing"){
				jQuery(".mandatoryerror").show();
			}else
				jQuery(".captchamsg").show();
        }
    });
  }else{
     return all;
  }

});
//city popup part
/*jQuery( ".citypopup" ).live("click",function() {
setTimeout(function(){
  jQuery(".ui-dialog-titlebar-close").html("X");
  jQuery(".errmsgcity").text("");
  }, 100);
jQuery( "#dialog-citypop" ).dialog({
title: "Search City",
width:350,
modal: true,
autoOpen: true,
});
});*/


<?php
if($no_cookie == 1)
echo 'jQuery(".citypopup").trigger( "click" );';
?>

</script>


<div class="fl-content-full">
	<div class="row content-row">
		<div class="fl-content" style="margin:0px;">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				<article <?php post_class( 'fl-post' ); ?> id="fl-post-<?php the_ID(); ?>" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

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
<?php
/*if(isset($_GET["ip"]))
{
echo $data->latitude;
echo $data->longitude;
echo $data->countryCode;
echo $clientlocation=$data->cityName.", ".$data->regionName.", ".$data->countryName;
}

print_r("Using rules engine");
$rulesgeolocation = RulesEngineCommon::get_geo_loction_details();
print_r("<pre>");
print_r($rulesgeolocation);

$data = ip2location_get_vars();
$data = json_decode($data);
print_r("Using ip2 location");
print_r("<pre>");
print_r($data);

echo "<pre>";
print_r($_GET);
*/

/*global $wp_query;
echo '<pre>';
print_r($wp_query);
echo 'f : ' . $wp_query->query_vars['food'];
echo '<br />';
echo 't : ' . $wp_query->query_vars['variety'];
echo  get_query_var('food');
echo $GLOBALS['food'];*/
get_footer(); ?>
<style>
.ui-dialog{
	top: 10% !important;
    left: 40%;
}
#connect_trainer_detail{
	width:95%;
}
</style>