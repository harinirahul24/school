<?php
/**
 * Branding template.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
				<div class="branding">
                    <a href="<?php echo home_url(); ?>" class="hfn-site-url"><?php include("logo.php"); ?></a>
					<div id="site-title" class="assistive-text"><?php bloginfo( 'name' ) ?></div>
					<div id="site-description" class="assistive-text"><?php bloginfo( 'description' ) ?></div>
				</div>