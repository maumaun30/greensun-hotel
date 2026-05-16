import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextareaControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, address, phone, email, hours, mapEmbed } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Map embed" initialOpen>
          <TextareaControl
            label="iframe HTML (Google Maps, OpenStreetMap, etc.)"
            help="Paste the embed iframe. Leave blank to omit the map column."
            value={mapEmbed}
            onChange={(v) => setAttributes({ mapEmbed: v })}
            rows={6}
          />
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'contact-info-editor' })} style={{ padding: '40px 0' }}>
        <RichText tagName="div" className="eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Eyebrow…" allowedFormats={[]} />
        <RichText
          tagName="h2"
          className="display"
          style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 14, marginBottom: 40, maxWidth: '18ch' }}
          value={sectionTitle}
          onChange={(v) => setAttributes({ sectionTitle: v })}
          placeholder="Section title…"
          allowedFormats={['core/italic']}
        />
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr 1fr', gap: 32 }}>
          <div>
            <div className="eyebrow" style={{ fontSize: 11 }}>Address</div>
            <RichText tagName="div" value={address} onChange={(v) => setAttributes({ address: v })} placeholder="Address" style={{ marginTop: 8, whiteSpace: 'pre-line', lineHeight: 1.6 }} allowedFormats={['core/bold']} />
          </div>
          <div>
            <div className="eyebrow" style={{ fontSize: 11 }}>Phone</div>
            <RichText tagName="div" value={phone} onChange={(v) => setAttributes({ phone: v })} placeholder="Phone" style={{ marginTop: 8 }} allowedFormats={[]} />
          </div>
          <div>
            <div className="eyebrow" style={{ fontSize: 11 }}>Email</div>
            <RichText tagName="div" value={email} onChange={(v) => setAttributes({ email: v })} placeholder="Email" style={{ marginTop: 8 }} allowedFormats={[]} />
          </div>
          <div>
            <div className="eyebrow" style={{ fontSize: 11 }}>Hours</div>
            <RichText tagName="div" value={hours} onChange={(v) => setAttributes({ hours: v })} placeholder="Hours" style={{ marginTop: 8, whiteSpace: 'pre-line', lineHeight: 1.6 }} allowedFormats={['core/bold']} />
          </div>
        </div>
        {mapEmbed ? (
          <div style={{ marginTop: 40, aspectRatio: '16 / 7', background: '#ede9d9', borderRadius: 14, overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#7b817b' }}>
            Map preview renders on the front-end.
          </div>
        ) : null}
      </section>
    </>
  );
}
