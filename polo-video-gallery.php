<?php 
/*
 * Plugin Name: Polo Video Gallery
 * Plugin URI:  http://bPlugins.com
 * Description: A lite weight completely customizable self hosted video player that support mp4 / ogg and subtitle
 * Version: 1.2
 * Author: bPlugins LLC
 * Author URI: http://bPlugins.com
 * License: GPLv3
 */
 
/*Some Set-up*/
define('PVG_PLUGIN_DIR', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' ); 


/* JS*/
if ( ! function_exists( 'pvg_get_script' ) ) :
function pvg_get_script(){    
    wp_enqueue_script( 'pvg-js', plugin_dir_url( __FILE__ ) . 'js/plyr.js', array('jquery'), '20120206', false );
}
add_action('wp_enqueue_scripts', 'pvg_get_script');
endif;



function pvg_style() {
    wp_enqueue_style( 'pvg-style', plugin_dir_url( __FILE__ ) . 'css/player-style.css' );

}
add_action( 'wp_enqueue_scripts', 'pvg_style' );


// HIDE everything in PUBLISH metabox except Move to Trash & PUBLISH button


function pvg_hide_publishing_actions(){
        $my_post_type = 'pgallery';
        global $post;
        if($post->post_type == $my_post_type){
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
        }
}
add_action('admin_head-post.php', 'pvg_hide_publishing_actions');
add_action('admin_head-post-new.php', 'pvg_hide_publishing_actions');
	



//Remove post update massage and link 
function pvg_updated_messages( $messages ) {
    $messages['pgallery'][1] = __('Updated');
    return $messages;
}
add_filter('post_updated_messages','pvg_updated_messages');
 



//  Metabox
		

include_once('metabox/meta-box-class/my-meta-box-class.php');
include_once('metabox/class-usage-demo.php');

 

// Register Custom Post Types 
     
            add_action( 'init', 'pvg_create_post_type' );
            function pvg_create_post_type() {
                    register_post_type( 'pgallery',
                            array(
                                    'labels' => array(
                                            'name' => __( 'Polo Video Gallery'),
                                            'singular_name' => __( 'Gallery' ),
                                            'add_new' => __( 'Add New' ),
                                            'add_new_item' => __( 'Add new item' ),
                                            'edit_item' => __( 'Edit' ),
                                            'new_item' => __( 'New' ),
                                            'view_item' => __( 'View' ),
											'search_items'       => __( 'Search'),
                                            'not_found' => __( 'Sorry, we couldn\'t find any item you are looking for.' )
                                    ),
                            'public' => false,
							'show_ui' => true, 									
                            'publicly_queryable' => true,
                            'exclude_from_search' => true,
                            'menu_position' => 14,
							'menu_icon' =>PVG_PLUGIN_DIR .'img/icon.png',
                            'has_archive' => false,
                            'hierarchical' => false,
                            'capability_type' => 'page',
                            'rewrite' => array( 'slug' => 'pgallery' ),
                            'supports' => array( 'title','thumbonail' )
                            )
                    );
            }	
			
// ONLY OUR CUSTOM TYPE POSTS
add_filter('manage_pgallery_posts_columns', 'pvg_column_handler', 10);
add_action('manage_pgallery_posts_custom_column', 'pvg_column_content_handler', 10, 2);
 
// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
function pvg_column_handler($defaults) {
    $defaults['directors_name'] = 'ShortCode';
    return $defaults;
}
function pvg_column_content_handler($column_name, $post_ID) {
    if ($column_name == 'directors_name') {
        // show content of 'directors_name' column
		echo '<input onClick="this.select();" value="[vgallery id='. $post_ID . ']" >';
    }
}
			
// Review Request as admin notice


function pvg_review_request_notice() {
    ?>
    <div class="notice notice-success ">
		<p><?php $url = 'https://wordpress.org/support/plugin/polo-video-gallery/reviews/?filter=5#new-post';
			$text = sprintf( __( 'If you like <strong>Polo Video Gallery</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. Need some improvement ? We like to listen from you ! <a href="mailto:abuhayat.du@gmail.com">Request for improvement.</a> ', 'pvg-review' ), $url ); echo $text; ?></p>
			
				
    </div>
    <?php
}
add_action( 'admin_notices', 'pvg_review_request_notice' );			

// Footer Review Request 

	add_filter( 'admin_footer_text','pvg_admin_footer');	 
	function pvg_admin_footer( $text ) {
		if ( 'pgallery' == get_post_type() ) {
			$url = 'https://wordpress.org/support/plugin/polo-video-gallery/reviews/?filter=5#new-post';
			$text = sprintf( __( 'If you like <strong>Polo Video Gallery</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'post-carousel' ), $url );
		}

		return $text;
	}

// Lets register our shortcode
function pvg_shortcode_func($atts){
	extract( shortcode_atts( array(

		'id' => null,

	), $atts ) ); 

?>
<?php ob_start();?>
	<div style="width:100%;padding:10px;<?php $bgc=get_post_meta($id,'_pvg_bg_color', true); if($bgc!=='#'){echo 'background-color:'.$bgc; }?>">
		<?php if(get_post_meta($id,'_pvg_gallery_title', true)!=='on'){?><h3 class="title"><?php echo get_the_title( $id ); ?></h3> <?php }  ?> 
		<div style="overflow:hidden;">
			<?php $videoitem=get_post_meta($id,'_pvg_re_', true); if(!empty($videoitem)){
			foreach($videoitem as $videoitems){ ?>
			<?php $theme=get_post_meta($id,'_pvg_theme',true); if($theme=='theme1'){?>
			
			<div style="margin:5px;float:left;overflow:hidden;width:<?php echo get_post_meta($id,'_pvg_column', true);?>">
				<div style="margin-bottom:25px;">
					<div style="margin-botton:25px;">
						<video  <?php $status1= get_post_meta($id,'_pvg_video_repeat', true); if ($status1=="loop"){echo "loop";}?> <?php $stutus= get_post_meta($id,'_pvg_video_muted', true); if ($stutus=="on"){echo"muted ";} ?> class="player<?php echo $id; ?>" controls poster="<?php echo $videoitems['_pvg_label']['url'];?>">
						  <source src="<?php echo $videoitems['_pvg_video']['url']; ?>" type="video/mp4">
						Your browser does not support the video tag.
						</video>
					</div>
					<div style="padding: 5px;border-width: 0 1px 1px 1px;border-style: solid;border-color: #eee;background-color: #fff;">
						<div class="post-grid-head">
							<h3 style="font-size: 15px; margin-bottom: 4px;margin-top: 0;">
								<?php echo $videoitems['_pvg_title'];?>  
							</h3>
						</div>

					</div>
				</div>
			</div><?php }else{ ?>			
				<div  style="margin:5px;float:left; overflow:hidden; width:<?php echo get_post_meta($id,'_pvg_column', true);?>">
					<div style="margin-bottom: 25px;padding: 0 12px;">
						<div style="position: relative;margin-left: -12px;margin-right: -12px;">
							<video  <?php $status1= get_post_meta($id,'_pvg_video_repeat', true); if ($status1=="loop"){echo "loop";}?> <?php $stutus= get_post_meta($id,'_pvg_video_muted', true); if ($stutus=="on"){echo"muted ";} ?> class="player<?php echo $id; ?>" controls poster="<?php echo $videoitems['_pvg_label']['url'];?>">
							<source src="<?php echo $videoitems['_pvg_video']['url']; ?>" type="video/mp4">
							Your browser does not support the video tag.
							</video>
							<div style="position: absolute;top: 15px;background: rgba(0, 0, 0, 0.7);padding: 5px 10px;left: 15px;font-size: 13px;color: #fff;">
								<?php echo $videoitems['_pvg_title'];?> 
							</div>
						</div>

					</div>
				</div>				
			<?php } ?>
			<?php }}else{ echo'<h1>Ooops ! You forget to add videos in that gallery</h1>';}  ?>
		</div>					
	</div>

<!-- frontend js -->
<script type="text/javascript">
const players<?php echo $id;?> = Plyr.setup('.player<?php echo $id;?>',{	

controls:['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings',  'fullscreen','download',],
});
<?php $autop=get_post_meta($id,'_pvg_auto_pause', true); if($autop=='on') {?>
	players<?php echo $id;?>.forEach(function(instance,index) {
            instance.on('play',function(){
                players<?php echo $id;?>.forEach(function(instance1,index1){
                    if(instance != instance1){
                        instance1.pause();
                    }
                });
            });
}); <?php } ?>
		
</script>
<?php $output=ob_get_clean(); return $output; ?>
<?php
}
add_shortcode('vgallery','pvg_shortcode_func');

add_action('edit_form_after_title','pvg_shortcode_area');
function pvg_shortcode_area(){
global $post;   
if($post->post_type=='pgallery'){
?>  
<div>
    <label style="cursor: pointer;font-size: 13px; font-style: italic;" for="pvg_shortcode">Copy this shortcode and paste it into your post, page, or text widget content:</label>
    <span style="display: block; margin: 5px 0; background:#1e8cbe; ">
        <input type="text" id="pvg_shortcode" style="font-size: 12px; border: none; box-shadow: none;padding: 4px 8px; width:100%; background:transparent; color:white;"  onfocus="this.select();" readonly="readonly"  value="[vgallery id=<?php echo $post->ID; ?>]" /> 
        
    </span>
</div>
 <?php   
}}