<?php

if( !class_exists ( 'KPG_Links' ) ) {

    class KPG_Links {

        function __construct(){
            
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
			                <th>Password</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php while ($wc_query->have_posts()) :  $wc_query->the_post(); ?>
			        		<tr>
			        			<td><a href="<?php echo get_permalink(); ?>" target="blank"><?php echo get_permalink(); ?></a></td>
			        			<td><?php echo base64_decode( get_post_meta( get_the_id(), 'kpg_protected_password', true ) ); ?></td>
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