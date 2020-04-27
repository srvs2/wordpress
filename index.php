<?php
/**
* Plugin Name: WP Social Post Share
* Plugin URI: http://wordpress.org/plugins/
* Description: Share your wordpress post on social media like facebook,twitter,pinterest,etc.
* Author: Abhishek Srivastva
* Author URI: https://www.ipraxa.com/
* Version: 1.0
**/?>
<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
function sps_save_sheet() 
{
    wp_register_style("style", plugin_dir_url(__FILE__) . "/assets/style.css");
    wp_enqueue_style("style");

}
add_action("wp_enqueue_scripts", "sps_save_sheet");
function sps_menu_section() { 
 add_menu_page('Social Post Share','Social Post Share','manage_options', 'social_share', 'SPS_menu_settings','dashicons-share');

}
add_action('admin_menu','sps_menu_section');
function sps_menu_settings(){?>
  <div class="media">
    <h2>Social Options Setting Page</h2>
    <form method="post" action="<?php echo admin_url('options.php');?>">
        <?php
         settings_fields("social_config");
         do_settings_sections("social-share");
       ?>
        <input type="submit" value="Save Changes"  class="button button-primary" />
      </form>
    </div>
<?php }
function sps_add_save_settings(){
       add_settings_section("social_config", "", null, "social-share");
       add_settings_field("facebook_share", "Facebook", "facebook_cbox", "social-share", "social_config");
       add_settings_field("twitter_share",  "Twitter", "twitter_cbox", "social-share", "social_config");
       add_settings_field("pinterest_share","Pinterest", "pinterest_cbox", "social-share", "social_config");
       add_settings_field("linkedin_share", "Linkedin", "linkedin_cbox", "social-share", "social_config");

       register_setting("social_config", "facebook_share");
       register_setting("social_config", "twitter_share");
       register_setting("social_config", "pinterest_share");
       register_setting("social_config", "linkedin_share");

}
  function facebook_cbox(){ ?>
   <input type="checkbox" name="facebook_share" value="1"<?php checked(1, get_option('facebook_share'), true); ?>/>
<?php 
  }
  function twitter_cbox(){ ?>
   <input type="checkbox" name="twitter_share"  value="1"<?php checked(1, get_option('twitter_share'), true); ?> />
<?php 
  }
 function pinterest_cbox(){ ?>
   <input type="checkbox" name="pinterest_share" value="1"<?php checked(1, get_option('pinterest_share'), true); ?> />
<?php 
  }
  function linkedin_cbox(){ ?>
   <input type="checkbox" name="linkedin_share" value="1"<?php checked(1, get_option('linkedin_share'), true); ?> />
<?php 
  }
  add_action("admin_init", "sps_add_save_settings");
function sps_add_opengraph_doctype( $output ) {
        return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
    }
add_filter('language_attributes', 'sps_add_opengraph_doctype'); 
function sps_insert_fb_in_head() {
    global $post;
    if ( !is_singular())
        return;
        echo '<meta property="og:title" content="' . get_the_title($post->ID) . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink($post->ID) . '"/>';
        echo '<meta property="og:site_name" content="'.get_the_content($post->ID).'"/>';
    if(!has_post_thumbnail( $post->ID )) {
       
      }
    else{
    $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
        echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
    }
}
add_action( 'wp_head', 'sps_insert_fb_in_head',11);
 function sps_add_icons($data){
    $html ='<div class="social_icon">';
    global $post;
    $url = get_permalink($post->ID);
    $url = esc_url($url);
    $title=get_the_title($post->ID);
    $thumbnail=get_the_post_thumbnail_url($post->ID);
    if(get_option('facebook_share') == 1)
    { 
      $facebook_icon=plugin_dir_url( __FILE__ ) . "image/facebook.png";
      $html=$html.'<div class="f_icon"><a class="f_share" target="_blank" href="http://www.facebook.com/sharer.php?u='.$url.'&p='.$title.'"><img src="'.$facebook_icon.'" class="icon"></a></div>';
    }
    if(get_option('twitter_share') == 1)
    {
    $twitter_icon=plugin_dir_url( __FILE__ ) . "image/twitter.png";
    $html=$html.'<div class="t_icon"><a class="t_share" href="http://twitter.com/share?text='.$title.'" target="_blank"><img src="'.$twitter_icon.'"class="icon"></a></div>';
    }
    if(get_option('pinterest_share') == 1)
    {
    $pinterest_icon=plugin_dir_url( __FILE__ ) . "image/pinterest.png"; 
    $html=$html.'<div class="p_icon"><a target="_blank" class="p_share" href="http://pinterest.com/pin/create/button/?&url='.$url.'&description='.$title.'&media='.$thumbnail.'"><img src="'.$pinterest_icon.'"class="icon"></a></div>';
    }
    if(get_option('linkedin_share')== 1){
    $linkedin_icon=plugin_dir_url( __FILE__ ) . "image/linkedin.png";
    $html=$html.'<div class="l_icon"><a target="_blank" class="l_share"  href="https://www.linkedin.com/shareArticle?url='.$url.'&title='.$title.'&media='.$thumbnail.'"><img src="'.$linkedin_icon.'"class="icon"></a></div>';

    }
    $html=$html.'</div>';
    return $data=$data . $html;
  }
  add_filter("the_content", "sps_add_icons");
