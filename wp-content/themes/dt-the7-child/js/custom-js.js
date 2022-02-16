/* =================================
/* =================================

   LOADER    04092015

=================================== */

// makes sure the whole site is loaded
var isTouch 	= ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ),
	evt_type 	= isTouch ? "touchend" :"click",
	resize_evt	= isTouch ? "orientationchange" : "resize",
	isMove 		= false;
jQuery(window).load(function() {

	//window.scrollTo(0,0);

  jQuery('body:not(.home)').removeClass('custom-background');

        // will first fade out the loading animation

  jQuery(".status").fadeOut();

        // will fade out the whole DIV that covers the website.

  jQuery(".preloader").delay(1000).fadeOut("slow");


  //jQuery('.carousel').carousel({interval: false});

	/* Begin: Fix - firefox wrong scroll position */
	var user_agent		= navigator.userAgent;
	if(user_agent.search('Firefox') > -1){
		var url_path 		= window.location.href;
		var hash_target		= window.location.hash;
		if(url_path.indexOf("target=") > -1 && hash_target == '' ){
			var target_id = jQuery(".responsive-tabs__panel--active").attr('id');
		}
		else if(hash_target && hash_target.substring(1)){
			var target_id = hash_target.substring(1);
		}
		if(typeof target_id != 'undefined' && jQuery("#"+target_id).length){
			var topOffest		= (jQuery('body').hasClass('admin-bar')) ? 32 : 0;
			var headerheight	= (jQuery('#main-nav').length) ? (jQuery('#main-nav').height() + 34) : 0; /* +34 : includes margin-bottom,top */
			var targetOffset	= jQuery("#"+target_id).offset().top;
			var scrollOffset	= targetOffset - (headerheight + topOffest);
			jQuery('html, body').stop().animate({'scrollTop': scrollOffset}, 1200);
		}
	}
	/* End: Fix - firefox wrong scroll position */

})

