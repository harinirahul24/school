<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.4.1
 *
 * @package    Easy_Accordion_Free
 * @subpackage Easy_Accordion_Free/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */
if ( ! class_exists( 'Easy_Accordion_Free_Gutenberg_Block' ) ) {

	/**
	 * Custom Gutenberg Block.
	 */
	class Easy_Accordion_Free_Gutenberg_Block {

		/**
		 * Block Initializer.
		 */
		public function __construct() {
			require_once SP_EA_PATH . '/admin/GutenbergBlock/class-easy-accordion-free-gutenberg-block-init.php';
			new Easy_Accordion_Free_Gutenberg_Block_Init();
		}

	}
}
