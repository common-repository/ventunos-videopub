<div class="vtn_video_pub wrapper clearfix">
<?php
$account_status = $account_result['account_status'];
include( plugin_dir_path( __FILE__ ) . 'ventuno-header-template.php');
?>
	<!-- ******************** --> 	
	<!-- Login Body START -->
	<!-- ******************** --> 
	
	<!-- Video Thumbs Box START-->
	<?php
	if($account_result['status']==FALSE && (in_array($account_result['account_status'], $account_result['account_status_array']))) {
		 $checked1='';$checked2='';
		 if($form_type==1) $checked1='checked=true';
		 elseif($form_type==2) $checked2='checked=true';
	?>
	<div class="w100 clearfix vtn_shadow_box vtn_content_box gray vtn_login">
		<div class="vtn_content_inner gray"> 
			<h3>Login</h3>
			<div class="inner_padded" >
				<p>Registered Publishers can link-up their Ventuno account by signing-in below. Choose create a new account if you don't have a Ventuno Account.</p>
				<div class="choose_form">
					<div><input type="radio" name="choose_form" id="choose_form1" value="existing" <?php echo $checked1; ?> onclick="show_content();" />Link-up your existing account </div>
					<div><input type="radio" name="choose_form" id="choose_form2" value="new" <?php echo $checked2; ?> onclick="show_content();"/>Create a new account</div>
				</div>
				<!-- existing Account -->
				<div class="w100 clearfix vtn_rounded_box vtn_content_box vtn_form_box" id="existing_account">
				
					<div class="vtn_content_inner ivory">
						<h3>EXISTING ACCOUNT</h3>
						<div class="inner_padded">
							<?php 
							echo html_entity_decode($account_result['response']['login']); 
							?>
						</div>	
					</div>
				</div>	
					
				<!-- new Account -->
				<div class="w100 clearfix vtn_rounded_box vtn_content_box vtn_form_box" id="new_account" style="display:none;">	
					<div class="vtn_content_inner ivory">
						<h3>NEW ACCOUNT</h3>
						<div class="inner_padded">
							<?php 
							echo html_entity_decode($account_result['response']['newuser']); 
							?>
						</div>
					</div>	
				</div>
					
				
			</div>	
		</div>	
	</div>
	<?php } 
	else { ?>
	<div class="w100 clearfix vtn_shadow_box vtn_content_box gray vtn_login">
		<div class="vtn_content_inner gray">
			<h3>Login</h3>
			<div class="inner_padded" >
				<?php
					$admin_page = get_admin_url();
					$admin_page = $admin_page.'admin.php?page=vtn-publish';
				?>
					<?php if($form_type==1 || $account_status=='Linked') { ?>
					<span class="error_message  block dark"> Thank you for linking-up your Ventuno account. You can start posting videos now!  </span>
					<!-- a id="action_go" href="<?php echo $admin_page;?>" class="floater left vtn_action_btn red">GO</a> -->
					<?php } elseif($form_type==2 && $account_status=='Approval Pending') { ?>
					<span class="error_message  block dark">Thank you for registering with us; you will be informed by an e-mail once your registration is approved. </span>
					<?php } elseif($account_status=='Approval Pending') { ?>
					<span class="error_message  block dark"> Your account registration is pending approval.You will be notified via email once your registration is approved. </span>	
					<?php } ?>	
					
			</div>	
		</div>	
	</div>
	<?php } ?>

	<!-- Video Thumbs Box END-->
	<!-- ******************** --> 	
	<!-- Login Body END -->
	<!-- ******************** --> 
</div>
<?php
$height="400px;";
include( plugin_dir_path( __FILE__ ) . 'ventuno-popup-template.php');
?>
<script type="text/javascript">
<?php if(isset($form_type) && $form_type==1) { ?>
jQuery('#new_account').hide(1000);
jQuery('#existing_account').show(800);
<?php } elseif(isset($form_type) && $form_type==2) {?>
jQuery('#existing_account').hide(1000);
jQuery('#new_account').show(800);
<?php } ?>

function show_content() {
	
	var form_type = jQuery('input:radio[name=choose_form]:checked').val();
	
	if(form_type=='new') {
		jQuery('#existing_account').hide(1000);
		jQuery('#new_account').show(800);
	} else {
		jQuery('#new_account').hide(1000);
		jQuery('#existing_account').show(800);
	}
}

jQuery("#new_partnrt_validate").click(function() {
	jQuery("#newpartner").validate({meta: "validate"});
	
	jQuery.validator.addMethod("specialChar", function(value, element) {
	    	return this.optional(element) || /([0-9a-zA-Z\s])$/.test(value);
	  }, "Please Fill Correct Value in Field.");
  
});

jQuery("#login_from").click(function() {
	if(jQuery("#login_id").val()=='') {
	
		jQuery("#clogin_id").text("Please enter the user name");
		return false;
		
	}
	if(jQuery("#access_secure_id").val()=='') {
		
		jQuery("#caccess_secure_id").text("Please enter the Password");
		return false;
	}  
	document.existing_account.submit();
	
});
</script>
<style type="text/css">
form label.error{
clear: both;
color: #FF0000;
font-family: arial,verdana;
font-size: 11px;
font-weight: normal;
margin: 0;
padding: 0;
}

.ventuno_error{
clear: both;
color: #FF0000;
font-family: arial,verdana;
font-size: 11px;
font-weight: normal;
margin: 0;
padding: 0;
}

</style>
