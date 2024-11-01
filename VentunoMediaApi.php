<?php
#ventuno Apis
if ( ! class_exists( 'VentunoMediaApi' ) ) {
	class VentunoMediaApi {
		
		static function call_ventuno_dashboard_api( $access_token ) {

		
			VentunoMedia::log();
			$post_data['type']  = 'dashborad';
			$post_data['api_access_token'] = $access_token;

			$location = 'http://'.VentunoMedia::get_ventuno_api_uri().'/'.VentunoMedia::get_ventuno_api_file();
			$curl= curl_init();
			curl_setopt( $curl, CURLOPT_URL, $location );

			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $post_data );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

			try {
			$api_response = curl_exec( $curl );
			} catch (Exception $e) {
			VentunoMedia::abort( 'API call error: '.$e->getMessage());
			}
			$api_status = curl_getinfo( $curl );
			curl_close( $curl );
			return $api_response;
		}			

		static function call_ventuno_api( $access_token ,$sort_by,$vtn_category,$vtn_tags ){
				
			VentunoMedia::log();
			$get_var_for_ventuno_api = '';
			$static_vars=VentunoMedia::get_ventuno_api_static_vars();
			$category_search='';
			
			if( $sort_by == '1') $search_mode='latest';
			elseif( $sort_by == '2') $search_mode='relevance';
			
			if( !in_array( 'all',$vtn_category ) ) {
				$categoy_array = VentunoMedia::get_ventuno_category();
				foreach( $vtn_category as $vtn_key => $vtn_value ) {
					$category_search.=str_replace(' & ', ',', trim($categoy_array[$vtn_value])).",";
				}
				$category_search=trim($category_search,',');
				$static_vars['category']=$category_search;
			}
			
			if($vtn_tags != 'all') 
				$static_vars['keywords']=urlencode($vtn_tags);
				
			$static_vars['api_access_token']=urlencode($access_token);
			$static_vars['type']='video_api';
			
			
			foreach	($static_vars as $static_var_key => $static_var_value) {
				$get_var_for_ventuno_api .= $static_var_key."=".$static_var_value.'&';
			}
			$get_var_for_ventuno_api=trim($get_var_for_ventuno_api,'&');
				
			$location = 'http://'.VentunoMedia::get_ventuno_api_uri().'/'.VentunoMedia::get_ventuno_api_file().'?'.$get_var_for_ventuno_api;
			$curl= curl_init();
			curl_setopt( $curl, CURLOPT_URL, $location );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			try {
			$api_response = curl_exec( $curl );                            
			} catch (Exception $e) {
			VentunoMedia::abort( 'API call error: '.$e->getMessage());
			}

			$api_status = curl_getinfo( $curl );//, CURLINFO_HTTP_CODE );
			curl_close( $curl ); 

			return $api_response;
                               

		}
		
				
		static function call_ventuno_account_setup(){
				
			VentunoMedia::log();
			$post_data['type'] = 'account_setup';

			$location = 'http://'.VentunoMedia::get_ventuno_api_uri().'/'.VentunoMedia::get_ventuno_api_file();
			$curl= curl_init();
			curl_setopt( $curl, CURLOPT_URL, $location );
			
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $post_data );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	
			try {
			$api_response = curl_exec( $curl );
			} catch (Exception $e) {
			VentunoMedia::abort( 'API call error: '.$e->getMessage());
			}
			$api_status = curl_getinfo( $curl );
			curl_close( $curl );
			return $api_response;
		}
		
		
		static function call_ventuno_login_api( $user_id,$access_secure_id,$plugin_version ){
				
			VentunoMedia::log();
			$post_data  = array( 'connecter_plugin_version' =>  $plugin_version,
			'loginid' => $user_id,
			'access_secure_id' => $access_secure_id,
			'type' => 'connect_to_ventuno'
			);
				
			$location = 'http://'.VentunoMedia::get_ventuno_api_uri().'/'.VentunoMedia::get_ventuno_api_file();
			$curl= curl_init();
			curl_setopt( $curl, CURLOPT_URL, $location );
			
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $post_data );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	
			try {
			$api_response = curl_exec( $curl );
			} catch (Exception $e) {
			VentunoMedia::abort( 'API call error: '.$e->getMessage());
			}
			$api_status = curl_getinfo( $curl );
			curl_close( $curl );
			return $api_response;

		}
		
		static function call_ventuno_new_partner_add_api($post_data,$plugin_version){
				
			VentunoMedia::log();
			$post_data['type'] = 'new_user_add';
			$post_data['connecter_plugin_version'] = $plugin_version;

			$location = 'http://'.VentunoMedia::get_ventuno_api_uri().'/'.VentunoMedia::get_ventuno_api_file();
			$curl= curl_init();
			curl_setopt( $curl, CURLOPT_URL, $location );
			
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $post_data );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	
			try {
			$api_response = curl_exec( $curl );
			} catch (Exception $e) {
			VentunoMedia::abort( 'API call error: '.$e->getMessage());
			}
			/*echo "<pre>";
			print_r($api_response);
			echo "</pre>";
			die;*/
			$api_status = curl_getinfo( $curl );
			curl_close( $curl );
			return $api_response;

		}
		
		static function call_ventuno_template_api($access_token){
				
			VentunoMedia::log();
			$post_data['type'] = 'manage_template';
			$post_data['api_access_token'] = $access_token;

			$location = 'http://'.VentunoMedia::get_ventuno_api_uri().'/'.VentunoMedia::get_ventuno_api_file();
			$curl= curl_init();
			curl_setopt( $curl, CURLOPT_URL, $location );
			
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $post_data );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	
			try {
			$api_response = curl_exec( $curl );
			} catch (Exception $e) {
			VentunoMedia::abort( 'API call error: '.$e->getMessage());
			}
			$api_status = curl_getinfo( $curl );
			curl_close( $curl );
			return $api_response;
		}
		
		static function call_ventuno_is_linked($access_token) {
			
			VentunoMedia::log();
			$post_data['type'] = 'is_linked';
			$post_data['api_access_token'] = $access_token;

			$location = 'http://'.VentunoMedia::get_ventuno_api_uri().'/'.VentunoMedia::get_ventuno_api_file();
			$curl= curl_init();
			curl_setopt( $curl, CURLOPT_URL, $location );
			
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $post_data );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	
			try {
			$api_response = curl_exec( $curl );
			} catch (Exception $e) {
			VentunoMedia::abort( 'API call error: '.$e->getMessage());
			}
			$api_status = curl_getinfo( $curl );
			curl_close( $curl );
			return $api_response;
		}

	}
}	
?>
