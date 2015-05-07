jQuery(document).ready(function($) { 
 
	$( ".cc-pucf-close" ).click(function( e ) {
		e.preventDefault();
		
		chchPopUpID = $(this).attr('data-modalID'); 
		controlViews = $(this).attr('data-views-control');  
		controlExpires = $(this).attr('data-expires-control'); 
		 
		if(controlViews === 'yes' && controlExpires != 'refresh'){
			$("#modal-"+chchPopUpID).addClass("chch_shown");
			if(!Cookies.get('shown_modal_'+chchPopUpID)){
				switch(controlExpires){
					case 'session':
						Cookies.set('shown_modal_'+chchPopUpID, 'true',{ path: '/' });	
					break;
					
					case 'day':
						Cookies.set('shown_modal_'+chchPopUpID, 'true',{ expires: 1, path: '/' });	
					break;
					
					case 'week':
						Cookies.set('shown_modal_'+chchPopUpID, 'true',{ expires: 7, path: '/' });	
					break;
					
					case 'month':
						Cookies.set('shown_modal_'+chchPopUpID, 'true',{ expires: 31, path: '/' });	
					break;
					
					case 'year':
						Cookies.set('shown_modal_'+chchPopUpID, 'true',{ expires: 365, path: '/' });	
					break;			
				}
				
			}
		}
		
		$("#modal-"+chchPopUpID).hide("slow");
		
	});
	 
});
