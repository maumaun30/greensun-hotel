import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, RangeControl, ToggleControl, TextControl, Flex, FlexItem, FlexBlock } from '@wordpress/components';
import { chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, columns, images, showCaptions } = attributes;

  const onSelect = (media) => {
    const arr = Array.isArray(media) ? media : [media];
    const added = arr.map((m) => ({
      id:      m.id,
      url:     m.sizes?.large?.url || m.url,
      full:    m.url,
      alt:     m.alt || '',
      caption: m.caption || '',
    }));
    setAttributes({ images: [...images, ...added] });
  };

  const removeImage = (i) => setAttributes({ images: images.filter((_, idx) => idx !== i) });
  const updateCaption = (i, value) => setAttributes({ images: images.map((im, idx) => idx === i ? { ...im, caption: value } : im) });
  const moveImage = (i, dir) => {
    const next = [...images];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ images: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title="Layout" initialOpen>
          <RangeControl label="Columns" value={columns} min={2} max={4} onChange={(v) => setAttributes({ columns: v })} />
          <ToggleControl label="Show captions" checked={showCaptions} onChange={(v) => setAttributes({ showCaptions: v })} />
        </PanelBody>
        <PanelBody title={`Images (${images.length})`} initialOpen>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={onSelect}
              allowedTypes={['image']}
              multiple
              gallery
              render={({ open }) => (
                <Button variant="secondary" onClick={open} style={{ width: '100%', justifyContent: 'center', marginBottom: 12 }}>
                  Add images
                </Button>
              )}
            />
          </MediaUploadCheck>
          {images.map((im, i) => (
            <div key={i} style={{ border: '1px solid #ddd', borderRadius: 4, padding: 8, marginBottom: 8 }}>
              <Flex align="center" gap={2}>
                <FlexItem>
                  <img src={im.url} alt="" style={{ width: 56, height: 56, objectFit: 'cover', borderRadius: 4 }} />
                </FlexItem>
                <FlexBlock>
                  {showCaptions && (
                    <TextControl label="Caption" value={im.caption || ''} onChange={(v) => updateCaption(i, v)} />
                  )}
                </FlexBlock>
                <FlexItem><Button icon={chevronUp} isSmall disabled={i === 0} onClick={() => moveImage(i, -1)} label="Move up" /></FlexItem>
                <FlexItem><Button icon={chevronDown} isSmall disabled={i === images.length - 1} onClick={() => moveImage(i, 1)} label="Move down" /></FlexItem>
                <FlexItem><Button icon={trash} isSmall isDestructive onClick={() => removeImage(i)} label="Remove" /></FlexItem>
              </Flex>
            </div>
          ))}
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'gallery-grid-editor' })} style={{ padding: '40px 0' }}>
        <div style={{ textAlign: 'center', marginBottom: 56 }}>
          <RichText tagName="div" className="eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Eyebrow…" allowedFormats={[]} />
          <RichText
            tagName="h2"
            className="display"
            style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 14 }}
            value={sectionTitle}
            onChange={(v) => setAttributes({ sectionTitle: v })}
            placeholder="Section title…"
            allowedFormats={['core/italic']}
          />
        </div>
        {images.length === 0 ? (
          <div style={{ padding: 64, textAlign: 'center', border: '1px dashed #c5c1ad', borderRadius: 14, color: '#7b817b' }}>
            Add images from the sidebar.
          </div>
        ) : (
          <div style={{ columnCount: columns, columnGap: 14 }}>
            {images.map((im, i) => (
              <figure key={i} style={{ breakInside: 'avoid', marginBottom: 14 }}>
                <img src={im.url} alt={im.alt || ''} style={{ width: '100%', display: 'block', borderRadius: 10 }} />
                {showCaptions && im.caption ? (
                  <figcaption style={{ marginTop: 6, fontSize: 12, color: '#7b817b' }}>{im.caption}</figcaption>
                ) : null}
              </figure>
            ))}
          </div>
        )}
      </section>
    </>
  );
}
