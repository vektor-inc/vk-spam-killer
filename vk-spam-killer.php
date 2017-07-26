<?php
/**
 * Plugin Name:     VK Spam Killer
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          Kurudrive@Vektor,Inc.
 * Author URI:      YOUR SITE HERE
 * Text Domain:     vk-spam-killer
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Vk_Spam_Killer
 */

add_action( 'save_post', 'vksk_do_action', 10, 2 );
function vksk_do_action(){
	global $post;
	if ( vksk_is_spam( $post ) ){
		vksk_post_kill();
	}
}

function vksk_post_kill(){
	global $post;

	foreach ($post as $key => $value) {
		$update_post[$key] = $value;
		if ( $key == 'post_title' ) {
			$update_post[$key] = '[SPAM] '.$value;
		}
	}
	
	$update_post['post_status'] = 'trash';

	// wp_update_post の前でremoveしないと save_post で無限ループになる
	remove_action( 'save_post', 'vksk_do_action', 10, 2 );

	$wp_error = wp_update_post( $update_post, true );

}

function vksk_is_spam( $post ){

	if ( ! isset( $post->post_content ) )  return;
	$content = $post->post_content;

	if ( preg_match("/[ぁ-んァ-ン一-龠]/", $content ) ) {
		return false;
	}else{
		return true;
	}
}
