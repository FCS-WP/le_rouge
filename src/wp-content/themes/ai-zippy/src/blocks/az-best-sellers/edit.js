import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from '@wordpress/block-editor';
import {
	Button,
	ComboboxControl,
	PanelBody,
	RangeControl,
	TextControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useState } from '@wordpress/element';

export default function Edit( { attributes, setAttributes } ) {
	const {
		eyebrow,
		title,
		viewAllText,
		viewAllUrl,
		limit,
		category,
		selectedProductIds = [],
		desktopColumns = 4,
		desktopRows = 1,
	} = attributes;
	const [ productToAdd, setProductToAdd ] = useState( '' );
	const products = useSelect(
		( select ) =>
			select( 'core' ).getEntityRecords( 'postType', 'product', {
				per_page: 100,
				status: 'publish',
				orderby: 'title',
				order: 'asc',
			} ) || [],
		[]
	);
	const productOptions = products.map( ( product ) => ( {
		label: product.title?.rendered || `#${ product.id }`,
		value: String( product.id ),
	} ) );
	const selectedProducts = selectedProductIds
		.map(
			( id ) =>
				products.find( ( product ) => product.id === id ) || {
					id,
					title: { rendered: `#${ id }` },
				}
		)
		.filter( Boolean );
	const previewCount =
		selectedProductIds.length || limit || desktopColumns * desktopRows || 4;

	const addSelectedProduct = () => {
		const nextProductId = parseInt( productToAdd, 10 );

		if ( ! nextProductId || selectedProductIds.includes( nextProductId ) ) {
			return;
		}

		setAttributes( {
			selectedProductIds: [ ...selectedProductIds, nextProductId ],
		} );
		setProductToAdd( '' );
	};

	const removeSelectedProduct = ( productId ) => {
		setAttributes( {
			selectedProductIds: selectedProductIds.filter(
				( id ) => id !== productId
			),
		} );
	};

	const placeholders = Array( previewCount ).fill( {
		badge: 'Sale',
		tag: 'Category',
		name: 'Product Name (Preview)',
		region: 'Origin/Region',
		price: '$99.00',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Query Settings', 'ai-zippy' ) }>
					<ComboboxControl
						label={ __( 'Choose Best Seller Product', 'ai-zippy' ) }
						value={ productToAdd }
						options={ productOptions }
						onChange={ setProductToAdd }
						help={ __(
							'Select products one by one. The selected order controls the frontend order.',
							'ai-zippy'
						) }
					/>
					<Button
						variant="secondary"
						onClick={ addSelectedProduct }
						disabled={ ! productToAdd }
					>
						{ __( 'Add Product', 'ai-zippy' ) }
					</Button>
					{ selectedProducts.length > 0 && (
						<ul className="az-selected-products">
							{ selectedProducts.map( ( product ) => (
								<li key={ product.id }>
									<span>
										{ product.title?.rendered ||
											`#${ product.id }` }
									</span>
									<Button
										variant="link"
										isDestructive
										onClick={ () =>
											removeSelectedProduct( product.id )
										}
									>
										{ __( 'Remove', 'ai-zippy' ) }
									</Button>
								</li>
							) ) }
						</ul>
					) }
					<TextControl
						label={ __( 'Fallback Product Limit', 'ai-zippy' ) }
						type="number"
						value={ limit }
						onChange={ ( val ) =>
							setAttributes( { limit: parseInt( val ) || 1 } )
						}
					/>
					<TextControl
						label={ __( 'Category Slug (Optional)', 'ai-zippy' ) }
						value={ category }
						onChange={ ( val ) =>
							setAttributes( { category: val } )
						}
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
						{ selectedProductIds.length > 0
							? __(
									'Showing selected WooCommerce products',
									'ai-zippy'
							  )
							: __(
									'Showing fallback best seller products',
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
