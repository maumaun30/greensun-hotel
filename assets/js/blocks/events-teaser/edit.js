import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
  PanelBody,
  TextControl,
  TextareaControl,
  Button,
  Card,
  CardBody,
  CardHeader,
  Flex,
  FlexItem,
  FlexBlock,
} from '@wordpress/components';
import { plus, chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, sectionTitle, subtitle, events } = attributes;

  const updateEvent = (i, field, value) => {
    setAttributes({ events: events.map((e, idx) => (idx === i ? { ...e, [field]: value } : e)) });
  };
  const setEventImage = (i, media) => {
    setAttributes({ events: events.map((e, idx) => idx === i ? { ...e, imageUrl: media.url, imageId: media.id, imageAlt: media.alt || '' } : e) });
  };
  const clearEventImage = (i) => {
    setAttributes({ events: events.map((e, idx) => idx === i ? { ...e, imageUrl: '', imageId: 0, imageAlt: '' } : e) });
  };
  const addEvent = () => setAttributes({ events: [...events, { imageUrl: '', imageId: 0, imageAlt: '', title: 'New event', description: 'Describe this event.', ctaText: 'Reserve', ctaUrl: '#' }] });
  const removeEvent = (i) => setAttributes({ events: events.filter((_, idx) => idx !== i) });
  const moveEvent = (i, dir) => {
    const next = [...events];
    const target = i + dir;
    if (target < 0 || target >= next.length) return;
    [next[i], next[target]] = [next[target], next[i]];
    setAttributes({ events: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={`Events (${events.length})`} initialOpen>
          {events.map((ev, index) => (
            <Card key={index} style={{ marginBottom: 12 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>Event {index + 1}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp} isSmall disabled={index === 0} onClick={() => moveEvent(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === events.length - 1} onClick={() => moveEvent(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={events.length <= 1} onClick={() => removeEvent(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => setEventImage(index, media)}
                    allowedTypes={['image']}
                    value={ev.imageId}
                    render={({ open }) => (
                      <div style={{ marginBottom: 12 }}>
                        {ev.imageUrl && <img src={ev.imageUrl} alt="" style={{ display: 'block', maxWidth: '100%', marginBottom: 8, borderRadius: 4 }} />}
                        <Button variant="secondary" isSmall onClick={open}>{ev.imageUrl ? 'Replace image' : 'Select image'}</Button>
                        {ev.imageUrl && <Button variant="tertiary" isSmall isDestructive onClick={() => clearEventImage(index)} style={{ marginLeft: 8 }}>Remove</Button>}
                      </div>
                    )}
                  />
                </MediaUploadCheck>
                <TextControl label="Title" value={ev.title} onChange={(v) => updateEvent(index, 'title', v)} />
                <TextareaControl label="Description" value={ev.description} onChange={(v) => updateEvent(index, 'description', v)} rows={2} />
                <TextControl label="CTA text" value={ev.ctaText} onChange={(v) => updateEvent(index, 'ctaText', v)} />
                <TextControl label="CTA URL" value={ev.ctaUrl} type="url" onChange={(v) => updateEvent(index, 'ctaUrl', v)} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={addEvent} style={{ width: '100%', justifyContent: 'center' }}>Add event</Button>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'events-teaser-editor' })} style={{ padding: '40px 0' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 48, alignItems: 'end', marginBottom: 48 }}>
          <div>
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
        <div style={{ display: 'grid', gridTemplateColumns: `repeat(${Math.min(events.length, 3)}, 1fr)`, gap: 28 }}>
          {events.map((ev, i) => (
            <article key={i} style={{ background: '#fff', borderRadius: 14, overflow: 'hidden', border: '1px solid #ede9d9' }}>
              <div style={{ aspectRatio: '4 / 3', background: '#ede9d9', overflow: 'hidden' }}>
                {ev.imageUrl && <img src={ev.imageUrl} alt={ev.imageAlt} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />}
              </div>
              <div style={{ padding: 24 }}>
                <h3 style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: 28, margin: 0 }}>{ev.title}</h3>
                <p style={{ marginTop: 10, color: '#3d433d', lineHeight: 1.6 }}>{ev.description}</p>
                <div style={{ marginTop: 18, fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', color: '#1f4a3a' }}>{ev.ctaText} →</div>
              </div>
            </article>
          ))}
        </div>
      </section>
    </>
  );
}
