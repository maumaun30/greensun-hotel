import {
  useBlockProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from '@wordpress/block-editor';
import {
  PanelBody,
  TextControl,
  Button,
  Card,
  CardHeader,
  CardBody,
  Flex,
  FlexItem,
  FlexBlock,
} from '@wordpress/components';
import { plus, chevronUp, chevronDown, trash } from '@wordpress/icons';

const ImagePicker = ({ label, url, id, alt, onSelect, onRemove }) => (
  <MediaUploadCheck>
    <MediaUpload
      onSelect={(media) => onSelect({ url: media.url, id: media.id, alt: media.alt || '' })}
      allowedTypes={['image']}
      value={id}
      render={({ open }) => (
        <div style={{ marginBottom: 16 }}>
          <div style={{ fontSize: 11, fontWeight: 600, letterSpacing: '.06em', textTransform: 'uppercase', marginBottom: 6 }}>{label}</div>
          {url && <img src={url} alt={alt} style={{ display: 'block', maxWidth: '100%', marginBottom: 8, borderRadius: 2 }} />}
          <Button variant="secondary" onClick={open}>{url ? 'Replace' : 'Select image'}</Button>
          {url && (
            <Button variant="tertiary" isDestructive onClick={onRemove} style={{ marginLeft: 8 }}>Remove</Button>
          )}
        </div>
      )}
    />
  </MediaUploadCheck>
);

export default function Edit({ attributes, setAttributes }) {
  const {
    eyebrow, title, subtitle, ctaText, ctaUrl, stats,
    primaryImageUrl, primaryImageId, primaryImageAlt,
    secondaryImageUrl, secondaryImageId, secondaryImageAlt,
    badgeNumber, badgeCaption,
  } = attributes;

  const updateStat = (i, field, value) =>
    setAttributes({ stats: stats.map((s, idx) => (idx === i ? { ...s, [field]: value } : s)) });
  const addStat    = () => setAttributes({ stats: [...stats, { value: '0', label: 'New metric' }] });
  const removeStat = (i) => setAttributes({ stats: stats.filter((_, idx) => idx !== i) });
  const moveStat   = (i, dir) => {
    const next = [...stats];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ stats: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title="Images" initialOpen>
          <ImagePicker
            label="Primary (top-right)"
            url={primaryImageUrl} id={primaryImageId} alt={primaryImageAlt}
            onSelect={(m) => setAttributes({ primaryImageUrl: m.url, primaryImageId: m.id, primaryImageAlt: m.alt })}
            onRemove={() => setAttributes({ primaryImageUrl: '', primaryImageId: 0, primaryImageAlt: '' })}
          />
          <ImagePicker
            label="Secondary (bottom-left)"
            url={secondaryImageUrl} id={secondaryImageId} alt={secondaryImageAlt}
            onSelect={(m) => setAttributes({ secondaryImageUrl: m.url, secondaryImageId: m.id, secondaryImageAlt: m.alt })}
            onRemove={() => setAttributes({ secondaryImageUrl: '', secondaryImageId: 0, secondaryImageAlt: '' })}
          />
        </PanelBody>

        <PanelBody title="Year badge" initialOpen={false}>
          <TextControl label="Badge number" value={badgeNumber}  onChange={(v) => setAttributes({ badgeNumber: v })} />
          <TextControl label="Caption"      value={badgeCaption} onChange={(v) => setAttributes({ badgeCaption: v })} />
        </PanelBody>

        <PanelBody title={`Stats (${stats.length})`} initialOpen={false}>
          {stats.map((s, index) => (
            <Card key={index} style={{ marginBottom: 12 }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem><strong style={{ fontSize: 12 }}>{s.value || `Stat ${index + 1}`}</strong></FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp}   isSmall disabled={index === 0} onClick={() => moveStat(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === stats.length - 1} onClick={() => moveStat(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={stats.length <= 1} onClick={() => removeStat(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <TextControl label="Value" value={s.value} onChange={(v) => updateStat(index, 'value', v)} />
                <TextControl label="Label" value={s.label} onChange={(v) => updateStat(index, 'label', v)} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={addStat} style={{ width: '100%', justifyContent: 'center' }}>Add stat</Button>
        </PanelBody>

        <PanelBody title="Call to action" initialOpen={false}>
          <TextControl label="Button text" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} />
          <TextControl label="Button URL"  value={ctaUrl}  type="url" onChange={(v) => setAttributes({ ctaUrl: v })} />
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'about-teaser-editor' })}
        style={{ display: 'grid', gridTemplateColumns: '1.05fr 1fr', gap: 60, alignItems: 'center', padding: '32px 0' }}>
        <div>
          <RichText tagName="div" className="eyebrow"
            value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })}
            placeholder="Eyebrow…" allowedFormats={[]} />
          <RichText tagName="h2" className="display"
            style={{ fontSize: 'clamp(36px, 5vw, 64px)', marginTop: 18, maxWidth: '14ch', lineHeight: 1.05 }}
            value={title} onChange={(v) => setAttributes({ title: v })}
            placeholder="Section title…" allowedFormats={['core/italic']} />
          <RichText tagName="p"
            style={{ marginTop: 22, color: '#3d433d', lineHeight: 1.7, maxWidth: '50ch', fontSize: 17 }}
            value={subtitle} onChange={(v) => setAttributes({ subtitle: v })}
            placeholder="Subtitle…" allowedFormats={['core/bold', 'core/italic']} />

          <div style={{ display: 'flex', gap: 16, flexWrap: 'wrap', marginTop: 30 }}>
            {stats.map((s, idx) => (
              <div key={idx} style={{ paddingRight: 24, borderRight: idx === stats.length - 1 ? 'none' : '1px solid #ede9d9', minWidth: 100 }}>
                <div className="display" style={{ fontSize: 36, color: '#1f4a3a', lineHeight: 1 }}>{s.value}</div>
                <div style={{ fontSize: 10, letterSpacing: '.16em', textTransform: 'uppercase', color: '#7b817b', marginTop: 6 }}>{s.label}</div>
              </div>
            ))}
          </div>

          <div style={{ marginTop: 28 }}>
            <span style={{ display: 'inline-block', padding: '12px 24px', background: '#1f4a3a', color: '#f7f6f0', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999 }}>
              {ctaText || 'Button'}
            </span>
          </div>
        </div>

        <div style={{ position: 'relative', height: 480 }}>
          <div style={{ position: 'absolute', top: 0, right: 20, width: '60%', height: 340, background: '#ede9d9', borderRadius: 4, overflow: 'hidden' }}>
            {primaryImageUrl
              ? <img src={primaryImageUrl} alt={primaryImageAlt} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
              : <div style={{ width: '100%', height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#7b817b', fontSize: 12 }}>Primary image</div>}
          </div>
          <div style={{ position: 'absolute', bottom: 0, left: 0, width: '52%', height: 280, background: '#ede9d9', borderRadius: 4, overflow: 'hidden' }}>
            {secondaryImageUrl
              ? <img src={secondaryImageUrl} alt={secondaryImageAlt} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
              : <div style={{ width: '100%', height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#7b817b', fontSize: 12 }}>Secondary image</div>}
          </div>
          {badgeNumber && (
            <div style={{ position: 'absolute', top: '38%', right: 0, background: '#1f4a3a', color: '#e8c46a', padding: '14px 18px', borderRadius: 4 }}>
              <div className="display" style={{ fontSize: 42, lineHeight: 1 }}>{badgeNumber}</div>
              {badgeCaption && (
                <div style={{ fontSize: 9, letterSpacing: '.22em', textTransform: 'uppercase', marginTop: 6, color: 'rgba(255,255,255,.7)' }}>{badgeCaption}</div>
              )}
            </div>
          )}
        </div>
      </section>
    </>
  );
}