jQuery(document).load(function() {
	if(jQuery('#mail_span').length<=0)
		jQuery('.sti-share-box').append('<span id="mail_span" style="height:36px;width:36px;"><a class="mail_span_a" href="#"><img src="/wp-content/uploads/sites/22/2016/03/mail_icon.png"/></a></span>');
});
/*** mobile menu */
jQuery(document).ready(function() {
/*** Samba codes */
	jQuery('.hfn-input-group textarea, .hfn-input-group input').focus(function(){
jQuery(this).addClass('active');
jQuery(this).parent().parent().find('label').addClass('active');
});
jQuery('.hfn-input-group textarea, .hfn-input-group input').focusout(function(){
if(jQuery(this).val().length == 0) {
jQuery(this).removeClass('active');
jQuery(this).parent().parent().find('label').removeClass('active');
}

});
/*** End Samba codes */

	 jQuery("#flip").click(function(){
        jQuery("#panel").slideDown("slow");
    });

	if(jQuery("#owl-home-banner").length){
		var owlhomebanner = jQuery("#owl-home-banner");
		var data_interval = jQuery("#owl-home-banner").attr("data-owl-interval");
		owlhomebanner.owlCarousel({
			autoPlay			: data_interval,
			navigation 			: false,
			slideSpeed 			: 300,
			singleItem			: true,
			autoHeight			: true,
			pagination			: true,
			paginationNumbers 	: false,
		});
		jQuery("#owl-home-banner a").on('click', function(e){
			owlhomebanner.trigger('owl.play',5000);
		});
	}


jQuery(".mail_span_a").click(function(e){

window.open("http://en.staging.heartfulness.org/mail_share.php");
console.log('aaaa');
});




	//karthi: for tab images
	jQuery(".wonderplugintabs-header-ul li").bind("click",function (e){
	var i=1;
	jQuery(".wonderplugintabs-header-ul li").each(function(e) {
	 if(jQuery(this).hasClass("wonderplugintabs-header-li-active"))
		jQuery(".wonderplugintabs-header-ul .wonderplugintabs-header-li-active img").attr("src","http://en-in.staging.heartfulness.org/wp-content/uploads/sites/10/2016/02/Tab_"+i+"_On.png");
	else
		jQuery(".wonderplugintabs-header-ul li img[src*='Tab_"+i+"']").attr("src","http://en-in.staging.heartfulness.org/wp-content/uploads/sites/10/2016/02/Tab_"+i+"_Off.png");
	i++;
	 });
	});

	jQuery("#signup_form a").click(function(e){
		jQuery("#dialog .nav li:nth-child(2)").removeClass("active");
		jQuery("#dialog .nav li:nth-child(1)").addClass("active");
	});

	jQuery("#login a").click(function(e){
		jQuery("#dialog .nav li:nth-child(1)").removeClass("active");
		jQuery("#dialog .nav li:nth-child(2)").addClass("active");
	});



/*Karthi*/
jQuery("#wpdlanguage").change(function(){
	var vid=jQuery("#wpdlanguage").val();
	var audio=document.getElementById('audio');
	if(vid=="English"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/i8f1Ggx8jM0?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayEnglish.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayEnglish.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Hindi"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/yI5u3W0otlM?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayHindi.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayHindi.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Japanese"){
		//make audio tab as active by default
		jQuery("#tablist1-tab1").removeClass("responsive-tabs__list__item--active");
		jQuery("#tablist1-panel1").removeClass("responsive-tabs__panel--active");
		jQuery("#tablist1-panel1").css("display","none");
		jQuery("#tablist1-tab2").addClass("responsive-tabs__list__item--active");
		jQuery("#tablist1-panel2").addClass("responsive-tabs__panel--active");
		jQuery("#tablist1-panel2").css("display","block");

		//jQuery('#video-head').html("Sorry!!! Video not available in this language at the moment!!!")
		jQuery('#vidframe').attr('src','http://en.staging.heartfulness.org/video-not-available.php');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayJapaneese.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayJapaneese.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Gujarati"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/0frw4Ha-yTI?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayGujarathi.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayGujarathi.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Tamil"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/CqJQh5dcZ8k?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayTamil.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayTamil.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Telugu"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/5JFdNxhOxlI?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayTelugu.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayTelugu.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="French"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/R6C3RdfDwDk?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayFrench.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayFrench.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Russian"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/p_cX8V2i6jQ?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayRussian.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayRussian.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Portuguese"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/tS6hPpSSziA?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayPortugese.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayPortugese.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Spanish"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/LJV5Fi7heiQ?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDaySpanish.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDaySpanish.mp3');
		jQuery('#audiocontrols').load();
	}
	else if(vid=="Chinese"){
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/rGXwvMfXhxk?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayChinese.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayChinese.mp3');
		jQuery('#audiocontrols').load();
	}
	else{
		jQuery('#vidframe').attr('src','https://www.youtube.com/embed/i8f1Ggx8jM0?rel=0');
		jQuery('#downloadaudio').attr('href','http://en.staging.heartfulness.org/download.php?id=UNWorldPeaceDayEnglish.mp3');
		jQuery('#audiosource').attr('src','http://d1esl92dm1pxp6.cloudfront.net/WorldPeaceDay/UNWorldPeaceDayEnglish.mp3');
		jQuery('#audiocontrols').load();
	}
	return false;
});
/*End Code*/
jQuery(document).ready(function(){
	jQuery(".wr-dropdown-Others").removeAttr('rows');
	var profileBaseURL="http://en-in.staging.heartfulness.org/";
//To show dropdown when India country is selected.
	jQuery('#ind-state').hide();
	if(jQuery('#id_country').val()!="")
{
if(jQuery('#id_country').val()=="India")
{
jQuery('#div_id_state').hide();
}
else
{
jQuery('#div_id_indstate').hide();
}
}
else
{
jQuery('#div_id_indstate').hide();
}
	jQuery("#registration_id_country").live('change', function(){
	if(jQuery(this).val()== 'India')
	{
	jQuery('#other-state').hide();
	jQuery('#other-state input').val(' ');
	jQuery('#ind-state').show();

	}
	else {
	jQuery('#other-state').show();
	jQuery('#ind-state').hide();
	jQuery('#ind-state select').val("");
	}
	});
	jQuery("#id_country").live('change', function(){
	if(jQuery(this).val()== 'India')
	{
	jQuery('#div_id_state').hide();
	jQuery('#div_id_indstate').show();
	}
	else{
	jQuery('#div_id_state').show();
	jQuery('#div_id_indstate').hide();
	}
	});





//To validate the email field in the registration form
function validateEmail(regemail) {
var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
if (filter.test(regemail)) {
return true;
}
else {
return false;
}
}
//To accept only numbers in contact number field
jQuery("#registration_id_contactnumber").bind("keydown", function (event)
 {
    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || (event.keyCode == 65 && event.ctrlKey === true) ||  (event.keyCode >= 35 && event.keyCode <= 39))
	{
               return;
    }
	else
	{

            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ))
			{
                event.preventDefault();
            }
    }
 });

	jQuery("#registerNow").click(function(){

		var allisfill=true;
		var fname= jQuery("#registration_id_first_name").val();
		if(/^[a-zA-Z ]*$/.test(jQuery('#registration_id_first_name').val()) == false || fname.length<2 || fname.length>25 || jQuery.isNumeric(jQuery("#registration_id_first_name").val()))
			{

				jQuery( ".fnameerrmsg" ).text( "First Name should contain only alphabets(2-25)" );
				allisfill=false;
			}
			else
			{
				jQuery( ".fnameerrmsg ").text("");
				jQuery("#id_first_name").val(fname);
			}
		var lname= jQuery("#registration_id_last_name").val();
		if(/^[a-zA-Z ]*$/.test(jQuery('#registration_id_last_name').val()) == false || lname.length<2 || lname.length>25 || jQuery.isNumeric(jQuery("#registration_id_last_name").val()))
			{

				jQuery( ".lnameerrmsg" ).text( "Last Name should contain only alphabets(2-25)" );
				allisfill=false;
			}
			else
			{
				jQuery(".lnameerrmsg").text("");
				jQuery("#id_last_name").val(lname);
			}
		var regemail= jQuery("#registration_id_email").val();
		if(jQuery.trim(regemail).length == 0)
		{

			jQuery(".emailerrmsg").text("Email is required");
			allisfill=false;

		}
		else
		{
			if (validateEmail(regemail)) {
			jQuery(".emailerrmsg").text("");
			jQuery("#id_email").val(regemail);
			jQuery("#profile_id_login").val(regemail);
			}
			else {
				jQuery(".emailerrmsg").text("Invalid EmailID");

				allisfill=false;

			}
		}
		var regcno= jQuery.trim(jQuery("#registration_id_contactnumber").val());
		if(regcno=="")
		{

			jQuery(".cnoerrmsg").text("Contact Number is required");
			allisfill=false;

		}
		else
		{
			jQuery(".cnoerrmsg").text("");
		}
		var regcountry= jQuery("#registration_id_country").val();
		if(regcountry=="")
		{

			jQuery(".countryerrmsg").text("Country is required");
			allisfill=false;

		}
		else
		{
			jQuery(".countryerrmsg").text("");
		}
		if(regcountry=="India")
		{
		var regstate=jQuery("#registration_id_indstate").val();
		if(regstate=="")
			{

				jQuery( ".stateerrmsg" ).text( "State is required" );
				allisfill=false;
			}
		else
		{
			jQuery(".stateerrmsg").text("");
		}
		}
		if(regcountry!="India")
		{
			var regstate=jQuery("#registration_id_otherstate").val();
			if(/^[a-zA-Z ]*$/.test(jQuery('#registration_id_otherstate').val()) == false || jQuery('#registration_id_otherstate').val().length<2 || jQuery('#registration_id_otherstate').val().length>25 || jQuery.isNumeric(jQuery("#registration_id_otherstate").val()))
			{

				jQuery( ".otherstateerrmsg" ).text( "State should contain only alphabets(2-25)" );
				allisfill=false;
			}
		else
		{
			jQuery(".otherstateerrmsg").text("");
		}
		}

		var regcity= jQuery("#registration_id_city").val();
		if(/^[a-zA-Z ]*$/.test(jQuery('#registration_id_city').val()) == false || regcity.length<2 || regcity.length>25 || jQuery.isNumeric(jQuery("#registration_id_city").val()))
			{



				jQuery( ".cityerrmsg" ).text( "City should contain only alphabets(2-25)" );
					allisfill=false;

			}
			else
			{
				jQuery(".cityerrmsg").text("");
			}

		var message=jQuery("#registration_id_message").val();
		if(message=="")
		{

			jQuery(".msgserrmsg").text("Message is requied");
			allisfill=false;

		}
		else
		{
			jQuery(".msgserrmsg").text("");
		}
		console.log(allisfill);
		if(allisfill){
		console.log(allisfill);

		if(jQuery("#profile_access_token").val() != '')
		{
		console.log(fname);
		var access = jQuery("#profile_access_token").val();
		jQuery.ajax({url: profileBaseURL+"profile/accesstokenregistration.php",
		 data: jQuery('#register_form').serialize(),
		 type: 'POST',
		 success: function(result){
			 var res = result;
			 console.log(res);
			if(res.search("Registered") >= 0){
			var result1 = '<h4 style="color: white;padding: 25px;background: darkseagreen;font-size: 20px;border-radius: 10px;">'+result+'</h4>';
			jQuery("#registerform").html(result1);
			return false;
			}
			else{
			jQuery(".errmsgspn").html(result);
			return false;
			}

			}});
			}else{
		jQuery("#resname").val(fname+" "+lname);
		jQuery("#resemail").val(regemail);
		jQuery("#resnumber").val(regcno);
		jQuery("#rescountry").val(regcountry);
		jQuery("#resstate").val(regstate);
		jQuery("#rescity").val(regcity);
		jQuery("#resmsg").val(message);

		jQuery("#resname1").val(fname+" "+lname);
		jQuery("#resemail1").val(regemail);
		jQuery("#resnumber1").val(regcno);
		jQuery("#rescountry1").val(regcountry);
		jQuery("#resstate1").val(regstate);
		jQuery("#rescity1").val(regcity);
		jQuery("#resmsg1").val(message);

		jQuery("#dialog").css("display:block");
		jQuery("#dialog").dialog({ height: 520,   width: 650});
		console.log('After Dialog');
		}
		return false;
		}
		return false;
	});

	jQuery("#popuploginform").submit(function(){

	var allisok=true;
		var vid= jQuery("#profile_id_login").val();
		if(vid=="")
		{

		jQuery( this ).find( ".usererrmsg" ).text("Please enter username");
		allisok=false;
		}
		else
		{
			jQuery( this ).find( ".usererrmsg" ).text("");
		}
		var pwd= jQuery("#profile_id_password").val();
		if(pwd=="")
		{

			jQuery( this ).find( ".passerrmsg" ).text("Please enter password");
			allisok=false;

		}
		else
		{
			jQuery( this ).find( ".passerrmsg" ).text("");
		}
		 console.log(allisok);
		if(allisok)
		{
		 jQuery.ajax({url: profileBaseURL+"profile/popuplogin.php",
		 data: jQuery('#popuploginform').serialize(),
		 type: 'POST',
		 success: function(result){
			 var res = result;
			 console.log(res);
			if(res.search("Registered") >= 0){
			var result1 = '<h4 style="color: white;padding: 25px;background: darkseagreen;font-size: 20px;border-radius: 10px;">'+result+'</h4>';
			jQuery("#dialog").dialog("close");
			jQuery("#registerform").html(result1);
			}
			else{
			jQuery(".passerrmsg").html(result);
			return false;
			}

			}});
		return false;

		}else
		{
		return allisok;
		}
	});


	jQuery("#signup_form_popup").submit(function(){

	var allisok=true;

	if(jQuery("#id_first_name").val()==""){
		jQuery(".fnameerror").text("First Name should not be empty");
		allisok=false;

	}else
		jQuery(".fnameerror").text("");


	if(jQuery("#id_last_name").val()==""){
		jQuery(".lnameerror").text("Last Name should not be empty");
		allisok=false;

	}else
		jQuery(".lnameerror").text("");


	if(jQuery("#id_email").val()==""){
		jQuery(".emailerror").text("Email should not be empty");
		allisok=false;
	}else if(!validateEmail(jQuery("#id_email").val())) {
		jQuery(".emailerror").text("Invalid Email");
		allisok=false;
	}
	else
		jQuery(".emailerror").text("");


		var passwordlength= jQuery("#signup_password1").val().length;
		if(passwordlength > 7)

		{
			jQuery(".passerrmsg1" ).text("");
		}
		else
		{

			jQuery( ".passerrmsg1" ).text("Password should contain minimum 8 characters");
			allisok=false;
		}
		var conformpasslength= jQuery("#signup_password2").val().length;
		if(conformpasslength > 7)

		{
			jQuery(".conformpasserrmsg" ).text("");
		}
		else
		{

			jQuery( ".conformpasserrmsg" ).text("Password should contain minimum 8 characters");
			allisok=false;
		}

		if(jQuery("#signup_password1").val()!=jQuery("#signup_password2").val()){
			jQuery( ".conformpasserrmsg" ).text("Password Mismatch");
			allisok=false;

		}
		else
			jQuery(".conformpasserrmsg" ).text("");

		 console.log(allisok);
		if(allisok)
		{
		 jQuery.ajax({url: profileBaseURL+"profile/popupsignup.php",
		 data: jQuery('#signup_form_popup').serialize(),
		 type: 'POST',
		 success: function(result){
			 var res = result;
			 console.log(res);
			if(res.search("Profile") >= 0){
			var result1 = '<h4 style="color: white;padding: 25px;background: darkseagreen;font-size: 20px;border-radius: 10px;">'+result+'</h4>';
			jQuery("#dialog").dialog("close");
			jQuery("#registerform").html(result1);

			}
			else{
			jQuery(".signuperr").html(result);
			return false;
			}

			}});
		return false;

		}else
		{
		return allisok;
		}
	});




	var profile_first_name=jQuery('#profile_id_first_name').val();
	if(profile_first_name!="")
		{
			jQuery("#registration_id_first_name").val(profile_first_name).attr('readonly',true);
			jQuery("#registration_id_first_name").css("background-color","#F0FFF0");
		}
	var profile_last_name=jQuery('#profile_id_last_name').val();
	if(profile_last_name!="")
		{
			jQuery("#registration_id_last_name").val(profile_last_name).attr('readonly',true);
			jQuery("#registration_id_last_name").css("background-color","#F0FFF0");

		}
	var profile_email=jQuery('#profile_id_email').val();
	if(profile_email!="")
	{
		jQuery("#registration_id_email").val(profile_email).attr('readonly',true);
		jQuery("#registration_id_email").css("background-color","#F0FFF0");

	}
	var profile_contact_number=jQuery('#profile_id_contact_number').val();
	if(profile_contact_number!="")
	{
		jQuery("#registration_id_contactnumber").val(profile_contact_number);

	}
	var profile_country=jQuery('#profile_id_country').val();
	if(profile_country!="")
	{
		jQuery("#registration_id_country").val(profile_country);

	}
	var profile_state=jQuery('#profile_id_state').val();
	if(profile_state!="")
	{
	if(profile_country =="India"){
		jQuery('#other-state').hide();
		jQuery('#ind-state').show();
		//jQuery("#registration_id_indstate :selected").text(profile_state);
		jQuery("#registration_id_indstate option[value='"+profile_state+"']").attr("selected","true");
		}
	else
		jQuery("#registration_id_otherstate").val(profile_state);
	}
	var profile_city=jQuery('#profile_id_city').val();
	if(profile_city!="")
	{
		jQuery("#registration_id_city").val(profile_city);

	}
	if( profile_first_name!=""|| profile_last_name!="" || profile_email!="")
	{
	jQuery("#textmsg").css("display","none");
	}
	else
	{
	jQuery("#textmsg").css("display","block");
	}

	jQuery("#loginform").submit(function(){

		var allisok=true;
		var vid= jQuery("#id_login").val();
		if(vid=="")
		{

		jQuery( this ).find( ".usererrmsg" ).text("Please enter username");
		allisok=false;
		}
		else
		{
			jQuery( this ).find( ".usererrmsg" ).text("");
		}
		var pwd= jQuery("#id_password").val();
		if(pwd=="")
		{

			jQuery( this ).find( ".passerrmsg" ).text("Please enter password");
			allisok=false;

		}
		else
		{
			jQuery( this ).find( ".passerrmsg" ).text("");
		}
		if(allisok)
		{
		 jQuery('#loginpreloaderimg').show();
		 jQuery('#loginsubmit').prop("disabled",true);
		 jQuery.ajax({url: profileBaseURL+"profile/login.php",
		 data: jQuery('#loginform').serialize(),
		 type: 'POST',
		 success: function(result){

			 jQuery('#loginsubmit').prop("disabled",false);
			 jQuery('#loginpreloaderimg').hide();
		     var res = result;
			 console.log(res);
			if(res.match(/success/gi)){
			window.location.href = profileBaseURL;
			}
			else{
			jQuery(".passerrmsg").html(result);
			return false;
			}

			}});
		return false;

		}else
		{
		return allisok;
		}
	});

	jQuery("#signup_form").submit(function(){
		var allisokay=true;
		var firstname= jQuery("#id_first_name").val();
		if(firstname=="")
		{

		jQuery( this ).find( ".firstname" ).text("Firstname is required");
		allisokay=false;
		}
		else
		{
			jQuery( this ).find( ".firstname" ).text("");
		}
		var lastname= jQuery("#id_last_name").val();
		if(lastname=="")
		{

		jQuery( this ).find( ".lastname" ).text("Lastname is required");
		allisokay=false;
		}
		else
		{
			jQuery( this ).find( ".lastname" ).text("");
		}

		/*var memname= jQuery("#id_idnumber").val();
		if(memname=="")
		{

		jQuery( ".membername" ).text("Please enter Membername");
		allisokay=false;
		}
		else{
			jQuery( ".membername" ).text("");
		}*/
		var email= jQuery("#id_email").val();
		if(email=="")
		{

		jQuery(".usererrmsg" ).text("EmailID is required");
		allisokay=false;
		}
		else{
			jQuery( ".usererrmsg" ).text("");
		}

		var passwordlength= jQuery("#id_password1").val().length;
		if(passwordlength > 7)

		{
			jQuery(".passerrmsg" ).text("");
		}
		else
		{

			jQuery( ".passerrmsg" ).text("Password should contain minimum 8 characters");
			allisokay=false;
		}
		var conformpasslength= jQuery("#id_password2").val().length;
		if(conformpasslength > 7)

		{
			jQuery(".conformpasserrmsg" ).text("");
		}
		else
		{

			jQuery( ".conformpasserrmsg" ).text("Password should contain minimum 8 characters");
			allisokay=false;
		}

		if(jQuery("#id_password1").val()!=jQuery("#id_password2").val()){
			jQuery( ".conformpasserrmsg" ).text("Password Mismatch");
			allisokay=false;

		}
		else
			jQuery(".conformpasserrmsg" ).text("");

		var recaptcha= jQuery("#recaptcha_response_field").val();
		if(recaptcha=="")
		{

		jQuery( ".recaptchasignup" ).text("Captcha  is required");
		allisokay=false;
		}
		else
		{
			jQuery( ".recaptchasignup" ).text("");
		}


		return allisokay;

	});
	/*home signupform Validation*/
	jQuery("#signuphome_button").click(function(){
		        var baseUrl = 'http://en.staging.heartfulness.org/';
				var name = jQuery("#signup_id_first_name").val();
                var lname = jQuery("#signup_id_last_name").val();
                var email = jQuery("#signup_id_email").val();
				var dataString = 'firstName='+ name + '&lastName='+ lname + '&userEmail='+ email;
				var allisoky=true;

	if(jQuery("#signup_id_first_name").val()==""){
		jQuery(".signup_fnameerrmsg").text("First Name should not be empty");
		allisoky=false;

	}else
		jQuery(".signup_fnameerrmsg").text("");



	if(jQuery("#signup_id_last_name").val()==""){
		jQuery(".signup_lnameerrmsg").text("Last Name should not be empty");
		allisoky=false;

	}else
		jQuery(".signup_lnameerrmsg").text("");


	if(jQuery("#signup_id_email").val()==""){
		jQuery(".signup_emailerrmsg").text("Email should not be empty");
		allisoky=false;
	}else if(!validateEmail(jQuery("#signup_id_email").val())) {
		jQuery(".signup_emailerrmsg").text("Invalid Email");
		allisoky=false;
	}
	else
		jQuery(".signup_emailerrmsg").text("");
	console.log(allisoky);
	if(allisoky)
		{

		 jQuery.ajax({url:baseUrl+"civi/subscribeProcess.php",
		 type: 'POST',
		 data: dataString,
		 cache: false,
		 beforeSend: function(){
			 jQuery('#wait').show();
			 jQuery("#signuphome_button").prop('disabled', true);
		 },
 // complete: function(){ //}
	 	 success: function(result){
		  jQuery('#wait').hide();
		  jQuery("#signuphome_button").prop('disabled', false);
			 var results = result;
						if(result == 'Thanks for Subscribing'){
	var result1 = '<div style="padding:40px;"><h4 style="text-align:center;color:white;padding:21px;background: darkseagreen;font-size: 20px;border-radius: 10px;">'+results+'</h4></div>';
			jQuery("#signupelement").css('background-color','white');
			jQuery("#signupelement").html(result1);

			}
			else{
			jQuery(".signup_errmsgspn").html(result);
			return false;
			}

			}});
		return false;

		}
		else
		{
		return allisoky;
		}
});
/* Unsubscribe Newsletter */
jQuery("#unsubscribe_button").click(function(){
	            var baseUrl = 'http://en.heartfulness.org/';
	            var name = jQuery("#unsubscribe_id_first_name").val();
                var lname = jQuery("#unsubscribe_id_last_name").val();
                var email = jQuery("#unsubscribe_id_email").val();
				var dataString = 'firstName='+ name + '&lastName='+ lname + '&userEmail='+ email;

	var alliswell=true;

	var unsubscribe_firsr_name=jQuery('#unsubscribe_id_first_name').val();
	if(/^[a-zA-Z ]*$/.test(jQuery('#unsubscribe_id_first_name').val()) == false || unsubscribe_firsr_name.length<2 || unsubscribe_firsr_name.length>25 || jQuery.isNumeric(jQuery("#unsubscribe_id_first_name").val()))
	{
		jQuery(".unsubscribe_fnameerrmsg").text("First Name should not be empty");
		alliswell=false;

	}else
		jQuery(".unsubscribe_fnameerrmsg").text("");


	var unsubscribe_last_name=jQuery("#unsubscribe_id_last_name").val();
	if(/^[a-zA-Z ]*$/.test(jQuery('#unsubscribe_id_last_name').val()) == false || unsubscribe_last_name.length<2 || unsubscribe_last_name.length>25 || jQuery.isNumeric(jQuery("#unsubscribe_id_last_name").val()))
	{
		jQuery(".unsubscribe_lnameerrmsg").text("Last Name should not be empty");
		alliswell=false;

	}else
		jQuery(".unsubscribe_lnameerrmsg").text("");

	if(jQuery("#unsubscribe_id_email").val()==""){
		jQuery(".unsubscribe_emailerrmsg").text("Email should not be empty");
		alliswell=false;
	}else if(!validateEmail(jQuery("#unsubscribe_id_email").val())) {
		jQuery(".unsubscribe_emailerrmsg").text("Invalid Email");
		alliswell=false;
	}
	else
		jQuery(".unsubscribe_emailerrmsg").text("");
	//console.log(alliswell);
	if(alliswell)
		{
		  jQuery.ajax({url:baseUrl+"civi/unsubscribeProcess.php",
		  type: 'POST',
		  data: dataString,
		  cache: false,
		  beforeSend: function(){
			 jQuery('#wait').show();
			 jQuery("#unsubscribe_button").prop('disabled', true);
		 },
		  success: function(result){
		   jQuery('#wait').hide();
		   jQuery("#unsubscribe_button").prop('disabled', false);
			var results = result;
			if(result == 'You are unsubscribed successfully')
			{
			var result1 = '<div><h4 style="text-align:center;color: white;padding: 21px;background: darkseagreen;font-size: 20px;border-radius: 10px;">'+results+'</h4></div>';
			jQuery("#signupelement").css('background-color','white');
			jQuery("#signupelement").html(result1);

			}
			else{
			jQuery(".unsubscribe_errmsgspn").html(result);
			return false;
			}

			}});
		return false;

		}
		else
		{
		return alliswell;
		}
});
/* Registration page */
jQuery("#registrationNow").click(function(){

		var allisfill=true;
		var fname= jQuery("#registration_id_firstname").val();
		if(/^[a-zA-Z ]*$/.test(jQuery('#registration_id_firstname').val()) == false || fname.length<2 || fname.length>25 || jQuery.isNumeric(jQuery("#registration_id_firstname").val()))
			{

				jQuery( ".fnameerrmsg" ).text( "First Name should contain only alphabets(2-25)" );
				allisfill=false;
			}
			else
			{
				jQuery( ".fnameerrmsg ").text("");

			}
		var lname= jQuery("#registration_id_lastname").val();
		if(/^[a-zA-Z ]*$/.test(jQuery('#registration_id_lastname').val()) == false || lname.length<2 || lname.length>25 || jQuery.isNumeric(jQuery("#registration_id_lastname").val()))
			{

				jQuery( ".lnameerrmsg" ).text( "Last Name should contain only alphabets(2-25)" );
				allisfill=false;
			}
			else
			{
				jQuery(".lnameerrmsg").text("");

			}
		var regemail= jQuery("#registration_id_emailid").val();
		if(jQuery.trim(regemail).length == 0)
		{

			jQuery(".emailerrmsg").text("Email is required");
			allisfill=false;

		}
		else
		{
			if (validateEmail(regemail)) {
			jQuery(".emailerrmsg").text("");
			/*jQuery("#id_email").val(regemail);
			 jQuery("#profile_id_login").val(regemail); */
			}
			else {
				jQuery(".emailerrmsg").text("Invalid EmailID");

				allisfill=false;

			}
		}
		var regcno= jQuery.trim(jQuery("#registration_id_contactnumber").val());
		if(regcno=="")
		{

			jQuery(".cnoerrmsg").text("Contact Number is required");
			allisfill=false;

		}
		else
		{
			jQuery(".cnoerrmsg").text("");
		}
		var regcountry= jQuery("#registration_id_country").val();
		if(regcountry=="")
		{

			jQuery(".countryerrmsg").text("Country is required");
			allisfill=false;

		}
		else
		{
			jQuery(".countryerrmsg").text("");
		}
		if(regcountry=="India")
		{
		var regstate=jQuery("#registration_id_indstate").val();
		if(regstate=="")
			{

				jQuery( ".stateerrmsg" ).text( "State is required" );
				allisfill=false;
			}
		else
		{
			jQuery(".stateerrmsg").text("");
		}
		}
		if(regcountry!="India")
		{
			var regstate=jQuery("#registration_id_otherstate").val();
			if(/^[a-zA-Z ]*$/.test(jQuery('#registration_id_otherstate').val()) == false || jQuery('#registration_id_otherstate').val().length<2 || jQuery('#registration_id_otherstate').val().length>25 || jQuery.isNumeric(jQuery("#registration_id_otherstate").val()))
			{

				jQuery( ".otherstateerrmsg" ).text( "State should contain only alphabets(2-25)" );
				allisfill=false;
			}
		else
		{
			jQuery(".otherstateerrmsg").text("");
		}
		}

		var regcity= jQuery("#registration_id_cities").val();
		if(/^[a-zA-Z ]*$/.test(jQuery('#registration_id_cities').val()) == false || regcity.length<2 || regcity.length>25 || jQuery.isNumeric(jQuery("#registration_id_cities").val()))
			{



				jQuery( ".cityerrmsg" ).text( "City should contain only alphabets(2-25)" );
					allisfill=false;

			}
			else
			{
				jQuery(".cityerrmsg").text("");
			}

		var message=jQuery("#registration_id_messages").val();
		if(message=="")
		{

			jQuery(".msgserrmsg").text("Message is requied");
			allisfill=false;

		}
		else
		{
			jQuery(".msgserrmsg").text("");
		}

		/* var recaptchas= jQuery("#recaptcha_response_field").val();
		if(recaptchas=="")
		{

		jQuery( ".recaptcha" ).text("Captcha  is required");
		allisfill=false;
		}
		else
		{
			jQuery( ".recaptcha" ).text("");
		} */

		if(grecaptcha.getResponse() == '')
		{
		jQuery(".recaptcha").text("Captcha is required");
		allisfill=false;
        } else {
             jQuery(".recaptcha").text();
         }
		console.log(allisfill);
		var captchavalue=jQuery("#captchafield").val();
		console.log(captchavalue);
			if(allisfill)
		{
		 jQuery.ajax({url:"/profile/registration.php",
		 data: jQuery('#dummyregister_form').serialize(),
		 type: 'POST',
		 success: function(result){
			 var results = result;
			 console.log(results);
			if(results.search("Registered") >= 0){
			var result1 = '<div><h4 style="text-align:center;color: white;padding: 21px;background: darkseagreen;font-size: 20px;border-radius: 10px;">'+results+'</h4></div>';
			jQuery("#registrationelement").css('background-color','white');
			jQuery("#registrationelement").html(result1);
			console.log(captchavalue);
			}
			else{
			jQuery(".registration_errmsgspn").html(result);
			return false;
			}

			}});
		return false;

		}
		else
		{
		return allisfill;
		}
	});
	/*Connect subscribe form*/
	jQuery(".alert-success").hide();
	jQuery(".alert-danger").hide();
	jQuery(".login-button").click(function(){
			jQuery(".alert-success").hide();
			jQuery(".alert-danger").hide();
			var baseUrl = 'http://en.heartfulness.org/';

			var allisokies=true;


			if(jQuery("#firstName").val()==""){
				jQuery(".errmsgfname").text("First Name should not be empty");
				allisokies=false;
			}else
				jQuery(".errmsgfname").text("");


			if(jQuery("#lastName").val()==""){
				jQuery(".errmsglname").text("Last Name should not be empty");
				allisokies=false;
			}else
				jQuery(".errmsglname").text("");


			if(jQuery("#userEmail").val()==""){
				jQuery(".errmsgemail").text("Email should not be empty");
				allisokies=false;
			}else if(!validateEmail(jQuery("#userEmail").val())) {
				jQuery(".errmsgemail").text("Invalid Email");
				allisokies=false;
			}
			else
				jQuery(".errmsgemail").text("");

			if(jQuery("#country").val()==""){
				jQuery(".errmsgcountry").text("Country should not be empty");
				allisokies=false;
			}else
				jQuery(".errmsgcountry").text("");

			if(jQuery('#g-recaptcha-response').val()==""){
				jQuery(".errmsgcaptcha").text("Captcha should not be empty");
				allisokies=false;
			}else
				jQuery(".errmsgcaptcha").text("");

			if(allisokies){
			jQuery.ajax({url:baseUrl+"register/registerProcess.php",
				 type: 'POST',
				 data: jQuery('#regform').serialize(),
				 cache: false,
				 beforeSend: function(){
			     jQuery('#wait').show();
			     jQuery(".login-button").prop('disabled', true);
		         },
				 success: function(result){
						jQuery('#wait').hide();
						jQuery(".login-button").prop('disabled', false);
						if(result == 'Thanks for registration'){
						/*var result1 = '<div style="padding:40px;"><h4 style="text-align:center;color:white;padding:21px;background: darkseagreen;font-size: 20px;border-radius: 10px;">'+results+'</h4></div>';*/
						var result1 = '<div style=""><h4 style="text-align:center;color:white;padding:20px;line-height: 1.4;background: darkseagreen;font-size: 20px;border-radius: 10px;"><a href="http://en.heartfulness.org/" style="text-decoration:none;"><p>Thank you for registering<br/>Please check your Inbox for a welcome email</p></a></h4></div>';
						jQuery("#registersub ").html(result1);
						//jQuery(".alert-success").html(result);
						//jQuery(".alert-success").show();

						}
						else{
						//var result1 = '<div style=""><h4 style="text-align:center;color:white;padding:20px;line-height: 1.4;background: darkseagreen;font-size: 20px;border-radius: 10px;"><a href="http://en.heartfulness.org/" style="text-decoration:none;">'+result+'</a></h4></div>';
						//jQuery("#registersub").html(result1);
						jQuery(".alert-danger").html(result);
						jQuery(".alert-danger").show();
						}
					}
				});
				return false;

			}
			return false;
			});
});

