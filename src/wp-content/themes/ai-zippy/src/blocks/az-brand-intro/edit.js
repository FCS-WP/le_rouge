import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  RichText,
  InspectorControls,
} from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, heading, btnText, btnUrl, textLeft, textRight } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Settings", "ai-zippy")}>
          <TextControl
            label={__("Button URL", "ai-zippy")}
            value={btnUrl}
            onChange={(val) => setAttributes({ btnUrl: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: "brand-intro" })}>
        <div className="bi-inner">
          <div className="bi-left">
            <RichText
              tagName="p"
              className="eyebrow"
              value={eyebrow}
              onChange={(val) => setAttributes({ eyebrow: val })}
            />
            <RichText
              tagName="h2"
              className="bi-heading serif"
              value={heading}
              onChange={(val) => setAttributes({ heading: val })}
            />
            <div className="bi-divider"></div>
            <RichText
              tagName="span"
              className="btn-outline"
              value={btnText}
              onChange={(val) => setAttributes({ btnText: val })}
            />
          </div>
          <div className="bi-right">
            <RichText
              tagName="p"
              className="bi-text"
              value={textLeft}
              onChange={(val) => setAttributes({ textLeft: val })}
            />
            <RichText
              tagName="p"
              className="bi-text"
              value={textRight}
              onChange={(val) => setAttributes({ textRight: val })}
            />
          </div>
        </div>
      </div>
    </>
  );
}
