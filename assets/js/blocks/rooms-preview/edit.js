import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, RangeControl, Button, ComboboxControl, Flex, FlexItem, FlexBlock } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, subtitle, ctaText, ctaUrl, featuredRooms, fallbackCount } = attributes;

  const rooms = useSelect((select) =>
    select(coreStore).getEntityRecords('postType', 'room', { per_page: 100, status: 'publish' }),
    []
  );

  const roomOptions = (rooms || []).map((r) => ({
    value: String(r.id),
    label: r.title?.rendered || `Room #${r.id}`,
  }));

  const selectedRooms = (featuredRooms || []).map((id) => (rooms || []).find((r) => r.id === id)).filter(Boolean);

  const addRoom = (id) => {
    const numId = parseInt(id, 10);
    if (!numId || featuredRooms.includes(numId)) return;
    setAttributes({ featuredRooms: [...featuredRooms, numId] });
  };
  const removeRoom = (id) => setAttributes({ featuredRooms: featuredRooms.filter((r) => r !== id) });
  const moveRoom = (i, dir) => {
    const next = [...featuredRooms];
    const target = i + dir;
    if (target < 0 || target >= next.length) return;
    [next[i], next[target]] = [next[target], next[i]];
    setAttributes({ featuredRooms: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title="Featured rooms" initialOpen>
          <ComboboxControl
            label="Add a room"
            value=""
            options={roomOptions.filter((o) => !featuredRooms.includes(parseInt(o.value, 10)))}
            onChange={(v) => v && addRoom(v)}
          />
          {selectedRooms.length === 0 && (
            <p style={{ fontSize: 12, color: '#777' }}>
              No rooms selected — the block will fall back to the most recent {fallbackCount} published rooms.
            </p>
          )}
          {selectedRooms.map((r, i) => (
            <Flex key={r.id} align="center" style={{ marginBottom: 6, padding: '6px 8px', border: '1px solid #ddd', borderRadius: 4 }}>
              <FlexBlock><span style={{ fontSize: 13 }}>{r.title?.rendered || `Room #${r.id}`}</span></FlexBlock>
              <FlexItem><Button icon={chevronUp} isSmall disabled={i === 0} onClick={() => moveRoom(i, -1)} label="Move up" /></FlexItem>
              <FlexItem><Button icon={chevronDown} isSmall disabled={i === selectedRooms.length - 1} onClick={() => moveRoom(i, 1)} label="Move down" /></FlexItem>
              <FlexItem><Button icon={trash} isSmall isDestructive onClick={() => removeRoom(r.id)} label="Remove" /></FlexItem>
            </Flex>
          ))}
          <RangeControl
            label="Fallback count (when none selected)"
            value={fallbackCount}
            min={1}
            max={6}
            onChange={(v) => setAttributes({ fallbackCount: v })}
          />
        </PanelBody>
        <PanelBody title="Section CTA" initialOpen={false}>
          <TextControl label="CTA text" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} />
          <TextControl label="CTA URL"  value={ctaUrl}  onChange={(v) => setAttributes({ ctaUrl: v })} />
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'rooms-preview-editor' })} style={{ padding: '40px 0' }}>
        <div style={{ textAlign: 'center', marginBottom: 48 }}>
          <RichText
            tagName="div"
            className="eyebrow"
            value={eyebrow}
            onChange={(v) => setAttributes({ eyebrow: v })}
            placeholder="Eyebrow…"
            allowedFormats={[]}
          />
          <RichText
            tagName="h2"
            className="display"
            style={{ fontSize: 'clamp(36px, 5vw, 72px)', marginTop: 14 }}
            value={sectionTitle}
            onChange={(v) => setAttributes({ sectionTitle: v })}
            placeholder="Section title…"
            allowedFormats={['core/italic']}
          />
          <RichText
            tagName="p"
            style={{ marginTop: 14, color: 'var(--ink-2, #3d433d)', lineHeight: 1.6 }}
            value={subtitle}
            onChange={(v) => setAttributes({ subtitle: v })}
            placeholder="Subtitle…"
            allowedFormats={['core/bold', 'core/italic']}
          />
        </div>
        <div style={{ display: 'grid', gridTemplateColumns: `repeat(${Math.max(1, Math.min(3, selectedRooms.length || fallbackCount))}, 1fr)`, gap: 28 }}>
          {(selectedRooms.length ? selectedRooms : new Array(fallbackCount).fill(null)).map((r, i) => (
            <div key={i} style={{ background: '#fff', borderRadius: 14, overflow: 'hidden', border: '1px solid #ede9d9' }}>
              <div style={{ aspectRatio: '4 / 3', background: '#ede9d9', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#7b817b', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase' }}>
                Room image
              </div>
              <div style={{ padding: 24 }}>
                <h3 style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: 28, margin: 0 }}>
                  {r?.title?.rendered || 'Room title'}
                </h3>
                <p style={{ marginTop: 8, color: '#3d433d', fontSize: 14 }}>
                  ACF fields render on the front-end.
                </p>
              </div>
            </div>
          ))}
        </div>
      </section>
    </>
  );
}
