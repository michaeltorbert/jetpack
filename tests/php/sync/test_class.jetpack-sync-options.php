<?php

/**
 * Testing CRUD on Options
 */
class WP_Test_Jetpack_New_Sync_Options extends WP_Test_Jetpack_New_Sync_Base {
	protected $post;

	public function setUp() {
		parent::setUp();

		$this->client->set_options_whitelist( array( 'test_option' ) );

		add_option( 'test_option', 'foo' );

		$this->client->do_sync();
	}

	public function test_added_option_is_synced() {
		$synced_option_value = $this->server_replica_storage->get_option( 'test_option' );
		$this->assertEquals( 'foo', $synced_option_value );
	}

	public function test_updated_option_is_synced() {
		update_option( 'test_option', 'bar' );
		$this->client->do_sync();
		$synced_option_value = $this->server_replica_storage->get_option( 'test_option' );
		$this->assertEquals( 'bar', $synced_option_value );
	}

	public function test_deleted_option_is_synced() {
		delete_option( 'test_option' );
		$this->client->do_sync();
		$synced_option_value = $this->server_replica_storage->get_option( 'test_option' );
		$this->assertEquals( false, $synced_option_value );
	}

	public function test_don_t_sync_option_if_not_on_whitelist() {
		add_option( 'don_t_sync_test_option', 'foo' );
		$this->client->do_sync();
		$synced_option_value = $this->server_replica_storage->get_option( 'don_t_sync_test_option' );
		$this->assertEquals( false, $synced_option_value );
	}
	
	public function test_sync_options_that_use_filter() {
		add_filter( 'jetpack_options_whitelist', array( $this, 'add_jetpack_options_whitelist_filter' ) );
		$this->client->update_options_whitelist();
		update_option( 'foo_option_bar', '123' );
		$this->client->do_sync();

		$this->assertEquals( '123', $this->server_replica_storage->get_option( 'foo_option_bar' ) );
	}

	public function test_sync_initalize_Jetpack_Sync_Action_on_init() {
		// prioroty should be set to 11 so that Plugins by default (10) initialize the whitelist_filter before.
		$this->assertEquals( 11, has_action('init', array( 'Jetpack_Sync_Actions', 'init' ) ) );
	}

