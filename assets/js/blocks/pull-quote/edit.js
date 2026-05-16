import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { quote, author, role, alignment } = attributes;
  const textAlign = ['left', 'center', 'right'].includes(alignment) ? alignment : 'center';

  return (
    <>
      <InspectorControls>
        <PanelBody title="Layout" initialOpen>
          <SelectControl
            label="Alignment"
            value={alignment}
            options={[
              { label: 'Left',   value: 'left' },
              { label: 'Center', value: 'center' },
              { label: 'Right',  value: 'right' },
            ]}
            onChange={(v) => setAttributes({ alignment: v })}
          />
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'pull-quote-editor' })} style={{ padding: '40px 0', textAlign }}>
        <RichText
          tagName="blockquote"
          className="display"
          style={{ fontSize: 'clamp(32px, 4.6vw, 60px)', lineHeight: 1.2, margin: 0, maxWidth: '24ch', marginInline: textAlign === 'center' ? 'auto' : (textAlign === 'right' ? '0 0 0 auto' : '0') }}
          value={quote}
          onChange={(v) => setAttributes({ quote: v })}
          placeholder="“The quote…”"
          allowedFormats={['core/italic', 'core/bold']}
        />
        <div style={{ marginTop: 24 }}>
          <RichText
            tagName="div"
            value={author}
            onChange={(v) => setAttributes({ author: v })}
            placeholder="Author"
            allowedFormats={[]}
            style={{ fontSize: 14, fontWeight: 600 }}
          />
          <RichText
            tagName="div"
            value={role}
            onChange={(v) => setAttributes({ role: v })}
            placeholder="Role"
            allowedFormats={[]}
            style={{ fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', color: '#7b817b', marginTop: 4 }}
          />
        </div>
      </section>
    </>
  );
}
