import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button, Card, CardBody, CardHeader, Flex, FlexItem, FlexBlock } from '@wordpress/components';
import { plus, chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, entries } = attributes;

  const update = (i, field, value) => setAttributes({ entries: entries.map((e, idx) => idx === i ? { ...e, [field]: value } : e) });
  const add    = () => setAttributes({ entries: [...entries, { year: 'Year', title: 'Milestone', body: 'Describe what happened.' }] });
  const remove = (i) => setAttributes({ entries: entries.filter((_, idx) => idx !== i) });
  const move   = (i, dir) => {
    const next = [...entries];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ entries: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={`Entries (${entries.length})`} initialOpen>
          {entries.map((e, index) => (
            <Card key={index} style={{ marginBottom: 12 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>{e.year || `Entry ${index + 1}`}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp}   isSmall disabled={index === 0} onClick={() => move(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === entries.length - 1} onClick={() => move(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={entries.length <= 1} onClick={() => remove(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <TextControl label="Year / label" value={e.year} onChange={(v) => update(index, 'year', v)} />
                <TextControl label="Title" value={e.title} onChange={(v) => update(index, 'title', v)} />
                <TextareaControl label="Body" value={e.body} onChange={(v) => update(index, 'body', v)} rows={3} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={add} style={{ width: '100%', justifyContent: 'center' }}>Add entry</Button>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'timeline-editor' })} style={{ padding: '40px 0', display: 'grid', gridTemplateColumns: '1fr 1.4fr', gap: 60 }}>
        <div>
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
        </div>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
          {entries.map((e, i) => (
            <article key={i} style={{ display: 'grid', gridTemplateColumns: '120px 1fr', gap: 24, paddingBottom: 24, borderBottom: '1px solid #ede9d9' }}>
              <div className="display" style={{ fontSize: 36, color: '#1f4a3a' }}>{e.year}</div>
              <div>
                <div className="display" style={{ fontSize: 24 }}>{e.title}</div>
                <p style={{ marginTop: 8, color: '#3d433d', lineHeight: 1.7 }}>{e.body}</p>
              </div>
            </article>
          ))}
        </div>
      </section>
    </>
  );
}
