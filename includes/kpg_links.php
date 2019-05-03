<?php

if( !class_exists ( 'KPG_Links' ) ) {

    class KPG_Links {

        function __construct(){
            
        	add_action( 'wp_ajax_kpg_link_password_update', array( $this, 'kpg_link_password_update_func' ) );

        } 

        function kpg_link_password_update_func() {
        	print_r($_REQUEST);
        }

        function kpg_page_content() {
            
            $params = array('post_type' => 'kpg_private_gallery','post_status'=>'publish');
            $wc_query = new WP_Query($params);

            if ( isset($wc_query->posts) && !empty($wc_query->posts) ){
            	?>
	        	<table id="kpg_links_data" class="table table-striped table-bordered" style="width:99%">
			        <thead>
			            <tr>
			                <th>Link</th>
			                <th width="50%">Password</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php while ($wc_query->have_posts()) :  $wc_query->the_post(); ?>
			        		<tr>
			        			<td><a href="<?php echo get_permalink(); ?>" target="blank"><?php echo get_permalink(); ?></a></td>
			        			<td>
			        				<span class="link_field"> 
			        					<a href="javascript:void(0);" class="kpg_change_passwrod"> Change Password </a> 
			        				</span>
			        				<span class="change_password_input">
			        					<input type="hidden" name="post_id" id="post_id" value="<?php echo get_the_ID(); ?>" />
			        					<input type="text" name="new_password" id="new_password" value="" />
			        					&nbsp;&nbsp;
			        					<a href="javascript:void(0);" class="kpg_link_password_update">Update</a>
			        					&nbsp;&nbsp;
			        					<a href="javascript:void(0);" class="kpg_link_cancel">Cancel</a>
			        				</span>
			        				<span class="change_password_success_msg">
			        					<?php _e('Password updated successfully.', KPG_txt_domain); ?>
			        				</span>
			        			</td>
			        		</tr>
			        	<?php endwhile; ?>
			        </tbody>
			    </table>
            	<?php
            }
        }

    }

    global $kpg_links;
    $kpg_links = new KPG_Links();

}


?>