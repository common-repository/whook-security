var Whook_Scanner = Object();
Whook_Scanner.addHtml = function(appendTo,Html){
	        if(jQuery(appendTo).length == 0) return;
            jQuery(appendTo).append(Html);
}

jQuery(document).ready( function($)
{
if(typeof Whook_Scanner != "undefinded"){
Whook_Scanner.addHtml(".plugins-php .bulkactions","<a id='whook_scan_btn' href='javascript:void(0)' >Click here for Vulnerability scan.</a><i id='whook_ajax_icon' style='display:none' class='spinner is-active'></i>");
}	

jQuery('.whook-tooltip').tooltipster({animation: 'fade',contentAsHTML:true});

jQuery(document).on("click","#whook_scan_btn",function(){
jQuery("#whook_ajax_icon").show();
jQuery(this).attr("disabled",true);
var $this = this;
  jQuery.ajax({
	url : ajaxurl,
	data : {action:"whook_plg_scan"},
	success:function(data){
		console.log(data)
		jQuery("#whook_ajax_icon").hide();
		jQuery($this).attr("disabled",false);
	   if(data.status == 1){
		jQuery('.msg-box').remove();							
		jQuery.each(data.data,function(pluginName,item){
		  var tag = "<div class='green-area msg-box'><img src='"+Whook_Plg_Url+"/images/check.png' style='width: 20px;height: 20px;'> <span>Woot! No issue detected.</span></div>";

			if(item != false && item['status']['vulnerable']['vulnerable_status']==1){
				
			  	var error_title = '';
				for(i=0;i<=item['status']['vulnerable']['vulnerable_error'].length;i++)
				{
				   if(typeof(item['status']['vulnerable']['vulnerable_error'][i])!='undefined')
				   {
					 msg_title = item['status']['vulnerable']['vulnerable_error'][i];
					 j = i+1;
					 error_title = error_title+j+'). '+msg_title+'<br/>';
				   }
				}
				
			   tag = "<div class='red-area msg-box'><img src='"+Whook_Plg_Url+"/images/close.png' style='width: 20px;height: 20px;'> <span>Vulnerble Plugin, this may harm your website.</span><span class='whook-tooltip' title='"+error_title+"'><img src='"+Whook_Plg_Url+"/images/tooltip.png' style='width: 20px;height: 20px;'></span></div>";
			}	
			if(item != false && item['status']['vulnerable']['vulnerable_status']==2){
			  
			  	var error_title = '';
				for(i=0;i<=item['status']['vulnerable']['vulnerable_error'].length;i++)
				{
				   if(typeof(item['status']['vulnerable']['vulnerable_error'][i])!='undefined')
				   {
					 msg_title = item['status']['vulnerable']['vulnerable_error'][i];
					 j = i+1;
					 error_title = error_title+j+'). '+msg_title+'<br/>';
				   }
				}
			  
			   tag = "<div class='yellow-area msg-box'><img src='"+Whook_Plg_Url+"/images/exc.png' style='width: 20px;height: 20px;'> <span>Vulnerability found, please upgrade plugin.</span><span class='whook-tooltip' title='"+error_title+"'><img src='"+Whook_Plg_Url+"/images/tooltip.png' style='width: 20px;height: 20px;'></span></div>";
			}
            jQuery('.whook-tooltip').tooltipster({animation: 'fade',contentAsHTML:true});

		    jQuery("tr[data-slug='"+pluginName+"'] .column-description").append(tag);
		})
	   }
	}
  })
});

});