/*bala
jQuery("#wpdvideoen").click(function(){
	var vid= jQuery("#wpdvideoen").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});
jQuery("#wpdvideohi").click(function(){
	var vid= jQuery("#wpdvideohi").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});
jQuery("#wpdvideogu").click(function(){
	var vid= jQuery("#wpdvideogu").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});
jQuery("#wpdvideota").click(function(){
	var vid= jQuery("#wpdvideota").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});

jQuery("#wpdvideote").click(function(){
	var vid= jQuery("#wpdvideote").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});
jQuery("#wpdvideofr").click(function(){
	var vid= jQuery("#wpdvideofr").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});
jQuery("#wpdvideoru").click(function(){
	var vid= jQuery("#wpdvideoru").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});
jQuery("#wpdvideopo").click(function(){
	var vid= jQuery("#wpdvideopo").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});
jQuery("#wpdvideosp").click(function(){
	var vid= jQuery("#wpdvideosp").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});
jQuery("#wpdvideoch").click(function(){
	var vid= jQuery("#wpdvideoch").attr('href');
	jQuery('#vidframe').attr('src', vid);
	return false;
});


jQuery("#linkwpdvid").click(function(){
//$('body').scrollTo('#wpdvid',{duration:'slow', offsetTop : '80'});
			var topOffest		= (jQuery('body').hasClass('admin-bar')) ? 32 : 0;
			var headerheight	= (jQuery('#main-nav').length) ? (jQuery('#main-nav').height() + 34) : 0;
			var scrollOffset	= targetOffset - (headerheight + topOffest);
			jQuery('html, body').stop().animate({'scrollTop': scrollOffset}, 1200);
});
jQuery("#linkwpdaud").click(function(){
//$('body').scrollTo('#wpdvid',{duration:'slow', offsetTop : '80'});
			var topOffest		= (jQuery('body').hasClass('admin-bar')) ? 32 : 0;
			var headerheight	= (jQuery('#main-nav').length) ? (jQuery('#main-nav').height() + 34) : 0;
			var targetOffset	= jQuery("#wpdaud").offset().top;
			var scrollOffset	= targetOffset - (headerheight + topOffest);
			jQuery('html, body').stop().animate({'scrollTop': scrollOffset}, 1200);
});

*/
	/*india*/
	jQuery('.ind-state').addClass('hide');
	jQuery('#53').live('change', function(){

	if(jQuery(this).val()== 'India')
	{
	jQuery('.other-state').addClass('hide');
	jQuery('.other-state input').val(' ');
	jQuery('.ind-state').removeClass('hide');

	}
	else {
	jQuery('.other-state').removeClass('hide');
	jQuery('.ind-state').addClass('hide');
	jQuery('.ind-state select').val("");
	}
	});
	jQuery('.maharashtra_city').addClass('hide');
	jQuery('#52').live('change', function(){
	var city=jQuery("#52").val();
	if(city=="Maharashtra ")
	{
	jQuery(".PersonCity1").addClass('hide');
	jQuery('.PersonCity1 input').val(' ');
	jQuery(".maharashtra_city").removeClass('hide');

	}
	else{
	jQuery(".PersonCity1").removeClass('hide');
	jQuery(".maharashtra_city").addClass('hide');
	jQuery('.maharashtra_city select').val("");

	}
	});
	/*french*/
	jQuery('#94').live('change', function(){

	if(jQuery(this).val()== 'Inde')
	{
	jQuery('.other-state').addClass('hide');
	jQuery('.other-state input').val(' ');
	jQuery('.ind-state').removeClass('hide');

	}
	else {
	jQuery('.other-state').removeClass('hide');
	jQuery('.ind-state').addClass('hide');
	jQuery('.ind-state select').val("");
	}
	});

	/*italy*/
	jQuery('#104').live('change', function(){

	if(jQuery(this).val()== 'India')
	{
	jQuery('.other-state').addClass('hide');
	jQuery('.other-state input').val(' ');
	jQuery('.ind-state').removeClass('hide');

	}
	else {
	jQuery('.other-state').removeClass('hide');
	jQuery('.ind-state').addClass('hide');
	jQuery('.ind-state select').val("");
	}
	});
