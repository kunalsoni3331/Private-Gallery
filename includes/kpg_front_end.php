<?php

if( !class_exists ( 'KPG_Frontend' ) ) {

    class KPG_Frontend {

        function __construct(){

        	add_action( 'wp', array( $this, 'kpg_front_end' ) );

            add_shortcode( 'kpg_display_password', array( $this, 'kpg_display_password_func' ) );

            add_shortcode( 'kpg_display_product', array( $this, 'kpg_display_product_func' ) );

        }

        function kpg_front_end() {
            global $wp, $wpdb, $wp_query;
            if( ! is_admin() ) {
                
                $table = $wpdb->prefix . 'postmeta';
                $pg_id = array();
                if ( is_post_type_archive( 'kpg_private_gallery' ) ) {
                    wp_redirect( site_url() );
                }

                $is_protected = false;
                if ( is_singular( 'kpg_private_gallery' ) ) {
                    $pg_id[] = get_the_ID();
                    $is_protected = true;
                }

                if ( is_product() ) {
                    $product_id = get_the_ID();
                    $where = " meta_key like 'kpg_protected_products_%' AND meta_value = ".$product_id;
                    $results = $wpdb->get_results( "SELECT * FROM " . $table . " WHERE " . $where, ARRAY_A );
                    foreach ($results as $r_key => $row) {
                        $pg_id[] = $row['post_id'];
                        $is_protected = true;
                    }
                }

                if( $is_protected ) {

                    $display_form = true;
                    $allowed_pg = ( isset($_COOKIE['pg_data']) && !empty($_COOKIE['pg_data']) ) ? explode( '|', $_COOKIE['pg_data'] ) : array();
                    
                    if( !empty($allowed_pg) ) {
                        $common_value = array_intersect($allowed_pg, $pg_id);
                        if( !empty($common_value) ) {
                            $display_form = false;
                        }
                    }

                    if( isset($_REQUEST['post_password']) ) {

                        foreach ($pg_id as $private_gallery_id) {
                            $password = get_post_meta( $private_gallery_id, 'kpg_protected_password', true );
                            if( $password == base64_encode( $_REQUEST['post_password'] ) ) {
                                $allowed_term[] = $private_gallery_id;
                                $cookie_value = implode( '|', $allowed_term );
                                $expiry_time = time() + $expires_after_days * 30;
                                if ( $referrer = wp_get_referer() ) {
                                    $secure = ( 'https' === parse_url( $referrer, PHP_URL_SCHEME ) );
                                } else {
                                    $secure = false;
                                }
                                
                                setcookie('pg_data', $cookie_value, time() + (86400 * 30), "/"); 
                                $display_form = false;
                            }
                        }
                    }

                    if( ! $display_form ) {
                        $post_title = '';
                        $products = array();
                        if ( is_product() ) {
                            $products[] = get_the_ID();
                        } else {
                            $where = " meta_key like 'kpg_protected_products_%' AND post_id = ".get_the_ID();
                            $results = $wpdb->get_results( "SELECT * FROM " . $table . " WHERE " . $where, ARRAY_A );
                            foreach ($results as $r_key => $row) {
                                $products[] = $row['meta_value'];
                            }
                        }
                        $product_ids = implode(',',$products);
                        $post_content = '[kpg_display_product product_ids="'.$product_ids.'"]';
                    } else {
                        $post_title = __('Enter Password', KPG_txt_domain);
                        $post_content = '[kpg_display_password '.$args.']';
                    }
                        
                    $post_id                 = rand( 1000000, 10000000 ); // attempt to avoid clash with a valid post
                    $post                    = new stdClass();
                    $post->ID                = $post_id;
                    $post->post_author       = 1;
                    $post->post_date         = current_time( 'mysql' );
                    $post->post_date_gmt     = current_time( 'mysql', 1 );
                    $post->post_status       = 'publish';
                    $post->comment_status    = 'closed';
                    $post->comment_count     = 0;
                    $post->ping_status       = 'closed';
                    $post->post_type         = 'page';
                    $post->filter            = 'raw'; // important
                    $post->post_name         = 'category-login-' . $post_id; // append post ID to avoid clash
                    $post->post_title        = $post_title;
                    $post->post_content      = $post_content;

                    $wp_post = new WP_Post( $post );

                    $wp_query->post                  = $wp_post;
                    $wp_query->posts                 = array( $wp_post );
                    $wp_query->queried_object        = $wp_post;
                    $wp_query->queried_object_id     = $wp_post->ID;
                    $wp_query->found_posts           = 1;
                    $wp_query->post_count            = 1;
                    $wp_query->max_num_pages         = 1;
                    $wp_query->comment_count         = 0;
                    $wp_query->comments              = array();
                    $wp_query->is_singular           = true;
                    $wp_query->is_page               = true;
                    $wp_query->is_single             = false;
                    $wp_query->is_attachment         = false;
                    $wp_query->is_archive            = false;
                    $wp_query->is_category           = false;
                    $wp_query->is_tag                = false;
                    $wp_query->is_tax                = false;
                    $wp_query->is_author             = false;
                    $wp_query->is_date               = false;
                    $wp_query->is_year               = false;
                    $wp_query->is_month              = false;
                    $wp_query->is_day                = false;
                    $wp_query->is_time               = false;
                    $wp_query->is_search             = false;
                    $wp_query->is_feed               = false;
                    $wp_query->is_comment_feed       = false;
                    $wp_query->is_trackback          = false;
                    $wp_query->is_home               = false;
                    $wp_query->is_embed              = false;
                    $wp_query->is_404                = false;
                    $wp_query->is_paged              = false;
                    $wp_query->is_admin              = false;
                    $wp_query->is_preview            = false;
                    $wp_query->is_robots             = false;
                    $wp_query->is_posts_page         = false;
                    $wp_query->is_post_type_archive  = false;

                    // Update globals
                    $GLOBALS['wp_query'] = $wp_query;
                    $wp->register_globals();
                    
                } else {
                    return;
                }
            }
        }

        function kpg_display_password_func( $args=array(), $content = '' ) {
            include( $this->kpg_template_location( "password-private_gallery.php" ) );
        }

        function kpg_display_product_func( $args = array(), $content = '' ) {
            if( isset($args['product_ids']) && !empty($args['product_ids']) ) {
                $params = array('post_type' => 'product','post_status'=>'publish', 'post__in' => explode(',',$args['product_ids']));
                $kpg_post = new WP_Query($params);
                include( $this->kpg_template_location( "gallery-private_gallery.php" ) );
            }
        }

        function kpg_template_location ( $template ) {
	        $theme_dir = get_template_directory() . KPG_PLUGIN;
	        $template_url = false;
	        if( $template != '' && file_exists( $theme_dir . $template ) ) {
	            $template_url = $theme_dir . $template;
	        } else if( $template != '' && file_exists( KPG_TEMPLATE_DIR . $template ) ) {
	            $template_url = KPG_TEMPLATE_DIR . $template;
	        } 
	        return $template_url;
	    }

    }

    global $KPG_frontend;
    $KPG_frontend = new KPG_Frontend();

}


?>