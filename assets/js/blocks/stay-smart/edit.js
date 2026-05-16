import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl, Button, Flex, FlexBlock, FlexItem } from '@wordpress/components';
import { plus, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { title, body, stats, showOrnament } = attributes;

  const update = (i, field, value) => setAttributes({ stats: stats.map((s, idx) => idx === i ? { ...s, [field]: value } : s) });
  const add    = () => setAttributes({ stats: [...stats, { value: '00', label: 'Label' }] });
  const remove = (i) => setAttributes({ stats: stats.filter((_, idx) => idx !== i) });

  return (
    <>
      <InspectorControls>
        <PanelBody title="Layout" initialOpen>
          <ToggleControl label="Show leaf ornament" checked={showOrnament} onChange={(v) => setAttributes({ showOrnament: v })} />
        </PanelBody>
        <PanelBody title={`Stats (${stats.length})`} initialOpen>
          {stats.map((s, i) => (
            <Flex key={i} gap={2} style={{ marginBottom: 8 }}>
              <FlexBlock><TextControl label="Value" hideLabelFromVision value={s.value} onChange={(v) => update(i, 'value', v)} placeholder="Value" /></FlexBlock>
              <FlexBlock><TextControl label="Label" hideLabelFromVision value={s.label} onChange={(v) => update(i, 'label', v)} placeholder="Label" /></FlexBlock>
              <FlexItem><Button icon={trash} isSmall isDestructive onClick={() => remove(i)} label="Remove" /></FlexItem>
            </Flex>
          ))}
          <Button icon={plus} variant="secondary" onClick={add} style={{ width: '100%', justifyContent: 'center' }}>Add stat</Button>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'stay-smart-editor' })} style={{ padding: '60px 40px', background: '#1f4a3a', color: '#f7f6f0', borderRadius: 8, display: 'grid', gridTemplateColumns: '1fr 1.1fr', gap: 48, alignItems: 'center' }}>
        <RichText
          tagName="h2"
          className="display"
          style={{ fontSize: 'clamp(36px, 5vw, 64px)', margin: 0, maxWidth: '12ch', color: '#e8c46a' }}
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Headline…"
          allowedFormats={['core/italic']}
        />
        <div>
          <RichText
            tagName="p"
            style={{ fontSize: 17, lineHeight: 1.75, color: 'rgba(255,255,255,.78)', maxWidth: 520, margin: 0 }}
            value={body}
            onChange={(v) => setAttributes({ body: v })}
            placeholder="Body copy…"
            allowedFormats={['core/bold', 'core/italic']}
          />
          <div style={{ marginTop: 32, display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 18, maxWidth: 460 }}>
            {stats.map((s, i) => (
              <div key={i} style={{ borderTop: '1px solid rgba(255,255,255,.18)', paddingTop: 12 }}>
                <div className="display" style={{ fontSize: 26, color: '#f7f6f0' }}>{s.value}</div>
                <div style={{ fontFamily: '"JetBrains Mono", monospace', fontSize: 12, color: 'rgba(255,255,255,.6)', marginTop: 4 }}>{s.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </>
  );
}
