import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from '@wordpress/block-editor';
import {
  PanelBody,
  Button,
  TextControl,
  RangeControl,
  ToggleControl,
  ResponsiveWrapper,
} from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const {
    imageUrl,
    imageId,
    imageAlt,
    title,
    subtitle,
    primaryButtonText,
    primaryButtonUrl,
    secondaryButtonText,
    secondaryButtonUrl,
    overlayOpacity,
    eyebrow,
    accent,
    kenBurns,
  } = attributes;

  const overlayStyle = {
    background: `linear-gradient(180deg, rgba(0,0,0,${overlayOpacity / 100 * 0.3}) 0%, rgba(0,0,0,${overlayOpacity / 100}) 100%)`,
  };

  const blockProps = useBlockProps({ className: 'carousel-slide-editor' });

  return (
    <>
      <InspectorControls>
        <PanelBody title="Background Image" initialOpen={true}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) =>
                setAttributes({
                  imageUrl: media.url,
                  imageId: media.id,
                  imageAlt: media.alt || '',
                })
              }
              allowedTypes={['image']}
              value={imageId}
              render={({ open }) => (
                <div>
                  {imageUrl ? (
                    <>
                      <ResponsiveWrapper naturalWidth={1920} naturalHeight={1080}>
                        <img src={imageUrl} alt={imageAlt} style={{ width: '100%', display: 'block' }} />
                      </ResponsiveWrapper>
                      <div style={{ marginTop: '8px', display: 'flex', gap: '8px' }}>
                        <Button variant="secondary" onClick={open} isSmall>
                          Replace Image
                        </Button>
                        <Button
                          variant="tertiary"
                          isDestructive
                          isSmall
                          onClick={() => setAttributes({ imageUrl: '', imageId: 0, imageAlt: '' })}
                        >
                          Remove
                        </Button>
                      </div>
                    </>
                  ) : (
                    <Button variant="primary" onClick={open}>
                      Select Image
                    </Button>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>

          <RangeControl
            label="Overlay Opacity (%)"
            value={overlayOpacity}
            onChange={(value) => setAttributes({ overlayOpacity: value })}
            min={0}
            max={100}
          />

          <ToggleControl
            label="Ken Burns effect"
            help="Slow zoom + pan on the background image."
            checked={kenBurns}
            onChange={(value) => setAttributes({ kenBurns: value })}
          />
        </PanelBody>

        <PanelBody title="Slide Content" initialOpen={false}>
          <TextControl
            label="Eyebrow"
            help="Small uppercase line above the title."
            value={eyebrow}
            onChange={(value) => setAttributes({ eyebrow: value })}
          />
          <TextControl
            label="Accent line"
            help="Small italic line below the title (use to highlight a key phrase)."
            value={accent}
            onChange={(value) => setAttributes({ accent: value })}
          />
        </PanelBody>

        <PanelBody title="Primary Button" initialOpen={false}>
          <TextControl
            label="Button Text"
            value={primaryButtonText}
            onChange={(value) => setAttributes({ primaryButtonText: value })}
          />
          <TextControl
            label="Button URL"
            value={primaryButtonUrl}
            onChange={(value) => setAttributes({ primaryButtonUrl: value })}
            type="url"
          />
        </PanelBody>

        <PanelBody title="Secondary Button" initialOpen={false}>
          <TextControl
            label="Button Text"
            value={secondaryButtonText}
            onChange={(value) => setAttributes({ secondaryButtonText: value })}
          />
          <TextControl
            label="Button URL"
            value={secondaryButtonUrl}
            onChange={(value) => setAttributes({ secondaryButtonUrl: value })}
            type="url"
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div
          className="carousel-slide-editor__bg"
          style={{
            backgroundImage: imageUrl ? `url(${imageUrl})` : 'none',
            backgroundColor: !imageUrl ? '#1f4a3a' : undefined,
          }}
        />

        <div className="carousel-slide-editor__overlay" style={overlayStyle} />

        <div className="carousel-slide-editor__content">
          <div className="carousel-slide-editor__inner">
            {eyebrow && (
              <div className="carousel-slide-editor__eyebrow">{eyebrow}</div>
            )}
            <RichText
              tagName="h1"
              className="carousel-slide-editor__title"
              value={title}
              onChange={(value) => setAttributes({ title: value })}
              placeholder="Enter slide title…"
              allowedFormats={['core/italic']}
            />
            {accent && (
              <div className="carousel-slide-editor__accent">{accent}</div>
            )}
            <RichText
              tagName="p"
              className="carousel-slide-editor__subtitle"
              value={subtitle}
              onChange={(value) => setAttributes({ subtitle: value })}
              placeholder="Enter subtitle…"
              allowedFormats={['core/bold', 'core/italic']}
            />
            <div className="carousel-slide-editor__buttons">
              {primaryButtonText && (
                <span className="carousel-slide-editor__btn carousel-slide-editor__btn--primary">
                  {primaryButtonText}
                </span>
              )}
              {secondaryButtonText && (
                <span className="carousel-slide-editor__btn carousel-slide-editor__btn--secondary">
                  {secondaryButtonText}
                </span>
              )}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
