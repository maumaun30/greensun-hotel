import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder } from '@wordpress/components';

/**
 * The Explore block renders server-side from baked default content
 * (on-site establishments + neighborhood grid). The editor shows a
 * lightweight placeholder; content/styling appear on the front end.
 */
export default function Edit() {
  return (
    <div {...useBlockProps()}>
      <Placeholder
        icon="location-alt"
        label="Explore"
        instructions="Renders the “Under our roof” on-site establishments and the Chino Roces neighborhood grid. Content is server-rendered; preview on the front end."
      />
    </div>
  );
}
