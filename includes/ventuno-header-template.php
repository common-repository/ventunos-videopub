<script>
var tot_record=0;
</script>
<?php 
if(!isset($account_status)) $account_status ='Un-Linked';
$height="400px;";

?>
<!-- Header START -->
<div class="vtn_header w100">
	<a href="http://<?php echo VentunoMedia::get_base_env();?>/en/" target="_blank">
		<img class="vtn_logo" src="<?php echo VentunoMedia::ventuno_plugin_url(); ?>/images/vtn_logo.png" alt="Ventuno Tech" >
		</img>
	</a>

</div>
<div class="vtn_title_bar w100 clearfix vtn_shadow_box">
	<h2 class="floater left">Video<span>Pub</span></h2>
	<div class="vtn_acc_sec clearfix floater right">
		<?php $admin_page = $admin_page.'admin.php?page=vtn-account-setup'; ?>
		<span class="vtn_acc_status floater left">Account Status : <span id="status"> <?php echo $account_status ?> </span></span>
		<?php if($account_status!='Linked') { ?> 
		<a id="acc_link" class="floater left" href="<?php echo $admin_page;?>">Account Settings</a>
		<?php } ?>
	</div>
</div>	
<!-- Header END -->
<!-- ThnkYou Message START -->
<div class="vtn_thnkyou_sec w100 clearfix vtn_shadow_box">
	<span id="thnkyou_message" class="floater left" >Thank you for activating <span class="vid_style">Video</span><span class="pub_style">Pub</span>.</span>
	 <a href="http://www.ventunotech.com/en/" target="_blank" class="vtn_action_btn floater right red">INDIA'S LEADING VIDEO ECOSYSTEM LEARN MORE</a></span>
</div>
<!-- ThnkYou Message END -->