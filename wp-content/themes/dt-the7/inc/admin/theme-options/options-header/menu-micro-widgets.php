<?php
// File Security Check.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options[] = array(
	'name'                => _x( 'Menu 1', 'theme-options', 'the7mk2' ),
	'id'                  => 'microwidgets-custom_menu-block',
	'type'                => 'block',
	'class'               => 'block-disabled',
	'exclude_from_search' => true,
);

presscore_options_apply_template( $options, 'header-element-mobile-layout', 'header-elements-menu' );

$options['header-elements-menu-style'] = array(
	'id'      => 'header-elements-menu-style',
	'name'    => _x( 'Desktop menu style', 'theme-options', 'the7mk2' ),
	'type'    => 'radio',
	'std'     => 'dropdown',
	'options' => array(
		'dropdown' => _x( 'Dropdown', 'theme-options', 'the7mk2' ),
		'list'     => _x( 'List', 'theme-options', 'the7mk2' ),
	),
);

$options['header-elements-menu-style-first-switch'] = array(
	'id'      => 'header-elements-menu-style-first-switch',
	'name'    => _x( 'First header switch menu style', 'theme-options', 'the7mk2' ),
	'type'    => 'radio',
	'std'     => 'dropdown',
	'options' => array(
		'dropdown' => _x( 'Dropdown', 'theme-options', 'the7mk2' ),
		'list'     => _x( 'List', 'theme-options', 'the7mk2' ),
	),
);

$options['header-elements-menu-style-second-switch'] = array(
	'id'      => 'header-elements-menu-style-second-switch',
	'name'    => _x( 'Second header switch menu style', 'theme-options', 'the7mk2' ),
	'type'    => 'radio',
	'std'     => 'dropdown',
	'options' => array(
		'dropdown' => _x( 'Dropdown', 'theme-options', 'the7mk2' ),
		'list'     => _x( 'List', 'theme-options', 'the7mk2' ),
	),
);

// Menu 2.
$options[] = array(
	'name'                => _x( 'Menu 2', 'theme-options', 'the7mk2' ),
	'id'                  => 'microwidgets-menu2-block',
	'type'                => 'block',
	'class'               => 'block-disabled',
	'exclude_from_search' => true,
);

presscore_options_apply_template( $options, 'header-element-mobile-layout', 'header-elements-menu2' );

$options['header-elements-menu2-style'] = array(
	'id'      => 'header-elements-menu2-style',
	'name'    => _x( 'Desktop menu style', 'theme-options', 'the7mk2' ),
	'type'    => 'radio',
	'std'     => 'dropdown',
	'options' => array(
		'dropdown' => _x( 'Dropdown', 'theme-options', 'the7mk2' ),
		'list'     => _x( 'List', 'theme-options', 'the7mk2' ),
	),
);

$options['header-elements-menu2-style-first-switch'] = array(
	'id'      => 'header-elements-menu2-style-first-switch',
	'name'    => _x( 'First header switch menu style', 'theme-options', 'the7mk2' ),
	'type'    => 'radio',
	'std'     => 'dropdown',
	'options' => array(
		'dropdown' => _x( 'Dropdown', 'theme-options', 'the7mk2' ),
		'list'     => _x( 'List', 'theme-options', 'the7mk2' ),
	),
);

$options['header-elements-menu2-style-second-switch'] = array(
	'id'      => 'header-elements-menu2-style-second-switch',
	'name'    => _x( 'Second header switch menu style', 'theme-options', 'the7mk2' ),
	'type'    => 'radio',
	'std'     => 'dropdown',
	'options' => array(
		'dropdown' => _x( 'Dropdown', 'theme-options', 'the7mk2' ),
		'list'     => _x( 'List', 'theme-options', 'the7mk2' ),
	),
);
