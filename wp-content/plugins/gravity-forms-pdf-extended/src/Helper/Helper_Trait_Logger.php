<?php

namespace GFPDF\Helper;

use Monolog\Logger;

/**
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2019, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 4.3
 */
trait Helper_Trait_Logger {

	/**
	 * Holds our log class
	 *
	 * @var \Monolog\Logger
	 *
	 * @since 4.3
	 */
	protected $logger;

	/**
	 * @param Logger $log
	 *
	 * @since 4.3
	 */
	public function set_logger( Logger $log ) {
		$this->logger = $log;
	}

	/**
	 * @return Logger
	 *
	 * @since 4.3
	 */
	public function get_logger() {
		return $this->logger;
	}
}
