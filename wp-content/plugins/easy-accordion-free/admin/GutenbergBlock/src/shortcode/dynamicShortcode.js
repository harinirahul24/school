/**
 * Shortcode select component.
 */
 import { escapeAttribute, escapeHTML } from "@wordpress/escape-html";
 const { __ } = wp.i18n;
 const { Fragment } = wp.element;
 const el = wp.element.createElement;
 
 const DynamicShortcodeInput = ( { attributes : { shortcode, shortcodelist}, shortcodeUpdate } ) => (
     <Fragment>
        {el('div', {className: 'speaf-gutenberg-shortcode editor-styles-wrapper'},
            el('select', {className: 'speaf-shortcode-selector', onChange: e => shortcodeUpdate(e), value: escapeAttribute( shortcode ) },
                el('option', {value: escapeAttribute('0')}, escapeHTML( __( '-- Select a shortcode --', 'easy-accordion-free' ))),
                shortcodelist.map( shortcode => {
                    var title = (shortcode.title.length > 35) ? shortcode.title.substring(0,30) + '.... #(' + shortcode.id + ')' : shortcode.title + ' #(' + shortcode.id + ')';
                    return el('option', {value: escapeAttribute( shortcode.id.toString() ), key: escapeAttribute( shortcode.id.toString() )}, escapeHTML( title ) )
                })
            )
        )}
     </Fragment>
    );
 
 export default DynamicShortcodeInput;