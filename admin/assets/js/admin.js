jQuery(document).ready( function ($) { 
	 
	var tabs = '<h2 class="nav-tab-wrapper" id="cc-pu-tabs"><a class="nav-tab nav-tab-active" href="#" title="Templates" data-target="cc-pu-tab-1">Templates</a><a class="nav-tab" href="#" title="Settings" data-target="cc-pu-tab-2">Settings</a></h2>';
	
	$('#post').prepend(tabs);
	
	$('#wpbody-content > .wrap').prepend(
	 '<a class="button button-secondary right button-hero" style="margin: 25px 0px 0px 2px; padding: 0px 20px; height: 47px;" href="https://shop.chop-chop.org/contact" target="_blank">Contact Support</a><a class="button button-primary right button-hero" href="http://ch-ch.org/pupro" style="margin: 25px 20px 0 2px;">Get Pro</a>');
	 
	$('#cc-pu-tabs a').on('click', function(e){
		e.preventDefault();
		var target = $(this).attr('data-target');
		
		if(!$(this).hasClass('nav-tab-active'))
		{
			$('.cc-pu-tab').hide();
			$('#cc-pu-tabs a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			
			$('.'+target).show();
		}
	});
	
	$('.chch-pucf-template-acivate').on('click', function(e){
		e.preventDefault();
		var template = $(this).attr('data-template');
		var base = $(this).attr('data-base');
		
		$('#poststuff .theme-browser .theme.active').removeClass('active');
		var theme = $(this).closest('.theme');
		theme.addClass('active'); 
		  
		$('#_chch_pucf_template').val(template); 
		$('#_chch_pucf_template_base').val(base); 
		$('#publish').trigger('click');
	});
	
	$('.cc-pu-customize-close').on('click', function(e){
		e.preventDefault();
		var template = $(this).attr('data-template');
		
		$('#cc-pu-customize-form-'+template).hide();  
	});
	 
	$('.chch-pucf-template-edit').on('click', function(e){
		e.preventDefault();
		var thisEl = $(this);
		template = thisEl.attr('data-template');
		base = thisEl.attr('data-base');
		id = thisEl.attr('data-postid');
		nounce = thisEl.attr('data-nounce'); 
		
		$.ajax({
            url: chch_pucf_ajax_object.ajaxUrl,
            async: true,
            type: "POST",
            data: {
                action: "chch_pucf_load_preview_module",
                template: template,
				base: base,
				nounce: nounce,
				id:id
				
            },
            success: function(data) { 
			  	
				if(!$('#'+base+'-css').length) { 
					$('head').append('<link rel="stylesheet" id="'+base+'-css"  href="'+chch_pucf_ajax_object.chch_pop_up_url+'public/templates/'+base+'/css/base.css" type="text/css" media="all" />');
				}
				
				if(!$('#'+template+'-css').length) { 
					$('head').append('<link rel="stylesheet" id="'+template+'-css"  href="'+chch_pucf_ajax_object.chch_pop_up_url+'public/templates/'+base+'/'+template+'/css/style.css" type="text/css" media="all" />');
				}
				
			 	theme = thisEl.closest('.theme');
				previewWrapper = $('#cc-pu-customize-form-'+template); 
                $('#cc-pu-customize-preview-'+template).html(data);
				
				$('.theme').removeClass('active');
				theme.addClass('active');  
				
				$('#_chch_pucf_template').val(template); 
				$('#_chch_pucf_template_base').val(base);
				 
				previewWrapper.find('.revealer-wrapper .cc-pu-customize-style').addClass('disable-option'); 
				previewWrapper.find('.revealer-wrapper.cc-pu-option-active .cc-pu-customize-style').removeClass('disable-option');  
				previewWrapper.find('.cc-pu-customize-style').not('.disable-option').trigger('change');     
				 
				previewWrapper.show();  
            }
        }); 
	});  
	  
	 
	function IsJsonString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
	
	/////////////
	//LIVE PREVIEW
	/////////////
	
	$( ".accordion-section-title" ).on('click', function(e){
	 	var el = $(this);
		var target = el.next('.accordion-section-content');
		if(!$(this).hasClass('open')){
			$( ".accordion-section-title").removeClass('open'); 
			el.addClass('open');
			target.slideDown('fast');	
		}	
		else
		{
			el.removeClass('open');
			target.slideUp('fast');	
		}  
	});
	 
	 $( '.cc-pu-colorpicker' ).wpColorPicker({
	 	change: _.throttle(function() {
			var el = $(this);
			var template = el.attr('data-template');
			var target = el.attr('data-customize-target');
			var styleAttr = el.attr('data-attr');
			var elValue = el.val(); 
			$('#cc-pu-customize-preview-'+template+' '+target).css(styleAttr,elValue);
		})
	 });
	 
	$('.cc-pu-customize-style').on('change', function(e){
		var el = $(this);
		
		var elId = el.attr('id');
		var elType = el.attr('type');
		var template = el.attr('data-template');
		var target = el.attr('data-customize-target');
		var styleAttr = el.attr('data-attr');
		var elValue = el.val(); 
		var elUnit = el.attr('data-unit');
		
		if(typeof elUnit === "undefined"){
			elUnit = '';
		}   
		
		if(styleAttr == 'background-image'){  
			$('#cc-pu-customize-preview-'+template+' '+target).css('background-image','url('+elValue+')');
			
			var n = elId.search("_image"); 
			if(n > 0) {
				$('#cc-pu-customize-preview-'+template+' '+target).css('background-size','cover');	
			}
		}
		else
		{ 
			$('#cc-pu-customize-preview-'+template+' '+target).css(styleAttr,elValue+elUnit);
		}
	  		  
	});
	
	$('.cc-pu-customize-content').on('keyup', function(e){
		var el = $(this); 
		var template = el.attr('data-template');
		var target = el.attr('data-customize-target');
		var elAttr = el.attr('data-attr');
		var elValue = el.val();  
		
		if(el.hasClass('remover')){
			if(elValue == ''){ 
				$('#cc-pu-customize-preview-'+template+' '+target).hide();	
			}else{
				$('#cc-pu-customize-preview-'+template+' '+target).show();	
			}
		}
		else if(typeof elAttr === "undefined"){
			$('#cc-pu-customize-preview-'+template+' '+target).text(elValue); 
		}
		else {   
			$('#cc-pu-customize-preview-'+template+' '+target).attr(elAttr,elValue); 
		}
	});
	 
	$('.remover-checkbox').on('change', function(){ 
		var target = $(this).attr('data-customize-target');
		
		if($(this).is(':checked')){
			$(target).hide();
		} else {
			$(target).show();	
		}
	});
	
	$('.revealer').on('change', function(){
		var el = $(this);
		var target = el.attr('data-customize-target');
		
		if(el.hasClass('active')){
			$('#'+target).slideUp('fast');
			el.removeClass('active');
		} 
		else
		{
			$('#'+target).slideDown('fast');
			el.addClass('active');
		}
	});   
}); 