/*bala*/


	/*Karthi: Name,City,State validation */
	/*English*/
	jQuery("#wr_form_53").find(".jsn-form-submit").click(function(){
		var result=true;
		if(/^[a-zA-Z ]*$/.test(jQuery('.PersonName .controls input').val()) == false || jQuery(".PersonName .controls input").val().length<2 || jQuery(".PersonName .controls input").val().length>25 || jQuery.isNumeric(jQuery(".PersonName .controls input").val())) {
			if(jQuery('.PersonName').find('.validationSpanName').length==0 ){
				jQuery( ".PersonName" ).append( "<span class='validationSpanName validation-result label label-important'> Name should contain only alphabets(2-25)</span>" );
				result=false;
			}
			else if(jQuery('.PersonName').find('.validationSpanName').length>0 ){
				jQuery( ".validationSpanName" ).replaceWith( "<span class='validationSpanName validation-result label label-important'> Name should contain only alphabets(2-25)</span>" );
				result=false;
			}
		}
		else
			jQuery( ".validationSpanName" ).replaceWith( "" );

		if(jQuery("#52").val()!= "Maharashtra ")
		{
		if(/^[a-zA-Z ]*$/.test(jQuery('.PersonCity .controls input').val()) == false || jQuery(".PersonCity .controls input").val().length<2 || jQuery(".PersonCity .controls input").val().length>25 || jQuery.isNumeric(jQuery(".PersonCity .controls input").val())){
			if(jQuery('.PersonCity').find('.validationSpanCity').length==0 ){
				jQuery( ".PersonCity" ).append( "<span class='validationSpanCity validation-result label label-important'> City should contain only alphabets(2-25)</span>" );
				result = false;
			}
			else if(jQuery('.PersonCity').find('.validationSpanCity').length>0){
				jQuery( ".validationSpanCity" ).replaceWith( " <span class='validationSpanCity validation-result label label-important'> City should contain only alphabets(2-25)</span>" );
				result = false;
			}
		}
		else
			jQuery( ".validationSpanCity" ).replaceWith( "" );
		}




		if(jQuery('#53').val()!="India"){
			if(/^[a-zA-Z ]*$/.test(jQuery('.other-state .controls input').val()) == false || jQuery(".other-state .controls input").val().length<2 || jQuery(".other-state .controls input").val().length>25 || jQuery.isNumeric(jQuery(".other-state .controls input").val())){
				if(jQuery('.other-state').find('.validationSpanState').length==0){
					jQuery( ".other-state" ).append( "<span class='validationSpanState validation-result label label-important'> State should contain only alphabets(2-25).</span>" );
					result = false;
				}
				else if(jQuery('.other-state').find('.validationSpanState').length>0){
					jQuery( ".validationSpanState" ).replaceWith( "<span class='validationSpanState validation-result label label-important'> State should contain only alphabets(2-25).</span>" );
					result = false;

				}
			}
			else
				jQuery( ".validationSpanState" ).replaceWith( "" );
		}
		if(jQuery('#53').val()=="India"){
			if(jQuery('.ind-state .controls select').val() == ""){
				if(jQuery('.ind-state').find('.validationSpanStateind').length==0){
					jQuery( ".ind-state" ).append( "<span class='validationSpanStateind validation-result label label-important'> This field can not be empty, please enter required information.</span>" );
					result = false;
				}
				else if(jQuery('.ind-state').find('.validationSpanStateind').length>0){
					jQuery( ".validationSpanStateind" ).replaceWith( "<span class='validationSpanStateind validation-result label label-important'> This field can not be empty, please enter required information.</span>" );
					result = false;

				}
			}
			else
				jQuery( ".validationSpanStateind" ).replaceWith( "" );
		}
		if(result)
			return true;
		else
			return false;

	});

	jQuery(".control-group .controls .phone").attr('maxlength','15'); //English and Italiy:phone Number max length is 15
	jQuery(".control-group .controls .number").attr('maxlength','15'); //French



	/*French*/
	jQuery("#wr_form_1964").find(".jsn-form-submit").click(function(){
		var result=true;
		if(/^[a-zA-Z]*$/.test(jQuery('.PersonName .controls input').val()) == false || jQuery(".PersonName .controls input").val().length<2 || jQuery(".PersonName .controls input").val().length>25 || jQuery.isNumeric(jQuery(".PersonName .controls input").val())) {
			if(jQuery('.PersonName').find('.validationSpanName').length==0 ){
				jQuery( ".PersonName" ).append( "<span class='validationSpanName validation-result label label-important'> Name should contain only alphabets(2-25)</span>" );
				result=false;
			}
			else if(jQuery('.PersonName').find('.validationSpanName').length>0 ){
				jQuery( ".validationSpanName" ).replaceWith( "<span class='validationSpanName validation-result label label-important'> Name should contain only alphabets(2-25)</span>" );
				result=false;
			}
		}
		else
			jQuery( ".validationSpanName" ).replaceWith( "" );


		if(/^[a-zA-Z]*$/.test(jQuery('.PersonCity .controls input').val()) == false || jQuery(".PersonCity .controls input").val().length<2 || jQuery(".PersonCity .controls input").val().length>25 || jQuery.isNumeric(jQuery(".PersonCity .controls input").val())){
			if(jQuery('.PersonCity').find('.validationSpanCity').length==0 ){
				jQuery( ".PersonCity" ).append( "<span class='validationSpanCity validation-result label label-important'> City should contain only alphabets(2-25)</span>" );
				result = false;
			}
			else if(jQuery('.PersonCity').find('.validationSpanCity').length>0){
				jQuery( ".validationSpanCity" ).replaceWith( " <span class='validationSpanCity validation-result label label-important'> City should contain only alphabets(2-25)</span>" );
				result = false;
			}
		}
		else
			jQuery( ".validationSpanCity" ).replaceWith( "" );

		if(jQuery('#94').val()!="Inde"){
			if(/^[a-zA-Z]*$/.test(jQuery('.other-state .controls input').val()) == false || jQuery(".other-state .controls input").val().length<2 || jQuery(".other-state .controls input").val().length>25 || jQuery.isNumeric(jQuery(".other-state .controls input").val())){
				if(jQuery('.other-state').find('.validationSpanState').length==0){
					jQuery( ".other-state" ).append( "<span class='validationSpanState validation-result label label-important'> State should contain only alphabets(2-25).</span>" );
					result = false;
				}
				else if(jQuery('.other-state').find('.validationSpanState').length>0){
					jQuery( ".validationSpanState" ).replaceWith( "<span class='validationSpanState validation-result label label-important'> State should contain only alphabets(2-25).</span>" );
					result = false;

				}
			}
			else
				jQuery( ".validationSpanState" ).replaceWith( "" );
		}

		if(result)
			return true;
		else
			return false;

	});
	/*Italy*/
	jQuery("#wr_form_3001").find(".jsn-form-submit").click(function(){
		var result=true;
		if(/^[a-zA-Z]*$/.test(jQuery('.PersonName .controls input').val()) == false || jQuery(".PersonName .controls input").val().length<2 || jQuery(".PersonName .controls input").val().length>25 || jQuery.isNumeric(jQuery(".PersonName .controls input").val())) {
			if(jQuery('.PersonName').find('.validationSpanName').length==0 ){
				jQuery( ".PersonName" ).append( "<span class='validationSpanName validation-result label label-important'> Name should contain only alphabets(2-25)</span>" );
				result=false;
			}
			else if(jQuery('.PersonName').find('.validationSpanName').length>0 ){
				jQuery( ".validationSpanName" ).replaceWith( "<span class='validationSpanName validation-result label label-important'> Name should contain only alphabets(2-25)</span>" );
				result=false;
			}
		}
		else
			jQuery( ".validationSpanName" ).replaceWith( "" );


		if(/^[a-zA-Z]*$/.test(jQuery('.PersonCity .controls input').val()) == false || jQuery(".PersonCity .controls input").val().length<2 || jQuery(".PersonCity .controls input").val().length>25 || jQuery.isNumeric(jQuery(".PersonCity .controls input").val())){
			if(jQuery('.PersonCity').find('.validationSpanCity').length==0 ){
				jQuery( ".PersonCity" ).append( "<span class='validationSpanCity validation-result label label-important'> City should contain only alphabets(2-25)</span>" );
				result = false;

			}
			else if(jQuery('.PersonCity').find('.validationSpanCity').length>0){
				jQuery( ".validationSpanCity" ).replaceWith( " <span class='validationSpanCity validation-result label label-important'> City should contain only alphabets(2-25)</span>" );
				result = false;
			}
		}
		else
			jQuery( ".validationSpanCity" ).replaceWith( "" );

		if(jQuery('#104').val()!="India"){
			if(/^[a-zA-Z]*$/.test(jQuery('.other-state .controls input').val()) == false || jQuery(".other-state .controls input").val().length<2 || jQuery(".other-state .controls input").val().length>25 || jQuery.isNumeric(jQuery(".other-state .controls input").val())){
				if(jQuery('.other-state').find('.validationSpanState').length==0){
					jQuery( ".other-state" ).append( "<span class='validationSpanState validation-result label label-important'> State should contain only alphabets(2-25).</span>" );
					result = false;
				}
				else if(jQuery('.other-state').find('.validationSpanState').length>0){
					jQuery( ".validationSpanState" ).replaceWith( "<span class='validationSpanState validation-result label label-important'> State should contain only alphabets(2-25).</span>" );
					result = false;

				}
			}
			else
				jQuery( ".validationSpanState" ).replaceWith( "" );
		}
		if(result)
			return true;
		else
			return false;

	});

	/*karthi*/





 //jQuery('.page-id-141').scrollTop(0);
	if ( jQuery(window).width() < 767 ){

		jQuery('#site-navigation li').each(function(){

			if ( jQuery(this).find('ul').length > 0 ){
				jQuery(this).addClass('has_children');
				jQuery(this).find('a').first().after('<p class="dropdownmenu"></p>');
			}

		});

	}

	jQuery('.dropdownmenu').click(function(){
		if( jQuery(this).parent('li').hasClass('this-open') ){
			jQuery(this).parent('li').removeClass('this-open');
		}else{
			jQuery(this).parent('li').addClass('this-open');
		}
	});

	/*jQuery('.btn-success').click(function(){
	if(jQuery('.checkboxes input').is(':checked')){

	if(jQuery('.email').val() != ""){
	if(jQuery('.knewsemail input').val();
	jQuery('.knews_add_user form').submit();
	}
	//es_submit_pages('http://heartfulness.org');
	}
	});*/


	jQuery('.btn-success').click(function(){
	if(jQuery('#99 input').is(':checked')){
	jQuery('.knewsemail input').val(jQuery('#92').val());
	jQuery('.knews_add_user form').submit();
	}
	});
	jQuery('.btn-success').click(function(){
	if(jQuery('#59 input').is(':checked')){
	jQuery('.knewsemail input').val(jQuery('#58').val());
	jQuery('.knews_add_user form').submit();
	}
	});


});


