import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
  PanelBody,
  TextControl,
  RangeControl,
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
  const { eyebrow, sectionTitle, columns, members } = attributes;

  const updateMember = (i, field, value) =>
    setAttributes({ members: members.map((m, idx) => (idx === i ? { ...m, [field]: value } : m)) });
  const setMemberImage = (i, media) =>
    setAttributes({ members: members.map((m, idx) => idx === i ? { ...m, imageUrl: media.url, imageId: media.id, imageAlt: media.alt || '' } : m) });
  const clearMemberImage = (i) =>
    setAttributes({ members: members.map((m, idx) => idx === i ? { ...m, imageUrl: '', imageId: 0, imageAlt: '' } : m) });
  const addMember = () => setAttributes({ members: [...members, { imageUrl: '', imageId: 0, imageAlt: '', name: 'New member', role: 'Role' }] });
  const removeMember = (i) => setAttributes({ members: members.filter((_, idx) => idx !== i) });
  const moveMember = (i, dir) => {
    const next = [...members];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ members: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title="Layout" initialOpen>
          <RangeControl label="Columns" value={columns} min={2} max={4} onChange={(v) => setAttributes({ columns: v })} />
        </PanelBody>
        <PanelBody title={`Members (${members.length})`} initialOpen>
          {members.map((m, index) => (
            <Card key={index} style={{ marginBottom: 12 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>Member {index + 1}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp} isSmall disabled={index === 0} onClick={() => moveMember(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === members.length - 1} onClick={() => moveMember(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={members.length <= 1} onClick={() => removeMember(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => setMemberImage(index, media)}
                    allowedTypes={['image']}
                    value={m.imageId}
                    render={({ open }) => (
                      <div style={{ marginBottom: 12 }}>
                        {m.imageUrl && <img src={m.imageUrl} alt="" style={{ display: 'block', maxWidth: '100%', marginBottom: 8, borderRadius: 4 }} />}
                        <Button variant="secondary" isSmall onClick={open}>{m.imageUrl ? 'Replace portrait' : 'Select portrait'}</Button>
                        {m.imageUrl && <Button variant="tertiary" isSmall isDestructive onClick={() => clearMemberImage(index)} style={{ marginLeft: 8 }}>Remove</Button>}
                      </div>
                    )}
                  />
                </MediaUploadCheck>
                <TextControl label="Name" value={m.name} onChange={(v) => updateMember(index, 'name', v)} />
                <TextControl label="Role" value={m.role} onChange={(v) => updateMember(index, 'role', v)} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={addMember} style={{ width: '100%', justifyContent: 'center' }}>Add member</Button>
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'team-grid-editor' })} style={{ padding: '40px 0' }}>
        <div style={{ textAlign: 'center', marginBottom: 56 }}>
          <RichText tagName="div" className="eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Eyebrow…" allowedFormats={[]} />
          <RichText
            tagName="h2"
            className="display"
            style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 14, maxWidth: '18ch', marginInline: 'auto' }}
            value={sectionTitle}
            onChange={(v) => setAttributes({ sectionTitle: v })}
            placeholder="Section title…"
            allowedFormats={['core/italic']}
          />
        </div>
        <div style={{ display: 'grid', gridTemplateColumns: `repeat(${columns}, 1fr)`, gap: 32 }}>
          {members.map((m, i) => (
            <div key={i} style={{ textAlign: 'center' }}>
              <div style={{ aspectRatio: '3 / 4', background: '#ede9d9', overflow: 'hidden', borderRadius: 14, marginBottom: 18 }}>
                {m.imageUrl && <img src={m.imageUrl} alt={m.imageAlt} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />}
              </div>
              <div style={{ fontFamily: '"Cormorant Garamond", serif', fontSize: 24 }}>{m.name}</div>
              <div style={{ fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', color: '#7b817b', marginTop: 6 }}>{m.role}</div>
            </div>
          ))}
        </div>
      </section>
    </>
  );
}
