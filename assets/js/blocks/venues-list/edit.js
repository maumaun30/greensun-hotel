import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Button, Card, CardBody, CardHeader, Flex, FlexItem, FlexBlock, Notice, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';
import { plus, chevronUp, chevronDown, trash, page } from '@wordpress/icons';

const VenuePicker = ({ value, onChange }) => {
  const { venues, isLoading } = useSelect((select) => {
    const args = { per_page: 50, orderby: 'menu_order', order: 'asc' };
    return {
      venues:    select(coreDataStore).getEntityRecords('postType', 'venue', args) || [],
      isLoading: select(coreDataStore).isResolving('getEntityRecords', ['postType', 'venue', args]),
    };
  }, []);

  if (isLoading && !venues.length) return <Spinner />;
  if (!venues.length) return <Notice status="info" isDismissible={false}>No venues published yet. Create one under <strong>Venues → Add New</strong>.</Notice>;

  const selectedSet = new Set(value);
  const remaining   = venues.filter((v) => !selectedSet.has(v.id));

  return (
    <>
      {value.length === 0 && (
        <Notice status="info" isDismissible={false}>Pick venues to feature, or leave empty to auto-load the first {6} by menu order.</Notice>
      )}
      <ul style={{ margin: 0, padding: 0, listStyle: 'none' }}>
        {value.map((id, idx) => {
          const v = venues.find((x) => x.id === id);
          return (
            <li key={id} style={{ padding: '8px 0', borderBottom: '1px solid #e0e0e0', display: 'flex', alignItems: 'center', gap: 8 }}>
              <span style={{ flex: 1 }}>{v ? v.title.rendered : `Venue #${id}`}</span>
              <Button icon={chevronUp}   isSmall disabled={idx === 0} onClick={() => onChange(reorder(value, idx, idx - 1))} label="Move up" />
              <Button icon={chevronDown} isSmall disabled={idx === value.length - 1} onClick={() => onChange(reorder(value, idx, idx + 1))} label="Move down" />
              <Button icon={trash} isSmall isDestructive onClick={() => onChange(value.filter((x) => x !== id))} label="Remove" />
            </li>
          );
        })}
      </ul>
      {remaining.length > 0 && (
        <div style={{ marginTop: 12 }}>
          <div style={{ fontSize: 11, letterSpacing: '0.06em', textTransform: 'uppercase', marginBottom: 6, color: '#757575' }}>Add a venue</div>
          {remaining.map((v) => (
            <Button key={v.id} icon={page} isSmall variant="tertiary" onClick={() => onChange([...value, v.id])} style={{ display: 'block', textAlign: 'left' }}>
              {v.title.rendered}
            </Button>
          ))}
        </div>
      )}
    </>
  );
};

const reorder = (arr, from, to) => {
  const next = [...arr];
  const [removed] = next.splice(from, 1);
  next.splice(to, 0, removed);
  return next;
};

export default function Edit({ attributes, setAttributes }) {
  const { featuredVenues, fallbackCount } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title="Featured venues" initialOpen>
          <VenuePicker value={featuredVenues || []} onChange={(v) => setAttributes({ featuredVenues: v })} />
        </PanelBody>
        <PanelBody title="Fallback" initialOpen={false}>
          <RangeControl
            label="Number to show when none selected"
            value={fallbackCount}
            min={1}
            max={12}
            onChange={(v) => setAttributes({ fallbackCount: v })}
          />
        </PanelBody>
      </InspectorControls>

      <section {...useBlockProps({ className: 'venues-list-editor' })} style={{ padding: 32, border: '1px dashed #c0c0c0', borderRadius: 4 }}>
        <div className="eyebrow" style={{ marginBottom: 10 }}>Venues list</div>
        <div className="display" style={{ fontSize: 28 }}>
          {featuredVenues && featuredVenues.length > 0
            ? `${featuredVenues.length} venue${featuredVenues.length === 1 ? '' : 's'} selected`
            : `Will show the first ${fallbackCount} venues by menu order`}
        </div>
        <p style={{ color: '#7b817b', marginTop: 10, fontSize: 13 }}>
          Edit venues under <strong>Venues</strong> in the admin menu. Each venue renders as an alternating image + spec row.
        </p>
      </section>
    </>
  );
}