jQuery(document).ready(function() {
	var current_height = jQuery('.header .container').height();
	jQuery('.header').css('min-height',current_height);

	/*bala*/

	//reply button click function for banner logo
	   jQuery('#replay').click(function(){
		jQuery('#bgvid').get(0).play();
		//console.log(jQuery('#bgvid').get(0));
		//if(localStorage.getItem("lastseen")){
		//localStorage.setItem("lastseen", new Date());
		//}
		//console.log(localStorage.getItem("lastseen"));
		});

	/*//check session variable to pause the video and show the reply button
		var bgObject = jQuery('#bgvid').get(0);
		if(sessionStorage.bannervideo){
		if(sessionStorage.bannervideo == 'control'){
		if(typeof bgObject != "undefined"){
		console.log('second time');
			bgObject.autoplay = false;
			jQuery('#bgvid').load();
		}
		jQuery('#replay').css('display','inline-block');
		}
		}else{
		jQuery('#replay').css('display','none');
		}

		jQuery('#lang_choice_1').change(function(){
		if(typeof(Storage) !== "undefined") {
		if(sessionStorage.bannervideo){
				   sessionStorage.bannervideo = "";
				   console.log('lang change');
		   }
		   }
		});

	//check for not home page and save the session variable
	var homeHiddenObject = jQuery('#homeIdentifier').val();
	   if(homeHiddenObject != 'home'){
				if(typeof(Storage) !== "undefined") {
							console.log('not home');
							sessionStorage.bannervideo = "control";
					}else{
					console.log('Sorry! No Web Storage support');
					}
		}

		var homeHiddenObject = jQuery('#homeIdentifier').val();
	  if(homeHiddenObject == 'home'){
				if(typeof(Storage) !== "undefined") {

						if(localStorage.getItem("lastseen")){
							var lastDate =  new Date(localStorage.getItem("lastseen"));
							var currentDate = new Date();
							console.log('current date '+currentDate);
							console.log('last seendate '+lastDate);
							var dateDiff = (currentDate-lastDate)/(1000*60*60*24)
							console.log(dateDiff);
								if( dateDiff > 30 ){
										var bgObject = jQuery('#bgvid').get(0);
										if(typeof bgObject != "undefined"){
											bgObject.autoplay = true;
											jQuery('#bgvid').load();
											jQuery('#replay').css('display','none');
											}
											localStorage.setItem("lastseen", new Date());

											console.log('if');
										}else{
										var bgObject = jQuery('#bgvid').get(0);
												if(typeof bgObject != "undefined"){
												bgObject.autoplay = false;
												jQuery('#bgvid').load();
												jQuery('#replay').css('display','inline-block');
												}
												console.log('else');
											}

								}else{
										console.log('first time');
										var bgObject = jQuery('#bgvid').get(0);
										bgObject.autoplay = true;
										jQuery('#bgvid').load();
										jQuery('#replay').css('display','none');
										localStorage.setItem("lastseen", new Date());
									}

					}else{
					console.log('Sorry! No Web Storage support');
					}

			}
	/*bala*/

});

