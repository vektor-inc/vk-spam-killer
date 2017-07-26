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
	if ( !$post ) return;

	$post_content = $post->post_content;

	if ( vksk_is_spam( $post_content ) ){
		vksk_kill_post();
	}
}

function vksk_kill_post(){
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

function vksk_is_spam( $post_content ){
	// 日本語が含まれていない or 空の場合
	if ( !preg_match("/[ぁ-んァ-ン一-龠]/", $post_content ) && $post_content ) {
		return true;
	}else{
		return false;
	}
}

/*-------------------------------------------*/
/*  bbpress reply spam
/*-------------------------------------------*/
add_filter( 'bbp_new_reply_pre_insert', 'vksk_kill_bbp_reply' );
function vksk_kill_bbp_reply( $reply_data ){
	if ( vksk_is_spam( $reply_data['post_content'] ) ){
		$reply_data['post_status'] = 'trash';
	}
	return $reply_data;
}
