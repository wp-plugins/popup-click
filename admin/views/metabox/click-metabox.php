<table class="form-table cmb_metabox">
	<tbody>  
		<tr class="cmb-type-radio">
			<th style="width:18%"><label for="_chch_pucf_scroll_adapter">Element:</label></th>
			<td> 
				<?php $item_option = get_post_meta($post->ID,'_chch_pucf_item',true);  ?> 
				<input class="cmb_text_medium" name="_chch_pucf_item" id="_chch_pucf_item" value="<?php echo $item_option; ?>" type="text">
				<br /> <span class="cmb_metabox_description">Enter the .class or #id of the element that should trigger the pop-up.</span>
			</td>
		</tr>  
	</tbody>
</table>
