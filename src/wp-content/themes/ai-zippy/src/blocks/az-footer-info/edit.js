import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";
import {
  PanelBody,
  TextControl,
  TextareaControl,
  Button,
} from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const {
    logoId,
    logoUrl,
    description,
    outlet1Title,
    outlet1Address,
    outlet1Hours,
    outlet2Title,
    outlet2Address,
    outlet2Hours,
  } = attributes;

  const onSelectImage = (media) => {
    setAttributes({ logoId: media.id, logoUrl: media.url });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Header/Logo", "ai-zippy")}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={onSelectImage}
              allowedTypes={["image"]}
              value={logoId}
              render={({ open }) => (
                <Button
                  className={!logoId ? "is-primary" : "is-secondary"}
                  onClick={open}
                >
                  {!logoId
                    ? __("Upload Logo", "ai-zippy")
                    : __("Change Logo", "ai-zippy")}
                </Button>
              )}
            />
          </MediaUploadCheck>
          <TextareaControl
            label={__("Description", "ai-zippy")}
            value={description}
            onChange={(val) => setAttributes({ description: val })}
          />
        </PanelBody>

        <PanelBody title={__("Outlet 1", "ai-zippy")} initialOpen={false}>
          <TextControl
            label={__("Name", "ai-zippy")}
            value={outlet1Title}
            onChange={(val) => setAttributes({ outlet1Title: val })}
          />
          <TextControl
            label={__("Address/Suite", "ai-zippy")}
            value={outlet1Address}
            onChange={(val) => setAttributes({ outlet1Address: val })}
          />
          <TextareaControl
            label={__("Operating Hours", "ai-zippy")}
            value={outlet1Hours}
            onChange={(val) => setAttributes({ outlet1Hours: val })}
          />
        </PanelBody>

        <PanelBody title={__("Outlet 2", "ai-zippy")} initialOpen={false}>
          <TextControl
            label={__("Name", "ai-zippy")}
            value={outlet2Title}
            onChange={(val) => setAttributes({ outlet2Title: val })}
          />
          <TextControl
            label={__("Address/Suite", "ai-zippy")}
            value={outlet2Address}
            onChange={(val) => setAttributes({ outlet2Address: val })}
          />
          <TextareaControl
            label={__("Operating Hours", "ai-zippy")}
            value={outlet2Hours}
            onChange={(val) => setAttributes({ outlet2Hours: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps()}>
        <div
          className="footer-info-preview"
          style={{
            opacity: 0.6,
            fontSize: "12px",
            border: "1px dashed #ccc",
            padding: "20px",
          }}
        >
          <strong>AZ Footer Info Block</strong>
          <br />
          <em>Content can be edited in the sidebar on the right.</em>
          <hr />
          {logoUrl && (
            <img
              src={logoUrl}
              style={{
                maxHeight: "40px",
                display: "block",
                marginBottom: "10px",
              }}
            />
          )}
          <p>{description}</p>
          <p>◇ {outlet1Title}</p>
          <p>◇ {outlet2Title}</p>
        </div>
      </div>
    </>
  );
}
