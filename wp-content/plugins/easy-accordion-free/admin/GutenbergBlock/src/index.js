import icons from "./shortcode/blockIcon";
import DynamicShortcodeInput from "./shortcode/dynamicShortcode";
import { escapeAttribute, escapeHTML } from "@wordpress/escape-html";
import { InspectorControls } from '@wordpress/block-editor';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { PanelBody, PanelRow } = wp.components;
const { Fragment } = wp.element;
const ServerSideRender = wp.serverSideRender;
const el = wp.element.createElement;

/**
 * Register: aa Gutenberg Block.
 */
registerBlockType("sp-easy-accordion-free/shortcode", {
  title: escapeHTML( __("Easy Accordion", "easy-accordion-free") ),
  description: escapeHTML( __(
    "Use Easy Accordion to insert an accordion shortcode in your page.",
    "easy-accordion-free"
  )),
  icon: icons.speafIcon,
  category: "common",
  supports: {
    html: true,
  },
  edit: (props) => {
    const { attributes, setAttributes } = props;
    
    let scriptLoad = ( shortcodeId ) => {
      let speafBlockLoaded = false;
      let speafBlockLoadedInterval = setInterval(function () {
        let uniqId = jQuery("#sp-ea-" + shortcodeId).parents().attr('id');
        if (document.getElementById(uniqId)) {
          //Actual functions goes here
          jQuery.getScript(sp_easy_accordion_free.loadScript);
          speafBlockLoaded = true;
          uniqId = '';
        }
        if (speafBlockLoaded) {
          clearInterval(speafBlockLoadedInterval);
        }
        if ( 0 == shortcodeId ) {
          clearInterval(speafBlockLoadedInterval);
        }
      }, 10);
    }


    let updateShortcode = ( updateShortcode ) => {
      setAttributes({shortcode: escapeAttribute( updateShortcode.target.value )});
    }

    let shortcodeUpdate = (e) => {
      updateShortcode(e);
      let shortcodeId = escapeAttribute( e.target.value );
      scriptLoad(shortcodeId);
    }

    document.addEventListener('readystatechange', event => {
      if (event.target.readyState === "complete") {
        let shortcodeId = escapeAttribute( attributes.shortcode );
        scriptLoad(shortcodeId);
      }
    });

    if( attributes.preview ) {
      return (
        el('div', {className: 'speaf_shortcode_block_preview_image'},
          el('img', { src: escapeAttribute( sp_easy_accordion_free.url + "admin/GutenbergBlock/src/easy-acondion-preview.svg" )})
        )
      )
    }

    if (attributes.shortcodelist.length === 0 ) {
      return (
        <Fragment>
          {
            el('div', {className: 'components-placeholder components-placeholder is-large'}, 
              el('div', {className: 'components-placeholder__label'}, 
                el('img', {className: 'block-editor-block-icon', src: escapeAttribute( sp_easy_accordion_free.url + 'admin/GutenbergBlock/src/easy-accordion-icon.svg' )}),
                escapeHTML( __("Easy Accordion", "easy-accordion-free") )
              ),
              el('div', {className: 'components-placeholder__instructions'}, 
                escapeHTML( __("No shortcode found. ", "easy-accordion-free") ),
                el('a', {href: escapeAttribute( sp_easy_accordion_free.link )}, 
                  escapeHTML( __("Create a shortcode now!", "easy-accordion-free") )
                )
              )
            )
          }
        </Fragment>
      );
    }

    if ( ! attributes.shortcode || attributes.shortcode == 0 ) {
      return (
        <Fragment>
          <InspectorControls>
            <PanelBody title="Select a shortcode">
                <PanelRow>
                  <DynamicShortcodeInput
                    attributes={attributes}
                    shortcodeUpdate={shortcodeUpdate}
                  />
                </PanelRow>
            </PanelBody>
          </InspectorControls>
          {
            el('div', {className: 'components-placeholder components-placeholder is-large'}, 
              el('div', {className: 'components-placeholder__label'},
                el('img', { className: 'block-editor-block-icon', src: escapeAttribute( sp_easy_accordion_free.url + "admin/GutenbergBlock/src/easy-accordion-icon.svg" )}),
                escapeHTML( __("Easy Accordion", "easy-accordion-free") )
              ),
              el('div', {className: 'components-placeholder__instructions'}, escapeHTML( __("Select a shortcode", "easy-accordion-free") ) ),
              <DynamicShortcodeInput
                attributes={attributes}
                shortcodeUpdate={shortcodeUpdate}
              />
            )
          }
        </Fragment>
      );
    }

    return (
      <Fragment>
        <InspectorControls>
            <PanelBody title="Select a shortcode">
                <PanelRow>
                  <DynamicShortcodeInput
                    attributes={attributes}
                    shortcodeUpdate={shortcodeUpdate}
                  />
                </PanelRow>
            </PanelBody>
        </InspectorControls>
        <ServerSideRender block="sp-easy-accordion-free/shortcode" attributes={attributes} />
      </Fragment>
    );
  },
  save() {
    // Rendering in PHP
    return null;
  },
});
