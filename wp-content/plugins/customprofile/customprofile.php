<?php
/*
Plugin Name: Custom Profile
Description: Custom Profile 
Version:     1.0
Author:      Karthikeyan G
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
ob_clean();
ob_start();

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */


add_filter( 'login_redirect', 'redirect_admin', 10, 3 );
function redirect_admin( $redirect_to, $request, $user ){


    if ( isset( $user->roles ) && is_array( $user->roles ) ) {

        //check for admins : add that roles
        if ( in_array( 'subscriber', $user->roles ) ) {
			global $wpdb;
            $redirect_to = get_site_url()."/user-profile/"; // Your redirect URL
			$query = "select count(*) as count from hos_gf_entry_notes where user_id=".$user->ID." and note_type='success' ";
			$result = $wpdb->get_results($query);
			if(isset($result[0]) && $result[0]->count > 0){
				update_user_meta($user->ID , "payment_status", "success");
			}
			
        }
    }

    return $redirect_to;
}

/*Add More User Meta Fields*/
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
add_action( "user_new_form", "extra_new_user_profile_fields" );

function extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("Extra profile information", "blank"); ?></h3>

    <table class="form-table">
	<tr>
        <th><label for="entryid"><?php _e("Form Submission Id"); ?></label></th>
        <td>
            <input type="text" name="entryid" id="entryid" value="<?php echo esc_attr( get_the_author_meta( 'entryid', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter Entry Id."); ?></span>
        </td>
    </tr>
	
	<tr>
        <th><label for="class"><?php _e("Contact"); ?></label></th>
        <td>
		<?php ?>
            <input type="text" name="contact" id="contact" value="<?php echo esc_attr( get_the_author_meta( 'contact', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your Contact."); ?></span>
        </td>
    </tr>
	
	 <tr>
		 <th> Student 1 Details</th>
		 <td>&nbsp;</td>
	 </tr>
	<tr>
        <th><label for="class"><?php _e("Name"); ?></label></th>
        <td>
		<?php ?>
            <input type="text" name="s1name" id="s1name" value="<?php echo esc_attr( get_the_author_meta( 's1name', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your Student Name."); ?></span>
        </td>
    </tr>
	
	<tr>
        <th><label for="class"><?php _e("Class"); ?></label></th>
        <td>
		<?php 	$class = get_the_author_meta( 'class', $user->ID )  ?>
			<select name="class" id="class">
				<option value="" >Select Class</option>
				<option value="Pre-KG" <?php echo ($class=='Pre-KG') ? 'selected': '' ?>>Pre-KG</option>
				<option value="LKG" <?php echo ($class=='LKG') ? 'selected': '' ?>>LKG</option>
				<option value="UKG" <?php echo ($class=='UKG') ? 'selected': '' ?>>UKG</option>
				<option value="I" <?php echo ($class=='I') ? 'selected': '' ?>>I</option>
				<option value="II" <?php echo ($class=='II') ? 'selected': '' ?>>II</option>
				<option value="III" <?php echo ($class=='III') ? 'selected': '' ?>>III</option>
				<option value="IV" <?php echo ($class=='IV') ? 'selected': '' ?>>IV</option>
				<option value="V" <?php echo ($class=='V') ? 'selected': '' ?>>V</option>
				<option value="VI" <?php echo ($class=='VI') ? 'selected': '' ?>>VI</option>
				<option value="VII" <?php echo ($class=='VII') ? 'selected': '' ?>>VII</option>
				<option value="VIII" <?php echo ($class=='VIII') ? 'selected': '' ?>>VIII</option>
			</select>

            <span class="description"><?php _e("Please enter class."); ?></span>
        </td>
    </tr>
	 <tr>
        <th><label for="amount"><?php _e("Term Fees"); ?></label></th>
        <td>
            <input type="text" name="amount" id="amount" value="<?php echo esc_attr( get_the_author_meta( 'amount', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter amount."); ?></span>
        </td>
    </tr>
	<tr>
	 <?php 	$hsds = get_the_author_meta( 'hsds', $user->ID ); ?>
        <th><label for="hsds"><?php _e("DS(Day Scholar)/HS(Hostel)"); ?></label></th>
        <td>
			<select name="hsds" id="hsds">
				<option value="" >Select HS/DS</option>
				<option value="HS" <?php echo ($hsds == 'HS') ? 'selected': '' ?>>HS</option>
				<option value="DS" <?php echo ($hsds == 'DS') ? 'selected': '' ?>>DS</option>
			</select>
            <span class="description"><?php _e("Please enter DS/HS."); ?></span>
        </td>
    </tr>
	
	<tr>
		 <th> Student 2 Details</th>
		 <td>&nbsp;</td>
	</tr>
	<tr>
        <th><label for="class"><?php _e("Name"); ?></label></th>
        <td>
		<?php ?>
            <input type="text" name="s2name" id="s2name" value="<?php echo esc_attr( get_the_author_meta( 's2name', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your Student Name."); ?></span>
        </td>
    </tr>
   
	 <tr>
        <th><label for="class2"><?php _e("Class"); ?></label></th>
        <td>
		<?php 	$class = get_the_author_meta( 'class2', $user->ID )  ?>
			<select name="class2" id="class2">
				<option value="" >Select Class</option>
				<option value="Pre-KG" <?php echo ($class=='Pre-KG') ? 'selected': '' ?>>Pre-KG</option>
				<option value="LKG" <?php echo ($class=='LKG') ? 'selected': '' ?>>LKG</option>
				<option value="UKG" <?php echo ($class=='UKG') ? 'selected': '' ?>>UKG</option>
				<option value="I" <?php echo ($class=='I') ? 'selected': '' ?>>I</option>
				<option value="II" <?php echo ($class=='II') ? 'selected': '' ?>>II</option>
				<option value="III" <?php echo ($class=='III') ? 'selected': '' ?>>III</option>
				<option value="IV" <?php echo ($class=='IV') ? 'selected': '' ?>>IV</option>
				<option value="V" <?php echo ($class=='V') ? 'selected': '' ?>>V</option>
				<option value="VI" <?php echo ($class=='VI') ? 'selected': '' ?>>VI</option>
				<option value="VII" <?php echo ($class=='VII') ? 'selected': '' ?>>VII</option>
				<option value="VIII" <?php echo ($class=='VIII') ? 'selected': '' ?>>VIII</option>
			</select>
      
            <span class="description"><?php _e("Please enter class."); ?></span>
        </td>
    </tr>
	
   	 <tr>
        <th><label for="amount2"><?php _e("Term Fees"); ?></label></th>
        <td>
            <input type="text" name="amount2" id="amount2" value="<?php echo esc_attr( get_the_author_meta( 'amount2', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter amount."); ?></span>
        </td>
    </tr>
	
	<tr>
	 <?php 	$hsds = get_the_author_meta( 'hsds2', $user->ID ); ?>
        <th><label for="hsds2"><?php _e("DS(Day Scholar)/HS(Hostel)"); ?></label></th>
        <td>
			<select name="hsds2" id="hsds2">
				<option value="" >Select HS/DS</option>
				<option value="HS" <?php echo ($hsds == 'HS') ? 'selected': '' ?>>HS</option>
				<option value="DS" <?php echo ($hsds == 'DS') ? 'selected': '' ?>>DS</option>
			</select>
            <span class="description"><?php _e("Please enter DS/HS."); ?></span>
        </td>
    </tr>

    </table>
