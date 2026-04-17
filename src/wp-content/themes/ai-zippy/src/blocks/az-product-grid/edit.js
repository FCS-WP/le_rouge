import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, viewAllText, viewAllUrl, limit, category } = attributes;

	const placeholders = Array(limit || 4).fill({
		badge: 'Sale',
		tag: 'Category',
		name: 'Product Name (Preview)',
		region: 'Origin/Region',
		price: '$99.00',
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Query Settings', 'ai-zippy')}>
					<TextControl
						label={__('Product Limit', 'ai-zippy')}
						type="number"
						value={limit}
						onChange={(val) => setAttributes({ limit: parseInt(val) || 1 })}
					/>
					<TextControl
						label={__('Category Slug (Optional)', 'ai-zippy')}
						value={category}
						onChange={(val) => setAttributes({ category: val })}
					/>
				</PanelBody>
				<PanelBody title={__('Button Settings', 'ai-zippy')}>
					<TextControl
						label={__('View All URL', 'ai-zippy')}
						value={viewAllUrl}
						onChange={(val) => setAttributes({ viewAllUrl: val })}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps({ className: 'az-product-grid-editor' })}>
				<RichText
					tagName="p"
					className="eyebrow"
					value={eyebrow}
					onChange={(val) => setAttributes({ eyebrow: val })}
				/>
				<div className="section-header-mock">
					<RichText
						tagName="h2"
						className="section-title"
						value={title}
						onChange={(val) => setAttributes({ title: val })}
					/>
					<RichText
						tagName="span"
						className="section-view-all"
						value={viewAllText}
						onChange={(val) => setAttributes({ viewAllText: val })}
					/>
				</div>
				
				<div className="product-grid-mock">
					<div className="dynamic-data-notice" style={{ gridColumn: '1/-1', textAlign: 'center', padding: '15px', border: '1px dashed #ccc', color: '#666', marginBottom: '20px' }}>
						{__('Showing Dynamic WooCommerce Products', 'ai-zippy')}
					</div>
					
					{placeholders.map((prod, index) => (
						<div key={index} className="product-card-mock">
							<div className="prod-img-wrap">
								<div className="img-placeholder" style={{ background: '#eee', height: '200px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
									{__('Product Image', 'ai-zippy')}
								</div>
								<div className="product-badge">{prod.badge}</div>
							</div>
							<div className="prod-info-mock">
								<p className="product-tag">{prod.tag}</p>
								<h3 className="product-name">{prod.name}</h3>
								<p className="product-region">{prod.region}</p>
								<span className="product-price">{prod.price}</span>
							</div>
						</div>
					))}
				</div>
			</div>
		</>
	);
}
