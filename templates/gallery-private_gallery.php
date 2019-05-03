<?php if ( $kpg_post->have_posts() ) : ?>
	<form id="private_gallery_frm" action="#" method="post">
		<div class="thumb">
			<?php while ( $kpg_post->have_posts() ) : $kpg_post->the_post(); ?>

	        	<li style="display: inline-block;margin:10px; position: relative;">		

	        		<input style="position: absolute; top: 0; right: -5px" type='checkbox' name='private_product[]' id="<?php the_id(); ?>" value="<?php the_ID()?>" class="private_checkbox">
	        		<div style="border:3px solid #17a2b8; margin-bottom: 5px;"><?php echo the_post_thumbnail('thumbnail');?></div>

	        		<label for="<?php the_id(); ?>" class="btn btn-info" style="font-size: 15px" > Select </label>
	        		<button style="border-radius: 5px; border:none;vertical-align: top"  type="button" class="thumbnail btn btn-success" data-image-id="" data-toggle="modal" data-title="" data-image="<?php echo get_the_post_thumbnail_url();?>" data-target="#image-gallery">Preview</button>				        		
				</li>
		        
			<?php endwhile; ?>
		</div>

		<input type="submit" name="submit" value="Add to Cart" class="btn btn-info mybtton" >
	</form>
	
<?php endif; ?>	