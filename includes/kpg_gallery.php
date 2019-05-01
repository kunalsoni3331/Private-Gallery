<?php

if( !class_exists ( 'KPG_Gallery' ) ) {

    class KPG_Gallery {

        function __construct(){
            add_action( 'init', array( $this, 'create_custom_post_type' ), 0 );
        }

        function create_custom_post_type() {
        
            $labels = array(
                'name'                => _x( 'Private Gallery', 'Post Type General Name', 'KPG_txt_domain' ),
                'singular_name'       => _x( 'Private Gallery', 'Post Type Singular Name', 'KPG_txt_domain' ),
                'menu_name'           => __( 'Private Gallery', 'KPG_txt_domain' ),
                'parent_item_colon'   => __( 'Private Gallery', 'KPG_txt_domain' ),
                'all_items'           => __( 'All Private Gallery', 'KPG_txt_domain' ),
                'view_item'           => __( 'View Private Gallery', 'KPG_txt_domain' ),
                'add_new_item'        => __( 'Add New Private Gallery', 'KPG_txt_domain' ),
                'add_new'             => __( 'Add New', 'KPG_txt_domain' ),
                'edit_item'           => __( 'Edit Private Gallery', 'KPG_txt_domain' ),
                'update_item'         => __( 'Update Private Gallery', 'KPG_txt_domain' ),
                'search_items'        => __( 'Search Private Gallery', 'KPG_txt_domain' ),
                'not_found'           => __( 'Not Found', 'KPG_txt_domain' ),
                'not_found_in_trash'  => __( 'Not found in Trash', 'KPG_txt_domain' ),
            );
             
            $args = array(
                'label'               => __( 'Private Gallery', 'KPG_txt_domain' ),
                'description'         => __( 'Private Gallery', 'KPG_txt_domain' ),
                'labels'              => $labels,
                'supports'            => array( 'title', 'excerpt', 'author', 'revisions', 'custom-fields', ),
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => false,
                'show_in_menu'        => false,
                'show_in_nav_menus'   => false,
                'show_in_admin_bar'   => false,
                'menu_position'       => 5,
                'can_export'          => true,
                'has_archive'         => true,
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'capability_type'     => 'page',
            );
             
            register_post_type( 'kprivate_gallery', $args );
         
        }

        function page_content() {
            $params = array('post_type' => 'product','post_status'=>'publish');
            $wc_query = new WP_Query($params);
            if ($wc_query->have_posts()){
                ?>                    
                <form action="#" method="post">
                    <div class="thumb">
                        <?php while ($wc_query->have_posts()) :  $wc_query->the_post(); ?>
                            <li style="display: inline-block;margin:10px; position: relative;">     

                                <input style="position: absolute; top: 0; right: -5px" type='checkbox' name='private[]' id="<?php the_id(); ?>" value="<?php the_ID()?>">
                                <div style="border:3px solid #17a2b8; margin-bottom: 5px;"><?php echo the_post_thumbnail('thumbnail');?></div>

                                <label for="<?php the_id(); ?>" class="btn btn-info" style="font-size: 15px" > Select </label>
                                <button style="border-radius: 5px; border:none;vertical-align: top"  type="button" class="thumbnail btn btn-success" data-image-id="" data-toggle="modal" data-title="" data-image="<?php echo get_the_post_thumbnail_url();?>" data-target="#image-gallery">Preview</button>                               
                            </li>
                        <?php endwhile; ?>
                    </div>

                    <!--  Light box Start -->
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog model-lg" style="min-width: 800px">
                            <div class="modal-content">
                                <div class="modal-header" style="padding:35px 50px;">
                                    <h4><span class="glyphicon glyphicon-lock"></span>Enter Private Product Details</h4>
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                </div>
                                <div class="modal-body" style="padding:40px 50px;">
                                    <form role="form">
                                        <div class="form-group">
                                            <label for="usrname"><span class="glyphicon glyphicon-url"></span> Private URL:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" style="width:30%" readonly="" name="site_url" value="<?php echo site_url()."/product_gallery/";?>">
                                                <input type="text" style="width:65%" class="form-control" placeholder="Enter Your URL" name="private_url" id="private_url">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Enter Password</label>
                                            <input type="password" class="form-control" name="user_pass" id="user_pass" id="psw" placeholder="Enter password">
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block" name="protect_product"><span class="glyphicon glyphicon-off"></span> Protact Product</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  Light box End -->

                    <input type="button" name="model_pop" value="Submit Product" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                </form>

                <!-- Image preview Light box Start -->
                <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="image-gallery-title"></h4>
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <img id="image-gallery-image" class="img-responsive col-md-12" src="">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary float-left" id="show-previous-image"><i class="fa fa-arrow-left"></i>
                                    </button>

                                    <button type="button" id="show-next-image" class="btn btn-secondary float-right"><i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Image preview Light box End -->
                <?php
            }
        }
 

    }

    global $kpg_gallery;
    $kpg_gallery = new KPG_Gallery();

}


?>