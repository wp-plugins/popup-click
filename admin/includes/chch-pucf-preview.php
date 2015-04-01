<?php


if ( ! class_exists( 'ChChPCFTemplate' ) )
    require_once( CHCH_PUCF_PLUGIN_DIR . 'public/includes/chch-pucf-template.php' );
	
/**
 * @package CcPopUpProClick
 * @author  Chop-Chop.org <shop@chop-chop.org>
 */
class ChChPopUpClickFreePeview { 
	
	private $template_id, $template_base, $template_name, $template_options , $options_prefix;
	
	public $fields  = array();
	
	function __construct($template, $template_base, $template_name) {
		$this->plugin = ChChPopUpClick::get_instance(); 
		$this->plugin_slug = $this->plugin->get_plugin_slug(); 
		
		$this->template_id = $template; 
		
		$this->template_name = $template_name;
		
		$this->template_base = $template_base;
		
		$this->options_prefix ='_'.$this->template_id.'_';
		
		$this->template = new ChChPCFTemplate($this->template_id,$this->template_base, get_the_ID());
		
		$this->template_options = $this->template->get_template_options();
		
	} 
	
	/**
	 * Build preview view
	 *
	 * @since    0.1.0
	 */
	public function build_preview() {
		  
		echo '<div class="cc-pu-customize-form" id="cc-pu-customize-form-'.$this->template_id.'">';
		 
		echo '<div class="cc-pu-customize-controls">';
		
		//preview options header
		echo '
			<div class="cc-pu-customize-header-actions">
				<input name="publish" id="publish-customize" class="button button-primary button-large" value="Save &amp; Close" accesskey="p" type="submit" />  
				<a class="cc-pu-customize-close" href="#" data-template="'.$this->template_id.'">
					<span class="screen-reader-text">Close</span>
				</a> 
		</div>';
		
		//preview options overlay - start
		echo '<div class="cc-pu-options-overlay">';
		
		//preview customize info
		echo '<div class="cc-pu-customize-info">
				<span class="preview-notice">
					You are customizing <strong class="template-title">'.$this->template_name.' Template</strong>
				</span>
			</div><!--#customize-info-->';
	
		//preview options accordion wrapper - start
		echo '<div class="customize-theme-controls"  class="accordion-section">';
		
		// build options sections
		
		echo $this->build_options();
		
		echo '
				</div><!--.accordion-section-->
			</div><!--.cc-pu-options-overlay-->
		</div><!--#cc-pu-customize-controls-->';
	
		echo '<div id="cc-pu-customize-preview-'.$this->template_id.'" class="cc-pu-customize-preview" style="position:relative;">';
			 
		echo '</div>';
		echo '</div>'; 
		 
	}
	
	
	private function build_options() {
		
		$fields['general'] = array( 
			'name'	=> 'General',
			'field_groups' => array(
				array(
					'option_group' => 'size',
					'title'		   => 'Size',
					'fields' => array(
						array(
							'type'	 => 'revealer',  
							'name'   => 'custom', 
							'desc'   => 'Enable custom pop-up size (If you set a custom pop-up size, the pop-up won’t be responsive.)',
							'revaeals' => array(
								'section_title' => 'Custom Pop-Up Size',
								'fields' => array(
									array(
										'type'	 => 'text',
										'name'   => 'width',
										'action' => 'css', 
										'target' => '.pop-up-cc',
										'attr'   => 'width',
										'unit'   =>	'px',
										'desc'   => 'Width:',
									),
									array(
										'type'	 => 'text',
										'name'   => 'height',
										'action' => 'css', 
										'target' => '.pop-up-cc',
										'attr'   => 'height',
										'unit'   =>	'px',
										'desc'   => 'Height:',
									),
								),
							),
						), 
					),
				),
				array(
					'option_group'=> 'overlay',
					'title'=> 'Overlay', 
					'fields' => array(
						array(
							'type'	 => 'checkbox',
							'name'   => 'hide',  
							'class'  => 'remover-checkbox', 
							'target' => '.cc-pu-bg',
							'attr'   => 'visible', 
							'desc'   => 'Hide overlay:',
						),
					),
				),
				array(
					'option_group'=> 'none',
					'title'=> 'Overlay',
					'disable' => true,
					'fields' => array( 
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none', 
							'desc'   => 'Color:',
						),
						array(
							'type'	 => 'slider',
							'name'   => 'opacity',  
							'min'	=> '0',
							'max'	=> '1.0',
							'step'	=> '0.1', 
							'target' => 'none', 
							'desc'   => 'Opacity:',
						),
					),
				),
			), 	
		);
		
