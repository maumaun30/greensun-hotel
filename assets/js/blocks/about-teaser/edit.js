import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, title, subtitle, ctaText, ctaUrl, imageUrl, imageId, imageAlt } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Image" initialOpen>
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
        </PanelBody>
        <PanelBody title="Call to action" initialOpen={false}>
          <TextControl label="Button text" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} />
          <TextControl label="Button URL" value={ctaUrl} type="url" onChange={(v) => setAttributes({ ctaUrl: v })} />
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'about-teaser-editor' })} style={{ display: 'grid', gridTemplateColumns: '1.05fr 1fr', gap: 60, alignItems: 'center', padding: '32px 0' }}>
        <div>
          <RichText
            tagName="div"
            className="eyebrow"
            value={eyebrow}
            onChange={(v) => setAttributes({ eyebrow: v })}
            placeholder="Eyebrow…"
            allowedFormats={[]}
          />
          <RichText
            tagName="h2"
            className="display"
            style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 18, maxWidth: '14ch' }}
            value={title}
            onChange={(v) => setAttributes({ title: v })}
            placeholder="Section title…"
            allowedFormats={['core/italic']}
          />
          <RichText
            tagName="p"
            style={{ marginTop: 18, color: 'var(--ink-2, #3d433d)', lineHeight: 1.7, maxWidth: '50ch' }}
            value={subtitle}
            onChange={(v) => setAttributes({ subtitle: v })}
            placeholder="Subtitle…"
            allowedFormats={['core/bold', 'core/italic']}
          />
          <div style={{ marginTop: 28 }}>
            <span style={{ display: 'inline-block', padding: '12px 24px', background: '#1f4a3a', color: '#f7f6f0', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999 }}>
              {ctaText || 'Button'}
            </span>
          </div>
        </div>
        <div style={{ background: '#ede9d9', aspectRatio: '4 / 5', overflow: 'hidden' }}>
          {imageUrl ? <img src={imageUrl} alt={imageAlt} style={{ width: '100%', height: '100%', objectFit: 'cover' }} /> : <div style={{ width: '100%', height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#7b817b' }}>Image placeholder</div>}
        </div>
      </section>
    </>
  );
}
