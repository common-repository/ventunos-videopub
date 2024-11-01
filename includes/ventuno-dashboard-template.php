<div class="vtn_video_pub wrapper clearfix">
<?php
$account_status = $dashboard_result['account_status'];
include( plugin_dir_path( __FILE__ ) . 'ventuno-header-template.php');
$date_only=mktime(00,00,00,date('m'),date('d')-1,date('Y'));
$screen_name_date=date("d",$date_only);
$screen_name_m_y=date("M y",$date_only);
$revenue = 0;
$ad_views = 0;

if(isset($dashboard_result['report']) && $dashboard_result['report']) {
	foreach($dashboard_result['report'] as $r_date) {
		
			$str_time = strtotime($r_date['screen_name']);
			$screen_name_date=date("d",$str_time);
			$screen_name_m_y=date("M y",$str_time);
			$revenue = round($r_date['revenue']);
			$ad_views = $r_date['ad_views'];
	}
}
?>
	<!-- ******************** --> 	
	<!-- Dashboard Body START -->
	<!-- ******************** --> 
	
	<!-- Videopub About Box START-->
	<div class="w50 floater left alpha clearfix vtn_shadow_box vtn_content_box vtn_videopub_about_box">
		<div class="vtn_content_inner">
			<h3>VIDEOPUB</h3>
			<div id="videopub_about" >
				<h4>Video<span>Pub</span></h4>
				<p>Ventuno’s VideoPub is a WordPress plugin solution to leverage the power of Ventuno’s content syndication platform to video enable and monetize your WordPress site.  Our extensive library of 150,000+ videos across genres, to which we add 150+ new videos daily, helps in adding relevant content to your site continuously. </p>
				<div>
					<img class="floater left"  src="<?php echo VentunoMedia::ventuno_plugin_url(); ?>/images/vid_pays.gif" alt="Video Pays!" />
					<p class="floater left" >Video Pays!  We run ads with the videos.  The simple mantra is, engage your audience with video and increase your revenue.  </p>
				</div>
			</div>
		</div>
	</div>
	<!-- Videopub About Box END-->
	<!-- Stats Box START-->
	<div class="w50 floater left omega clearfix vtn_shadow_box vtn_content_box gray vtn_stats_box" >
		<div class="vtn_content_inner gray">
			<h3>Your Stats</h3>
			<?php if($dashboard_result['status']==TRUE && $dashboard_result['message']=='Sucess'){ ?>
			<div id="stats_inner" class="inner_padded" >
				<div class="figure_holder floater left">
					<span id="ad_imp" class="stats_circle vtn_center stats_figure_circle"><?php echo $ad_views ?></span>
					<span class="stats_figure_text">Ad Views</span>
				</div>
				<div class="figure_holder floater left">
					<span id="ad_rev" class="stats_circle vtn_center stats_figure_circle"><span class="stats_sub_text">INR.</span><?php echo $revenue ?></span>
					<span class="stats_figure_text">Revenue</span>
				</div>
				<div class="stats_date_holder floater left">
					<span id="stats_date_range"><?php echo $screen_name_date; ?> th</span>
					<span id="stats_month_year"><?php echo $screen_name_m_y ?></span>
				</div>
				<a href="javascript:;" onclick="redirect_ventuno('REPORT',0);" class="vtn_action_btn floater right gray">More Stats</a>
			</div>
			<?php } else {
						$admin_page = get_admin_url();
						$admin_page = $admin_page.'admin.php?page=vtn-account';
						
						if($dashboard_result['message']=='Link-up your account')
							echo '<span class="error_message  block dark" onclick=linkup() style="cursor:pointer;">Link-up your Ventuno account to view your stats</span>';
						else 
							echo '<span class="error_message  block dark">'.$dashboard_result['message'].'</span>';
			 } ?>
		</div>
	</div>
	<!-- Stats Box END-->
	<!-- Latest Videos Box START-->
	<div class="w60 floater left alpha clearfix vtn_shadow_box vtn_content_box gray vtn_videos_list_box vtn_latest_box">
		<div class="vtn_content_inner gray">
			<h3>Latest Videos</h3>
				<?php 
				$admin_page_pub = get_admin_url();
				$admin_page_pub = $admin_page_pub.'admin.php?page=vtn-publish';
				if($dashboard_result['status']==TRUE && $dashboard_result['message']=='Sucess'){
					echo '<div class="vid_thumb_wrapper">';
					$count_rec=count($dashboard_result['response']);
						foreach ($dashboard_result['response'] as $result) {
							$vname = ucfirst(strtolower($result['videoname']));
							$vname= VentunoMedia::url_gen($vname);
							$vfullname = $vname;
							if(strlen($vname) >=30) $vname=substr($vname,0,30)."...";
							$vdesc = $result['description'];
							$vdesc= VentunoMedia::url_gen($vdesc);
							$vtags = $result['keywords'];
							$emb_var = explode('embed src=', $result['embed']);
							$emb_src_value = explode(' ', $emb_var[1]);
							$emb_src=str_replace('"', '', $emb_src_value['0']);
							$emb_src=str_replace('&quot;', '', $emb_src);
							$vupdate = date("M d Y",strtotime($result['publishdate']));
							$video_duration =VentunoMedia::video_duration( $result['videoduration']);
							$videokey = $result['key'];
						?>
						<div class="vid_thumb_item">
							<img src="<?php echo $result['thumbpath']?>" alt="Video Title" class="floater left" onclick="return showpopup('<?php echo $vfullname; ?>','<?php echo $emb_src; ?>','<?php echo $videokey; ?>','<?php echo $vdesc; ?>','<?php echo $vtags; ?>','<?php echo $vupdate;?>','<?php echo $video_duration; ?>');"/>
							<h4 id="video_title_<?php echo $result['videoid']?>"><?php echo html_entity_decode($result['videoname'],ENT_QUOTES, 'UTF-8'); ?></h4>
							<p id="video_desc_<?php echo $result['videoid']?>"><?php echo html_entity_decode(VentunoMedia::video_description($result['description'],100),ENT_QUOTES, 'UTF-8'); ?></span></p>
							<span class="vid_details_item vid_date right_border"><?php echo date("M d y",strtotime($result['publishdate'])); ?></span>
							<span class="vid_details_item vid_time"><?php echo $video_duration; ?></span>
							<a href="javascript:;" class="vid_details_item vid_preview right_border" onclick="return showpopup('<?php echo $vfullname; ?>','<?php echo $emb_src; ?>','<?php echo $videokey; ?>','<?php echo $vdesc; ?>','<?php echo $vtags; ?>','<?php echo $vupdate;?>','<?php echo $video_duration; ?>');">Preview</a>
							<a href="javascript:;" class="vid_details_item vid_publish" onclick="createpost('<?php echo urlencode($vfullname); ?>','<?php echo $videokey; ?>','<?php echo urlencode($vdesc); ?>','<?php echo urlencode($vtags); ?>','<?php echo $result['videoid']?>','post');">Publish</a>
						</div>
						<?php 

						}	?>
						</div>
						<a href="<?php echo $admin_page_pub?>" class="vtn_action_btn floater right gray">More Videos</a>
						<?php			
						} else {

								if($dashboard_result['message']=='Link-up your account')
									echo '<span class="error_message  block dark" onclick=linkup() style="cursor:pointer;">Link-up your Ventuno account to view videos</span>';
								else 
									echo '<span class="error_message  block dark">'.$dashboard_result['message'].'</span>';
						}	
						?>
		</div>	
	</div>
	<!-- Latest Videos Box END-->
	<!-- About Ventuno Blend START-->
	<div class="w40 floater left omega clearfix vtn_shadow_box vtn_content_box gray vtn_ventuno_about_box">
		<div class="vtn_content_inner  gray">
			<h3>VENTUNO BLEND</h3>
			<?php 
			$admin_page_pub = get_admin_url();
			$admin_page_pub = $admin_page_pub.'admin.php?page=vtn-blend-setup';
			if($dashboard_result['status']==TRUE && $dashboard_result['message']=='Sucess'){ ?>
			<div class="inner_padded" >
				<h3 style="width: 137px;">Summery View </h3>
				<p class="answer" style="font-size: 14px;"><br/> A Summery View is a collection of ‘story summaries’. You can integrate the Summary View as a widget in your side-bar.</p>
				
				<h3 style="width: 115px;">Detail View</h3> 
				<p class="answer" style="font-size: 14px;"><br/> A Story Summary will lead a viewer to the Detail View of that story. Detail View will contain a story title, pre-formatted story content, accompanied by a video and images.Ventuno Blend Widget will automatically create the Detail View page for you.</p>
				<a href="<?php echo $admin_page_pub?>" class="vtn_action_btn floater right gray">Blend Setup</a>
			</div>	
			
			<?php 
			}
			else {
			if($dashboard_result['message']=='Link-up your account')
				echo '<span class="error_message  block dark" onclick=linkup() style="cursor:pointer;">Link-up your Ventuno account.</span>';
			else 
				echo '<span class="error_message  block dark">'.$dashboard_result['message'].'</span>';
			}	
			?>
		</div>	
	</div>
	<!-- About Ventuno Blend END-->
	<!-- FAQs Box START-->
	<div class="w100 clearfix vtn_shadow_box vtn_content_box gray vtn_ventuno_faq_box">
		<div class="vtn_content_inner gray">
			<h3>FAQs</h3>
			<div class="inner_padded" >
			<p class="question">Q. What is the Ventuno VideoPub?</p>
			<p class="answer">A. Ventuno’s VideoPub is a WordPress plug-in that lets you leverage the power of Ventuno’s video syndication platform to video-enable your WordPress site.  VideoPub will also provides additional revenue avenue for your WordPress site.</p>
			<p class="question">Q. Do I need a Ventuno account to publish videos through Ventuno VideoPub?</p>
			<p class="answer">A. Yes you need to register as a ‘publisher’ with the Ventuno Platform to publish videos through VideoPub. To register please go VideoPub >> Account Setup, choose the ‘create a new account’ option and fill-in the ‘new account’ form. Do remember that once you have submitted the ‘new account’ form, you need to wait for Ventuno’s approval before you are registered as a ‘publisher’.</p>
			<p class="question">Q.  What is a ‘Long Live Access Token’ and how does it work?</p>
			<p class="answer">A. As a Registered publisher, you can link the VideoPub plugin with your account by logging-in to the Ventuno Platform in the Account Setup page. Once you have logged in a ‘Long Live Access Token’ is created. You will not have to re-login again. This ‘Long Live Access Token’ is your link to Ventuno platform.</p>
			<p class="question">Q. What happens if I change my username or password in the Ventuno Platform ?</p>
			<p class="answer">A. The ‘Long Live Access Token’ will get expired and you will have to re-login to the Ventuno Platform in the Account Setup page. Please note that once the ‘Long Live Access Token’ expires, you will no longer to be able to post videos in your site.</p>
			<p class="question">Q. Will Ventuno videos still play if I delete or deactivate the Ventuno VideoPub plugin?</p>
			<p class="answer">A. No, the videos will not play once you delete or deactivate the Ventuno VideoPub plugin. You will need to reactivate or re-install the plugin and re-login to the Ventuno Platform the Account Setup page in order for the videos to play again.</p>
			<p class="question">Q. What are the player properties that WordpPress publishers can customize in the plugin?</p>
			<p class="answer">A. At this point we only allow WordPress publishers to customize the height and width of the player.</p>
			<p class="question">Q.  What is a Video Template?</p>
			<p class="answer">A. A Video Template is an integrated video page that showcases featured videos, related videos, latest videos, most viewed videos and videos channels. The template design, video type, and category can be customized by you.  Videos are deep-linked and subsequent pages are automatically rendered by VideoPub.</p>
			<p class="question">Q.  How to create and manage a Video template?</p>
			<p class="answer">A. you can create and manage a template in the Ventuno platform. Your  ‘Long Live Access Token’ links VideoPub to your Ventuno account and thus you can create, customize and manage your video template in the platform.</p>
			<p class="question">Q.  What is Template Page URL?</p>
			<p class="answer">A. The Template Page URL is the destination page of your template. We strongly recommend that create and update the Template Page URL for each template you publish so that we can divert video views to your destination page. </p>
			</div>	
		</div>	
	</div>
	<!-- FAQs Box END-->
	<!-- ******************** --> 	
	<!-- Dashboard Body END -->
	<!-- ******************** --> 
</div>
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
<?php
$height="350px;";
include( plugin_dir_path( __FILE__ ) . 'ventuno-popup-template.php');
?>
