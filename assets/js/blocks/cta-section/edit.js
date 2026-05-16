import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, Button, RangeControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, title, subtitle, ctaText, ctaUrl, imageUrl, imageId, imageAlt, overlayOpacity } = attributes;

  const overlayAlpha = Math.max(0, Math.min(100, overlayOpacity)) / 100;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Background image" initialOpen>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ imageUrl: media.url, imageId: media.id, imageAlt: media.alt || '' })}
              allowedTypes={['image']}
              value={imageId}
              render={({ open }) => (
                <div>
                  {imageUrl && <img src={imageUrl} alt={imageAlt} style={{ display: 'block', maxWidth: '100%', marginBottom: 8 }} />}
                  <Button variant="secondary" onClick={open}>{imageUrl ? 'Replace image' : 'Select image'}</Button>
                  {imageUrl && (
                    <Button variant="tertiary" isDestructive onClick={() => setAttributes({ imageUrl: '', imageId: 0, imageAlt: '' })} style={{ marginLeft: 8 }}>
                      Remove
                    </Button>
                  )}
                </div>
              )}
            />
          </MediaUploadCheck>
          <RangeControl
            label="Overlay opacity"
            value={overlayOpacity}
            min={0}
            max={100}
            onChange={(v) => setAttributes({ overlayOpacity: v })}
          />
        </PanelBody>
        <PanelBody title="Call to action" initialOpen={false}>
          <TextControl label="Button text" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} />
          <TextControl label="Button URL" value={ctaUrl} type="url" onChange={(v) => setAttributes({ ctaUrl: v })} />
        </PanelBody>
      </InspectorControls>

      <section
        {...useBlockProps({ className: 'cta-section-editor' })}
        style={{
          position: 'relative',
          padding: '120px 40px',
          textAlign: 'center',
          color: '#f7f6f0',
          backgroundImage: imageUrl ? `linear-gradient(rgba(15,32,24,${overlayAlpha}), rgba(15,32,24,${overlayAlpha})), url(${imageUrl})` : 'none',
          backgroundColor: imageUrl ? 'transparent' : '#1f4a3a',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
        }}
      >
        <RichText
          tagName="div"
          className="eyebrow"
          value={eyebrow}
          onChange={(v) => setAttributes({ eyebrow: v })}
          placeholder="Eyebrow…"
          allowedFormats={[]}
          style={{ color: '#e8c46a' }}
        />
        <RichText
          tagName="h2"
          className="display"
          style={{ fontSize: 'clamp(40px, 6vw, 88px)', marginTop: 18, maxWidth: '18ch', marginInline: 'auto' }}
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="CTA title…"
          allowedFormats={['core/italic']}
        />
        <RichText
          tagName="p"
          style={{ marginTop: 22, lineHeight: 1.7, maxWidth: '52ch', marginInline: 'auto', opacity: 0.92 }}
          value={subtitle}
          onChange={(v) => setAttributes({ subtitle: v })}
          placeholder="Subtitle…"
          allowedFormats={['core/bold', 'core/italic']}
        />
        <div style={{ marginTop: 36 }}>
          <span style={{ display: 'inline-block', padding: '14px 28px', background: '#e8c46a', color: '#1f4a3a', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999, fontWeight: 600 }}>
            {ctaText || 'Button'}
          </span>
        </div>
      </section>
    </>
  );
}
