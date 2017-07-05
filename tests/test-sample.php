<?php
/**
 * Class SpamKillerTest
 *
 * @package Vk_Spam_Killer
 */

/**
 * Sample test case.
 */
class SpamKillerTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_spam_check() {
		// Replace this with some actual testing code.
		global $post;

		// チェックする文字列と想定する結果
		$test_content = array(
			'No Japanese'						=> true,
			'日本語と英語が入っています English'		=> false,
			'abcd1234' 							=> true,
			'http://aaa.com' 					=> true,
			);

		foreach ($test_content as $key => $value) {
			$post_array = array( 
				 'post_content' => $key,
			);
			$post_id = wp_insert_post( $post_array );
			$post = get_post($post_id);
			$is_spam = vksk_is_spam( $post );
			$this->assertEquals( $value, $is_spam );
		}
	}
}
