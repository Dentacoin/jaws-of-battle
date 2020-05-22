<?php
/**
 * @package All-in-One-SEO-Pack
 */
/**
 * The Video Sitemap class.
 */
if ( !class_exists( 'All_in_One_SEO_Pack_Sitemap' ) ) {
	include_once( "aioseop_sitemap.php" );
}
if ( class_exists( 'All_in_One_SEO_Pack_Sitemap' ) && ( !class_exists( 'All_in_One_SEO_Pack_Video_Sitemap' ) ) ) {
	class All_in_One_SEO_Pack_Video_Sitemap extends All_in_One_SEO_Pack_Sitemap {
		function All_in_One_SEO_Pack_Video_Sitemap( ) {
			$this->name = __( 'Video Sitemap', 'all_in_one_seo_pack' );	// Human-readable name of the plugin
			$this->prefix = 'aiosp_video_sitemap_';						// option prefix
			$this->file = __FILE__;									// the current file
			parent::__construct();
			$this->default_options['filename']['default'] = 'video-sitemap';
			$this->default_options['videos_only'] = Array( 'name' => __( 'Show Only Posts With Videos', 'all_in_one_seo_pack' ), 'default' => 'On' );
			$this->default_options['video_scan'] = Array( 'name' => __( 'Scan Posts For Videos', 'all_in_one_seo_pack' ), 'type' => 'custom', 'save' => false, 'nowrap' => false );
			$this->default_options['restrict_access'] = Array( 'name' => __( 'Restrict Access to Video Sitemap', 'all_in_one_seo_pack' ),
															   'condshow'  => Array( "{$this->prefix}rewrite" => 'on' ) );
			$this->layout['default']['options'][] = 'videos_only';
			$this->layout['default']['options'][] = 'restrict_access';
			$this->layout['status']['options'][] = 'video_scan';
			
			$this->layout['status']['help_link'] = 'http://semperplugins.com/documentation/video-sitemap/';
			
			$this->help_text['video_scan']		= __( 'Press the Scan button to scan your posts for videos! Do this if video content from a post or posts is not showing up in your sitemap.', 'all_in_one_seo_pack' );
			$this->help_text['videos_only']		= __( 'If checked, only posts that have videos in them will be displayed on the sitemap.', 'all_in_one_seo_pack' );
			$this->help_text['restrict_access'] = __( 'Enable this option to only allow access to your sitemap by site administrators and major search engines.', 'all_in_one_seo_pack' );
			
			$this->help_anchors['video_scan']		= '#scan-posts-for-videos';
			$this->help_anchors['videos_only']		= 'http://semperplugins.com/documentation/video-sitemap/#show-only-posts-with-videos';
			$this->help_anchors['restrict_access']	= 'http://semperplugins.com/documentation/video-sitemap/#restrict-access-to-video-sitemap';
			
			$this->add_help_text_links();
			
			add_filter( $this->prefix . 'prio_item_filter', Array( $this, 'do_post_video'), 10, 3 );
			add_filter( 'embed_oembed_html', Array( $this, 'oembed_discovery' ), 10, 4 );
			add_filter( 'save_post', Array( $this, 'oembed_cache' ) );
			add_filter( $this->prefix . 'xml_namespace', Array( $this, 'add_namespace' ) );
		}
		function add_namespace( $ns ) {
			$ns['xmlns:video'] = 'http://www.google.com/schemas/sitemap-video/1.1';
			return $ns;
		}
		/** Initialize options, after constructor **/
		function load_sitemap_options() {
			parent::load_sitemap_options();
			if ( $this->option_isset( 'videos_only' ) ) {
				add_filter( $this->prefix . 'post_query', Array( $this, 'fetch_videos_only' ) );
				add_filter( $this->prefix . 'post_counts', Array( $this, 'count_videos_only' ), 10, 2 );
			}
		}
		/** Custom settings **/
		function display_custom_options( $buf, $args ) {
			if ( $args['name'] == "{$this->prefix}video_scan" ) {
				ob_start();
				$this->scan_videos();
				return $buf . ob_get_clean();
			} else {
				return parent::display_custom_options( $buf, $args );
			}
		}
		
		function do_rewrite_sitemap( $sitemap_type, $page = 0 ) {
			if ( $this->option_isset( 'restrict_access' ) && !$this->is_admin() && !$this->is_good_bot() ) {
				header( "Content-Type: text/plain", true );
				echo "You do not have access to this page; try logging in as an administrator.";
				die(-1);
			} else {
				parent::do_rewrite_sitemap( $sitemap_type, $page );									
			}
		}
		
		function scan_videos() {
			set_time_limit(0);
			//$post_ids = get_posts( Array( 'numberposts' => -1, 'fields' => 'ids', 'post_type' => 'any', 'no_found_rows' => true ) );
			if ( $this->option_isset( 'videos_only' ) )
				remove_filter( $this->prefix . 'post_counts', Array( $this, 'count_videos_only' ), 10, 2 );				
			$max = $this->get_total_post_count( Array( 'post_type' => $this->options["{$this->prefix}posttypes"], 'post_status' => 'publish' ) );
			if ( $this->option_isset( 'videos_only' ) )
				add_filter( $this->prefix . 'post_counts', Array( $this, 'count_videos_only' ), 10, 2 );				
			if ( $max > 0 ) {
			?><div id="aiosp_sitemap_oembed_scan"><input name=aiosp_sitemap_scan id=aiosp_sitemap_scan type="submit" value="<?php
				echo __( 'Scan', 'all_in_one_seo_pack' ); ?>" class="button-primary"><div id="aiosp_sitemap_scan" style="display:inline-block;margin-left:10px;""></div></div><progress id=p style="width:100%;" value=0 max=<?php echo (int)$max; 
			?>></progress><script>
			jQuery(document).ready(function() {
				var min = 5;
				var cur = 5;
				var max = 25000;
				var count = <?php echo (int)$max; ?>;
				var scale = 2;
				jQuery("div#aiosp_sitemap_oembed_scan").delegate("input[name='aiosp_sitemap_scan']", "click", function(e) {
					e.preventDefault();
					var c = 0;
					var i = 0;
					var start;
					var end;
					var succ = function() {
						var diff = new Date().getTime() - start;
						if ( diff > 2 ) {
							if ( diff < 2500 ) {
								scale *= 2;
								if ( scale > 4 ) scale = 4;
							} else if ( diff < 5000 ) {
								if ( scale > 3 ) scale = 3;
							} else if ( scale > 2 ) scale = 2;
							if ( diff < 15000 )
								cur *= scale;
							else {
								cur /= scale;
								scale = 1 + ( (scale - 1) / 2 );
								if ( scale < 1.1 ) scale = 1.1;
							}
							cur = Math.round( cur );
							if ( cur < min ) cur = min;
							if ( cur > max ) cur = max;
							// console.log('milliseconds passed', diff + ' ' + cur);
						}
						c = i;
						jQuery("#p").val(c);
						if ( c >= count ) return;
						var s = "";
						i = c;
						s += i;
						i = c + cur;
						if ( c + cur > count ) i = count;
						s += ',';
						s += i;
						start = new Date().getTime();
						aioseop_handle_post_url('aioseop_ajax_update_oembed', 'sitemap_scan', s, succ );
					}
					start = new Date().getTime();
					succ();
					return false;
				});
			});
			</script>
			<?php
			}
		}
		function do_post_video( $pr_info, $post, $args ) {
			if ( !empty( $post ) ) {
				$post_id = $post->ID;
				$opts = get_post_meta( $post_id, '_aioseop_oembed_info', true );
				if ( !empty( $opts ) ) {
					$pr_info["video:video"] = Array();
					foreach( $opts as $o )
						$pr_info["video:video"][] = $this->parse_video_opts( $o );
					return $pr_info;
				}
			}
			if ( $this->option_isset( 'videos_only' ) ) return Array();
			return $pr_info;
		}
		function fetch_videos_only( $args ) {
			$args['meta_query'] = Array(
				Array( 'key' => '_aioseop_oembed_info', 'compare' => 'EXISTS' )
			);
			return $args;
		}
		function count_videos_only( $counts, $args ) {
			if ( !empty( $counts ) ) {				
				$status = 'inherit';
				if ( !empty( $args['post_status'] ) ) $status = $args['post_status'];
				if ( !is_array( $counts ) ) {
					$counts = Array( $args['post_type'] => $counts );
				}
				foreach( $counts as $post_type => $count ) {
					$args = Array( 'numberposts' => -1, 'post_status' => 'publish', 'fields' => 'ids', 'post_type' => $post_type, 'status' => $status );
					if ( $post_type == 'attachment' )
						$args['status'] = 'inherit';
					$args = $this->fetch_videos_only( $args );
					$q = new WP_Query( $args );
					$counts[$post_type] = $q->found_posts;
				}
			}
			return $counts;
		}
		function parse_video_opts( $data ) {
			$opts = Array();
			$fields = Array(
				'thumbnail_url' => 'video:thumbnail_loc',
				'title' => 'video:title',
				'description' => 'video:description',
				'duration' => 'video:duration',
				'author_name' => 'video:uploader',
				'html' => 'video:player_loc'
			);
			$link_found = 0;
			if ( !empty( $data ) ) {
				$data = (Array)$data;
				if ( !empty( $data['html'] ) ) {
					$dom_document = new DOMDocument();
				   @$dom_document->loadHTML( $data['html'] );
				   $dom_xpath = new DOMXpath( $dom_document );
				   $iframes = $dom_xpath->query( "//iframe" );
				   if (!is_null( $iframes ) && $iframes->length ) {
				      foreach ( $iframes as $iframe )
				        if ( $iframe->hasAttributes() ) { 
				            $attributes = $iframe->attributes; 
				            if ( !is_null( $attributes ) )
				               foreach ( $attributes as $index=>$attr )
				                  if ( $attr->name == 'src' ) { 
				                     $data['html'] = $attr->value;
									 $link_found = 1;
								     break 2;
				                  }
				         }
					} else {
						$embeds = $dom_xpath->query( "//embed" );
						if (!is_null( $embeds ) && $embeds->length ) {
						      foreach ( $embeds as $embed )
						        if ( $embed->hasAttributes() ) { 
						            $attributes = $embed->attributes; 
						            if ( !is_null( $attributes ) )
						               foreach ( $attributes as $index=>$attr )
						                  if ( $attr->name == 'src' ) { 
						                     $data['html'] = $attr->value;
											 $link_found = 1;
										     break 2;
						                  }
						         }
							}
					}
				}
				if ( !$link_found )
					unset( $data["html"] );
				else {
					$parse_url = parse_url( str_replace( ':////', '://', esc_url_raw( $data["html"] ) ) );
					if ( empty( $parse_url['scheme'] ) ) {
						$parse_url['scheme'] = 'http';
						$data["html"] = str_replace( ':////', '://', esc_url_raw( $this->unparse_url( $parse_url ) ) );							
					}
				}
				foreach( $fields as $k => $v )
					if ( !empty( $data[$k] ) )
						$opts[$v] = ent2ncr( esc_attr( $data[$k] ) );
				if ( !empty( $data['html'] ) && empty( $opts['video:description'] ) ) {
					$opts['video:description'] = "Video ";
					if ( !empty($opts['video:title']) ) $opts['video:description'] .= $opts['video:title'];
					if ( !empty( $opts['video:uploader'] ) )
						$opts['video:description'] .= ' by ' . $opts['video:uploader'];
				}
			}
			return $opts;
		}
		
		function oembed_discover_url( $url ) {
			$data = Array();
			if ( !empty( $url ) ) {
				$parse_url = parse_url( str_replace( ':////', '://', esc_url_raw( $url ) ) );
				if ( empty( $parse_url['scheme'] ) ) $parse_url['scheme'] = 'http';
				$url = $this->unparse_url( $parse_url );
				
				include_once( ABSPATH . 'wp-includes/class-oembed.php' );
				$wp_oembed = _wp_oembed_get_object();
				$provider = false;
                foreach ( $wp_oembed->providers as $matchmask => $d ) {
                        list( $providerurl, $regex ) = $d;
                        if ( !$regex ) {
                                $matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
                                $matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
                        }
                        if ( preg_match( $matchmask, $url ) ) {
                                $provider = str_replace( '{format}', 'json', $providerurl ); // JSON is easier to deal with than XML
                                break;
                        }
                }
				if ( empty( $provider ) )
					$provider = $wp_oembed->discover( $url );
				if ( !empty( $provider ) )
					$data = $wp_oembed->fetch( $provider, $url, Array( 'discover' => true ) );
			}
			return $data;
		}
		
		/** oEmbed discovery - save in post meta **/
		function oembed_discovery( $html, $url, $c, $id ) {
			$opts = get_post_meta( $id, '_aioseop_oembed_info', true );
			if ( empty( $opts ) ) $opts = Array();
			if ( !empty( $opts[$url] ) ) return $html;
			$info = $this->oembed_discover_url( $url );
			if ( !empty( $info ) ) {
				$opts[$url] = $info;
				update_post_meta( $id, '_aioseop_oembed_info', $opts );
			}
			return $html;
		}
		
		function oembed_cache( $id ) {
			global $wp_embed;
			global $post;
			$old_post = $post;
			delete_post_meta( (int)$id, '_aioseop_oembed_info' );
		//	$wp_embed->cache_oembed( (int)$id );
			$post = get_post( (int)$id );
			if ( !empty( $post ) && !empty($post->post_content) ) {
				$wp_embed->post_ID = (int)$post->ID;
				$wp_embed->usecache = false;
				$content = $wp_embed->run_shortcode( $post->post_content );
				$wp_embed->autoembed( $content );
				$wp_embed->usecache = true;
			}
			$post = $old_post;
		}
	}
}