/* =================================

===  Bootstrap Fix              ====

=================================== */

if (navigator.userAgent.match(/IEMobile\/10\.0/)) {

  var msViewportStyle = document.createElement('style')

  msViewportStyle.appendChild(

    document.createTextNode(

      '@-ms-viewport{width:auto!important}'

    )

  )

  document.querySelector('head').appendChild(msViewportStyle)

}



/* =================================

===  STICKY NAV                 ====

=================================== */



jQuery(document).ready(function() {



  // Sticky Header - http://jqueryfordesigners.com/fixed-floating-elements/

  var top = jQuery('#main-nav').offset().top - parseFloat(jQuery('#main-nav').css('margin-top').replace(/auto/, 0));



  jQuery(window).scroll(function (event) {

    // what the y position of the scroll is

    var y = jQuery(this).scrollTop();



    // whether that's below the form

    if (y > top) {

      // if so, ad the fixed class

      jQuery('#main-nav').addClass('fixed');

    } else {

      // otherwise remove it

      jQuery('#main-nav').removeClass('fixed');

    }

  });









});





/*=================================

===  SMOOTH SCROLL             ====

=================================== */

var scrollAnimationTime = 1200,

        scrollAnimation = 'easeInOutExpo';

    jQuery('a.scrollto').bind('click.smoothscroll',function (event) {



        event.preventDefault();

        var target = this.hash;

        jQuery('html, body').stop().animate({

            'scrollTop': jQuery(target).offset().top

        }, scrollAnimationTime, scrollAnimation, function () {

            window.location.hash = target;

        });

    });



