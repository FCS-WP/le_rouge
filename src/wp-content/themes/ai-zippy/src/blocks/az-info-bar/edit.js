import { __ } from "@wordpress/i18n";
import { useBlockProps, RichText } from "@wordpress/block-editor";
import { Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { items } = attributes;

  const updateItem = (index, key, value) => {
    const newItems = [...items];
    newItems[index][key] = value;
    setAttributes({ items: newItems });
  };

  const addItem = () => {
    setAttributes({
      items: [...items, { icon: "◆", title: "Title", text: "Text" }],
    });
  };

  const removeItem = (index) => {
    const newItems = items.filter((_, i) => i !== index);
    setAttributes({ items: newItems });
  };

  return (
    <div {...useBlockProps({ className: "info-bar" })}>
      <div className="info-bar-inner">
        {items.map((item, index) => (
          <div key={index} className="info-bar-item">
            <RichText
              tagName="span"
              className="info-icon"
              value={item.icon}
              onChange={(val) => updateItem(index, "icon", val)}
            />
            <span className="info-text">
              <RichText
                tagName="strong"
                value={item.title}
                onChange={(val) => updateItem(index, "title", val)}
              />
              &nbsp;
              <RichText
                tagName="span"
                value={item.text}
                onChange={(val) => updateItem(index, "text", val)}
              />
            </span>
            <Button
              icon="no"
              onClick={() => removeItem(index)}
              style={{ marginLeft: "10px" }}
            />
          </div>
        ))}
        <Button icon="plus" isPrimary onClick={addItem}>
          {__("Add Item", "ai-zippy")}
        </Button>
      </div>
    </div>
  );
}
