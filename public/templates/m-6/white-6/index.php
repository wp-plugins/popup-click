<?php
/**
 *
 * id: white-6
 * base: m-6
 * title: White
 * 
 */

?>


<?php $overlay = $this-> get_template_option('overlay','hide'); ?>
<?php  if(!$overlay || is_admin()): ?>
<div class="cc-pu-bg m-6 white-6"></div>
<?php endif;?>

<article class="pop-up-cc m-6 white-6">
	<div class="modal-inner">
		<?php $views_control = get_post_meta($id,'_chch_pucf_show_only_once',true); ?>
		<a class="cc-pu-close cc-pucf-close" data-modalId="<?php echo $id; ?>" data-views-control="yes" data-expires-control="<?php echo $views_control ?>">  <i class="fa fa-times"></i> </a> 
		<?php $content = $template_options['contents']; ?> 
		<?php 
		if(is_admin()):
			echo '<img src="'.CHCH_PUCF_PLUGIN_URL.'public/templates/m-6/css/video-ph.png">';
		else:
		?>
			<div class="modal-inner-video">
			<?php echo $content['video'];?>
			</div>	
		<?php 
		endif;
		?>
		 
		
		<div class="modal-inner-caption">
			<p><?php echo $content['desc'];?></p>
		</div>	  
	</div>
</article>
