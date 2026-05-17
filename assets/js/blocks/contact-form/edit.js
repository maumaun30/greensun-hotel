import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { title, subtitle, submitText, successTitle, successText, subjects } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Form" initialOpen>
          <TextControl label="Submit button text" value={submitText}   onChange={(v) => setAttributes({ submitText: v })} />
          <TextControl label="Success title"      value={successTitle} onChange={(v) => setAttributes({ successTitle: v })} />
          <TextControl label="Success body"       value={successText}  onChange={(v) => setAttributes({ successText: v })} />
        </PanelBody>
        <PanelBody title="Subject options" initialOpen={false}>
          <TextControl
            label="Subjects (comma-separated)"
            value={(subjects || []).join(', ')}
            onChange={(v) => setAttributes({ subjects: v.split(',').map((s) => s.trim()).filter(Boolean) })}
            help="Options shown in the Subject dropdown."
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: 'contact-form-editor' })} style={{ background: '#f8f5e9', border: '1px solid #ede9d9', borderRadius: 4, padding: 40 }}>
        <RichText
          tagName="h3"
          className="display"
          style={{ fontSize: 32, margin: 0, lineHeight: 1.15 }}
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Form title…"
          allowedFormats={['core/italic']}
        />
        <RichText
          tagName="p"
          style={{ marginTop: 8, color: '#7b817b' }}
          value={subtitle}
          onChange={(v) => setAttributes({ subtitle: v })}
          placeholder="Subtitle…"
          allowedFormats={['core/italic']}
        />

        <div style={{ marginTop: 28, display: 'grid', gap: 26 }}>
          {['Your name', 'Email', 'Subject', 'Message'].map((l) => (
            <div key={l} style={{ borderBottom: '1px solid #ede9d9', paddingBottom: 6 }}>
              <div style={{ fontFamily: '"JetBrains Mono", monospace', fontSize: 11, letterSpacing: '0.12em', textTransform: 'uppercase', color: '#7b817b' }}>{l}</div>
              <div style={{ height: l === 'Message' ? 90 : 32 }} />
            </div>
          ))}
        </div>

        <span style={{ display: 'inline-block', marginTop: 32, padding: '14px 28px', background: '#e8c46a', color: '#1f4a3a', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999, fontWeight: 600 }}>
          {submitText || 'Send message'}
        </span>
      </div>
    </>
  );
}
