import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const {
		eyebrow,
		title,
		viewAllText,
		viewAllUrl,
		category,
		desktopColumns = 4,
		desktopRows = 2,
	} = attributes;
	const productLimit = desktopColumns * desktopRows;

	const placeholders = Array( productLimit || 6 ).fill( {
		badge: 'New',
		tag: 'Category',
		name: 'Product Name (Preview)',
		region: 'Origin/Region',
		price: '$99.00',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Query Settings', 'ai-zippy' ) }>
					<TextControl
						label={ __( 'Category Slug (Optional)', 'ai-zippy' ) }
						value={ category }
						onChange={ ( val ) =>
							setAttributes( { category: val } )
						}
						help={ __(
							'Leave empty to show the latest products from all categories.',
							'ai-zippy'
						) }
					/>
					<RangeControl
						label={ __( 'Desktop Columns', 'ai-zippy' ) }
						value={ desktopColumns }
						onChange={ ( val ) =>
							setAttributes( { desktopColumns: val } )
						}
						min={ 1 }
						max={ 6 }
					/>
					<RangeControl
						label={ __( 'Desktop Rows', 'ai-zippy' ) }
						value={ desktopRows }
						onChange={ ( val ) =>
							setAttributes( { desktopRows: val } )
						}
						min={ 1 }
						max={ 4 }
						help={ __(
							'Mobile always shows 3 rows and 2 columns.',
							'ai-zippy'
						) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Button Settings', 'ai-zippy' ) }>
					<TextControl
						label={ __( 'View All URL', 'ai-zippy' ) }
						value={ viewAllUrl }
						onChange={ ( val ) =>
							setAttributes( { viewAllUrl: val } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div
				{ ...useBlockProps( {
					className: 'az-product-grid-editor',
					style: { '--az-product-columns': desktopColumns },
				} ) }
			>
				<RichText
					tagName="p"
					className="eyebrow"
					value={ eyebrow }
					onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
				/>
				<div className="section-header-mock">
					<RichText
						tagName="h2"
						className="section-title"
						value={ title }
						onChange={ ( val ) => setAttributes( { title: val } ) }
					/>
					<RichText
						tagName="span"
						className="section-view-all"
						value={ viewAllText }
						onChange={ ( val ) =>
							setAttributes( { viewAllText: val } )
						}
					/>
				</div>

				<div className="product-grid-mock">
					<div className="dynamic-data-notice">
						{ __(
							'Showing latest WooCommerce products',
							'ai-zippy'
						) }
					</div>

					{ placeholders.map( ( prod, index ) => (
						<div key={ index } className="product-card-mock">
							<div className="prod-img-wrap">
								<div className="img-placeholder">
									{ __( 'Product Image', 'ai-zippy' ) }
								</div>
								<div className="product-badge">
									{ prod.badge }
								</div>
							</div>
							<div className="prod-info-mock">
								<p className="product-tag">{ prod.tag }</p>
								<h3 className="product-name">{ prod.name }</h3>
								<p className="product-region">
									{ prod.region }
								</p>
								<span className="product-price">
									{ prod.price }
								</span>
							</div>
						</div>
					) ) }
				</div>
			</div>
		</>
	);
}
