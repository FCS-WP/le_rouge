import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, subtitle, btnText, btnUrl, images } = attributes;

	const updateImage = (index, key, value) => {
		const newImages = [...images];
		newImages[index][key] = value;
		setAttributes({ images: newImages });
	};

	const addImage = () => {
		setAttributes({ images: [...images, { url: '', label: '@lerougesg' }] });
	};

	const removeImage = (index) => {
		const newImages = images.filter((_, i) => i !== index);
		setAttributes({ images: newImages });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'ai-zippy')}>
					<TextControl
						label={__('Button URL', 'ai-zippy')}
						value={btnUrl}
						onChange={(val) => setAttributes({ btnUrl: val })}
					/>
					<Button isPrimary onClick={addImage}>
						{__('Add Social Tile', 'ai-zippy')}
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps({ className: 'az-community-editor' })}>
				<div className="community-mock">
					<RichText
						tagName="p"
						className="eyebrow"
						value={eyebrow}
						onChange={(val) => setAttributes({ eyebrow: val })}
					/>
					<RichText
						tagName="h2"
						className="community-title"
						value={title}
						onChange={(val) => setAttributes({ title: val })}
					/>
					<RichText
						tagName="p"
						className="community-sub"
						value={subtitle}
						onChange={(val) => setAttributes({ subtitle: val })}
					/>
					<RichText
						tagName="span"
						className="btn-primary"
						value={btnText}
						onChange={(val) => setAttributes({ btnText: val })}
					/>
					<div className="social-grid-mock">
						{images.map((img, index) => (
							<div key={index} className="social-tile-mock">
								<MediaUploadCheck>
									<MediaUpload
										onSelect={(m) => updateImage(index, 'url', m.url)}
										allowedTypes={['image']}
										value={img.url}
										render={({ open }) => (
											<div className="img-placeholder" onClick={open} style={{ backgroundImage: `url(${img.url})` }}>
												{!img.url && __('Select', 'ai-zippy')}
											</div>
										)}
									/>
								</MediaUploadCheck>
								<RichText
									tagName="span"
									className="social-label"
									value={img.label}
									onChange={(val) => updateImage(index, 'label', val)}
								/>
								<Button icon="no" onClick={() => removeImage(index)} />
							</div>
						))}
					</div>
				</div>
			</div>
		</>
	);
}
