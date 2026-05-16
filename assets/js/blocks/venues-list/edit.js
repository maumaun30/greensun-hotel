import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button, Card, CardBody, CardHeader, Flex, FlexItem, FlexBlock } from '@wordpress/components';
import { plus, chevronUp, chevronDown, trash } from '@wordpress/icons';

export default function Edit({ attributes, setAttributes }) {
  const { venues } = attributes;

  const updateVenue = (i, field, value) => setAttributes({ venues: venues.map((v, idx) => idx === i ? { ...v, [field]: value } : v) });
  const setVenueImage = (i, media) =>
    setAttributes({ venues: venues.map((v, idx) => idx === i ? { ...v, imageUrl: media.url, imageId: media.id, imageAlt: media.alt || '' } : v) });
  const clearVenueImage = (i) =>
    setAttributes({ venues: venues.map((v, idx) => idx === i ? { ...v, imageUrl: '', imageId: 0, imageAlt: '' } : v) });

  const updateCap = (vi, ci, field, value) =>
    setAttributes({ venues: venues.map((v, idx) => idx !== vi ? v : { ...v, capacities: v.capacities.map((c, cidx) => cidx === ci ? { ...c, [field]: value } : c) }) });
  const addCap    = (vi) => setAttributes({ venues: venues.map((v, idx) => idx !== vi ? v : { ...v, capacities: [...(v.capacities || []), { layout: 'Layout', value: '0' }] }) });
  const removeCap = (vi, ci) => setAttributes({ venues: venues.map((v, idx) => idx !== vi ? v : { ...v, capacities: v.capacities.filter((_, cidx) => cidx !== ci) }) });

  const addVenue = () => setAttributes({ venues: [...venues, { name: 'New venue', area: 'Area', capacity: '00 pax', blurb: '', imageUrl: '', imageId: 0, imageAlt: '', capacities: [], ctaText: 'Inquire', ctaUrl: '/contact' }] });
  const removeVenue = (i) => setAttributes({ venues: venues.filter((_, idx) => idx !== i) });
  const moveVenue = (i, dir) => {
    const next = [...venues];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ venues: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={`Venues (${venues.length})`} initialOpen>
          {venues.map((v, vi) => (
            <Card key={vi} style={{ marginBottom: 14 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>{v.name || `Venue ${vi + 1}`}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp}   isSmall disabled={vi === 0} onClick={() => moveVenue(vi, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={vi === venues.length - 1} onClick={() => moveVenue(vi, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={venues.length <= 1} onClick={() => removeVenue(vi)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => setVenueImage(vi, media)}
                    allowedTypes={['image']}
                    value={v.imageId}
                    render={({ open }) => (
                      <div style={{ marginBottom: 12 }}>
                        {v.imageUrl && <img src={v.imageUrl} alt="" style={{ display: 'block', maxWidth: '100%', marginBottom: 8, borderRadius: 4 }} />}
                        <Button variant="secondary" isSmall onClick={open}>{v.imageUrl ? 'Replace image' : 'Select image'}</Button>
                        {v.imageUrl && <Button variant="tertiary" isSmall isDestructive onClick={() => clearVenueImage(vi)} style={{ marginLeft: 8 }}>Remove</Button>}
                      </div>
                    )}
                  />
                </MediaUploadCheck>
                <TextControl label="Name"     value={v.name}     onChange={(val) => updateVenue(vi, 'name', val)} />
                <TextControl label="Area"     value={v.area}     onChange={(val) => updateVenue(vi, 'area', val)} />
                <TextControl label="Capacity headline" value={v.capacity} onChange={(val) => updateVenue(vi, 'capacity', val)} />
                <TextareaControl label="Blurb" value={v.blurb}  onChange={(val) => updateVenue(vi, 'blurb', val)} rows={3} />

                <div style={{ marginTop: 12, fontSize: 11, letterSpacing: '0.14em', textTransform: 'uppercase', color: '#7b817b' }}>Capacities</div>
                {(v.capacities || []).map((c, ci) => (
                  <Flex key={ci} gap={2} style={{ marginTop: 8 }}>
                    <FlexBlock><TextControl label="Layout" hideLabelFromVision value={c.layout} onChange={(val) => updateCap(vi, ci, 'layout', val)} placeholder="Layout" /></FlexBlock>
                    <FlexBlock><TextControl label="Value"  hideLabelFromVision value={c.value}  onChange={(val) => updateCap(vi, ci, 'value', val)}  placeholder="Value" /></FlexBlock>
                    <FlexItem><Button icon={trash} isSmall isDestructive onClick={() => removeCap(vi, ci)} label="Remove" /></FlexItem>
                  </Flex>
                ))}
                <Button variant="tertiary" icon={plus} isSmall onClick={() => addCap(vi)} style={{ marginTop: 6 }}>Add capacity row</Button>

                <TextControl label="CTA text" value={v.ctaText} onChange={(val) => updateVenue(vi, 'ctaText', val)} />
                <TextControl label="CTA URL"  value={v.ctaUrl}  onChange={(val) => updateVenue(vi, 'ctaUrl', val)} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={addVenue} style={{ width: '100%', justifyContent: 'center' }}>Add venue</Button>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'venues-list-editor' })} style={{ padding: '40px 0', display: 'grid', gap: 56 }}>
        {venues.map((v, idx) => {
          const flip = idx % 2 === 1;
          return (
            <article key={idx} style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 40, alignItems: 'center' }}>
              <div style={{ order: flip ? 2 : 1, position: 'relative' }}>
                <div style={{ height: 320, background: v.imageUrl ? `center/cover no-repeat url(${v.imageUrl})` : '#ede9d9', borderRadius: 4 }} />
                <div style={{ position: 'absolute', top: 16, [flip ? 'right' : 'left']: -16, background: '#e8c46a', color: '#0f2018', padding: '8px 14px', fontFamily: '"Cormorant Garamond", serif', fontSize: 28, lineHeight: 1, borderRadius: 2 }}>0{idx + 1}</div>
              </div>
              <div style={{ order: flip ? 1 : 2 }}>
                <div className="eyebrow">{v.area || ''}{v.capacity ? ` · ${v.capacity}` : ''}</div>
                <h2 className="display" style={{ fontSize: 'clamp(32px, 5vw, 56px)', margin: '12px 0 0', maxWidth: '14ch' }}>{v.name}</h2>
                <p style={{ marginTop: 18, color: '#3d433d', lineHeight: 1.75 }}>{v.blurb}</p>
                {(v.capacities || []).length > 0 && (
                  <div style={{ marginTop: 22, display: 'grid', gridTemplateColumns: `repeat(${Math.min(3, v.capacities.length)}, 1fr)`, gap: 12 }}>
                    {v.capacities.map((c, ci) => (
                      <div key={ci} style={{ borderTop: '1px solid #ede9d9', paddingTop: 10 }}>
                        <div style={{ fontFamily: '"JetBrains Mono", monospace', fontSize: 12, color: '#7b817b' }}>{c.layout}</div>
                        <div className="display" style={{ fontSize: 22, color: '#1f4a3a' }}>{c.value}{c.value && !/\D/.test(c.value) ? ' pax' : ''}</div>
                      </div>
                    ))}
                  </div>
                )}
                {v.ctaText && (
                  <div style={{ marginTop: 24 }}>
                    <span style={{ display: 'inline-block', padding: '12px 22px', border: '1px solid #1f4a3a', color: '#1f4a3a', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999 }}>{v.ctaText}</span>
                  </div>
                )}
              </div>
            </article>
          );
        })}
      </section>
    </>
  );
}
