import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
  PanelBody,
  TextControl,
  Button,
  RangeControl,
  Card,
  CardBody,
  CardHeader,
  Flex,
  FlexItem,
  FlexBlock,
} from '@wordpress/components';
import { plus, chevronUp, chevronDown, trash } from '@wordpress/icons';

function SvgPlaceholder() {
  return (
    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" style={{ width: 28, height: 28, opacity: 0.3 }}>
      <rect x="8" y="8" width="48" height="48" rx="4" fill="none" stroke="currentColor" strokeWidth="3" strokeDasharray="6 3" />
      <text x="32" y="38" textAnchor="middle" fontSize="20" fill="currentColor">SVG</text>
    </svg>
  );
}

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, columns, items } = attributes;

  const updateItem = (i, field, value) => {
    const updated = items.map((it, idx) => (idx === i ? { ...it, [field]: value } : it));
    setAttributes({ items: updated });
  };
  const setItemSvg = (i, media) => updateItem(i, 'svgUrl', media.url) || setAttributes({ items: items.map((it, idx) => idx === i ? { ...it, svgId: media.id, svgUrl: media.url } : it) });
  const clearItemSvg = (i) => setAttributes({ items: items.map((it, idx) => idx === i ? { ...it, svgId: 0, svgUrl: '' } : it) });
  const addItem = () => setAttributes({ items: [...items, { svgId: 0, svgUrl: '', label: 'New amenity' }] });
  const removeItem = (i) => setAttributes({ items: items.filter((_, idx) => idx !== i) });
  const moveItem = (i, dir) => {
    const next = [...items];
    const target = i + dir;
    if (target < 0 || target >= next.length) return;
    [next[i], next[target]] = [next[target], next[i]];
    setAttributes({ items: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title="Section" initialOpen>
          <RangeControl label="Columns" value={columns} min={2} max={6} onChange={(v) => setAttributes({ columns: v })} />
        </PanelBody>
        <PanelBody title={`Amenities (${items.length})`} initialOpen>
          {items.map((item, index) => (
            <Card key={index} style={{ marginBottom: 12 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>Item {index + 1}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp} isSmall disabled={index === 0} onClick={() => moveItem(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === items.length - 1} onClick={() => moveItem(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={items.length <= 1} onClick={() => removeItem(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => setItemSvg(index, media)}
                    allowedTypes={['image/svg+xml', 'image']}
                    value={item.svgId}
                    render={({ open }) => (
                      <Flex align="center" gap={2} style={{ marginBottom: 12 }}>
                        <FlexItem>
                          <div style={{ width: 48, height: 48, borderRadius: 8, border: '1px solid #ddd', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden', background: '#fff' }}>
                            {item.svgUrl ? <img src={item.svgUrl} alt="" style={{ width: 28, height: 28, objectFit: 'contain' }} /> : <SvgPlaceholder />}
                          </div>
                        </FlexItem>
                        <FlexBlock>
                          <Button variant="secondary" isSmall onClick={open} style={{ marginBottom: 4, display: 'block', width: '100%' }}>
                            {item.svgUrl ? 'Replace icon' : 'Upload icon'}
                          </Button>
                          {item.svgUrl && (
                            <Button variant="tertiary" isSmall isDestructive onClick={() => clearItemSvg(index)} style={{ display: 'block', width: '100%' }}>
                              Remove
                            </Button>
                          )}
                        </FlexBlock>
                      </Flex>
                    )}
                  />
                </MediaUploadCheck>
                <TextControl label="Label" value={item.label} onChange={(v) => updateItem(index, 'label', v)} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={addItem} style={{ width: '100%', justifyContent: 'center' }}>Add amenity</Button>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'amenities-grid-editor' })} style={{ padding: '40px 0' }}>
        <div style={{ textAlign: 'center', marginBottom: 48 }}>
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
            style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 12, maxWidth: '20ch', marginInline: 'auto' }}
            value={sectionTitle}
            onChange={(v) => setAttributes({ sectionTitle: v })}
            placeholder="Section title…"
            allowedFormats={['core/italic']}
          />
        </div>
        <div style={{ display: 'grid', gridTemplateColumns: `repeat(${columns}, 1fr)`, gap: 32 }}>
          {items.map((item, i) => (
            <div key={i} style={{ textAlign: 'center', padding: 16 }}>
              <div style={{ width: 64, height: 64, marginInline: 'auto', borderRadius: 999, background: '#ede9d9', display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 16 }}>
                {item.svgUrl ? <img src={item.svgUrl} alt="" style={{ width: 32, height: 32, objectFit: 'contain' }} /> : <SvgPlaceholder />}
              </div>
              <div style={{ fontSize: 14, color: '#3d433d', lineHeight: 1.4 }}>{item.label}</div>
            </div>
          ))}
        </div>
      </section>
    </>
  );
}
