import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const { submitText, actionUrl, showRoomType } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Booking Bar" initialOpen>
          <TextControl label="Submit button text" value={submitText} onChange={(v) => setAttributes({ submitText: v })} />
          <TextControl
            label="Booking engine URL (deep link fallback)"
            help="Until the eZee API is wired up, submissions deep-link to this URL with check-in / check-out / guests as query params. Leave blank to use the site's /booking page."
            value={actionUrl}
            onChange={(v) => setAttributes({ actionUrl: v })}
          />
          <ToggleControl
            label="Show room type field"
            checked={showRoomType}
            onChange={(v) => setAttributes({ showRoomType: v })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: 'booking-bar-editor' })}>
        <div className="booking-bar" style={{ display: 'grid', gridTemplateColumns: showRoomType ? '1fr 1fr 1fr 1fr auto' : '1fr 1fr 1fr auto', gap: 12, padding: 16, background: '#fff', border: '1px solid #ede9d9', borderRadius: 14 }}>
          <div className="field"><label style={{ fontSize: 11, letterSpacing: '0.14em', textTransform: 'uppercase', color: '#7b817b' }}>Check-in</label><div style={{ height: 24 }}>—</div></div>
          <div className="field"><label style={{ fontSize: 11, letterSpacing: '0.14em', textTransform: 'uppercase', color: '#7b817b' }}>Check-out</label><div style={{ height: 24 }}>—</div></div>
          <div className="field"><label style={{ fontSize: 11, letterSpacing: '0.14em', textTransform: 'uppercase', color: '#7b817b' }}>Guests</label><div style={{ height: 24 }}>2</div></div>
          {showRoomType && (
            <div className="field"><label style={{ fontSize: 11, letterSpacing: '0.14em', textTransform: 'uppercase', color: '#7b817b' }}>Room type</label><div style={{ height: 24 }}>Any</div></div>
          )}
          <span style={{ alignSelf: 'end', display: 'inline-block', padding: '12px 22px', background: '#e8c46a', color: '#1f4a3a', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999, fontWeight: 600 }}>
            {submitText || 'Search'}
          </span>
        </div>
      </div>
    </>
  );
}
