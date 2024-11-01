var popupStatus = 0;
function showpopup(title, embed, partnercode, desc, tag,vdate,video_duration) {
	
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = jQuery("#popupContact").height();
	var popupWidth = jQuery("#popupContact").width();
	//centering
	t = windowHeight/2-popupHeight/2;
	l = windowWidth/2-popupWidth/2;


	jQuery("#popupContact").css({
		"_position":"absolute",
		"top": t-20,
		"left": l
	});
	//only need force for IE6
	
	jQuery("#backgroundPopup").css({
		"_position":"absolute",
		"height": windowHeight
	});	

	//load popup
	if(popupStatus==0){
		jQuery("#backgroundPopup").css({
			"opacity": "0.7"
		});
		jQuery("#backgroundPopup").fadeIn("slow");
		jQuery("#popupContact").fadeIn("slow");
		document.getElementById('pre_player_head').innerHTML = title;
		document.getElementById('pre_player').innerHTML ='<embed src="'+embed+'" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" allowNetworking="all" width="300" height="250" ></embed>';
		jQuery('#pre_desc').html('<b>Description:</b><font style="font-weight:normal">'+desc+'</font>');
		jQuery('#pre_tags').html('<b>Keywords:</b><font style="font-weight:normal">'+tag+'</font>');
		jQuery('#pre_date').html('<b>Uploaded Date:</b><font style="font-weight:normal">'+vdate+'</font>');
		jQuery('#pre_vduration').html('<b>Video Duration:</b><font style="font-weight:normal">'+video_duration+' </font>');

		var video_get_code = '[ventunomedia_video ven_vidoe_id="'+partnercode+'" ven_size="480×320"]';

		jQuery('#get_code_option').html('<b>Video Code:</b><br/><font style="font-weight:normal">'+video_get_code+' </font>');
		popupStatus = 1;
	}
	return false;
}
function disablepopup(){
	if(popupStatus==1){
		jQuery("#backgroundPopup").fadeOut("slow");
		jQuery("#popupContact").fadeOut("slow");
		jQuery("#pre_player > embed").remove();
		popupStatus = 0;
	}
}

function redirect_ventuno(type,data_post) {
	
 	 jQuery.ajax({
         type : "post",
         dataType : "json",
         url : ajaxurl,
         data : 
		 {
		 action: "ventuno_redirect", 
	     format : 'post', 
	     click_type : type,
		 data_post: data_post,
		 },
		 success: function(response) {
            if(response.status == 'redirect') {
                var popup = window.open(response.url, '_blank');
		popupBlockerChecker.check(popup);
            }
		},
        error: function(MLHttpRequest, textStatus, errorThrown){  
  			alert(errorThrown);  
  		}

    })  
}

var popupBlockerChecker = {
        check: function(popup_window){
            var _scope = this;
            if (popup_window) {
                if(/chrome/.test(navigator.userAgent.toLowerCase())){
                    setTimeout(function () {
                        _scope._is_popup_blocked(_scope, popup_window);
                     },200);
                }else{
                    popup_window.onload = function () {
                        _scope._is_popup_blocked(_scope, popup_window);
                    };
                }
            }else{
                _scope._displayError();
            }
        },
        _is_popup_blocked: function(scope, popup_window){
            if ((popup_window.innerHeight > 0)==false){ scope._displayError(); }
        },
        _displayError: function(){
            alert("Popup Blocker is enabled! Please add this site to your exception list.");
        }
};


