<?php
/**
 * Archive pages.
 *
 * @package The7
 * @since 1.0.0
 */
// vars
$term = get_queried_object();
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = presscore_config();
$config->set( 'layout', 'masonry' );
$config->set( 'template.layout.type', 'masonry' );
get_header();
?>
			<!-- Content -->
			<div id="content" class="content" role="main">
				<?php if( get_field('category_description',$term) ): ?>
				   <?php echo get_field('category_description',$term); ?>
				<?php endif; ?>
				<div class="container">
				<?php

				if ( have_posts() ) {
					the7_archive_loop();
                } else {
					//get_template_part( 'no-results', 'search' );
                }
				?>
				</div>
			</div><!-- #content -->

			<?php do_action( 'presscore_after_content' ) ?>
<?php
		$place = 'post';
		$buttons = of_get_option( 'social_buttons-' . $place, array() );

		if ( empty( $buttons ) ) {
			return array();
		}
		$t = $term->cat_name;
		// get permalink
		$u = get_category_link($term->term_id);
		$buttons_list = presscore_themeoptions_get_social_buttons_list();
		$protocol = is_ssl() ? "https" : "http";
		$share_buttons = array();

		foreach ( $buttons as $button ) {
			$esc_url = true;
			$url = $custom = $icon_class = '';
			$desc = $buttons_list[ $button ];

			switch ( $button ) {
				case 'twitter':
					$icon_class = 'twitter';
					$url = 'https://twitter.com/share?url='.$u.'&text='.$t;
					break;
				case 'facebook':
					$icon_class = 'facebook';
					$url = 'https://www.facebook.com/sharer.php?u='.$u.'&t='.$t;
					break;
				case 'google+':
					$icon_class = 'google';
					$url = $protocol . '://plus.google.com/share?url='.$u.'&title='.$t;
					break;
				case 'pinterest':
					$icon_class = 'pinterest pinit-marklet';
					$url = '//pinterest.com/pin/create/button/';
					$custom = ' data-pin-config="above" data-pin-do="buttonBookmark"';
					// if image
					// if ( wp_attachment_is_image( $post_id ) ) {
					// 	$image = wp_get_attachment_image_src( $post_id, 'full' );
					// 	if ( ! empty( $image ) ) {
					// 		$url = add_query_arg( array(
					// 			'url'         => rawurlencode( $u ),
					// 			'media'       => rawurlencode( $image[0] ),
					// 			'description' => rawurlencode( apply_filters( 'get_the_excerpt', $_post->post_content ) )
					// 		), $url );
					// 		$custom = ' data-pin-config="above" data-pin-do="buttonPin"';
					// 		$icon_class = 'pinterest';
					// 	}
					// }
					break;
				case 'linkedin':
					$bt = get_bloginfo( 'name' );
					$url = $protocol . '://www.linkedin.com/shareArticle?mini=true&url=' .$u. '&title=' .$t. '&summary=&source=' .$bt;
					$icon_class = 'linkedin';
					break;
				case 'whatsapp':
				    $esc_url = false;
					$url = 'https://api.whatsapp.com/send?text=' ."{$t} - {$u}" ;
					$custom = ' data-action="share/whatsapp/share"';
					$icon_class = 'whatsapp';
					break;
			}

			if ( $esc_url ) {
				$url = esc_url( $url );
			}

			$share_button = '<a class="' . $icon_class . '" href="' . $url . '" title="' . esc_attr( $desc ) . '" target="_blank"' . $custom . '><span class="soc-font-icon"></span><span class="screen-reader-text">' . sprintf( __( 'Share with %s', 'the7mk2' ), $desc ) . '</span></a>';

			$share_buttons[] = apply_filters( 'presscore_share_button', $share_button, $button, $icon_class, $url, $desc, $t, $u );
		}

		$html =	'<div class="single-share-box">'
		           . '<div class="share-link-description">Share this Post</div>'
		           . '<div class="share-buttons">'
		           . implode( '', $share_buttons )
		           . '</div>'
		           . '</div>';
		 echo $html;
?>
 <?php get_footer() ?>
