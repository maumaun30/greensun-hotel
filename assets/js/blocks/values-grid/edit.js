import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, SelectControl, RangeControl, Button, Card, CardBody, CardHeader, Flex, FlexItem, FlexBlock } from '@wordpress/components';
import { plus, chevronUp, chevronDown, trash } from '@wordpress/icons';

const BACKGROUNDS = {
  sage:  'var(--sage-2, #e0e9d8)',
  paper: 'var(--paper, #f8f5e9)',
  bone:  'var(--bone, #ede9d9)',
  none:  'transparent',
};

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, background, columns, items } = attributes;
  const bg = BACKGROUNDS[background] || BACKGROUNDS.sage;

  const update = (i, field, value) => setAttributes({ items: items.map((it, idx) => idx === i ? { ...it, [field]: value } : it) });
  const add    = () => setAttributes({ items: [...items, { title: 'New value', body: 'Describe it.' }] });
  const remove = (i) => setAttributes({ items: items.filter((_, idx) => idx !== i) });
  const move   = (i, dir) => {
    const next = [...items];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ items: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title="Layout" initialOpen>
          <SelectControl
            label="Background"
            value={background}
            options={[
              { label: 'Sage',  value: 'sage' },
              { label: 'Paper', value: 'paper' },
              { label: 'Bone',  value: 'bone' },
              { label: 'None',  value: 'none' },
            ]}
            onChange={(v) => setAttributes({ background: v })}
          />
          <RangeControl label="Columns" value={columns} min={2} max={4} onChange={(v) => setAttributes({ columns: v })} />
        </PanelBody>
        <PanelBody title={`Values (${items.length})`} initialOpen>
          {items.map((it, index) => (
            <Card key={index} style={{ marginBottom: 12 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>0{index + 1}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp}   isSmall disabled={index === 0} onClick={() => move(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === items.length - 1} onClick={() => move(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={items.length <= 1} onClick={() => remove(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <TextControl label="Title" value={it.title} onChange={(v) => update(index, 'title', v)} />
                <TextareaControl label="Body" value={it.body} onChange={(v) => update(index, 'body', v)} rows={3} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={add} style={{ width: '100%', justifyContent: 'center' }}>Add value</Button>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'values-grid-editor' })} style={{ padding: '40px', background: bg }}>
        <RichText tagName="div" className="eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Eyebrow…" allowedFormats={[]} />
        <RichText
          tagName="h2"
          className="display"
          style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 22, maxWidth: '18ch' }}
          value={sectionTitle}
          onChange={(v) => setAttributes({ sectionTitle: v })}
          placeholder="Section title…"
          allowedFormats={['core/italic']}
        />
        <div style={{ display: 'grid', gridTemplateColumns: `repeat(${columns}, 1fr)`, gap: 28, marginTop: 60 }}>
          {items.map((it, i) => (
            <div key={i} style={{ background: '#fff', padding: 36, borderRadius: 4, border: '1px solid #ede9d9' }}>
              <div className="display" style={{ fontSize: 56, color: '#d8a04c', lineHeight: 1 }}>0{i + 1}</div>
              <h3 className="display" style={{ fontSize: 26, marginTop: 8 }}>{it.title}</h3>
              <p style={{ marginTop: 14, color: '#3d433d', lineHeight: 1.75, fontSize: 15.5 }}>{it.body}</p>
            </div>
          ))}
        </div>
      </section>
    </>
  );
}
