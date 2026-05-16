import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, subtitle, submitText, successText } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Form" initialOpen>
          <TextControl label="Submit button text" value={submitText} onChange={(v) => setAttributes({ submitText: v })} />
          <TextControl label="Success message" value={successText} onChange={(v) => setAttributes({ successText: v })} />
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'contact-form-editor' })} style={{ padding: '40px 0' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 64, alignItems: 'start' }}>
          <div>
            <RichText tagName="div" className="eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Eyebrow…" allowedFormats={[]} />
            <RichText
              tagName="h2"
              className="display"
              style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 14, maxWidth: '14ch' }}
              value={sectionTitle}
              onChange={(v) => setAttributes({ sectionTitle: v })}
              placeholder="Section title…"
              allowedFormats={['core/italic']}
            />
            <RichText
              tagName="p"
              style={{ marginTop: 18, color: '#3d433d', lineHeight: 1.75, maxWidth: '40ch' }}
              value={subtitle}
              onChange={(v) => setAttributes({ subtitle: v })}
              placeholder="Subtitle…"
              allowedFormats={['core/bold', 'core/italic']}
            />
          </div>
          <div style={{ padding: 32, background: '#fff', border: '1px solid #ede9d9', borderRadius: 14 }}>
            {['Name', 'Email', 'Phone', 'Message'].map((l) => (
              <div key={l} style={{ marginBottom: 18 }}>
                <div style={{ fontSize: 11, letterSpacing: '0.18em', textTransform: 'uppercase', color: '#7b817b', marginBottom: 6 }}>{l}</div>
                <div style={{ height: l === 'Message' ? 96 : 32, borderBottom: '1px solid #ede9d9' }} />
              </div>
            ))}
            <span style={{ display: 'inline-block', marginTop: 12, padding: '14px 28px', background: '#e8c46a', color: '#1f4a3a', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999, fontWeight: 600 }}>
              {submitText || 'Send'}
            </span>
          </div>
        </div>
      </section>
    </>
  );
}