<?php }

function extra_new_user_profile_fields($user) { ?>
    <h3><?php _e("Extra profile information", "blank"); ?></h3>

    <table class="form-table">
	<tr>
        <th><label for="entryid"><?php _e("Form Submission Id"); ?></label></th>
        <td>
            <input type="text" name="entryid" id="entryid" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter Entry Id."); ?></span>
        </td>
    </tr>
	
	<tr>
        <th><label for="class"><?php _e("Contact"); ?></label></th>
        <td>
            <input type="text" name="contact" id="contact" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your Contact."); ?></span>
        </td>
    </tr>
	
	 <tr>
		 <th> Student 1 Details</th>
		 <td>&nbsp;</td>
	 </tr>
	<tr>
        <th><label for="class"><?php _e("Name"); ?></label></th>
        <td>
            <input type="text" name="s1name" id="s1name" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your Student Name."); ?></span>
        </td>
    </tr>
	
	<tr>
        <th><label for="class"><?php _e("Class"); ?></label></th>
        <td>
			<select name="class" id="class">
				<option value="" >Select Class</option>
				<option value="Pre-KG" >Pre-KG</option>
				<option value="LKG" >LKG</option>
				<option value="UKG" >UKG</option>
				<option value="I" >I</option>
				<option value="II" >II</option>
				<option value="III" >III</option>
				<option value="IV" >IV</option>
				<option value="V" >V</option>
				<option value="VI" >VI</option>
				<option value="VII" >VII</option>
				<option value="VIII" >VIII</option>
			</select>

            <span class="description"><?php _e("Please enter class."); ?></span>
        </td>
    </tr>
	 <tr>
        <th><label for="amount"><?php _e("Term Fees"); ?></label></th>
        <td>
            <input type="text" name="amount" id="amount" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter amount."); ?></span>
        </td>
    </tr>
	<tr>
        <th><label for="hsds"><?php _e("DS(Day Scholar)/HS(Hostel)"); ?></label></th>
        <td>
			<select name="hsds" id="hsds">
				<option value="" >Select HS/DS</option>
				<option value="HS" >HS</option>
				<option value="DS" >DS</option>
			</select>
            <span class="description"><?php _e("Please enter DS/HS."); ?></span>
        </td>
    </tr>
	
	<tr>
		 <th> Student 2 Details</th>
		 <td>&nbsp;</td>
	</tr>
	<tr>
        <th><label for="class"><?php _e("Name"); ?></label></th>
        <td>
		<?php ?>
            <input type="text" name="s2name" id="s2name" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your Student Name."); ?></span>
        </td>
    </tr>
   
	 <tr>
        <th><label for="class2"><?php _e("Class"); ?></label></th>
        <td>
			<select name="class2" id="class2">
				<option value="" >Select Class</option>
				<option value="Pre-KG" >Pre-KG</option>
				<option value="LKG" >LKG</option>
				<option value="UKG">UKG</option>
				<option value="I" >I</option>
				<option value="II" >II</option>
				<option value="III">III</option>
				<option value="IV">IV</option>
				<option value="V" >V</option>
				<option value="VI" >VI</option>
				<option value="VII" >VII</option>
				<option value="VIII">VIII</option>
			</select>
      
            <span class="description"><?php _e("Please enter class."); ?></span>
        </td>
    </tr>
	
   	 <tr>
        <th><label for="amount2"><?php _e("Term Fees"); ?></label></th>
        <td>
            <input type="text" name="amount2" id="amount2" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter amount."); ?></span>
        </td>
    </tr>
	
	<tr>
        <th><label for="hsds2"><?php _e("DS(Day Scholar)/HS(Hostel)"); ?></label></th>
        <td>
			<select name="hsds2" id="hsds2">
				<option value="" >Select HS/DS</option>
				<option value="HS">HS</option>
				<option value="DS">DS</option>
			</select>
            <span class="description"><?php _e("Please enter DS/HS."); ?></span>
        </td>
    </tr>

    </table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
add_action( 'user_register', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
	update_user_meta( $user_id, 's1name', $_POST['s1name'] );
    update_user_meta( $user_id, 'contact', $_POST['contact'] );
    update_user_meta( $user_id, 'class', $_POST['class'] );
    update_user_meta( $user_id, 'amount', $_POST['amount'] );
    update_user_meta( $user_id, 'entryid', $_POST['entryid'] );
	update_user_meta( $user_id, 'hsds', $_POST['hsds'] );
	
	/*Student 2 Details updation*/
	update_user_meta( $user_id, 's2name', $_POST['s2name'] );
	update_user_meta( $user_id, 'class2', $_POST['class2'] );
    update_user_meta( $user_id, 'amount2', $_POST['amount2'] );
	update_user_meta( $user_id, 'hsds2', $_POST['hsds2'] );

}
/*Add More User Meta Fields*/


