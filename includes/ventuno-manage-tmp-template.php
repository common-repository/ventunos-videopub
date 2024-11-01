<div class="vtn_video_pub wrapper clearfix">
<?php
$account_status = $manage_template_result['account_status'];
include( plugin_dir_path( __FILE__ ) . 'ventuno-header-template.php');
?>
			<!-- ******************** --> 	
			<!-- Gallery Body START -->
			<!-- ******************** --> 
			
			<!-- Video Thumbs Box START-->
			<div class="w100 clearfix vtn_shadow_box vtn_content_box white vtn_template_outer">
				<div class="vtn_content_inner ivory">
					<h3>Templates</h3>
							<?php
							if($account_status=='Linked') { ?> 
								<div class="vtn_holder" >
									<a id="new_template" href="javascript:;" onclick="redirect_ventuno('TMP_CREATE',0);" class="floater right vtn_btn red">Create Template</a>
								</div>
							<?php
							}
												
							if($manage_template_result['status']==TRUE && $manage_template_result['message']=='Sucess') { ?>
						

							<div class="added_padding">
								<div class="w100 clearfix vtn_rounded_box vtn_content_box gray vtn_template_inner">
									<div class="vtn_content_inner gray">
										<div class="template_item_wrapper">
											<?php 
												echo html_entity_decode($manage_template_result['response']); 
											?>
										</div>
									</div>
								</div>
							</div>
						<?php } else {
								if($manage_template_result['message']=='Link-up your account')
									echo '<span class="error_message block yellow" onclick=linkup() style="cursor:pointer;">Link-up your Ventuno account to view your video templates</span>';
								else 
									echo '<span class="error_message block yellow">'.$manage_template_result['message'].'</span>';
							} ?>
				</div>	
			</div>
			<!-- Video Thumbs Box END-->
	</div>
<?php
$height="400px;";
include( plugin_dir_path( __FILE__ ) . 'ventuno-popup-template.php');
?>
<?php 
$admin_page = get_admin_url();
$admin_page = $admin_page.'admin.php?page=vtn-account-setup';
?>
<script>
function linkup() {
	var url='<?php echo $admin_page; ?>'
	window.location.href = url
}
</script>