import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import {
  PanelBody,
  TextControl,
  Button,
  Dashicon,
} from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { title, links } = attributes;

  const updateLink = (index, key, value) => {
    const newLinks = [...links];
    newLinks[index][key] = value;
    setAttributes({ links: newLinks });
  };

  const addLink = () => {
    setAttributes({ links: [...links, { label: "", url: "" }] });
  };

  const removeLink = (index) => {
    const newLinks = links.filter((_, i) => i !== index);
    setAttributes({ links: newLinks });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Menu Settings", "ai-zippy")}>
          <TextControl
            label={__("Column Title", "ai-zippy")}
            value={title}
            onChange={(val) => setAttributes({ title: val })}
          />
          <hr />
          <p>
            <strong>{__("Links", "ai-zippy")}</strong>
          </p>
          {links.map((link, index) => (
            <div
              key={index}
              style={{
                marginBottom: "20px",
                padding: "10px",
                background: "#f0f0f0",
                position: "relative",
              }}
            >
              <Button
                isDestructive
                isSmall
                onClick={() => removeLink(index)}
                style={{ position: "absolute", top: "5px", right: "5px" }}
              >
                <Dashicon icon="no" />
              </Button>
              <TextControl
                label={__("Label", "ai-zippy")}
                value={link.label}
                onChange={(val) => updateLink(index, "label", val)}
              />
              <TextControl
                label={__("URL", "ai-zippy")}
                value={link.url}
                onChange={(val) => updateLink(index, "url", val)}
              />
            </div>
          ))}
          <Button isPrimary onClick={addLink}>
            {__("Add Link", "ai-zippy")}
          </Button>
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps()}>
        <div
          style={{
            opacity: 0.8,
            fontSize: "12px",
            border: "1px dashed #ccc",
            padding: "20px",
          }}
        >
          <strong>{title || __("Menu Column", "ai-zippy")}</strong>
          <ul style={{ listStyle: "none", padding: 0 }}>
            {links.map((link, i) => (
              <li key={i} style={{ margin: "5px 0" }}>
                {link.label || __("(Empty Label)", "ai-zippy")}
              </li>
            ))}
          </ul>
          <em style={{ fontSize: "10px" }}>
            {__("Edit links in the sidebar.", "ai-zippy")}
          </em>
        </div>
      </div>
    </>
  );
}
