<?php 
	$count_rec = 0;
	$account_status = $result_ventuno_feed['account_status'];
?>
<div class="vtn_video_pub wrapper clearfix">
<?php
include( plugin_dir_path( __FILE__ ) . 'ventuno-header-template.php');
?>
			<!-- ******************** --> 	
			<!-- Gallery Body START -->
			<!-- ******************** --> 
			<!-- Search Box START-->
			<form method="post" action="" name="formventunocatalogpage" id="formventunocatalogpage">
			<input type="hidden" id="action" name="action" value="vtn-publish" />
			<input type="hidden" id="vtn-publish" name="action" value="vtn-publish" />
			<div class="w100 clearfix vtn_shadow_box vtn_content_box vtn_form_box">
				<div class="vtn_content_inner ivory">
					<h3>SEARCH</h3>
					<div class="inner_padded" >

							<div class="elements">
								<div class="labels floater left">
									<label for="categories">Categories</label>
								</div>
								<div>
									<span>
										<select name="vtn_category[]" id="vtn_category[]" multiple>
										<?php foreach( VentunoMedia::get_ventuno_category() as $cate_key => $cate_value ) { 
												$category_selected=''; 
										if(in_array($cate_key,$vtn_category)) $category_selected='selected';
										?>
										<option value="<?php echo $cate_key; ?>" <?php echo $category_selected; ?>><?php echo $cate_value;?></option>
										<?php } ?>
										</select>
									</span>
								</div>
							</div>
							<div class="elements with_instruct">
								<div class="labels floater left">
									<label for="key_words">Key Words</label>
								</div>
								<div>
								

									<input type="text" name="vtn_tags" id="vtn_tags" value="<?php echo $vtn_tags; ?>"/>
									<span class="instruction_text" > * Key words should be separated by commas.</span>
								</div>
							</div>
							<div class="elements bottom">
								<div class="labels floater left">
									<label for="criteria">Search criteria</label>
								</div>
								<div class="padded_top">
									<?php
										if($sort_by==1) { $latest='checked=true'; $rel=''; }
										elseif($sort_by==2) { $rel='checked=true'; $latest=''; }
									?>
									<input type="radio" name="sort_by" value="1" <?php echo $latest ?>/>Latest Videos
									<input type="radio" name="sort_by" value="2" <?php echo $rel ?>/>Relevant Videos
								</div>	
							</div>
							<input type="button" value="Search" class="vtn_btn floater right red" id="search_button" onclick="ventuno_api_search();"/>
							<input type="reset" value="Reset" class="vtn_btn floater right gray" />
						
					</div>	
				</div>	
			</div>
			<!-- Search Box END-->
			
			<!-- Player Custmz Box START-->
			<div class="w100 clearfix vtn_shadow_box vtn_content_box vtn_form_box vtn_expandable down">
				<div class="vtn_content_inner ivory" style="min-height: 0px;">
					<h3 id='togglebar' style="Cursor: Pointer;">CUSTOMIZE PLAYER <span class="added_msg">(select player's size and customize other features)</span><span class="down_arrow"></span></h3>
					<div class="inner_padded hide_palyer" style="display:none;">
							<div class="elements">
								<div class="labels floater left">
									<label for="size">Choose Size</label>
								</div>
								<div>
									<span>
										<select name="vtn_size" id="vtn_size">
											<option>640×480</option>
											<option>512×384</option>
											<option selected>480×320</option>
											<option>320×240</option>
										</select>
									</span>
								</div>
							</div>
							<!--
							<div class="elements">
								<div class="labels floater left">
									<label for="play_back">Play Back</label>
								</div>
								<div>
									<input type="radio" name="play_back" id="play_back" value="1" checked/>Auto Play
									<input type="radio" name="play_back" id="play_back" value="2" />User Initiated
								</div>
							</div>
							-->
							<div class="elements bottom">
								<div class="labels floater left">
									<label for="include">Keywords & Description</label>			
								</div>
								<div  class="padded_top">
									<input type="checkbox" name="include_desc" id="include_desc" value="1" />Include Description
									<input type="checkbox" name="include_keywords" id="include_keywords" value="2" />Include Keywords
								</div>	
							</div>
						</form>
					</div>	
				</div>	
			</div>
		
			<!-- Player Custmz Box END-->

			<!-- Video Thumbs Box START-->
			<div class="w100 clearfix vtn_shadow_box vtn_content_box gray vtn_videos_list_box vtn_gallery">
				<div class="vtn_content_inner gray">
					<h3>Latest Videos</h3>

			<?php 	
			$i = 1;
			$j = 1;
			$count_rec=0;
			if($result_ventuno_feed['status']==TRUE && $result_ventuno_feed['message']=='Sucess'){
			?>
				<div class="vtn_pagnation bottom_border" style="height:40px;">
					<div id="selector"  style="float:right;"></div>
				</div>
			<div class="vid_thumb_wrapper">
			<?php
				$count_rec=count($result_ventuno_feed['response']);
				foreach ($result_ventuno_feed['response'] as $result) {
					
					$vname = ucfirst(strtolower($result['videoname']));
					$vname= VentunoMedia::url_gen($vname);
					$vfullname = $vname;
					if(strlen($vname) >=30) {
						$vname=substr($vname,0,30)."...";
					}
					
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
					<input type="hidden" id="tot-records" name="tot-records" value="<?php echo $count_rec; ?>" />	
					<input type="hidden" id="per_page" name="per_page" value="10" />
					<?php if( $i==1 ) { ?>
					<div class="selection" id="page-<?php echo $j; ?>">
					<?php } ?>
					<div class="page-load-<?php echo $j; ?>">
						
							<div class="vid_thumb_item">
							<img src="<?php echo $result['thumbpath']?>" alt="Video Title" class="floater left" onclick="return showpopup('<?php echo $vfullname; ?>','<?php echo $emb_src; ?>','<?php echo $videokey; ?>','<?php echo $vdesc; ?>','<?php echo $vtags; ?>','<?php echo $vupdate;?>','<?php echo $video_duration; ?>');"/>
							<h4 id="video_title_<?php echo $result['videoid']?>"><?php echo html_entity_decode($result['videoname'],ENT_QUOTES, 'UTF-8'); ?></h4>
							<p id="video_desc_<?php echo $result['videoid']?>"><?php echo html_entity_decode(VentunoMedia::video_description($result['description'],200),ENT_QUOTES, 'UTF-8'); ?></span></p>
							<span class="vid_details_item vid_date right_border"><?php echo date("M d y",strtotime($result['publishdate'])); ?></span>
							<span class="vid_details_item vid_time"><?php echo $video_duration; ?></span>
							<a href="javascript:;" class="vid_details_item vid_preview right_border" onclick="return showpopup('<?php echo $vfullname; ?>','<?php echo $emb_src; ?>','<?php echo $videokey; ?>','<?php echo $vdesc; ?>','<?php echo $vtags; ?>','<?php echo $vupdate;?>','<?php echo $video_duration; ?>');">Preview / GetCode</a>
							<a href="javascript:;" class="vid_details_item vid_publish" onclick="createpost('<?php echo urlencode($vfullname); ?>','<?php echo $videokey; ?>','<?php echo urlencode($vdesc); ?>','<?php echo urlencode($vtags); ?>','<?php echo $result['videoid']?>','post');">Publish</a>
							</div>
					<?php $i++;	 if( $i == 10 || $i == $count_rec) { $i = 1;  $j++; }?>
					</div>
					<?php if( $i==1 ) { ?> 	</div>
					<?php 
						}
					 }
					echo '</div>'; 
			 } 
			 else {
					if($result_ventuno_feed['message']=='Link-up your account')
						echo '<span class="error_message block dark" onclick=linkup() style="cursor:pointer;">'.$result_ventuno_feed['message'].' to view your latest videos</span>';
					else 
						echo '<span class="error_message block dark">'.$result_ventuno_feed['message'].'</span>';
			 } ?>	
				</div>	
			</div>
			<!-- Video Thumbs Box END-->
			<!-- ******************** --> 	
			<!-- Gallery Body END -->
			<!-- ******************** --> 
	</form>
</div>
<?php
$height="420px;";
include( plugin_dir_path( __FILE__ ) . 'ventuno-popup-template.php');
?>
<?php 
$admin_page = get_admin_url();
$admin_page = $admin_page.'admin.php?page=vtn-account-setup';
?>
<style>.selection { display: none;} </style>
<script>


jQuery("#togglebar").click(function () {
	jQuery(".hide_palyer").slideToggle("slow");
	if(jQuery('.down')[0])
		jQuery('.down').removeClass('down').addClass('up');
	else if(jQuery('.up')[0])
		jQuery('.up').removeClass('up').addClass('down');
});

function linkup() {
	var url='<?php echo $admin_page; ?>'
	window.location.href = url
}


var tot_record=<?php echo $count_rec; ?>;
var per_page=10;

function ventuno_api_search() {
	document.formventunocatalogpage.submit();
}
</script>