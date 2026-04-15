import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import metadata from './block.json';
import './style.scss';

registerBlockType(metadata.name, {
	edit: ({ attributes, setAttributes }) => {
		return (
			<div {...useBlockProps({ className: 'az-section-divider-editor' })}>
				<div className="section-divider-mock">
					<RichText
						tagName="span"
						className="section-divider-inner"
						value={attributes.text}
						onChange={(val) => setAttributes({ text: val })}
					/>
				</div>
			</div>
		);
	},
	save: () => null,
});