		$fields['borders'] = array(
			'id' 	=> 'general-options-group',
			'name'	=> 'Borders',
			'field_groups' => array(
				array(
					'option_group' => 'border',
					'title'		   => 'Border',
					'fields' => array(
						array(
							'type'	 => 'slider',
							'min'	=> '0',
							'max'	=> '50',
							'step'	=> '1',
							'name'   => 'radius',  
							'target' => '.modal-inner',
							'attr'   => 'border-radius',
							'unit'   =>	'px',
							'desc'   => 'Border Radius:',
						), 
					),
				), 
			), 	
		);
		
		
		$fields['background'] = array(
			'name'	=> 'Background',
			'disable' => true,
			'field_groups' => array(
				array(
					'option_group' => 'none',
					'title'		   => 'Background',
					'disable' => true,
					'fields' => array( 
						array(
							'type'	 => 'color_picker',
							'name'   => 'color', 
							'target' => 'none', 
							'desc'   => 'Color:',
						),
						array(
							'type'	 => 'select', 
							'name'   => 'type',  
							'desc'   => 'Background Type:', 
							'target' => 'none',   
							'options' => array(
								'no'  => 'No Image',
								'image' => 'Image',
								'pattern' => 'Pattern',
							), 
						), 
					),
				), 
			), 	
		);
		
