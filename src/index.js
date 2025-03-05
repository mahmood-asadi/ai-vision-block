import { registerBlockType } from "@wordpress/blocks";
import { TextControl, TextareaControl, Button } from "@wordpress/components";
import { useState } from "@wordpress/element";
import { useBlockProps } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import metadata from "../block.json";

registerBlockType(metadata.name, {
  ...metadata,
  edit: (props) => {
    const { attributes, setAttributes } = props;
    const blockProps = useBlockProps();
    const [isLoading, setIsLoading] = useState(false);

    const generateImage = async () => {
      if (!attributes.prompt) return;

      setIsLoading(true);
      try {
        // Make an API request to Pollinations to generate the image
        const width = attributes.width || 1280;
        const height = attributes.height || 1280;
        const response = await fetch(
          "https://image.pollinations.ai/prompt/" +
            encodeURIComponent(attributes.prompt) +
            "?width=" +
            width +
            "&height=" +
            height +
            "&nologo=true&enhance=true"
        );
        if (response.status === 500) {
          throw new Error("Server error: Failed to fetch image (500)");
        }
        if (!response.ok) {
          throw new Error("Failed to fetch image");
        }

        const imageUrl = response.url; // Get image URL from response
        setAttributes({ imageUrl });
      } catch (error) {
        console.error("Error generating image:", error);
      } finally {
        setIsLoading(false);
      }
    };

    return (
      <div {...blockProps}>
        <TextareaControl
          label={__("Prompt", "ai-vision-block")}
          value={attributes.prompt}
          onChange={(value) => setAttributes({ prompt: value })}
        />
        <TextControl
          label={__("Width", "ai-vision-block")}
          value={attributes.width}
          onChange={(value) => setAttributes({ width: value })}
        />
        <TextControl
          label={__("Height", "ai-vision-block")}
          value={attributes.height}
          onChange={(value) => setAttributes({ height: value })}
        />
        <Button isPrimary onClick={generateImage} disabled={isLoading}>
          {isLoading
            ? __("Generating...", "ai-vision-block")
            : __("Generate Image", "ai-vision-block")}
        </Button>
        {attributes.imageUrl && (
          <figure>
            <img
              src={attributes.imageUrl}
              alt={__("Generated Image", "ai-vision-block")}
              style={{ width: "100%" }}
            />
          </figure>
        )}
      </div>
    );
  },

  save: (props) => {
    const { attributes } = props;
    const blockProps = useBlockProps.save();

    return (
      <figure {...blockProps} className="wp-block-image size-full">
        {attributes.imageUrl && (
          <img
            src={attributes.imageUrl}
            alt={__("Generated Image", "ai-vision-block")}
          />
        )}
      </figure>
    );
  },
});
