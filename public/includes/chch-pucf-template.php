<?php
/**
 * Pop-Up PRO CC - Click 
 *
 * @package   CcPopUpProClick
 * @author    Chop-Chop.org <shop@chop-chop.org>
 * @license   GPL-2.0+
 * @link      https://shop.chop-chop.org
 * @copyright 2014 
 */

/**
 * @package CcPopUpProClick
 * @author  Chop-Chop.org <shop@chop-chop.org>
 */
class ChChPCFTemplate { 
	
	/**
	* @var string $template	- template id
	* @var string $template_base - template base (category)
	* @var string $post_id - pop-up id
	*/
	private $template, $template_base, $post_id = 0;

	function __construct($template, $template_base, $post_id = 0) {
		$this->plugin = ChChPopUpClick::get_instance(); 
		$this->plugin_slug = $this->plugin->get_plugin_slug();
		
		$this->template = $template;
		$this->template_base = $template_base;
		$this->post_id = $post_id; 
 
	} 
	
	
	/**
	* Return all template options.
	*
	* If popup doesn't have saved options function returns default options array
	*
	* @return array
	*/
	function get_template_options(){
		if(!$options = get_post_meta($this->post_id, '_'.$this->template.'_template_data',true)){
			if(file_exists(CHCH_PUCF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/defaults.php'))
			{
				$options = (include(CHCH_PUCF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/defaults.php'));
			}
		}
		 
		return $options;
	} 
	
	
	/**
	* Return single template option.
	*
	* If popup doesn't have saved option with given name, function returns value from default options array.
	*
	* @param string $base - options group name, string $option - option name
	*
	* @return array
	*/
	function get_template_option($base, $option){
		
		$all_options = $this->get_template_options();
		
		if(isset($all_options[$base][$option])){
			
			return $all_options[$base][$option];
			
		} elseif(file_exists(CHCH_PUCF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/defaults.php')) {
			
			$default_options = (include(CHCH_PUCF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/defaults.php'));
			
			if(isset($default_options[$base][$option])){ 
				return $default_options[$base][$option];
			}
		}
		 
		return '';
	} 
	
	/**
	* Function includes template file to frontend and live preview.
	*  
	* @return void
	*/
	function get_template(){ 
		$template_options = $this->get_template_options(); 
		$id = $this->post_id;
		include(CHCH_PUCF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/index.php' );  
	}
	
	
	/**
	* Build pop-up css.
	*  
	* @return string $css - css styles generate from popup options
	*/
	function build_css(){ 
		$options = $this->get_template_options();
		$template = $this->template_base;
		
		$prefix = '#modal-'.$this->post_id.' '; 
		$css = '<style>';
		
		$size_options = $options['size'];
		if($size_options['custom'])
		{
			$css .= $prefix.'.'.$template.' .modal-inner  {
				width: '.$size_options['width'].'px;
				height: '.$size_options['height'].'px;
			}';
		}
		 
		$border_options = $options['border']; 
		$css .= $prefix.'.'.$template.' .modal-inner {
			border-radius: '.$border_options['radius'].'px; '; 
		$css .= '}';
		 
		 
		$css .= '</style>';
	
		echo $css;  
	}
	
	
	/**
	* Build pop-up js.
	*  
	* @return string $js - js script generate from popup options
	*/
	function build_js()
	{
		$id = $this->post_id; 
		
		$mobile_header = 'if($(window).width() > 1024){';
		$mobile_footer = '}';
		
		if(get_post_meta($id, '_chch_pucf_show_on_mobile',true))
		{
			$mobile_header = '';
			$mobile_footer = '';	
		}
		
		if(get_post_meta($id, '_chch_pucf_show_only_on_mobile',true))
		{
			$mobile_header = 'if($(window).width() < 1025){'; 
			$mobile_footer = '}';
		}
		
		$item = get_post_meta($id, '_chch_pucf_item',true);
		
		$script = '<script type="text/javascript">';
		$script .= 'jQuery(function($) {';
		
		$script .= 'if(!Cookies.get("shown_modal_'.$id.'")){ ';

		$script .= $mobile_header; 
	 
	 	$scroll_item = get_post_meta($id, '_chch_pucf_item',true);
				
		$script .= 'clicklEl = $("'.$scroll_item.'");';
		$script .= 'clicklEl.css("cursor","pointer");';		
		$script .= 'clicklEl.on("click", function(){
						$("#modal-'.$id.'").not(".chch_shown").show("fast");  	
						windowPos = $(window).scrollTop();
						windowHeight = $(window).height();
						popupHeight = $( "#modal-'.$id.' .modal-inner" ).outerHeight();
						popupPosition = windowPos + ((windowHeight - popupHeight)/2);
						$( "#modal-'.$id.' .pop-up-cc").css("top",Math.abs(popupPosition));
					});'; 
			 
		$script .= $mobile_footer;
		
		$script .= '}';
		
		$script .= '});';
		$script .= '</script>'; 
		
		echo $script;		
	}
	
	
	/**
	* Enqueue js and css files.
	*  
	* @return void
	*/
	function enqueue_template_style(){ 	
	
		$options = $this->get_template_options();
     
	   wp_enqueue_style($this->plugin_slug .'_google-fonts', '//fonts.googleapis.com/css?family=Playfair+Display:400,700,900|Lora:400,700|Open+Sans:400,300,700|Oswald:700,300|Roboto:400,700,300|Signika:400,700,300', null, ChChPopUpClick::VERSION, 'all');  
	
		if(file_exists(CHCH_PUCF_PLUGIN_DIR . 'public/templates/css/defaults.css')) {
			wp_enqueue_style($this->plugin_slug .'_template_defaults', CHCH_PUCF_PLUGIN_URL . 'public/templates/css/defaults.css', null, ChChPopUpClick::VERSION, 'all');  
		}
			
		if(file_exists(CHCH_PUCF_PLUGIN_DIR . 'public/templates/css/fonts.css')) {
			wp_enqueue_style($this->plugin_slug .'_template_fonts', CHCH_PUCF_PLUGIN_URL . 'public/templates/css/fonts.css', null, ChChPopUpClick::VERSION, 'all');  
		}
		
		if(file_exists(CHCH_PUCF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/css/base.css')){
			wp_enqueue_style('base_'.$this->template_base, CHCH_PUCF_PLUGIN_URL . 'public/templates/'.$this->template_base.'/css/base.css', null, ChChPopUpClick::VERSION, 'all');  
			  
		} 
		
		 
		if(file_exists(CHCH_PUCF_PLUGIN_DIR . 'public/assets/js/jquery-cookie/jquery.cookie.js')){	
			wp_enqueue_script( $this->plugin_slug .'jquery-cookie', CHCH_PUCF_PLUGIN_URL . 'public/assets/js/jquery-cookie/jquery.cookie.js', array('jquery') );
			
		}
		
		if(file_exists(CHCH_PUCF_PLUGIN_DIR . 'public/assets/js/public.js')){	
			wp_enqueue_script( $this->plugin_slug .'public-script', CHCH_PUCF_PLUGIN_URL . 'public/assets/js/public.js', array('jquery') ); 
			wp_localize_script( $this->plugin_slug .'public-script', 'chch_pucf_ajax_object', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' )) );
		} 
		
		if(file_exists(CHCH_PUCF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/css/style.css')){
			wp_enqueue_style('style_'.$this->template, CHCH_PUCF_PLUGIN_URL . 'public/templates/'.$this->template_base.'/'.$this->template.'/css/style.css', null, ChChPopUpClick::VERSION, 'all');  
			  
		}   
		 
	}
}