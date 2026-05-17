import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, ToggleControl, TextControl, SelectControl, Flex, FlexItem, FlexBlock, Notice } from '@wordpress/components';
import { chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, showFilters, categories, images } = attributes;

  const onSelect = (media) => {
    const arr = Array.isArray(media) ? media : [media];
    const added = arr.map((m) => ({
      id:       m.id,
      url:      m.sizes?.large?.url || m.url,
      full:     m.url,
      alt:      m.alt || '',
      caption:  m.caption || '',
      category: '',
      colSpan:  1,
      rowSpan:  1,
    }));
    setAttributes({ images: [...images, ...added] });
  };

  const update    = (i, field, value) => setAttributes({ images: images.map((im, idx) => idx === i ? { ...im, [field]: value } : im) });
  const removeImg = (i) => setAttributes({ images: images.filter((_, idx) => idx !== i) });
  const moveImg   = (i, dir) => {
    const next = [...images];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ images: next });
  };

  const categoryOptions = [{ label: '— None —', value: '' }, ...((categories || []).map((c) => ({ label: c, value: c })))];
  const sizeOptions     = [{ label: '1 (default)', value: 1 }, { label: '2 (wide / tall)', value: 2 }];

  return (
    <>
      <InspectorControls>
        <PanelBody title="Filters" initialOpen>
          <ToggleControl label="Show category filter chips" checked={showFilters} onChange={(v) => setAttributes({ showFilters: v })} />
          <TextControl
            label="Filter categories"
            help="Comma-separated. Images can be assigned to one of these (or left blank)."
            value={(categories || []).join(', ')}
            onChange={(v) => setAttributes({ categories: v.split(',').map((s) => s.trim()).filter(Boolean) })}
          />
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
          {images.length === 0 && <Notice status="info" isDismissible={false}>Pick images, then assign category + span per image.</Notice>}
          {images.map((im, i) => (
            <div key={i} style={{ border: '1px solid #ddd', borderRadius: 4, padding: 10, marginBottom: 10 }}>
              <Flex align="center" gap={2} style={{ marginBottom: 8 }}>
                <FlexItem>
                  <img src={im.url} alt="" style={{ width: 56, height: 56, objectFit: 'cover', borderRadius: 4 }} />
                </FlexItem>
                <FlexBlock>
                  <strong style={{ fontSize: 12, display: 'block' }}>{im.alt || `Image ${i + 1}`}</strong>
                  <span style={{ fontSize: 11, color: '#7b817b' }}>{im.category || 'Uncategorized'} · {im.colSpan || 1}×{im.rowSpan || 1}</span>
                </FlexBlock>
                <FlexItem><Button icon={chevronUp}   isSmall disabled={i === 0} onClick={() => moveImg(i, -1)} label="Move up" /></FlexItem>
                <FlexItem><Button icon={chevronDown} isSmall disabled={i === images.length - 1} onClick={() => moveImg(i, 1)} label="Move down" /></FlexItem>
                <FlexItem><Button icon={trash} isSmall isDestructive onClick={() => removeImg(i)} label="Remove" /></FlexItem>
              </Flex>
              <SelectControl label="Category" value={im.category || ''} options={categoryOptions} onChange={(v) => update(i, 'category', v)} />
              <Flex gap={2}>
                <FlexBlock>
                  <SelectControl label="Width (cols)" value={im.colSpan || 1} options={sizeOptions} onChange={(v) => update(i, 'colSpan', parseInt(v, 10))} />
                </FlexBlock>
                <FlexBlock>
                  <SelectControl label="Height (rows)" value={im.rowSpan || 1} options={sizeOptions} onChange={(v) => update(i, 'rowSpan', parseInt(v, 10))} />
                </FlexBlock>
              </Flex>
              <TextControl label="Caption (lightbox)" value={im.caption || ''} onChange={(v) => update(i, 'caption', v)} />
            </div>
          ))}
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'gallery-grid-editor' })} style={{ padding: '40px 0' }}>
        <div style={{ textAlign: 'center', marginBottom: 32 }}>
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
        {showFilters && (categories || []).length > 0 && (
          <div style={{ display: 'flex', gap: 12, flexWrap: 'wrap', marginBottom: 24 }}>
            <span style={{ padding: '8px 16px', borderRadius: 999, background: '#1f4a3a', color: '#f7f6f0', fontSize: 12, letterSpacing: '0.06em' }}>All</span>
            {(categories || []).map((c) => (
              <span key={c} style={{ padding: '8px 16px', borderRadius: 999, background: '#f8f5e9', color: '#3d433d', border: '1px solid #ede9d9', fontSize: 12, letterSpacing: '0.06em' }}>{c}</span>
            ))}
          </div>
        )}
        {images.length === 0 ? (
          <div style={{ padding: 64, textAlign: 'center', border: '1px dashed #c5c1ad', borderRadius: 4, color: '#7b817b' }}>
            Add images from the sidebar.
          </div>
        ) : (
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gridAutoRows: '120px', gap: 10 }}>
            {images.map((im, i) => (
              <figure key={i} style={{ position: 'relative', overflow: 'hidden', borderRadius: 4, gridColumn: `span ${im.colSpan || 1}`, gridRow: `span ${im.rowSpan || 1}`, margin: 0 }}>
                <img src={im.url} alt={im.alt || ''} style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
                {im.category && (
                  <span style={{ position: 'absolute', top: 8, left: 8, padding: '4px 10px', borderRadius: 999, background: 'rgba(255,255,255,0.85)', fontSize: 11, letterSpacing: '0.04em' }}>{im.category}</span>
                )}
              </figure>
            ))}
          </div>
        )}
      </section>
    </>
  );
}
