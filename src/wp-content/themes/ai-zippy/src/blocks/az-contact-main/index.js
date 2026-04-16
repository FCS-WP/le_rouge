import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { useBlockProps, RichText } from '@wordpress/block-editor';

registerBlockType(metadata.name, {
	edit: ({ attributes, setAttributes }) => {
        const { formTitle, formSubtitle } = attributes;
        return (
            <div { ...useBlockProps({ className: 'contact-body-wrapper' }) }>
                <div className="contact-body">
                    <div className="contact-locations">
                        <div className="location-card" style={{ borderLeft: '3px solid #8B1C3F' }}>
                            <span className="location-num serif">01</span>
                            <h2 className="location-name serif">Raffles Place</h2>
                            <p style={{fontSize: '11px', color: '#999'}}>3 Locations rendered here...</p>
                        </div>
                    </div>
                    <div className="contact-form-container">
                        <RichText
                            tagName="h2"
                            className="form-title serif"
                            value={ formTitle }
                            onChange={ ( val ) => setAttributes( { formTitle: val } ) }
                        />
                        <RichText
                            tagName="p"
                            className="form-sub"
                            value={ formSubtitle }
                            onChange={ ( val ) => setAttributes( { formSubtitle: val } ) }
                        />
                        <div style={{ border: '1px dashed #ccc', padding: '20px', textAlign: 'center', fontSize: '11px' }}>
                            FORM INTERFACE
                        </div>
                    </div>
                </div>
            </div>
        );
    },
	save: () => null,
});
