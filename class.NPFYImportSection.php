<?php

/**
 * Transfer posts from rss different sections.
 */
class NPFYImportSection {
	/**
	 * An array of sections to import with the appropriate parameters.
	 */
	public $arr_post_type;
	/**
	 * An array of sorted data that is ready to be added to the site.
	 */
	public $new_posts;

	public function __construct() {
		$this->arr_post_type['entertainment']['rss'] = 'https://www.yahoo.com/entertainment/rss/';
		$this->arr_post_type['news']['rss']          = 'https://finance.yahoo.com/rss/';

		add_action( 'init', array( $this, 'create_post_type' ) );

		add_filter( 'cron_schedules', array( $this, 'cron_add_weekly' ) );

		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			add_action( 'adding_new_entries_from_rss', array( $this, 'get_data_with_rss' ) );
		}
	}

	/**
	 * Creating a new time for the cron.
	 *
	 * @param $schedules
	 *
	 * @return mixed
	 */
	public function cron_add_weekly( $schedules ) {
		$schedules['two_hours'] = array(
			'interval' => 7200,
			'display'  => __( 'Two hours' )
		);

		return $schedules;
	}

	/**
	 * Creation of custom post types for transfer of records in them.
	 */
	public function create_post_type() {
		$this->arr_post_type['entertainment']['labels'] = array(
			'name'                  => __( 'Entertainments', 'Post type general name', NPFY_TEXT_DOMAIN ),
			'singular_name'         => _x( 'Item', 'Post type singular name', NPFY_TEXT_DOMAIN ),
			'menu_name'             => _x( 'Entertainments', 'Admin Menu text', NPFY_TEXT_DOMAIN ),
			'name_admin_bar'        => _x( 'Item', 'Add New on Toolbar', NPFY_TEXT_DOMAIN ),
			'add_new'               => __( 'Add New', NPFY_TEXT_DOMAIN ),
			'add_new_item'          => __( 'Add New Item', NPFY_TEXT_DOMAIN ),
			'new_item'              => __( 'New Item', NPFY_TEXT_DOMAIN ),
			'edit_item'             => __( 'Edit item', NPFY_TEXT_DOMAIN ),
			'view_item'             => __( 'View item', NPFY_TEXT_DOMAIN ),
			'all_items'             => __( 'All items', NPFY_TEXT_DOMAIN ),
			'search_items'          => __( 'Search items', NPFY_TEXT_DOMAIN ),
			'featured_image'        => _x( 'Featured image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', NPFY_TEXT_DOMAIN ),
			'set_featured_image'    => _x( 'Set featured image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', NPFY_TEXT_DOMAIN ),
			'remove_featured_image' => _x( 'Remove featured image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', NPFY_TEXT_DOMAIN ),
			'use_featured_image'    => _x( 'Use featured image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', NPFY_TEXT_DOMAIN ),
			'archives'              => _x( 'Archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', NPFY_TEXT_DOMAIN ),
			'uploaded_to_this_item' => _x( 'Uploaded to this item', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', NPFY_TEXT_DOMAIN ),
			'filter_items_list'     => _x( 'Filter items list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', NPFY_TEXT_DOMAIN ),
			'items_list_navigation' => _x( 'Items list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', NPFY_TEXT_DOMAIN ),
			'items_list'            => _x( 'Items list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', NPFY_TEXT_DOMAIN ),
		);
		$this->arr_post_type['news']['labels']          = array(
			'name'                  => __( 'News', 'Post type general name', NPFY_TEXT_DOMAIN ),
			'singular_name'         => _x( 'Item', 'Post type singular name', NPFY_TEXT_DOMAIN ),
			'menu_name'             => _x( 'News', 'Admin Menu text', NPFY_TEXT_DOMAIN ),
			'name_admin_bar'        => _x( 'Item', 'Add New on Toolbar', NPFY_TEXT_DOMAIN ),
			'add_new'               => __( 'Add New', NPFY_TEXT_DOMAIN ),
			'add_new_item'          => __( 'Add New Item', NPFY_TEXT_DOMAIN ),
			'new_item'              => __( 'New Item', NPFY_TEXT_DOMAIN ),
			'edit_item'             => __( 'Edit item', NPFY_TEXT_DOMAIN ),
			'view_item'             => __( 'View item', NPFY_TEXT_DOMAIN ),
			'all_items'             => __( 'All items', NPFY_TEXT_DOMAIN ),
			'search_items'          => __( 'Search items', NPFY_TEXT_DOMAIN ),
			'featured_image'        => _x( 'Featured image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', NPFY_TEXT_DOMAIN ),
			'set_featured_image'    => _x( 'Set featured image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', NPFY_TEXT_DOMAIN ),
			'remove_featured_image' => _x( 'Remove featured image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', NPFY_TEXT_DOMAIN ),
			'use_featured_image'    => _x( 'Use featured image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', NPFY_TEXT_DOMAIN ),
			'archives'              => _x( 'Archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', NPFY_TEXT_DOMAIN ),
			'uploaded_to_this_item' => _x( 'Uploaded to this item', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', NPFY_TEXT_DOMAIN ),
			'filter_items_list'     => _x( 'Filter items list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', NPFY_TEXT_DOMAIN ),
			'items_list_navigation' => _x( 'Items list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', NPFY_TEXT_DOMAIN ),
			'items_list'            => _x( 'Items list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', NPFY_TEXT_DOMAIN ),
		);

		foreach ( $this->arr_post_type as $post_type => $param ) {
			$args = array(
				'labels'             => $param['labels'],
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
				'menu_icon'          => 'dashicons-format-aside'
			);
			register_post_type( $post_type, $args );
		}
	}

	/**
	 * Adding in the array of section parameters the date of the last post of the corresponding section existing on the site.
	 */
	public function get_date_of_last_post() {
		foreach ( $this->arr_post_type as $post_type => $param ) {
			$args                                                   = array(
				'numberposts' => 1,
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'post_type'   => $post_type,
				'post_status' => 'publish',
			);
			$result                                                 = wp_get_recent_posts( $args );
			$this->arr_post_type[ $post_type ]['date_of_last_post'] = ! empty( $result[0] ) ? strtotime( $result[0]['post_date_gmt'] ) : 0;
		}
	}

	/**
	 * Retrieving rss data.
	 */
	public function get_data_with_rss() {
		$this->get_date_of_last_post();

		foreach ( $this->arr_post_type as $post_type => $param ) {
			if ( empty( $this->new_posts[ $post_type ] ) ) {
				$this->new_posts[ $post_type ] = array();
			}

			if ( empty( $param['rss'] ) ) {
				continue;
			}
			$content_rss = file_get_contents( $param['rss'] );
			$items_rss   = new SimpleXmlElement( $content_rss );
			if ( ! empty( $items_rss->channel->item ) ) {
				foreach ( $items_rss->channel->item as $item_rss ) {
					if ( strtotime($item_rss->pubDate) > $param['date_of_last_post'] ) {
						array_unshift( $this->new_posts[ $post_type ], $item_rss );
					}
				}
			}
		}

		$this->create_new_posts();
	}

	/**
	 * Adding posts to your site with image copying.
	 */
	public function create_new_posts() {
		if ( empty( $this->new_posts ) ) {
			return false;
		}

		foreach ( $this->new_posts as $post_type => $items ) {
			foreach ( $items as $item ) {
				if ( empty( $item->title ) ) {
					continue;
				}
				$images   = array();
				$desc_str = ! empty( $item->description ) ? $item->description : '';

				date_default_timezone_set('UTC');
				$pubDate_g = gmdate("Y-m-d H:i:s", strtotime($item->pubDate));
				date_default_timezone_set('Europe/Kiev');
				$pubDate = date("Y-m-d H:i:s", strtotime($item->pubDate));

				$post_data = array(
					'post_title'   => $item->title,
					'post_content' => $desc_str,
					'post_status'  => 'publish',
					'post_date'    => $pubDate,
					'post_date_gmt'=> $pubDate_g,
					'post_author'  => 1,
					'post_type'    => $post_type
				);
				$id_post   = wp_insert_post( $post_data );

				if ( ! empty( $id_post ) ) {
					$wp_upload_dir = wp_upload_dir();
					preg_match_all( '/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $desc_str, $images, PREG_SET_ORDER );

					if ( ! empty( $images ) ) {
						foreach ( $images as $image ) {
							$url_image_rss = $image[1];
							$name_file     = basename( $url_image_rss );
							$ext           = pathinfo( $url_image_rss, PATHINFO_EXTENSION );

							if ( ! empty( $ext ) ) {
								$filetype = wp_check_filetype( $name_file, null );
							} else {
								$filetype = wp_check_filetype( $name_file . '.jpg', null );
							}

							if ( ! empty( $filetype['type'] ) ) {
								$content_image_rss = file_get_contents( $url_image_rss );
								$save_path         = $wp_upload_dir['path'] . '/' . $name_file;
								file_put_contents( $save_path, $content_image_rss );

								$attachment = array(
									'guid'           => $wp_upload_dir['url'] . '/' . $name_file,
									'post_mime_type' => $filetype['type'],
									'post_title'     => preg_replace( '/\.[^.]+$/', '', $name_file ),
									'post_content'   => '',
									'post_status'    => 'inherit'
								);
								$attach_id  = wp_insert_attachment( $attachment, $save_path, $id_post );

								if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
									require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
									require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
									require_once( ABSPATH . "wp-admin" . '/includes/media.php' );
								}

								update_post_meta( $id_post, '_thumbnail_id', $attach_id );
							}
						}
					}
				}

			}
		}
	}
}
