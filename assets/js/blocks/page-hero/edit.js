import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, RangeControl, ToggleControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, title, subtitle, imageUrl, imageId, imageAlt, minHeight, overlayOpacity, kenBurns } = attributes;
  const alpha = Math.max(0, Math.min(100, overlayOpacity)) / 100;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Background" initialOpen>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ imageUrl: media.url, imageId: media.id, imageAlt: media.alt || '' })}
              allowedTypes={['image']}
              value={imageId}
              render={({ open }) => (
                <div>
                  {imageUrl && <img src={imageUrl} alt={imageAlt} style={{ display: 'block', maxWidth: '100%', marginBottom: 8, borderRadius: 4 }} />}
                  <Button variant="secondary" onClick={open}>{imageUrl ? 'Replace image' : 'Select image'}</Button>
                  {imageUrl && <Button variant="tertiary" isDestructive onClick={() => setAttributes({ imageUrl: '', imageId: 0, imageAlt: '' })} style={{ marginLeft: 8 }}>Remove</Button>}
                </div>
              )}
            />
          </MediaUploadCheck>
          <ToggleControl label="Ken Burns drift" checked={kenBurns} onChange={(v) => setAttributes({ kenBurns: v })} />
        </PanelBody>
        <PanelBody title="Layout" initialOpen={false}>
          <RangeControl label="Min height (% of viewport)" value={minHeight} min={60} max={100} onChange={(v) => setAttributes({ minHeight: v })} />
          <RangeControl label="Overlay opacity" value={overlayOpacity} min={0} max={100} onChange={(v) => setAttributes({ overlayOpacity: v })} />
        </PanelBody>
      </InspectorControls>

      <section
        {...useBlockProps({ className: 'page-hero-editor' })}
        style={{
          position: 'relative',
          minHeight: 360,
          padding: '40px',
          color: '#f7f6f0',
          backgroundImage: imageUrl
            ? `linear-gradient(rgba(13,42,32,${alpha * 0.6}), rgba(13,42,32,${alpha})), url(${imageUrl})`
            : 'linear-gradient(160deg, #1f4a3a, #0f2018)',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          borderRadius: 8,
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'flex-end',
        }}
      >
        <RichText
          tagName="div"
          className="eyebrow"
          style={{ color: '#e8c46a', marginBottom: 18 }}
          value={eyebrow}
          onChange={(v) => setAttributes({ eyebrow: v })}
          placeholder="Eyebrow…"
          allowedFormats={[]}
        />
        <RichText
          tagName="h1"
          className="display"
          style={{ fontSize: 'clamp(40px, 7vw, 96px)', margin: 0, maxWidth: '14ch', fontWeight: 500 }}
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Page title…"
          allowedFormats={['core/italic']}
        />
        <RichText
          tagName="p"
          style={{ maxWidth: 620, marginTop: 24, color: 'rgba(255,255,255,.85)', fontSize: 17, lineHeight: 1.7 }}
          value={subtitle}
          onChange={(v) => setAttributes({ subtitle: v })}
          placeholder="Subtitle…"
          allowedFormats={['core/bold', 'core/italic']}
        />
      </section>
    </>
  );
}
