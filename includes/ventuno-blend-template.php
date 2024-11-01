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
		 $checked1='';$checked2='';
		 if($form_type==1) $checked1='checked=true';
		 elseif($form_type==2) $checked2='checked=true';
	?>
	<div class="w100 clearfix vtn_shadow_box vtn_content_box gray vtn_login">
		<div class="vtn_content_inner gray"> 
			<h3>Blend Setup</h3>
			<?php 	if($result_blend_setup['status']==TRUE && $result_blend_setup['message']=='Sucess') { ?>
					<div class="inner_padded" >
							<p>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspA Summery View is a collection of ‘story summaries’. You can integrate the Summary View as a widget in your side-bar by following the simple steps below:<p>
							<p>1. Navigate to <b>Appearance</b> >> <b>Widgets</b> and locate <b>Ventuno Blend Widget</b> among the Available Widgets .<p>
							<p>2. Drag the Ventuno Blend Widget to the side bar panel.<p>
							<p>3. Assign the Blend Widget a title in the side bar panel and configure the Blend Summary API URL.<p>
							<p>4. Click ‘Save’ to save the widget setup.<p>
						</div>	
					</div>
		<?php 
			}  
			else {
								if($result_blend_setup['message']=='Link-up your account')
									echo '<span class="error_message block dark" onclick=linkup() style="cursor:pointer;">'.$result_blend_setup['message'].'</span>';
								else 
									echo '<span class="error_message block dark">'.$result_blend_setup['message'].'</span>';
			} ?>	
</div>
<?php
$height="400px;";
include( plugin_dir_path( __FILE__ ) . 'ventuno-popup-template.php');
?>
