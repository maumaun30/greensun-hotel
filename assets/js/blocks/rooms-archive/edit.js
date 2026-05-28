import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, title, lead, perPage } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Listing" initialOpen>
          <RangeControl
            label="Rooms per page"
            value={perPage}
            min={1}
            max={24}
            onChange={(v) => setAttributes({ perPage: v })}
          />
          <p style={{ fontSize: 12, color: '#777' }}>
            Every published Room renders on the front-end in the alternating
            editorial layout. Edit copy below; the listing is built from the Room CPT.
          </p>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'rooms-archive-editor' })} style={{ padding: '24px 0' }}>
        <RichText
          tagName="div"
          className="eyebrow"
          value={eyebrow}
          onChange={(v) => setAttributes({ eyebrow: v })}
          placeholder="Eyebrow…"
          allowedFormats={[]}
        />
        <RichText
          tagName="h1"
          className="display"
          style={{ fontSize: 'clamp(40px, 6vw, 80px)', margin: '16px 0 0', lineHeight: 1.05 }}
          value={title}
          onChange={(v) => setAttributes({ title: v })}
          placeholder="Archive title…"
          allowedFormats={['core/italic']}
        />
        <RichText
          tagName="p"
          style={{ marginTop: 20, maxWidth: 540, color: 'var(--ink-2, #3d433d)', lineHeight: 1.7 }}
          value={lead}
          onChange={(v) => setAttributes({ lead: v })}
          placeholder="Lead paragraph…"
          allowedFormats={['core/bold', 'core/italic']}
        />
        <div style={{ marginTop: 28, padding: '40px 0', border: '1px dashed #cdd3cd', borderRadius: 4, textAlign: 'center', color: '#7b817b', fontSize: 13, letterSpacing: '0.04em' }}>
          Rooms listing renders here on the front-end
        </div>
      </section>
    </>
  );
}
