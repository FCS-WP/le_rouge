import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import { PanelBody, Button, TextControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const {
    eyebrow,
    title,
    subtitle,
    primaryBtnText,
    primaryBtnUrl,
    ghostBtnText,
    ghostBtnUrl,
    imageUrl,
    taglineSmall,
    taglineLarge,
  } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Settings", "ai-zippy")}>
          <TextControl
            label={__("Eyebrow", "ai-zippy")}
            value={eyebrow}
            onChange={(val) => setAttributes({ eyebrow: val })}
          />
          <TextControl
            label={__("Primary Button URL", "ai-zippy")}
            value={primaryBtnUrl}
            onChange={(val) => setAttributes({ primaryBtnUrl: val })}
          />
          <TextControl
            label={__("Ghost Button URL", "ai-zippy")}
            value={ghostBtnUrl}
            onChange={(val) => setAttributes({ ghostBtnUrl: val })}
          />
          <TextControl
            label={__("Floating Tag Small", "ai-zippy")}
            value={taglineSmall}
            onChange={(val) => setAttributes({ taglineSmall: val })}
          />
          <TextControl
            label={__("Floating Tag Large", "ai-zippy")}
            value={taglineLarge}
            onChange={(val) => setAttributes({ taglineLarge: val })}
          />
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ imageUrl: media.url })}
              allowedTypes={["image"]}
              value={imageUrl}
              render={({ open }) => (
                <Button isPrimary onClick={open}>
                  {__("Select Hero Image", "ai-zippy")}
                </Button>
              )}
            />
          </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: "hero" })}>
        <div className="hero-content">
          <RichText
            tagName="p"
            className="eyebrow"
            value={eyebrow}
            onChange={(val) => setAttributes({ eyebrow: val })}
            placeholder={__("Eyebrow...", "ai-zippy")}
          />
          <RichText
            tagName="h1"
            className="hero-title"
            value={title}
            onChange={(val) => setAttributes({ title: val })}
            placeholder={__("Title...", "ai-zippy")}
          />
          <RichText
            tagName="p"
            className="hero-subtitle"
            value={subtitle}
            onChange={(val) => setAttributes({ subtitle: val })}
            placeholder={__("Subtitle...", "ai-zippy")}
          />
          <div className="hero-cta">
            <RichText
              tagName="span"
              className="btn-primary"
              value={primaryBtnText}
              onChange={(val) => setAttributes({ primaryBtnText: val })}
            />
            <RichText
              tagName="span"
              className="btn-ghost"
              value={ghostBtnText}
              onChange={(val) => setAttributes({ ghostBtnText: val })}
            />
          </div>
        </div>
        <div className="hero-visual">
          {imageUrl && <img src={imageUrl} alt="" />}
          <div className="hero-overlay"></div>
          <div className="hero-float-tag">
            <RichText
              tagName="p"
              value={taglineSmall}
              onChange={(val) => setAttributes({ taglineSmall: val })}
            />
            <RichText
              tagName="span"
              value={taglineLarge}
              onChange={(val) => setAttributes({ taglineLarge: val })}
            />
          </div>
        </div>
      </div>
    </>
  );
}
