import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, title, lead, inquiryTitle, inquiryText, inquiryCtaText, inquiryCtaUrl } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Inquiry strip" initialOpen>
          <TextControl label="CTA text" value={inquiryCtaText} onChange={(v) => setAttributes({ inquiryCtaText: v })} />
          <TextControl label="CTA URL"  value={inquiryCtaUrl}  onChange={(v) => setAttributes({ inquiryCtaUrl: v })} />
        </PanelBody>
        <PanelBody title="Listing" initialOpen={false}>
          <p style={{ fontSize: 12, color: '#777' }}>
            Every published Venue renders on the front-end in the alternating layout,
            built from the Venue CPT and its ACF fields.
          </p>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'venues-archive-editor' })} style={{ padding: '24px 0' }}>
        <RichText tagName="div" className="eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Eyebrow…" allowedFormats={[]} />
        <RichText tagName="h1" className="display" style={{ fontSize: 'clamp(40px, 6vw, 88px)', margin: '16px 0 0', lineHeight: 1.05 }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Archive title…" allowedFormats={['core/italic']} />
        <RichText tagName="p" style={{ marginTop: 20, maxWidth: 620, color: 'var(--ink-2, #3d433d)', lineHeight: 1.7 }} value={lead} onChange={(v) => setAttributes({ lead: v })} placeholder="Lead paragraph…" allowedFormats={['core/bold', 'core/italic']} />

        <div style={{ margin: '28px 0', padding: '40px 0', border: '1px dashed #cdd3cd', borderRadius: 4, textAlign: 'center', color: '#7b817b', fontSize: 13 }}>
          Venues listing renders here on the front-end
        </div>

        <div style={{ background: 'var(--forest, #1f4a3a)', color: '#fff', padding: 32, borderRadius: 4 }}>
          <RichText tagName="h2" className="display" style={{ fontSize: 'clamp(28px, 4vw, 48px)', color: 'var(--sun, #e8c46a)', margin: 0, maxWidth: '14ch' }} value={inquiryTitle} onChange={(v) => setAttributes({ inquiryTitle: v })} placeholder="Inquiry title…" allowedFormats={['core/italic']} />
          <RichText tagName="p" style={{ marginTop: 16, color: 'rgba(255,255,255,.78)', lineHeight: 1.75, maxWidth: 480 }} value={inquiryText} onChange={(v) => setAttributes({ inquiryText: v })} placeholder="Inquiry text…" allowedFormats={['core/bold', 'core/italic']} />
        </div>
      </section>
    </>
  );
}