/* ================================

===  PARALLAX                  ====

================================= */

jQuery(document).ready(function(){

  var jQuerywindow = jQuery(window);

  jQuery('div[data-type="background"], header[data-type="background"], section[data-type="background"]').each(function(){

    var jQuerybgobj = jQuery(this);

    jQuery(window).scroll(function() {

      var yPos = -(jQuerywindow.scrollTop() / jQuerybgobj.data('speed'));

      var coords = '50% '+ yPos + 'px';

      jQuerybgobj.css({

        backgroundPosition: coords

      });

    });

  });

});



/* ================================

===  KNOB                      ====

================================= */

jQuery(function($) {

jQuery(".skill1").knob({

                'max':100,

                'width': 64,

                'readOnly':true,

                'inputColor':' #FFFFFF ',

                'bgColor':' #222222 ',

                'fgColor':' #e96656 '

                });

jQuery(".skill2").knob({

                'max':100,

                'width': 64,

                'readOnly':true,

                'inputColor':' #FFFFFF ',

                'bgColor':' #222222 ',

                'fgColor':' #34d293 '

                });

  jQuery(".skill3").knob({

                'max': 100,

                'width': 64,

                'readOnly': true,

                'inputColor':' #FFFFFF ',

                'bgColor':' #222222 ',

                'fgColor':' #3ab0e2 '

                });

  jQuery(".skill4").knob({

                'max': 100,

                'width': 64,

                'readOnly': true,

                'inputColor':' #FFFFFF ',

                'bgColor':' #222222 ',

                'fgColor':' #E7AC44 '

                });

	var setCarousel = function() {
		if( $(".carousel").length ) {
			var carousel 	= 	$(".carousel-inner"),
				items		=	carousel.find(".latestnews-box"),
				count 		= 	4,
				prevCount	=	$(".carousel").data('prevCount');

			if( window.innerWidth >= 1024 )
				count 		= 	4;
			else if( window.innerWidth >= 768 )
				count 		= 	2;
			else
				count		=	1;

			if( typeof prevCount !== 'undefined' && prevCount == count )
				return;

			items.each(function(){
				if( $(this).parent().hasClass("item") )
					$(this).unwrap();
			});
			carousel.find(".clear").remove();

			for(var i = 0; i < items.length; i+=count) {
				if( i == 0 )
					items.slice(i, i+count).wrapAll("<div class='item active'></div>");
				else
					items.slice(i, i+count).wrapAll("<div class='item'></div>");
			  $('<div class="clear"></div></div>').insertAfter(items.slice(i, i+count).last());
			}
			jQuery('.carousel').carousel({interval: false});
			jQuery('.carousel').data('prevCount', count);
		}
	}

	if( $(".carousel").length ) {
		setCarousel();
		var timer;
		$(window).on(resize_evt, function(){
			clearTimeout(timer);
			timer = setTimeout(setCarousel,200);
		});
	}

});



