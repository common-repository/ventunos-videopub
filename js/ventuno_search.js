
jQuery(function() {
	if(tot_record > 0 ) {
	generate_page(1);
	jQuery('#selector').pagination({
		items: jQuery('#tot-records').val(),
		itemsOnPage:  jQuery('#per_page').val(),
		cssStyle: 'light-theme',
		onPageClick: function(pageNumber){generate_page(pageNumber)}
	    });
	}
});

function generate_page(pageNumber){
 
 var page="#page-"+pageNumber;
 jQuery('.selection').hide();
 jQuery(page).show();

	jQuery('.page-load-'+pageNumber).each( function( index ) { 
		jQuery(this).stop();
		jQuery(this).hide(); 
	});

	jQuery('.page-load-'+pageNumber).each(function(index) {
	    jQuery(this).fadeOut(1000).delay(200*index).fadeIn(1850);
	});
 
}

function createpost(video_name,video_key,video_desc,video_tag,video_id,post_type) {
	
	var desc=0;
	var tag=0;
	if(jQuery('input:checkbox[name=include_desc]').is(':checked')) {
		desc=1;
	}
	if(jQuery('input:checkbox[name=include_keywords]').is(':checked')) {
		var tag=1;
	}
	
 	jQuery.ajax({
         type : "post",
         dataType : "json",
         url : ajaxurl,
         data : 
	 {
	 action: "vtn_insert_post", 
     format : 'post', 
     video_name : video_name,
	 video_key: video_key,
	 video_desc:video_desc, 
	 video_tag: video_tag,
	 vtn_size: jQuery('#vtn_size').val(),
	 vtn_play: jQuery('input:radio[name=play_back]:checked').val(),
	 include_desc: desc,
	 include_keywords:tag, 
	 post_type : post_type,
	 },
         success: function(response) {
            if(response.status == 'redirect') {
                window.location = response.url;
            }
	    else if (response.status == "ok") {
                window.send_to_editor(response.content);
				jQuery('#title').val(response.title)
            }	
            else {
               alert("Your Video could not be post")
            }
         },
	error: function(MLHttpRequest, textStatus, errorThrown){  
  		alert(errorThrown);  
  	}

      })  
}

function publish_template(temp_id,publiser_id) {
	
 	jQuery.ajax({
         type : "post",
         dataType : "json",
         url : ajaxurl,
         data : 
		 {
		 action: "vtn_insert_template_post", 
	     format : 'post', 
	     vn_vt_template_code : temp_id,
		 vn_vt_publisher_code: publiser_id,
		 },
         success: function(response) {
            if(response.status == 'redirect') {
                window.location = response.url;
            }
            else {
               alert("Your Template could not be post");
            }
         },
	error: function(MLHttpRequest, textStatus, errorThrown){  
  		alert(errorThrown);  
  	}

    })  
}


jQuery('.upload-button').click(function() {
});
