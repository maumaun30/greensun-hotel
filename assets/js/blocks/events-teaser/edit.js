import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, RangeControl, Button, ComboboxControl, Flex, FlexItem, FlexBlock } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, subtitle, ctaText, ctaUrl, featuredEvents, fallbackCount } = attributes;

  const events = useSelect((select) =>
    select(coreStore).getEntityRecords('postType', 'event', { per_page: 100, status: 'publish' }),
    []
  );

  const eventOptions = (events || []).map((e) => ({
    value: String(e.id),
    label: e.title?.rendered || `Event #${e.id}`,
  }));

  const selectedEvents = (featuredEvents || []).map((id) => (events || []).find((e) => e.id === id)).filter(Boolean);

  const addEvent = (id) => {
    const numId = parseInt(id, 10);
    if (!numId || featuredEvents.includes(numId)) return;
    setAttributes({ featuredEvents: [...featuredEvents, numId] });
  };
  const removeEvent = (id) => setAttributes({ featuredEvents: featuredEvents.filter((e) => e !== id) });
  const moveEvent = (i, dir) => {
    const next = [...featuredEvents];
    const target = i + dir;
    if (target < 0 || target >= next.length) return;
    [next[i], next[target]] = [next[target], next[i]];
    setAttributes({ featuredEvents: next });
  };

  const previewCount = Math.max(1, Math.min(3, selectedEvents.length || fallbackCount));

  return (
    <>
      <InspectorControls>
        <PanelBody title="Featured events" initialOpen>
          <ComboboxControl
            label="Add an event"
            value=""
            options={eventOptions.filter((o) => !featuredEvents.includes(parseInt(o.value, 10)))}
            onChange={(v) => v && addEvent(v)}
          />
          {selectedEvents.length === 0 && (
            <p style={{ fontSize: 12, color: '#777' }}>
              No events selected — the block will fall back to the next {fallbackCount} upcoming events.
            </p>
          )}
          {selectedEvents.map((e, i) => (
            <Flex key={e.id} align="center" style={{ marginBottom: 6, padding: '6px 8px', border: '1px solid #ddd', borderRadius: 4 }}>
              <FlexBlock><span style={{ fontSize: 13 }}>{e.title?.rendered || `Event #${e.id}`}</span></FlexBlock>
              <FlexItem><Button icon={chevronUp} isSmall disabled={i === 0} onClick={() => moveEvent(i, -1)} label="Move up" /></FlexItem>
              <FlexItem><Button icon={chevronDown} isSmall disabled={i === selectedEvents.length - 1} onClick={() => moveEvent(i, 1)} label="Move down" /></FlexItem>
              <FlexItem><Button icon={trash} isSmall isDestructive onClick={() => removeEvent(e.id)} label="Remove" /></FlexItem>
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

      <section {...useBlockProps({ className: 'events-teaser-editor' })} style={{ padding: '40px 0' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 48, alignItems: 'end', marginBottom: 48 }}>
          <div>
            <RichText tagName="div" className="eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Eyebrow…" allowedFormats={[]} />
            <RichText
              tagName="h2"
              className="display"
              style={{ fontSize: 'clamp(36px, 5vw, 72px)', marginTop: 14, maxWidth: '14ch' }}
              value={sectionTitle}
              onChange={(v) => setAttributes({ sectionTitle: v })}
              placeholder="Section title…"
              allowedFormats={['core/italic']}
            />
          </div>
          <RichText
            tagName="p"
            style={{ color: 'var(--ink-2, #3d433d)', lineHeight: 1.75, maxWidth: '48ch' }}
            value={subtitle}
            onChange={(v) => setAttributes({ subtitle: v })}
            placeholder="Subtitle…"
            allowedFormats={['core/bold', 'core/italic']}
          />
        </div>
        <div style={{ display: 'grid', gridTemplateColumns: `repeat(${previewCount}, 1fr)`, gap: 28 }}>
          {(selectedEvents.length ? selectedEvents : new Array(fallbackCount).fill(null)).map((e, i) => (
            <article key={i} style={{ background: '#fff', borderRadius: 14, overflow: 'hidden', border: '1px solid #ede9d9' }}>
              <div style={{ aspectRatio: '4 / 3', background: '#ede9d9', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#7b817b', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase' }}>
                Event image
              </div>
              <div style={{ padding: 24 }}>
                <h3 style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: 28, margin: 0 }}>
                  {e?.title?.rendered || 'Event title'}
                </h3>
                <p style={{ marginTop: 8, color: '#3d433d', fontSize: 13 }}>
                  Date, location, and CTA render on the front-end from ACF fields.
                </p>
              </div>
            </article>
          ))}
        </div>
      </section>
    </>
  );
}
