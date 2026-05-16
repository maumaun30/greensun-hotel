import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button, Card, CardBody, CardHeader, Flex, FlexItem, FlexBlock } from '@wordpress/components';
import { plus, chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { channels, mapEmbed } = attributes;

  const update = (i, field, value) => setAttributes({ channels: channels.map((c, idx) => idx === i ? { ...c, [field]: value } : c) });
  const add    = () => setAttributes({ channels: [...channels, { title: 'New channel', lines: '' }] });
  const remove = (i) => setAttributes({ channels: channels.filter((_, idx) => idx !== i) });
  const move   = (i, dir) => {
    const next = [...channels];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ channels: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title="Map embed" initialOpen={false}>
          <TextareaControl
            label="iframe HTML"
            help="Optional. A Google Maps / OpenStreetMap iframe rendered below the channels."
            value={mapEmbed}
            onChange={(v) => setAttributes({ mapEmbed: v })}
            rows={6}
          />
        </PanelBody>
        <PanelBody title={`Channels (${channels.length})`} initialOpen>
          {channels.map((c, index) => (
            <Card key={index} style={{ marginBottom: 12 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>{c.title || `Channel ${index + 1}`}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp}   isSmall disabled={index === 0} onClick={() => move(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === channels.length - 1} onClick={() => move(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={channels.length <= 1} onClick={() => remove(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <TextControl label="Title" value={c.title} onChange={(v) => update(index, 'title', v)} />
                <TextareaControl label="Lines (one per line)" value={c.lines} onChange={(v) => update(index, 'lines', v)} rows={3} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={add} style={{ width: '100%', justifyContent: 'center' }}>Add channel</Button>
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: 'contact-channels-editor' })} style={{ padding: '20px 0' }}>
        {channels.map((c, i) => (
          <div key={i} style={{ paddingBottom: 22, marginBottom: 22, borderBottom: '1px solid #ede9d9' }}>
            <div style={{ fontFamily: '"JetBrains Mono", monospace', fontSize: 13, color: '#7b817b', marginBottom: 8 }}>{c.title}</div>
            <div style={{ whiteSpace: 'pre-line', lineHeight: 1.7, color: '#1a1f1a' }}>{c.lines}</div>
          </div>
        ))}
      </div>
    </>
  );
}