		$fields['button'] = array( 
			'name'	=> 'Buttons',
			'field_groups' => array( 
				array(
					'option_group' => 'none',
					'title'		   => 'Close Button',
					'disable' => true,
					'fields' => array(  
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none',  
							'desc'   => 'Button Color:',
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'background',  
							'target' => 'none', 
							'desc'   => 'Background Color:',
						),  
					),
				),
			),
		);
		
		$fields['fonts'] = array( 
			'name'	=> 'Fonts and Colors',
			'field_groups' => array(
				array(
					'option_group' => 'none',
					'title'		   => 'Description',
					'disable' => true,
					'fields' => array(
						array(
							'type'	 => 'select', 
							'name'   => 'font',  
							'target' => 'none', 
							'desc'   => 'Description Font:',
							'options' => array(
								'Open Sans'  => 'Open Sans', 
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none',  
							'desc'   => 'Description Color:',
						), 
					),
				),
				array(
					'option_group' => 'none',
					'title'		   => 'Link',
					'disable' => true,
					'fields' => array( 
						array(
							'type'	 => 'select', 
							'name'   => 'font',   
							'desc'   => 'Font:',
							'target' => 'none', 
							'options' => array(
								'Open Sans'  => 'Open Sans', 
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none', 
							'desc'   => 'Color:',
						), 
					),
				),
			),
		);
		
		$fields['content'] = array( 
			'name'	=> 'Content',
			'field_groups' => array(
				array(
					'option_group' => 'contents',
					'title'		   => 'Content',
					'fields' => array(
						array(
							'type'	 => 'textarea', 
							'name'   => 'video', 
							'action' => 'text', 
							'target' => '.modal-inner-video', 
							'desc'   => 'Video embed code:',
						),
						array(
							'type'	 => 'editor', 
							'name'   => 'desc',  
							'target' => '.modal-inner-caption p', 
							'desc'   => 'Description:',
						),   
					),
				), 
			), 	
		); 
		   
		return $this->build_tabs($fields);
	}  
	
	private function build_tabs($fields) {
		if(!is_array($this->fields)) return; 
		 
		$controls ='';
		$i=0;
		foreach($fields as $field):
		
			$section_name = !empty($field['name']) ? $field['name'] : 'Section';
			$controls .='
				<h3 class="accordion-section-title" tabindex="'.$i.'">
					'.$section_name.'
					<span class="screen-reader-text">Press return or enter to expand</span> 
				</h3>';	
			$controls .= '<div class="accordion-section-content">';	 
			
			foreach($field['field_groups'] as $option):   
				$controls .= $this->build_sections($option); 
			endforeach;
			$i++;
			$controls .= '</div>'; 
		endforeach;
		
		return $controls; 
	}
	
	/**
	 * Build fields groups
	 *
	 * @since     1.0.0
	 *
	 * @return    $section - html
	 */
	private function build_sections($fields) {
		if(!is_array($fields)) return; 
		
		$section = '<div class="cc-pu-fields-wrapper">';
		
		if(isset($fields['disable'])){
			$section .= '
				<div class="cc-pu-overlay">
					<a href="http://ch-ch.org/puipro" target="_blank">AVAILABLE IN PRO</a>
				</div>'; 	
		}
		
		$section .= '<h4>'.$fields['title'].'</h4>'; 
		
		foreach($fields['fields'] as $field): 
			$type_func = 'build_field_'.$field['type'];  
			$section .= $this->$type_func($field, $fields['option_group']);
		endforeach; 
		 
		$section .= ' </div>'; 	 
		
		return $section;  
         
    }   
	
	private function build_field_slider($field, $options_group) {
		$options_prefix = $this->options_prefix;
		$template = $this->template_id;
		
		$name = $options_prefix.$options_group.'_'.$field['name']; 
		
		$options = isset($this->template_options[$options_group][$field['name']]) ? $this->template_options[$options_group][$field['name']] : '0';
 
		$option_html = '<label><span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<input type="hidden" ';	
		$option_html .= $this->build_field_attributes($field, $options_group);	
		$option_html .= '>';
					
		$option_html .= '<script type="text/javascript">
						jQuery(document).ready( function ($) { 
							 $( "#'.$name.'-slider" ).slider({
								max: '.$field['max'].',
								min: '.$field['min'].',
								step: '.$field['step'].',
								value: '.$options.',
								slide: function(e,ui) {
									var target = $(this).attr("data-target");
									$("#"+target).val(ui.value);
									$("#"+target).trigger("change");
								}
							});
									 
						}); 
						</script>
						<div id="'.$name.'-slider" data-target="'.$name.'"></div>';
			$option_html .= '</label>';			
		return $option_html;
					 
    }
	
	/**
	 * Build color picker field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_color_picker($field, $options_group) { 
		 
		$option_html = '<label class="cc-pu-option-active">';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		$option_html .= '<input type="text" ';
		$option_html .= $this->build_field_attributes($field, $options_group);	 
		$option_html .= '>';
		$option_html .= '</label>';					
		
		return $option_html; 		 
    }
	
	private function build_field_revealer($field, $options_group) {
		
		$options_prefix = $this->options_prefix;
		$template = $this->template_id;
		 
		$name = $options_prefix.$options_group.'_'.$field['name'];
		$id = str_replace('_','-',$name);
		$target = $id.'-revealer';
		
		$options = $this->template_options[$options_group];
		
		$checked = $options[$field['name']] ? 'checked' : '';
		
		$option_html = '<label><span class="customize-control-title">'.$field['desc'].'</span>';
		$option_html .= '
		<input 
			type="checkbox" 
			name="'.$name.'"
			id="'.$id .'" 
			class="revealer"
			data-customize-target="'.$target.'"    
			data-template="'.$template.'" 
			'.$checked.'
		>';	
		
		$option_html .= '</label>';	
		
		$hide = $options[$field['name']] ? '' : 'hide-section';
			
		$option_html .= '<div class="'.$hide.'" id="'.$target.'">';
					
		foreach($field['revaeals']['fields'] as $reveals): 
			$type_func = 'build_field_'.$reveals['type'];  
		 	$option_html .= $this->$type_func($reveals, $options_group);
		endforeach;
					
		$option_html .= '</div>';	
		
		return $option_html;
					 
    }
	
	/**
	 * Build revealer group field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private function build_field_revealer_group($field, $options_group) {
		
		$options_prefix = $this->options_prefix;
		$template = $this->template_id;
		
		$option_name =  $field['name'];
		$name = $options_prefix.$options_group.'_'.$field['name']; 
		$group = $options_group.'-'.$field['name'].'-group';
		
		$options = $this->template_options[$options_group]; 
		
		$option_html = '<label>';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<select 
						name="'.$name.'" 
						class="revealer-group" 
						data-group="'.$group.'"  
						data-customize-target="'.$field['target'].'"  
						data-attr="'.$field['attr'].'" 
						data-template="'.$template.'"  
						> ';
						
		if(!empty($field['options'])):
			foreach($field['options'] as $val => $desc):
				$selected = '';
				if($options[$field['name']] == $val){
						$selected = 'selected';
				}
				$option_html .= '<option value="'.$val.'" '.$selected.'>'.$desc.'</option> ';
			endforeach;
		endif; 
		
		$option_html .= '</select>';	
		$option_html .= '</label>';	
		
		foreach($field['revaeals'] as $reveals): 
			$hide = 'hide-section';
			if($this->template_options[$options_group][$option_name] == $reveals['section_id']){
				$hide = 'cc-pu-option-active';	
			}
				
			$option_html .= '<div class="'.$hide.' '.$group.' revealer-wrapper" id="'.$reveals['section_id'].'">';
						
			foreach($reveals['fields'] as $field): 
				$type_func = 'build_field_'.$field['type'];  
		 		$option_html .= $this->$type_func($field, $options_group);
			endforeach;
			
			$option_html .= '</div>';	
		endforeach;	 
		
		return $option_html;
					 
    }
	
	/**
	 * Build text field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_text($field, $options_group) {  
		
		$option_html = '<label class="cc-pu-option-active">';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<input type="text" '; 
		$option_html .= $this->build_field_attributes($field, $options_group);	
		$option_html .= '>';
		
		$option_html .= '</label>';		
					
		return $option_html;
					 
    }
	
	/**
	 * Build text field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_textarea($field, $options_group) {  
		
		$option_html = '<label class="cc-pu-option-active">';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<textarea '; 
		$option_html .= $this->build_field_attributes($field, $options_group); 
		$option_html .= '>';
		$option_html .= $this->build_field_values($field, $options_group);
		$option_html .= '</textarea>';
		
		$option_html .= '</label>';		
					
		return $option_html;
					 
    }
	
	
	/**
	 * Build checkbox field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_checkbox($field, $options_group) {  
		
		$option_html = '<label class="cc-pu-option-active">';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<input type="checkbox" '; 
		$option_html .= $this->build_field_attributes($field, $options_group);	
		$option_html .= $this->build_field_values($field, $options_group);
		$option_html .= '>';
		
		$option_html .= '</label>';		
					
		return $option_html;
					 
    }
	 
	
	/**
	 * Build select field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_select($field, $options_group) { 
		
		$option_html = '<label><span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<select ';
		$option_html .= $this->build_field_attributes($field, $options_group);	 
		$option_html .= '>';
		
		$option_html .= $this->build_field_values($field, $options_group);
		
		$option_html .= '</select></label>';					
		return $option_html;
					 
    }
	 
	
	private function build_field_editor($field, $options_group) {
		$options_prefix = $this->options_prefix;
		$template = $this->template_id;
		
		$options = $this->template_options[$options_group];
		
		$name = $options_prefix.$options_group.'_'.$field['name'];
		
		ob_start();  
 
		$settings = array( 
			'editor_class' => 'cc-pu-customize-content',
			'media_buttons' => false,
			'quicktags' => false,
			'textarea_name' => $name,
			'tinymce' => array(
				'toolbar1'=> ', bold,italic,underline,link,unlink,forecolor,undo,redo',
				'toolbar2'=> '',
				'toolbar3'=> ''
			)
		);
						 
		echo '<label><span class="customize-control-title">'.$field['desc'].'</span>';
		 wp_editor( $options[$field['name']], $field['name'].'_'.$template, $settings ); 
	  
		echo '</select></label>';
		$option_html = ob_get_clean();					
		return $option_html;
					 
    }
	 
	
		/**
	 * Return field attributes
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	function build_field_attributes($atts, $options_group){ 
		
		$type = $atts['type'];   
		  
		$attributes = ' ';	
		
		if(isset($atts['name']) && !empty($atts['name']))
		{
			$name = $this->options_prefix.$options_group.'_'.$atts['name'];	
		}
		else
		{
			$name = $this->options_prefix.$options_group.'_field';		
		}
		
		if(isset($atts['id']) && !empty($atts['id']))
		{
			$id = $atts['id'];	
		}
		else
		{
			$id = $name;		
		}
		
		$target = '';	
		if(isset($atts['target']) && !empty($atts['target']))
		{
			$target = $atts['target'];	
		} 
		
		$unit = '';
		if(isset($atts['unit']) && !empty($atts['unit']))
		{
			$unit = $atts['unit'];	
		}
		
		$attr = '';
		if(isset($atts['attr']) && !empty($atts['attr']))
		{
			$attr = $atts['attr'];	
		} 
		
		$value =  $this->build_field_values($atts, $options_group);
		 
		
		$action = '';
		
		if(isset($atts['action']) && !empty($atts['action']))
		{
			if($atts['target'] !=='none')
			{
				switch($atts['action']){
					case 'css': 
						$action = 'cc-pu-customize-style';
						break;
					case 'text': 
						$action = 'cc-pu-customize-content';
						break; 	 			
				}
			}
		}
		else
		{
			switch($type){
				case 'color_picker': 
					$action = 'cc-pu-colorpicker';
					break;
					
				case 'revealer': 
					$action = 'revealer';
					break;
					 
				case 'revealer_group': 
					$action = 'revealer-group';
					break;
					 	
				case 'font': 
					$action = 'cc-pu-fonts';
					break; 	
					 	
			}
			
			if(($type != 'revealer' || $type != 'revealer_group' || $type != 'text' || $type != 'textarea') && $atts['target'] !=='none') 
			{
				$action .= ' cc-pu-customize-style';
			}
		}
		
		if(isset($atts['class']) && !empty($atts['class'])){
			$action .= ' '.$atts['class'];
		}
		
		$attributes .= 'name="'.$name.'" ';	
		$attributes .= 'id="'.$id.'" ';	
		$attributes .= 'class="'.$action.'" ';	 
		$attributes .= 'data-template="'.$this->template_id.'" ';
		$attributes .= 'data-customize-target="'.$target.'" '; 
		
		if($unit) {
			$attributes .= 'data-unit="'.$unit.'" '; 	
		}
		
		if($attr) {
			$attributes .= 'data-attr="'.$attr.'" '; 	
		}
		
		$exclude_types = array('revealer','revealer_group','select', 'checkbox', 'textarea');
		if(!in_array($type, $exclude_types)) 
		{
			$attributes .= 'value="'.$value.'" '; 
		}
		
		return $attributes; 
	}
	
	/**
	 * get field values
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	function build_field_values($atts, $options_group){ 
		$option = $this->template->get_template_option($options_group, $atts['name']);
	 
		
		switch($atts['type']):
			case 'select':
				$select_option ='';
				foreach($atts['options'] as $val => $desc):
					$selected = '';
					if($option == $val){
							$selected = 'selected';
					}
					$select_option .= '<option value="'.$val.'" '.$selected.'>'.$desc.'</option> ';	
				endforeach; 	
				return $select_option;
			break; 
			
			case 'checkbox':
				if($option):
					return 'checked'; 
				endif;	
			break;
			
			default :
			 
					return $option; 
				
			break;
		endswitch; 
	}
}