	public function test_sync_default_options() {
		$this->setSyncClientDefaults();
		// check that these values exists in the whitelist options
		$options = array(
			'stylesheet' => 'test',
			'blogname' => 'test',
			'home' => 'http://test.com',
			'siteurl' => 'http://test.com',
			'blogdescription' => 'banana',
			'blog_charset' => 'stuffs',
			'permalink_structure' => 'stuffs',
			'category_base' => 'orange',
			'tag_base' => 'apple',
			'comment_moderation' => true,
			'default_comment_status' => 'kiwi',
			'thread_comments' => 0,
			'thread_comments_depth' => 2,
			'social_notifications_like' => 'test',
			'page_on_front' => false,
			'rss_use_excerpt' => false,
			'subscription_options' => 'pineapple',
			'stb_enabled' => true,
			'stc_enabled' => false,
			'comment_registration' => 'pineapple',
			'show_avatars' => 'pineapple',
			'avatar_default'=> 'pineapple',
			'avatar_rating' => 'pineapple',
			'highlander_comment_form_prompt'=> 'pineapple',
			'jetpack_comment_form_color_scheme'=> 'pineapple',
			'stats_options'=> 'pineapple',
			'gmt_offset'=> 1,
			'timezone_string'=> 'America/Anchorage',
			'jetpack_sync_non_public_post_stati'=> 'pineapple',
			'jetpack_options' => array( 'food' => 'pineapple' ),
			'site_icon' => '1',
			'jetpack_site_icon_url' => 'http://test.com',
			'default_post_format'=> 'pineapple',
			'default_category'=> 0,
			'large_size_w'=> 1000,
			'large_size_h'=> 2000,
			'thumbnail_size_w'=> 1000,
			'thumbnail_size_h'=> 9999,
			'medium_size_w'=> 200,
			'medium_size_h'=> 200,
			'thumbnail_crop'=> 'pineapple',
			'image_default_link_type'=> 'pineapple',
			'site_logo'=> 1,
			'sharing-options'=> 'pineapple',
			'sharing-services'=> 'pineapple',
			'post_count'=> 'pineapple',
			'default_ping_status'=> 'pineapple',
			'sticky_posts'=> 'pineapple',
			'blog_public'=> 0,
			'default_pingback_flag'=> 'pineapple',
			'require_name_email'=> 'pineapple',
			'close_comments_for_old_posts'=> 'pineapple',
			'close_comments_days_old'=> 99,
			'page_comments'=> 'pineapple',
			'comments_per_page'=> 99,
			'default_comments_page'=> 'pineapple',
			'comment_order'=> 'pineapple',
			'comments_notify'=> 'pineapple',
			'moderation_notify'=> 'pineapple',
			'social_notifications_reblog'=> 'pineapple',
			'social_notifications_subscribe'=> 'pineapple',
			'comment_whitelist'=> 'pineapple',
			'comment_max_links'=> 99,
			'moderation_keys'=> 'pineapple',
			'blacklist_keys'=> 'pineapple',
			'lang_id'=> 'pineapple',
			'wga'=> 'pineapple',
			'disabled_likes'=> 'pineapple',
			'disabled_reblogs'=> 'pineapple',
			'jetpack_comment_likes_enabled'=> 'pineapple',
			'twitter_via'=> 'pineapple',
			'twitter-cards-site-tag'=> 'pineapple',
			'wpcom_publish_posts_with_markdown'=> 'pineapple',
			'wpcom_publish_comments_with_markdown'=> 'pineapple',
			'jetpack_activated'=> 'pineapple',
			'jetpack_active_modules'=> 'pineapple',
			'jetpack_available_modules'=> 'pineapple',
			'jetpack_autoupdate_plugins'=> 'pineapple',
			'jetpack_autoupdate_themes'=> 'pineapple',
			'jetpack_autoupdate_core'=> 'pineapple',
			'carousel_background_color'=> 'pineapple',
			'carousel_display_exif'=> 'pineapple',
			'jetpack_portfolio'=> 'pineapple',
			'jetpack_portfolio_posts_per_page'=> 'pineapple',
			'jetpack_testimonial'=> 'pineapple',
			'jetpack_testimonial_posts_per_page'=> 'pineapple',
			'tiled_galleries'=> 'pineapple',
			'gravatar_disable_hovercards'=> 'pineapple',
			'infinite_scroll'=> 'pineapple',
			'infinite_scroll_google_analytics'=> 'pineapple',
			'wp_mobile_excerpt'=> 'pineapple',
			'wp_mobile_featured_images'=> 'pineapple',
			'wp_mobile_app_promos'=> 'pineapple',
			'monitor_receive_notifications'=> 'pineapple',
			'post_by_email_address'=> 'pineapple',
			'jetpack_protect_key'=> 'pineapple',
			'jetpack_protect_global_whitelist'=> 'pineapple',
			'sharing_services'=> 'pineapple',
			'jetpack_sso_require_two_step'=> 'pineapple',
			'jetpack_relatedposts'=> 'pineapple',
			'verification_services_codes'=> 'pineapple',
		);
		
		$theme_mod_key = 'theme_mods_' . get_option( 'stylesheet' );
		$options[ $theme_mod_key ] = 'pineapple';

		$whitelist = $this->client->get_options_whitelist();

		// update all the opyions.
		foreach( $options as $option_name => $value) {
			update_option( $option_name, $value );
		}

		$this->client->do_sync();

		foreach( $options as $option_name => $value ) {
			$this->assertOptionIsSynced( $option_name, $value );
		}
		$option_keys = array_keys( $options );
		$whitelist_and_option_keys_difference = array_diff( $whitelist, $option_keys );
		// Are we testing all the options
		$unique_whitelist = array_unique( $whitelist );
		
		$this->assertEquals( count( $unique_whitelist ), count( $whitelist ), 'The duplicate keys are: '. print_r( array_diff_key( $whitelist , array_unique( $whitelist ) ) ,1 ) );
		$this->assertTrue( empty( $whitelist_and_option_keys_difference ), 'Some whitelisted options don\'t have a test: ' . print_r( $whitelist_and_option_keys_difference, 1 )  );
	}

	function assertOptionIsSynced( $option_name, $value ) {
		$this->assertEquals( $value, $this->server_replica_storage->get_option( $option_name ), 'Option '. $option_name .' did\'t have the extected value of ' . json_encode( $value ) );
	}

	function add_fiter_on_init() {
		add_filter( 'jetpack_options_whitelist', array( $this, 'add_jetpack_options_whitelist_filter' ) );
	}

	public function add_jetpack_options_whitelist_filter( $options ) {
		$options[] = 'foo_option_bar';
		return $options;
	}
}