/* ======================================

============ MOBILE NAV =============== */

jQuery('.navbar-toggle').on('click', function () {

  jQuery(this).toggleClass('active');

});

/* FOOTER */
jQuery(window).load(function() {

	/* vp_h will hold the height of the browser window */
	var vp_h = jQuery(window).height();

	/* b_g will hold the height of the html body */
	var b_g = jQuery('body').height();

	/* If the body height is lower than window */
	if(b_g < vp_h) {

		jQuery('footer').css("position","absolute");
		jQuery('footer').css("bottom","0px");
		jQuery('footer').css("width","100%");

	}
});

var headerTimer;
jQuery(window).on('load resize orientationchange', function(){
	clearTimeout(headerTimer);
	headerTimer = setTimeout(function(){
		var current_height = jQuery('.header .container').height();
		jQuery('.header').css('min-height',current_height);
	},300);
});


var autocomplete;
google.maps.event.addDomListener(window, 'load', heartfulCenterAddressAutocomplete);
function heartfulCenterAddressAutocomplete(){
	var address_input = jQuery('.cm-autocomplete-geo input');
	if(address_input.length){
		autocomplete = new google.maps.places.Autocomplete( address_input.get(0), {types: ['geocode']});
		autocomplete.addListener('place_changed', heartfulCenterAddressFillIn);
		jQuery(document).ajaxComplete(function( event, xhr, settings ) {
			heartfulCenterAddressAutocomplete();
		});
	}
}

function heartfulCenterAddressFillIn(){
	// Get the place details from the autocomplete object.
	var place = autocomplete.getPlace();
	// Get each component of the address from the place details
	// and fill the corresponding field on the form.
	for (var i = 0; i < place.address_components.length; i++) {
		var addressType = place.address_components[i].types[0];
		var tempval = '';
		if(addressType == 'street_number'){
			tempval = place.address_components[i]['short_name'];
			jQuery('.cm-address input').val(tempval);
			jQuery('.cm-address input').next('.help-block').remove();
		}
		else if(addressType == 'route'){
			tempval = place.address_components[i]['long_name'];
			jQuery('.cm-address input').val(jQuery('.cm-address input').val() + ' ' + tempval).trigger('onchange');
			jQuery('.cm-address input').next('.help-block').remove();
		}
		else if(addressType == 'locality'){
			tempval = place.address_components[i]['long_name'];
			jQuery('.cm-city input').val(tempval).trigger('onchange');
			jQuery('.cm-city input').next('.help-block').remove();
		}
		else if(addressType == 'administrative_area_level_1'){
			tempval = place.address_components[i]['long_name'];
			jQuery('.cm-state input').val(tempval);
		}
		else if(addressType == 'postal_code'){
			tempval = place.address_components[i]['short_name'];
			jQuery('.cm-postcode input').val(tempval);
		}
		else if(addressType == 'country'){
			tempval = place.address_components[i]['long_name'];
			jQuery('.cm-country input').val(tempval);
		}
	}
}
