import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType(metadata.name, {
	edit: ({ attributes, setAttributes }) => {
        const { mapIframe } = attributes;
        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Map Settings', 'ai-zippy')}>
                        <TextareaControl
                            label={__('Google Maps Iframe Code', 'ai-zippy')}
                            help={__('Paste the <iframe> embed code from Google Maps', 'ai-zippy')}
                            value={ mapIframe }
                            onChange={ ( val ) => setAttributes( { mapIframe: val } ) }
                        />
                    </PanelBody>
                </InspectorControls>
                <div { ...useBlockProps({ className: 'map-section' }) }>
                    <div className="map-placeholder">
                        { mapIframe ? (
                            <div style={{ width: '100%', height: '100%', backgroundColor: '#eee', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                MAP PREVIEW (Iframe loaded on frontend)
                            </div>
                        ) : (
                            <>
                                <span style={{ fontSize: '28px', color: '#b8964a', opacity: 0.25 }}>◇</span>
                                <span className="map-label">Google Maps Embed Placeholder</span>
                                <p style={{fontSize: '11px'}}>Paste iframe code in block settings</p>
                            </>
                        )}
                    </div>
                </div>
            </>
        );
    },
	save: () => null,
});
