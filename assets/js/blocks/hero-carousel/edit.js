import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
  PanelBody,
  TextControl,
  TextareaControl,
  ToggleControl,
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
import { useState } from '@wordpress/element';

export default function Edit({ attributes, setAttributes }) {
  const { slides, intervalMs, showMark, markText, primaryCta, primaryUrl, secondaryCta, secondaryUrl } = attributes;
  const [current, setCurrent] = useState(0);
  const active = slides[Math.min(current, slides.length - 1)] || {};

  const updateSlide = (i, field, value) =>
    setAttributes({ slides: slides.map((s, idx) => (idx === i ? { ...s, [field]: value } : s)) });
  const setSlideImage = (i, media) =>
    setAttributes({ slides: slides.map((s, idx) => idx === i ? { ...s, imageUrl: media.url, imageId: media.id, imageAlt: media.alt || '' } : s) });
  const clearSlideImage = (i) =>
    setAttributes({ slides: slides.map((s, idx) => idx === i ? { ...s, imageUrl: '', imageId: 0, imageAlt: '' } : s) });
  const addSlide = () => setAttributes({ slides: [...slides, { eyebrow: 'Eyebrow', title: 'A new chapter <em>begins</em>.', subtitle: '', accent: '', imageUrl: '', imageId: 0, imageAlt: '' }] });
  const removeSlide = (i) => {
    setAttributes({ slides: slides.filter((_, idx) => idx !== i) });
    if (current >= slides.length - 1) setCurrent(Math.max(0, slides.length - 2));
  };
  const moveSlide = (i, dir) => {
    const next = [...slides];
    const t = i + dir;
    if (t < 0 || t >= next.length) return;
    [next[i], next[t]] = [next[t], next[i]];
    setAttributes({ slides: next });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title="Hero" initialOpen>
          <RangeControl
            label="Autoplay interval (ms)"
            value={intervalMs}
            min={3000} max={12000} step={500}
            onChange={(v) => setAttributes({ intervalMs: v })}
          />
          <ToggleControl
            label="Show editorial mark"
            checked={showMark}
            onChange={(v) => setAttributes({ showMark: v })}
          />
          {showMark && (
            <TextControl label="Mark text" value={markText} onChange={(v) => setAttributes({ markText: v })} />
          )}
        </PanelBody>
        <PanelBody title="Buttons" initialOpen={false}>
          <TextControl label="Primary button text" value={primaryCta}  onChange={(v) => setAttributes({ primaryCta: v })} />
          <TextControl label="Primary button URL"  value={primaryUrl}  onChange={(v) => setAttributes({ primaryUrl: v })} />
          <TextControl label="Secondary button text" value={secondaryCta} onChange={(v) => setAttributes({ secondaryCta: v })} />
          <TextControl label="Secondary button URL"  value={secondaryUrl} onChange={(v) => setAttributes({ secondaryUrl: v })} />
        </PanelBody>
        <PanelBody title={`Slides (${slides.length})`} initialOpen>
          {slides.map((s, index) => (
            <Card key={index} style={{ marginBottom: 12, outline: index === current ? '2px solid #1f4a3a' : 'none' }}>
              <CardHeader>
                <Flex align="center">
                  <FlexItem>
                    <Button variant="link" onClick={() => setCurrent(index)} style={{ fontSize: 12 }}>
                      {`Slide ${index + 1}`}
                    </Button>
                  </FlexItem>
                  <FlexBlock />
                  <FlexItem><Button icon={chevronUp}   isSmall disabled={index === 0} onClick={() => moveSlide(index, -1)} label="Move up" /></FlexItem>
                  <FlexItem><Button icon={chevronDown} isSmall disabled={index === slides.length - 1} onClick={() => moveSlide(index, 1)} label="Move down" /></FlexItem>
                  <FlexItem><Button icon={trash} isSmall isDestructive disabled={slides.length <= 1} onClick={() => removeSlide(index)} label="Remove" /></FlexItem>
                </Flex>
              </CardHeader>
              <CardBody>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) => setSlideImage(index, media)}
                    allowedTypes={['image']}
                    value={s.imageId}
                    render={({ open }) => (
                      <div style={{ marginBottom: 12 }}>
                        {s.imageUrl && <img src={s.imageUrl} alt="" style={{ display: 'block', maxWidth: '100%', marginBottom: 8, borderRadius: 4 }} />}
                        <Button variant="secondary" isSmall onClick={open}>{s.imageUrl ? 'Replace image' : 'Select image'}</Button>
                        {s.imageUrl && <Button variant="tertiary" isSmall isDestructive onClick={() => clearSlideImage(index)} style={{ marginLeft: 8 }}>Remove</Button>}
                      </div>
                    )}
                  />
                </MediaUploadCheck>
                <TextControl     label="Eyebrow"  value={s.eyebrow}  onChange={(v) => updateSlide(index, 'eyebrow', v)} />
                <TextareaControl label="Title (HTML allowed, wrap accent in <em>…</em>)" value={s.title} onChange={(v) => updateSlide(index, 'title', v)} rows={2} />
                <TextareaControl label="Subtitle" value={s.subtitle} onChange={(v) => updateSlide(index, 'subtitle', v)} rows={3} />
                <TextControl     label="Accent label (right rail)" value={s.accent} onChange={(v) => updateSlide(index, 'accent', v)} />
              </CardBody>
            </Card>
          ))}
          <Button icon={plus} variant="secondary" onClick={addSlide} style={{ width: '100%', justifyContent: 'center' }}>Add slide</Button>
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: 'hero-carousel-editor' })} style={{
        position: 'relative',
        minHeight: 360,
        padding: 40,
        color: '#f7f6f0',
        background: active.imageUrl
          ? `linear-gradient(180deg, rgba(8,28,22,.55), rgba(8,28,22,.92)), url(${active.imageUrl}) center/cover`
          : 'linear-gradient(160deg, #1f4a3a, #0f2018)',
        borderRadius: 8,
        overflow: 'hidden',
      }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1.4fr 1fr', gap: 32, alignItems: 'end', minHeight: 280 }}>
          <div>
            {active.eyebrow && <div className="eyebrow" style={{ color: '#e8c46a', marginBottom: 18 }}>{active.eyebrow}</div>}
            {active.title && <h1 className="display" style={{ fontSize: 'clamp(36px, 5vw, 72px)', margin: 0, maxWidth: '14ch' }} dangerouslySetInnerHTML={{ __html: active.title }} />}
            {active.subtitle && <p style={{ marginTop: 18, maxWidth: 480, color: 'rgba(255,255,255,.92)', lineHeight: 1.6 }}>{active.subtitle}</p>}
            <div style={{ marginTop: 28, display: 'flex', gap: 12 }}>
              <span style={{ padding: '12px 22px', background: '#e8c46a', color: '#1f4a3a', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999, fontWeight: 600 }}>{primaryCta}</span>
              <span style={{ padding: '12px 22px', border: '1px solid rgba(255,255,255,.5)', color: '#f7f6f0', fontSize: 12, letterSpacing: '0.18em', textTransform: 'uppercase', borderRadius: 999 }}>{secondaryCta}</span>
            </div>
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 12, alignItems: 'flex-end' }}>
            {slides.map((s, idx) => (
              <button key={idx} onClick={() => setCurrent(idx)} style={{
                background: 'transparent', border: 0, cursor: 'pointer', padding: 0,
                display: 'flex', alignItems: 'center', gap: 12,
                color: idx === current ? '#e8c46a' : 'rgba(255,255,255,.55)',
                fontSize: 12, letterSpacing: '0.15em', textTransform: 'uppercase',
              }}>
                <span style={{ fontFamily: '"JetBrains Mono", monospace', fontSize: 11 }}>{String(idx + 1).padStart(2, '0')}</span>
                <span style={{ display: 'inline-block', height: 1, width: idx === current ? 48 : 20, background: 'currentColor', transition: 'width 250ms' }} />
                <span>{s.accent || `Slide ${idx + 1}`}</span>
              </button>
            ))}
          </div>
        </div>
      </div>
    </>
  );
}
