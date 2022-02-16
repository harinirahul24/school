<?php
// File Security Check.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Address.
$options[] = array(
	'name'                => _x( 'Address', 'theme-options', 'the7mk2' ),
	'id'                  => 'microwidgets-address-block',
	'type'                => 'block',
	'class'               => 'block-disabled',
	'exclude_from_search' => true,
);

presscore_options_apply_template( $options, 'basic-header-element', 'header-elements-contact-address', array(
	'caption'     => array(
		'name'    => _x( 'Address', 'theme-options', 'the7mk2' ),
		'divider' => false,
		'class'   => 'wide',
	),
	'custom-icon' => array(
		'std' => 'the7-mw-icon-address',
	),
) );

// Email.
$options[] = array(
	'name'                => _x( 'Email', 'theme-options', 'the7mk2' ),
	'id'                  => 'microwidgets-email-block',
	'type'                => 'block',
	'class'               => 'block-disabled',
	'exclude_from_search' => true,
);

presscore_options_apply_template( $options, 'basic-header-element', 'header-elements-contact-email', array(
	'caption'     => array(
		'name'    => _x( 'Email', 'theme-options', 'the7mk2' ),
		'divider' => false,
		'class'   => 'wide',
	),
	'custom-icon' => array(
		'std' => 'the7-mw-icon-mail',
	),
) );

// Phone.
$options[] = array(
	'name'                => _x( 'Phone', 'theme-options', 'the7mk2' ),
	'id'                  => 'microwidgets-phone-block',
	'type'                => 'block',
	'class'               => 'block-disabled',
	'exclude_from_search' => true,
);

presscore_options_apply_template( $options, 'basic-header-element', 'header-elements-contact-phone', array(
	'caption'     => array(
		'name'    => _x( 'Phone', 'theme-options', 'the7mk2' ),
		'divider' => false,
		'class'   => 'wide',
	),
	'custom-icon' => array(
		'std' => 'the7-mw-icon-phone',
	),
) );

// Skype.
$options[] = array(
	'name'                => _x( 'Skype', 'theme-options', 'the7mk2' ),
	'id'                  => 'microwidgets-skype-block',
	'type'                => 'block',
	'class'               => 'block-disabled',
	'exclude_from_search' => true,
);

presscore_options_apply_template( $options, 'basic-header-element', 'header-elements-contact-skype', array(
	'caption'     => array(
		'name'    => _x( 'Skype', 'theme-options', 'the7mk2' ),
		'divider' => false,
		'class'   => 'wide',
	),
	'custom-icon' => array(
		'std' => 'the7-mw-icon-skype',
	),
) );

// Working hours.
$options[] = array(
	'name'                => _x( 'Working hours', 'theme-options', 'the7mk2' ),
	'id'                  => 'microwidgets-working_hours-block',
	'type'                => 'block',
	'class'               => 'block-disabled',
	'exclude_from_search' => true,
);

presscore_options_apply_template( $options, 'basic-header-element', 'header-elements-contact-clock', array(
	'caption'     => array(
		'name'    => _x( 'Working hours', 'theme-options', 'the7mk2' ),
		'divider' => false,
		'class'   => 'wide',
	),
	'custom-icon' => array(
		'std' => 'the7-mw-icon-clock',
	),
) );
