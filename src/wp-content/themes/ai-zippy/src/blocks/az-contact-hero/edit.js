import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, subtitle } = attributes;
	return (
		<div { ...useBlockProps({ className: 'contact-hero' }) }>
			<div className="contact-hero-inner">
				<RichText
					tagName="p"
					className="eyebrow"
					value={ eyebrow }
					onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
				/>
				<RichText
					tagName="h1"
					className="contact-page-title serif"
					value={ title }
					onChange={ ( val ) => setAttributes( { title: val } ) }
				/>
				<RichText
					tagName="p"
					style={{ fontSize: '13px', marginTop: '14px', color: 'rgba(26, 10, 13, 0.55)' }}
					value={ subtitle }
					onChange={ ( val ) => setAttributes( { subtitle: val } ) }
				/>
			</div>
		</div>
	);
}
