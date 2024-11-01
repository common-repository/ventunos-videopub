
<!-- ******************** --> 	
<!-- PRIVIEW POPUP START -->
<!-- ******************** --> 

<div id="backgroundPopup"></div>

   <div id="popupContact" style="height:<?php echo $height ?>; width:750px;">
  <div id="close">
  <img onclick="disablepopup();" border="0" src="<?php echo VentunoMedia::ventuno_plugin_url() ?>images/close.jpg">
  </div>
	<div id="pre_player_head"></div>
		<table width="100%" cellpadding="5">
		<tr>
			<td valign="top" width="50%">        
            	<div id="pre_player"></div>
            </td>
            <td valign=top>
            	<div style="height:270px; overflow-y:auto;">            
	            	<div id="pre_desc"></div>
		            <BR />
		            <div id="pre_date"></div>
		            <BR />
		            <div id="pre_vduration"></div>
		            <BR />
		            <div id="pre_tags"></div>
	            </div>
            </td>
        </tr>
		<tr>
			<td colspan="2">
					
					<div id="get_code_option"></div>
			</td>
		</tr>
        </table>
</div>

<style>
body{ 
height:auto;
margin: 0; 
padding: 0; 
direction: ltr; 
background-repeat:repeat; 
background-image:url( "<?php echo VentunoMedia::ventuno_plugin_url(); ?>/images/bg/bg_dark.gif" );
}
</style>