<?php
/**
 * Bottom bar template
 *
 * @package vogue
 * @since   1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!--Heartfulness Social Icons Widget-->
<div class="hfn-social-info">
<div class="container">
<div class="text-center">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Social Widget Area") ) : ?>
<?php endif;?>
</div>
</div>
</div>
<!--End Heartfulness Social Icons Widget-->
<!-- !Bottom-bar -->
<div id="bottom-bar" <?php echo presscore_bottom_bar_class(); ?> role="contentinfo">
    <div class="wf-wrap">
        <div class="wf-container-bottom">

			<?php
			$logo = presscore_get_the_bottom_bar_logo();
			if ( $logo ) {
				echo '<div id="branding-bottom">';
				presscore_display_the_logo( $logo );
				echo '</div>';
			}

			do_action( 'presscore_credits' );

			$config     = presscore_config();
			$copyrights = $config->get( 'template.bottom_bar.copyrights' );
			$credits    = $config->get( 'template.bottom_bar.credits' );

			if ( $copyrights || $credits ) : ?>

                <div class="wf-float-left">

					<?php
					echo do_shortcode( $copyrights );

					if ( $credits ) {
						echo '&nbsp;Dream-Theme &mdash; truly <a href="http://dream-theme.com" target="_blank">premium WordPress themes</a>';
					}
					?>

                </div>

			<?php endif; ?>

            <div class="wf-float-right">

				<?php
				presscore_nav_menu_list( 'bottom', '' );

				$bottom_text = $config->get( 'template.bottom_bar.text' );
				if ( $bottom_text ) {
					echo '<div class="bottom-text-block">' . do_shortcode( shortcode_unautop( wpautop( $bottom_text ) ) ) . '</div>';
				}
				?>

            </div>

        </div><!-- .wf-container-bottom -->
    </div><!-- .wf-wrap -->
</div><!-- #bottom-bar -->
