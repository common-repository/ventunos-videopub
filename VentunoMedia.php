<?php

require_once dirname(__FILE__)."/VentunoMediaApi.php";
/*
Plugin Name: Ventuno’s VideoPub
Plugin URI: http://www.wordpress.ventunotech.com/
Description: Easy to Post the a video by Search  
Version: 2.1
Author: Ventuno Media
Author URI: http://ventuno.in/
License: GPL2
*/

/*  Copyright 2013  VentunoTech.  (email : support@ventunotech.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! class_exists( 'VentunoMedia' ) ) {
	class VentunoMedia {
		static $version = '2.1';
		static $vtn_debug = false;
		static $vtn_message = false;
		static $vtn_error = false;
		static $count_rec=0; 
		static $category=array();
				
		static function log( $vtn_message = false ) {
			if ( VentunoMedia::$vtn_debug ) {
				if ( !$vtn_message ) {
					$vtn_stack_array = debug_backtrace();
					$final_stack = $vtn_stack_array[1];
					@$vtn_message = 'VentunoMedia:<line '.$final_stack['line'].'>'.$final_stack['class'].$final_stack['type'].$final_stack['function'].'('.implode( ', ', $final_stack['args'] ).')';
				}
				error_log( $vtn_message);
			}
		}
		
		static function ventuno_plugin_url(){			
            		 return plugin_dir_url( __FILE__ ) ; 
		}
		
		static function get_base_env() {
			return $live = "www.ventunotech.com";
		}
		
		static function get_ventuno_api_uri() {
			
			return $ventunomedia_uri_env_setup = VentunoMedia::get_base_env()."/api/wp";
		}
		
		static function get_ventuno_api_file() {
			
			return $ventunomedia_file_setup = "wp_api.php";
		}

		static function get_ventuno_blend_api_file() {
			
			return $ventunomediablend_uri_env_setup = "blend.php";
		}

		static function get_ventuno_blend_api_uri() {
			
			return $ventunomediablend_uri_env_setup = VentunoMedia::get_base_env()."/api/publisher";
		}
		
		static function get_ventuno_api_static_vars() {
			return $ventuno_api_static_vars =	array (
						'category' => 'all' ,
						'keywords' => 'all' );
		}
		
		static function get_ventuno_category() {
				return $category = array( 3=>'Auto & Vehicles',
				4=>'Entertainment',
				5=>'Health & Fitness',
				7=>'Sports',
				8=>'Technology',
				9=>'Business',
				12=>'Comedy',
				13=>'Food & Dining',
				14=>'Lifestyle & Fashion',
				15=>'Music',
				16=>'News',
				19=>'Travel'
				);
		}
		
		static function ventuno_setup() {
			VentunoMedia::log();
			delete_option( 'ventunomedia_access_token' );
			$data_option = VentunoMedia::get_blend_details();
			if($data_option['vtn_blend_postid']>0) {
				wp_delete_post($data_option['vtn_blend_postid']);
				delete_option( 'ventuno_blend_page_id');
				delete_option('ventuno_blend_player_size');
			}
			VentunoMedia::enable_xmlrpc_for_api();
			unregister_widget('VentunoBlendWidget');
		}
		// Register and load the widget
		static function ventuno_belnd_load_widget() {

			$return_acces_token = VentunoMedia::ventuno_user_api_key();
			if(!$return_acces_token) {
				unregister_widget('VentunoBlendWidget');
			} 
			else {
				register_widget( 'VentunoBlendWidget' );
			}
			
		}
		static function ventuno_user_api_key() {
			$ventuno_access_keys = json_decode(get_option( 'ventunomedia_access_token' ) ,true);
			$ventuno_key_script = $ventuno_access_keys['response'];
			return $ventuno_key_script;
		}
		
		static function enable_xmlrpc_for_api() {
			VentunoMedia::log();
			update_option( 'enable_xmlrpc', 1 );
			if ( ! get_option( 'enable_xmlrpc' ) ) {
				VentunoMedia::abort( 'Error enabling XML-RPC.' );
			}
		}
		
		static function get_ventuno_icon() {
			return VentunoMedia::ventuno_plugin_url().'/images/favicon.ico';
		}
		
		static function ventuno_delete_connector() {
           		 VentunoMedia::log();
           		 try {    
				#API call to ventuno
				VentunoMedia::$vtn_message = 'Ventuno plugin has been deactivated.';
           		 } 
           		 catch (Exception $e) {
                   		VentunoMedia::log('API call exception: '.$e->getMessage());
           		 }
		}
		
		static function show_alert() {
			VentunoMedia::log();
			$vtn_icon_src = VentunoMedia::get_ventuno_icon();
		
			if(VentunoMedia::$vtn_error!='')
				echo '<div style="margin: 0.5em 0;padding: 2px;"><p><img src="'.$vtn_icon_src.'"/>'.VentunoMedia::$vtn_error.'</p></div>';
			
			if(VentunoMedia::$vtn_message!='')
				echo '<div style="margin: 0.5em 0;padding: 2px;"><p><img src="'.$vtn_icon_src.'"/>'.VentunoMedia::$vtn_message.'</p></div>';
		}
		
		static function abort( $vtn_message ) {
			VentunoMedia::log( '<><><> "FATAL" ERROR. CRITICAL ERROR...<><><>:'.$vtn_message );
		}
		
		static function ventuno_admin_scripts($page) {

			$plugin_url = VentunoMedia::ventuno_plugin_url();
			wp_enqueue_style( 'live_google_font', 'http://fonts.googleapis.com/css?family=Lato:100,300,400,700' );
			wp_enqueue_style( 'ventuno_videopub_style', $plugin_url.'css/vtn_videopub_app.css' );
			wp_enqueue_style( 'ventuno_jquery_simple_pagination_css', $plugin_url.'css/simplePagination.css');

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'ventuno_jquery_simple_pagination', $plugin_url.'js/jquery.simplePagination.js' , array("jquery") );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );
			
			wp_enqueue_style( 'ventuno_popup_css',$plugin_url.'css/ventuno_popup.css' );
			wp_enqueue_script( 'ventuno_popup_js' ,$plugin_url.'js/ventuno_popup.js' , array("jquery"));
				
			    
		}
		
		static function ventuno_plugin_menu() {
			
			VentunoMedia::log();
			add_menu_page( 'ventunomedia', 'VideoPub', 'manage-menu', 'ventunomedia',array( 'VentunoMedia', '' ), VentunoMedia::get_ventuno_icon(), 10 );
			add_submenu_page( 'ventunomedia', 'Dashboard', 'Dashboard', 'publish_posts', 'vtn-dashboard', array( 'VentunoMedia', 'ventuno_menu_control' ) );
			add_submenu_page( 'ventunomedia', 'Account Setup', 'Account Setup', 'publish_posts', 'vtn-account-setup', array( 'VentunoMedia', 'ventuno_menu_control' ));
			add_submenu_page( 'ventunomedia', 'Blend Setup', 'Blend Setup', 'publish_posts', 'vtn-blend-setup', array( 'VentunoMedia', 'ventuno_menu_control' ));
			add_submenu_page( 'ventunomedia', 'Video Catalog', 'Video Catalog', 'publish_posts', 'vtn-publish', array( 'VentunoMedia', 'ventuno_menu_control' ));
			add_submenu_page( 'ventunomedia', 'Video Templates', 'Video Templates', 'publish_posts', 'vtn-manage-template', array( 'VentunoMedia', 'ventuno_menu_control' ));

			global $submenu;
			if(current_user_can("manage-menu")){
				unset( $submenu['ventunomedia'][0] );
			}
		}
		
		static function get_ventuno_auto_post_callback(){
	
			if(isset($_REQUEST['type_call'])) {
				$sort_by = $_REQUEST['sort_by'];
				$vtn_category = explode(',',$_REQUEST['vtn_category']);
				$vtn_tags = urldecode($_REQUEST['vtn_tags']);
			} else {
				$sort_by = isset( $_REQUEST['sort_by'] ) ? $_REQUEST['sort_by'] : 1;
				$vtn_category = isset( $_REQUEST['vtn_category'] ) ? $_REQUEST['vtn_category'] : array( 'all' );
				$vtn_tags = isset( $_REQUEST['vtn_tags'] ) ? $_REQUEST['vtn_tags'] : 'all';
			}
			
			$data_result['status'] = FALSE;
			$data_result['message'] = 'Link-up your account';
			$data_result['account_status'] = 'Un-link';
			$return_access_token = VentunoMedia::ventuno_user_api_key();
						
			if(isset($return_access_token['access_token']) && $return_access_token['access_token']!= '') {		
				$data_result = VentunoMediaApi::call_ventuno_api( $return_access_token['access_token'] , $sort_by,$vtn_category,$vtn_tags );
				$data_result = json_decode( $data_result, true );
			}
				
			print VentunoMedia::show_page_content( "includes/ventuno-search-template-ajax.php",array( 'result_ventuno_feed' =>  $data_result ,
			'sort_by' => $sort_by,
			'vtn_category' => $vtn_category,
			'vtn_tags' => $vtn_tags 
			));
			die();
		}
		
		static function call_ventuno_redirect() {
			
			$return_acces_token = VentunoMedia::ventuno_user_api_key();
			
			if($return_acces_token) {
				$api_keys = explode('|#|',base64_decode($return_acces_token['access_token']));
				
				$post_data='';
				if($_POST['data_post']>0) {
					$post_data = $_POST['data_post'];
				}
			
				if($_POST['click_type']=='REPORT') {
					$redirect_url = 'http://'.VentunoMedia::get_base_env().$return_acces_token['ventuno_report_url'];
				}
				elseif($_POST['click_type']=='TMP_CREATE') {
					$redirect_url = 'http://'.VentunoMedia::get_base_env().$return_acces_token['ventuno_template'].'create';
				} elseif($_POST['click_type']=='TMP_EDIT') {
					$redirect_url = 'http://'.VentunoMedia::get_base_env().$return_acces_token['ventuno_template'].'edit/'.$post_data;
				}
				$login_url = 'http://'.VentunoMedia::get_base_env().$return_acces_token['ventuno_login_url'];
				$parms = base64_encode($api_keys['2'].'|##|'.$api_keys['3'].'|##|'.$redirect_url.'|##|wp');
				$url = $login_url.'/'.$parms;
				echo json_encode(array(	"status" => "redirect",	"url" =>$url));	exit;
			} else {
					echo json_encode(array("status" => "error",));exit;
			}
		}
	static function update_access_token($data_result_db_update) {
			delete_option('ventunomedia_access_token');
			add_option( 'ventunomedia_access_token', $data_result_db_update, '', 'yes');
	}
	static function update_blend_details($key , $data_result_blend_db_update,$force_update=0) {
			if('ventuno_blend_player_size'==$key) {
				delete_option('ventuno_blend_player_size');
				add_option( $key, json_encode($data_result_blend_db_update), '', 'yes');
			}
			if('ventuno_blend_page_id'==$key)  {
				if($force_update==1) delete_option('ventuno_blend_page_id');
				add_option( $key, json_encode($data_result_blend_db_update), '', 'yes');
			}
	 }
	 static function get_blend_details() {
			$get_option_belnd_size = json_decode(get_option( 'ventuno_blend_player_size'),true);
			$get_option_belnd_page_id =json_decode(get_option( 'ventuno_blend_page_id'),true);
			$belnd_data['vtn_blend_postid']=0;
		
			if(isset($get_option_belnd_page_id) && $get_option_belnd_page_id)
				$belnd_data['vtn_blend_postid'] = $get_option_belnd_page_id ;
			if(isset($get_option_belnd_size) && $get_option_belnd_size)
				$belnd_data['vtn_size'] = $get_option_belnd_size;
			return $belnd_data;
		}
		
		#video listing page 
		static function ventuno_menu_control() {
			VentunoMedia::log();
			$_REQUEST["action"] = array_key_exists("action", $_REQUEST)?$_REQUEST["action"]:"vtn-publish";
			$action = $_REQUEST[ 'action' ];
			$page = $_GET["page"];
			$plugin_url = VentunoMedia::ventuno_plugin_url();
			$data_result['status'] = '';
			$data_result['message'] = '';
	        	
				switch ( $page ) {

					case 'vtn-dashboard': {
				 
						wp_enqueue_script( 'ventuno_jquery-search', $plugin_url.'js/ventuno_search.js' , array("jquery") );	
						$return_access_token = VentunoMedia::ventuno_user_api_key();
						$data_result = array();
						$data_result = VentunoMediaApi::call_ventuno_dashboard_api( $return_access_token['access_token']  ) ;
						$data_result = json_decode( $data_result, true );
						print VentunoMedia::show_page_content( "includes/ventuno-dashboard-template.php" ,
						array('dashboard_result' => $data_result 
						));
						break;
					}
					case 'vtn-publish': {
						$data_result = array();
						wp_enqueue_script( 'ventuno_jquery-search', $plugin_url.'js/ventuno_search.js' , array("jquery") );	
						
						$sort_by = isset( $_REQUEST['sort_by'] ) ? $_REQUEST['sort_by'] : 1;
						$vtn_category = isset( $_REQUEST['vtn_category'] ) ? $_REQUEST['vtn_category'] : array( 'all' );
						$vtn_tags = isset( $_REQUEST['vtn_tags'] ) ? $_REQUEST['vtn_tags'] : 'all';
												
						$data_result['status'] = FALSE;
						$data_result['message'] = 'Link-up your account';
						$data_result['account_status'] = 'Un-Linked';
						$return_access_token = VentunoMedia::ventuno_user_api_key();
						if(isset($return_access_token['access_token']) && $return_access_token['access_token']!= '') {					
							$data_result = VentunoMediaApi::call_ventuno_api( $return_access_token['access_token'] , $sort_by,$vtn_category,$vtn_tags );
							$data_result = json_decode( $data_result, true );
						}
						print VentunoMedia::show_page_content( "includes/ventuno-search-template.php",array( 'result_ventuno_feed' =>  $data_result ,
						'sort_by' => $sort_by,
						'vtn_category' => $vtn_category,
						'vtn_tags' => $vtn_tags 
						));
						break;
					}
					case 'vtn-account-setup': {
						wp_enqueue_script( 'ventuno_jquery_validate_min_js' ,$plugin_url.'js/jquery.validate.min.js' , array("jquery"));
						wp_enqueue_script( 'ventuno_jquery_meta_validate_js' ,$plugin_url.'js/jquery.metadata.min.js' , array("jquery"));
						$data_result = array();
						
						$data_result['status'] = FALSE;
						$data_result['message'] = 'Link-up your account';
						$data_result['account_status'] = 'Un-Linked';
						$data_result['account_status_array'] = array ('Un-Linked');
	
						$form =0;
						$return_access_token = VentunoMedia::ventuno_user_api_key();
						if(isset($return_access_token['access_token']) && $return_access_token['access_token']!= '') {
							$data_result = json_decode( VentunoMediaApi::call_ventuno_is_linked( $return_access_token['access_token']) ,true);	
						}
				
						#link to ventuno
						if(in_array($data_result['account_status'], $data_result['account_status_array'])  && isset($_POST['login_id']) && isset($_POST['access_secure_id'])) {
							$form = 1;
							$data_result = VentunoMediaApi::call_ventuno_login_api( $_POST['login_id'], $_POST['access_secure_id'], VentunoMedia::$version);
							if($data_result['status']==TRUE) VentunoMedia::update_access_token( $data_result );
							$data_result = json_decode( $data_result, true );
						} 
						elseif(in_array($data_result['account_status'], $data_result['account_status_array']) && $_POST['new_partnrt_validate']=='Register') {
							$form = 2;
							$data_result = VentunoMediaApi::call_ventuno_new_partner_add_api($_POST, VentunoMedia::$version);
							$data_result = json_decode( $data_result, true );
							if($data_result['status']==TRUE) VentunoMedia::update_access_token(json_encode( $data_result['data'] ));
						}
						elseif(in_array($data_result['account_status'], $data_result['account_status_array'])) {
							$form = 1 ;
							$data_result = VentunoMediaApi::call_ventuno_account_setup();
							$data_result = json_decode( $data_result, true );
						} 
						print VentunoMedia::show_page_content( "includes/ventuno-account-template.php" ,array('account_result' => $data_result,'form_type' => $form));
						break;
					}
					case 'vtn-manage-template': {
						
						wp_enqueue_script( 'ventuno_jquery-search', $plugin_url.'js/ventuno_search.js' , array("jquery") );	
						
						$data_result_template = array();
						
						$data_result_template['status'] = FALSE;
						$data_result_template['message'] = 'Link-up your account';
						$data_result_template['account_status'] = 'Un-linked';
						$return_access_token = VentunoMedia::ventuno_user_api_key();

						if(isset($return_access_token['access_token']) && $return_access_token['access_token']!= '') {		
							$data_result_template = json_decode(VentunoMediaApi::call_ventuno_template_api( $return_access_token['access_token'] ) ,true);
						}
						
						print VentunoMedia::show_page_content( "includes/ventuno-manage-tmp-template.php" ,
						array('manage_template_result' => $data_result_template ,
						));
						break;
					}
					case 'vtn-blend-setup': {
						
						wp_enqueue_script( 'ventuno_jquery-search', $plugin_url.'js/ventuno_search.js' , array("jquery") );	
						
						$result_blend_setup = array();
						
						$result_blend_setup['status'] = FALSE;
						$result_blend_setup['message'] = 'Link-up your account';
						$result_blend_setup['account_status'] = 'Un-linked';
						$return_access_token = VentunoMedia::ventuno_user_api_key();
						if( $return_access_token['access_token'] ) {
							$result_blend_setup['status'] = TRUE;
							$result_blend_setup['message'] = 'Sucess';
							$result_blend_setup['account_status'] = 'linked';
						}
						print VentunoMedia::show_page_content( "includes/ventuno-blend-template.php" ,array('result_blend_setup'=> $result_blend_setup));
						break;
					}
				}
		}
		
	    static function show_page_content( $file = null, $data = array() ) {
			VentunoMedia::_data_check($data);			
			VentunoMedia::log();
			if ( !$file ) $file = $this->file;
			extract( $data ); // Extract the vars to local namespace
			ob_start(); // Start output buffering
			include $file; // Include the file
			$contents = ob_get_contents(); // Get the contents of the buffer
			ob_end_clean(); // End buffering and discard
			return $contents; // Return the contents
			VentunoMedia::show_alert();
		}
		
		static function _data_check(&$data){
			if(is_array($data)||is_object($data)){
				foreach ($data as $key => &$value) {
					VentunoMedia::_data_check($value);
				}
			}else{
				$data = htmlentities(stripslashes($data), ENT_QUOTES, "UTF-8");
			}
		}
		
		static function show_plugin_message() {
		
			$return_acces_token = VentunoMedia::ventuno_user_api_key();
			if(!$return_acces_token) {	
				$admin_page = get_admin_url();
				$admin_page = $admin_page.'admin.php?page=vtn-login';
				$click_here = '<a href="'.$admin_page.'">here</a>';
				VentunoMedia::$vtn_message = 'Thank you for activating Ventuno Plugin. Try connect to Ventuno Account '.$click_here.'.';
			}				
		}
	
		static function get_wordpress_plugins (){
			$plugins = get_option('active_plugins');
			$wordpress_plugins = '';
			  foreach ($plugins as $key => $value) {
			    $tmp = explode('/', $value);
					$wordpress_plugins .= $tmp[0].', ';
			  }
			$ventuno_api_version = VentunoMedia::$version;
		}
		
		static function video_duration( $sec ) {
				$duration = $sec;
				$return_video='';
				$hours = $mins = $sec = 0;
				$hours = floor( $duration / 3600 );
		
				if($hours)
					$mins = floor( ( $duration - ( $hours *3600 )) / 60 );
				else
					$mins = floor( $duration / 60 );
		
				if($hours && $mins)	
					$sec = $duration - ( ( $hours*3600 ) * ( $mins*60 ) );
				elseif($hours && !$mins)
					$sec = $duration - ($hours*3600);
				elseif(!$hours && $mins)
					$sec = $duration - ( $mins*60 );
				else
					$sec = $duration;
		
				//$return_vidoe. = sprintf( "%02s", $hours ).":";
				$return_video.= sprintf( "%02s", $mins ).":";
				$return_video.= sprintf( "%02s", $sec );
				
				return $return_video;
		} 
		
		 static function video_description($string, $max_length){
		    if ( strlen($string) > $max_length ){
		        $string = substr( $string, 0, $max_length );
		        $pos = strrpos( $string, " " );
		        if( $pos === false ) {
		                return substr( $string, 0, $max_length )."...";
		        }
		            return substr( $string, 0, $pos )."...";
		    }else{
		        return $string;
		    }
		}
		
		static function url_gen($title) {
			$title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
			$title = htmlentities($title, ENT_QUOTES, 'UTF-8');
			return $title;
		}
		static function ventuno_custom_button($page) {
		
			$vtn_icon_src = VentunoMedia::get_ventuno_icon();
			$title = 'Insert Ventuno Video';
			$onclick = 'tb_show("Ventuno Media Video Publish", "admin-ajax.php?action=ventuno_auto_post&amp;width=639&amp;height=497" );';
			$context .= "<a title='{$title}' href='#' onclick='{$onclick}'><img src='{$vtn_icon_src}'/></a>";
			return $context;
		}

	       static function ventuno_video_upload_into_post() {

			$video_id = $_REQUEST['video_id'];
			$format = $_REQUEST['format'];
			$video_key = $_REQUEST['video_key'];
			$video_name = urldecode($_REQUEST['video_name']);
		
			$video_tag =  '';
			$video_desc = '';
			
			if(isset($_REQUEST['vtn_size']))
				$vtn_size = $_REQUEST['vtn_size'];
			else 
				$vtn_size ='480×320';

			if( $_REQUEST['post_type'] == 'post'){
					$text = "[ventunomedia_video ven_vidoe_id=\"".$video_key."\" ven_size=\"".$vtn_size."\"]";
					$text .=$video_desc;
					$post_id = wp_insert_post(array(
						"post_content" => $text,
						"post_title" => html_entity_decode($video_name),
						"post_type" => "post",
						"post_status" => "draft",
						"tags_input" => html_entity_decode($video_tag)
					));
										
				echo json_encode(array(
					"status" => "redirect", 
					"url" => "post.php?post=".$post_id."&action=edit"));
				exit;
			} elseif( $_REQUEST['post_type'] =='code') {
				
				echo json_encode(array("status" => "ok",
				"content" => "[ventunomedia_video ven_vidoe_id=\"".$video_key."\" ven_size=\"".$vtn_size."\"]",
				"title" => html_entity_decode($video_name)));
				exit;
			}
	
		}
		
		 static function ventuno_template_upload_into_post() {
				
				$vn_vt_template_code = $_POST['vn_vt_template_code'];
				$vn_vt_publisher_code = $_POST['vn_vt_publisher_code'];
				
				$text = "[ventunomedia_template vn_vt_template_code=\"".$vn_vt_template_code."\" vn_vt_publisher_code=\"".$vn_vt_publisher_code."\"]";
				$post_id = wp_insert_post(array(
				"post_content" => $text,
				"post_title" => 'Videos',
				"post_type" => "page",
				"post_status" => "draft",
				));
				echo json_encode(array(
				"status" => "redirect", 
				"url" => "post.php?post=".$post_id."&action=edit"));
				exit;
		 }
			
		static function check_for_shortcode($atts) {
			
				extract( shortcode_atts( array(
					'ven_vidoe_id' => 'default',
					'ven_size' => 'default',
				), $atts, EXTR_SKIP ) );
				
				$return_access_token = VentunoMedia::ventuno_user_api_key();
				
				$ven_size_exp=explode('×',$ven_size);
				$ven_width=$ven_size_exp['0'];
				$ven_height=$ven_size_exp['1'];
				$single_video_update='<div id="vnVideoPlayerContent"></div>
				<script>
				var ven_video_key="'.$ven_vidoe_id.'";
				var ven_width="'.$ven_width.'";
				var ven_height="'.$ven_height.'";
				</script>
				<script type="text/javascript" src="http://'.$return_access_token['ventuno_single_video_javascipt'].'"></script>';
				return $single_video_update;
		}
		
		static function check_for_template_shortcode($atts) {
				
				extract( shortcode_atts( array(
					'vn_vt_template_code' => 'default',
					'vn_vt_publisher_code' => 'default',
				), $atts, EXTR_SKIP ) );
				
				$return_access_token = VentunoMedia::ventuno_user_api_key();
				$single_video_update='<script>
				var vn_vt_template_code="'.$vn_vt_template_code.'";
				var vn_vt_publisher_code="'.$vn_vt_publisher_code.'";
				</script>
				<script type="text/javascript" src="http://'.$return_access_token['ventuno_template_javascipt'].'"></script>';
				return $single_video_update;
		}
		static function check_for_blend_details_view_shortcode() {
				
			$publisher_id = '';
			$return_acces_token = VentunoMedia::ventuno_user_api_key();
			if($return_acces_token) {
				$api_keys = explode('|#|',base64_decode($return_acces_token['access_token']));
				$publisher_id = $api_keys[1];
			}
			$array_player_size = array("1"=>"640x480","2"=>"512x384","3"=>"480x320","4"=>"320x240","5"=>"100%");	
			$data_option = VentunoMedia::get_blend_details();
			if($data_option['vtn_size']!=5) {
				$player_size = explode('x',$array_player_size[$data_option['vtn_size']]);
				$extra_param ="&player_width=$player_size[0]&player_height=$player_size[1]";
			}
			else 	$extra_param ="&responsive=1";
			
			
				
			$param ="partner_key=$publisher_id&video_id=$_GET[video_id]&api_method=get_story_details$extra_param";
			$apiurl=VentunoMedia::get_ventuno_blend_api_uri().'/'.VentunoMedia::get_ventuno_blend_api_file().'?'.$param;
			$description = '';
			$result=file_get_contents('http://'.$apiurl);
			$content = simplexml_load_string($result);
			$description .="<style>#post-$data_option[vtn_blend_postid] .entry-header {display: none;}</style>";
			foreach ($content->channel->item as $contentitems)  {
				$description .=$contentitems->description;
			}
			return $description;
		}
      }
}
	
// Creating the widget 
class VentunoBlendWidget extends WP_Widget {

	 function __construct() {
		parent::__construct(
		'VentunoBlendWidget', 
		__('Ventuno Blend Widget', 'VentunoWidgetDetails'), 
		array( 'description' => __( 'Ventuno Blend Summary details', 'VentunoWidgetDetails' ), ) 
		);
	 }

	 // Creating widget front-end
	 public function widget( $args, $instance ) {
			$categorys = VentunoMedia::get_ventuno_category() ;
			$title = apply_filters( 'widget_title', $instance['title'] );
			$vtn_keywords = $instance['vtn_keywords'];
			$vtn_category = $instance['vtn_category'];
			$vtn_shows = $instance['vtn_showids'];
			$publisher_id = '';
			$return_acces_token = VentunoMedia::ventuno_user_api_key();
			if($return_acces_token) {
				$api_keys = explode('|#|',base64_decode($return_acces_token['access_token']));
				$publisher_id = $api_keys[1];
			}
			$vtncate = '';
			foreach($categorys as $cate_key=>$catvalue) {
				if(in_array($cate_key,$vtn_category)) {
					$catvalue= str_replace('&',',',$catvalue);
					$catvalue= str_replace(' ','',$catvalue);
					$vtncate .=$catvalue.",";
				}
			}
			if(!empty($vtncate)) 	$vtncate = trim($vtncate ,',');
			else $vtncate='all';
			
			$data_option = VentunoMedia::get_blend_details();
			if($data_option['vtn_blend_postid']>0) {
				$link = get_permalink( $data_option['vtn_blend_postid'] );
				$page_data = get_page($data_option['vtn_blend_postid']);
				if($page_data && $page_data->post_status != 'publish'){ 
						$text = "[VENTUNO_BLEND]";
						$post_id = wp_insert_post(array(
						"post_content" => $text,
						"post_title" => $instance['vtn_page_name'] ,
						"post_type" => "page",
						"post_status" => "publish",
						));
						VentunoMedia::update_blend_details('ventuno_blend_page_id',$post_id,1);	
						$link = get_permalink( $post_id );
				}
				$link = urlencode($link);
				$vtn_keywords = urlencode($vtn_keywords); 
				$param ="partner_key=$publisher_id&keywords=$vtn_keywords&category=$vtncate&show_id=all&page_url=$link&start_position=0&no_of_videos=7&&content_shuffle=1&api_method=get_story_summaries&language_type=EN";
				$apiurl='http://'.VentunoMedia::get_ventuno_blend_api_uri().'/'.VentunoMedia::get_ventuno_blend_api_file().'?'.$param;
				// before and after widget arguments are defined by themes
				echo $args['before_widget'];
				if ( ! empty( $title ) )
				echo $args['before_title'] ."<span style='font-family:Arial;font-weight:bold;font-size:14px;'>".$title."</span><hr size='3' color='black'></hr>". $args['after_title'];

				$description = '';
				$result=file_get_contents($apiurl);
				$content = simplexml_load_string($result);
				foreach ($content->channel->item as $contentitems)  {
					$description .=$contentitems->description;
				}		
			// This is where you run the code and display the output
			echo __( $description, 'VentunoWidgetDetails' );
			echo $args['after_widget'];
		}
	 }
	 // Widget Backend 
	 public function form( $instance ) {
				if ( isset( $instance[ 'title' ] ) ) 	$title = $instance[ 'title' ];
				else $title = __( 'New title', 'VentunoWidgetDetails' );
				
				if ( isset( $instance['vtn_size'] ) ) 	$vtn_size = $instance['vtn_size'];
				else $vtn_size = "2";
				
				if ( isset( $instance['vtn_category'] ) ) $vtn_category = $instance['vtn_category'];
				else $vtn_category = 'all';
				
				if ( isset( $instance['vtn_keywords'] ) ) 	$vtn_keywords = $instance['vtn_keywords'];
				else $vtn_keywords = 'all';
				
				if ( isset( $instance['vtn_page_name'] ) ) 	$vtn_page_name = $instance['vtn_page_name'];
				else $vtn_page_name = 'Blend Details';
				
			$data_option = VentunoMedia::get_blend_details();	
			if($data_option['vtn_blend_postid'] ==0 ) {
			?>
			<p>
			<label for="<?php echo $this->get_field_id('vtn_page_name'); ?>"><?php _e('Page Name : ' ); ?></label>
			<input type="text"  id="<?php echo $this->get_field_id('vtn_page_name'); ?>" name="<?php echo $this->get_field_name('vtn_page_name'); ?>" value="<?php echo esc_attr( $vtn_page_name ); ?>"/><br/>* Page Name should be string.
			</p>
			<?php } ?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title : ' ); ?></label> 
			<input  id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('vtn_category'); ?>"><?php _e('Blend Category : ' ); ?></label><br/>
			<select  id="<?php echo $this->get_field_id('vtn_category'); ?>" name="<?php echo $this->get_field_name('vtn_category'); ?>[]"  multiple>
			<?php 
			foreach( VentunoMedia::get_ventuno_category() as $cate_key => $cate_value ) { 
				$category_selected=''; 
				if($vtn_category!='all'  && is_array($vtn_category) && in_array($cate_key,$vtn_category)) $category_selected='selected';
				?>
				<option value="<?php echo $cate_key;?>" <?php echo $category_selected; ?>><?php echo $cate_value; ?></option>
			<?php }  ?>
			</select>
			</p>

			<p>
			<label for="<?php echo $this->get_field_id('vtn_keywords'); ?>"><?php _e('Keywords : ' ); ?></label><br/>
			<input type="text"  id="<?php echo $this->get_field_id('vtn_keywords'); ?>" name="<?php echo $this->get_field_name('vtn_keywords'); ?>" value="<?php echo esc_attr( $vtn_keywords ); ?>"/><br/>* Keywords should be separated by commas.
			</p>
			<p>
			<?php 
				$array_player_size = array("1"=>"640x480","2"=>"512x384","3"=>"480x320","4"=>"320x240","5"=>"100%");	
			?>
			<label for="<?php echo $this->get_field_id('vtn_size'); ?>"><?php _e('Player Size : ' ); ?></label>
			<select id="<?php echo $this->get_field_id('vtn_size'); ?>" name="<?php echo $this->get_field_name('vtn_size'); ?>">
				<?php 
				foreach( $array_player_size as $psize_key =>$psize_value ) { 
						$psize_sel = '';
						if($vtn_size==$psize_key) $psize_sel='selected';
				?>
				<option value="<?php echo $psize_key; ?>" <?php echo $psize_sel ; ?> ><?php echo $psize_value ?></option>
				<?php 
				}	
				?>
			</select>
			</p>

			<!--
			<p>
			<label for="<?php echo $this->get_field_id('apiurl'); ?>"><?php _e('Blend API URL:' ); ?></label>
			<textarea  style="width: 231px; height: 190px;" id="<?php echo $this->get_field_id('apiurl'); ?>" name="<?php echo $this->get_field_name('apiurl'); ?>">
			<?php echo $apiurl; ?></textarea>
			</p>
			<p><a href="<?php echo get_permalink( 656 ); ?>">My link to a post or page</a></p> 
			-->
			<?php 
	 }
	  // Updating widget replacing old instances with new
	  public function update( $new_instance, $old_instance ) {
			$instance = array();
			$data_option = VentunoMedia::get_blend_details();
			$new_instance['vtn_page_name']= preg_replace("/[^a-zA-Z0-9_-]+/", "", $new_instance['vtn_page_name']);
			$instance['vtn_page_name']  =  ( ! empty( $new_instance['vtn_page_name'] ) ) ? strip_tags( $new_instance['vtn_page_name'] ) : 'Blend Details';
			if($data_option['vtn_blend_postid'] ==0 ) {
				$text = "[VENTUNO_BLEND]";
					$post_id = wp_insert_post(array(
					"post_content" => $text,
					"post_title" => $instance['vtn_page_name'] ,
					"post_type" => "page",
					"post_status" => "publish",
				));
				$array_blend_key['vtn_blend_postid'] = $post_id;	
				VentunoMedia::update_blend_details('ventuno_blend_page_id',$array_blend_key['vtn_blend_postid'] ,0);	
			} 
			$array_blend_key['vtn_size'] = $instance['vtn_size'] = ( ! empty( $new_instance['vtn_size'] ) ) ? strip_tags( $new_instance['vtn_size'] ) : '2';
			VentunoMedia::update_blend_details("ventuno_blend_player_size",$array_blend_key['vtn_size'] );
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			
			$new_instance['vtn_keywords']= preg_replace("/[^a-zA-Z0-9, ]+/", "", $new_instance['vtn_keywords'] ,0);
			$instance['vtn_keywords'] = ( ! empty( $new_instance['vtn_keywords'] ) ) ? strip_tags( $new_instance['vtn_keywords'] ) : 'all';
			$instance['vtn_category'] = ( ! empty( $new_instance['vtn_category'] ) ) ? $new_instance['vtn_category'] : 'all';
			return $instance;
	  }
} 
// Class wpb_widget ends here

if( is_admin() ){
VentunoMedia::log( '----------------------VentunoMedia-----------------------' );
	add_action( 'admin_enqueue_scripts', array( 'VentunoMedia', 'ventuno_admin_scripts' ) );
	register_activation_hook( __FILE__, array( 'VentunoMedia', 'ventuno_setup' ) );
	register_uninstall_hook(__FILE__, array( $this, 'ventuno_delete_connector' ));
	add_action( 'admin_menu', array( 'VentunoMedia', 'ventuno_plugin_menu' ) );
	//add_action( 'admin_footer', array( 'VentunoMedia', 'show_alert' ));
	add_action( 'wp_loaded', array( 'VentunoMedia', 'show_plugin_message' ) );
	add_action( 'wp_ajax_vtn_insert_post', array( 'VentunoMedia', 'ventuno_video_upload_into_post' ));
	add_action( 'wp_ajax_vtn_insert_template_post', array( 'VentunoMedia', 'ventuno_template_upload_into_post' ));
	add_action( 'wp_ajax_ventuno_auto_post', array( 'VentunoMedia', 'get_ventuno_auto_post_callback' ));
	add_action( 'wp_ajax_ventuno_redirect', array( 'VentunoMedia', 'call_ventuno_redirect' ));
	add_action( 'media_buttons_context',  array( 'VentunoMedia', 'ventuno_custom_button') );
}
add_shortcode( 'ventunomedia_video', array("VentunoMedia", "check_for_shortcode") );		
add_shortcode( 'ventunomedia_template', array("VentunoMedia", "check_for_template_shortcode") );
add_action( 'widgets_init', array( 'VentunoMedia', 'ventuno_belnd_load_widget'));
add_shortcode( 'VENTUNO_BLEND', array("VentunoMedia", "check_for_blend_details_view_shortcode") );
?>
