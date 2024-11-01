<form method="post" action="" name="vt-login" id="vt-login">
    <input type="hidden" id="action" name="action" value="vtn-publish" />
	<input type="hidden" id="vtn-publish" name="action" value="vtn-publish" />
		<div class="vtn_editorial_plugin skin_default" >
			<div class="content_box login shadow">
				<h1>
					<span>Ventuno Editorial Plugin</span>
				</h1>
				
				<div class="title_box gradation">
					<h2>LOGIN & LINK TO VENTUNO</h2>
					<a href="" class="help"></a>
				</div>
				<div class="form_box dark_gray_trans ventuno_font_sucess">

				<?php 
				if( isset($login_result['status']) && $login_result['status']==true) { 
					$admin_page = get_admin_url();
					$admin_page = $admin_page.'admin.php?page=vtn-publish';
					$click_here = '<a href="'.$admin_page.'"  class=ventuno_a_link>click here</a>';	
				?>
				 <div> 
				 <?php 
						echo "<span> You have link to ventuno successfully and post a video in your blog ".$click_here.".</span>";
				 ?>
				</div>
				<?php } 
					else { 
						
						if( isset($login_result['status']) ) { 
							if($login_result['status']==false) 
								echo "<div><span class='ventuno_font_error'>".$login_result['message']."</span></div>";	
						}
						?> 
						<label id="login_lable" for="login">Login ID</label>&nbsp;&nbsp;<span></span>
						<input id="login_id" type="text" name="login_id" class="input_text"/>
						<label id="password_lable" for="password">Password</label> &nbsp;&nbsp;<span></span>
						<input id="access_secure_id" type="password" name="access_secure_id" class="input_text" />
						<input class="dark_gray_button" type="submit" value="LOGIN" id="submit">
						<input class="dark_gray_button" type="button" value=" CREATE ACCOUNT " id="submit" onclick='create_account();'>
				<?php }	?>
				</div>
			</div>
</div>

<script>
function create_account() {
	var url = "http://<?php echo VentunoMedia::get_base_env() ?>/newplatform/index.php/home/new_partner";
	window.open(url,'_blank');
}
</script>