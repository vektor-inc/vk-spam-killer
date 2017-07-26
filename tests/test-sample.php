<?php
/**
 * Class SpamKillerTest
 *
 * @package Vk_Spam_Killer
 */

/**
 * Sample test case.


$ vagrant ssh
$ cd $(wp plugin path --dir vk-spam-killer)
$ bash bin/install-wp-tests.sh wordpress_test root 'wordpress' localhost latest

 */

class SpamKillerTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_spam_check() {
		// Replace this with some actual testing code.
		global $post;

		// チェックする文字列と想定する結果
		$test_contents = array(
			'No Japanese'						=> true,
			'日本語と英語が入っています English'	=> false,
			'abcd1234' 							=> true,
			'http://aaa.com' 					=> true,
			);

		// チェック文字列をループしながら確認
		foreach ( $test_contents as $key => $value ) {
			$post_array = array( 
				 'post_content' => $key,
			);
			// 投稿を作成する
			$post_id = wp_insert_post( $post_array );
			// 作成した投稿の情報を取得する
			$post = get_post( $post_id );
			// $postデータからスパムかどうかを判定する
			$is_spam = vksk_is_spam( $post );
			$this->assertEquals( $value, $is_spam );
		}
	}
}
