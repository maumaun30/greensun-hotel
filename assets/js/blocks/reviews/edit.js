import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, RangeControl, Button, Card, CardBody, CardHeader, Flex, FlexItem, FlexBlock } from '@wordpress/components';
import { plus, chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, autoplayMs, reviews } = attributes;

  const update = (i, field, value) => setAttributes({ reviews: reviews.map((r, idx) => idx === i ? { ...r, [field]: value } : r) });
  const add    = () => setAttributes({ reviews: [...reviews, { name: 'Guest', caption: 'Verified stay', text: 'A kind word.', stars: 5 }] });
  const remove = (i) => setAttributes({ reviews: reviews.filter((_, idx) => idx !== i) });
  const move   = (i, dir) => {
    const next = [...reviews];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ reviews: next });
  };

  const first = reviews[0] || {};

  return (
    <>
      <InspectorControls>
        <PanelBody title="Layout" initialOpen>
          <RangeControl label="Autoplay interval (ms)" value={autoplayMs} min={4000} max={15000} step={500} onChange={(v) => setAttributes({ autoplayMs: v })} />
        </PanelBody>
        <PanelBody title={`Reviews (${reviews.length})`} initialOpen>
          {reviews.map((r, index) => (
            <Card key={index} style={{ marginBottom: 12 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>{r.name || `Review ${index + 1}`}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp}   isSmall disabled={index === 0} onClick={() => move(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === reviews.length - 1} onClick={() => move(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={reviews.length <= 1} onClick={() => remove(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <TextControl     label="Name"    value={r.name}    onChange={(v) => update(index, 'name', v)} />
                <TextControl     label="Caption" value={r.caption} onChange={(v) => update(index, 'caption', v)} />
                <TextareaControl label="Quote"   value={r.text}    onChange={(v) => update(index, 'text', v)} rows={4} />
                <RangeControl    label="Stars"   value={r.stars}   min={1} max={5} onChange={(v) => update(index, 'stars', v)} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={add} style={{ width: '100%', justifyContent: 'center' }}>Add review</Button>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'reviews-editor' })} style={{ padding: 40, background: '#e0e9d8', position: 'relative', overflow: 'hidden', borderRadius: 8 }}>
        <div style={{ position: 'absolute', left: 30, top: 20, fontFamily: '"Cormorant Garamond", serif', fontSize: 240, lineHeight: 0.7, color: '#e8c46a', opacity: 0.25 }}>“</div>
        <div style={{ position: 'relative' }}>
          <RichText tagName="div" className="eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Eyebrow…" allowedFormats={[]} />
          <RichText
            tagName="h2"
            className="display"
            style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 18, maxWidth: '14ch' }}
            value={sectionTitle}
            onChange={(v) => setAttributes({ sectionTitle: v })}
            placeholder="Section title…"
            allowedFormats={['core/italic']}
          />
          <div style={{ marginTop: 40 }}>
            <div style={{ color: '#d8a04c', display: 'flex', gap: 6, marginBottom: 18 }}>
              {Array.from({ length: first.stars || 5 }).map((_, k) => (
                <svg key={k} width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1 L12.5 7 L19 7.6 L14 12 L15.6 18.5 L10 15 L4.4 18.5 L6 12 L1 7.6 L7.5 7 Z"/></svg>
              ))}
            </div>
            <p className="display" style={{ fontSize: 'clamp(20px, 2.2vw, 28px)', lineHeight: 1.45, fontStyle: 'italic', color: '#0f2018', maxWidth: 880 }}>
              “{first.text || 'A kind word…'}”
            </p>
            <div style={{ marginTop: 22, display: 'flex', alignItems: 'center', gap: 14 }}>
              <div style={{ width: 44, height: 44, borderRadius: '50%', background: '#1f4a3a', color: '#e8c46a', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontFamily: '"Cormorant Garamond", serif', fontSize: 18 }}>{(first.name || '?')[0]}</div>
              <div>
                <div className="display" style={{ fontSize: 20 }}>{first.name}</div>
                <div style={{ fontFamily: '"JetBrains Mono", monospace', fontSize: 12, color: '#7b817b' }}>{first.caption}</div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
