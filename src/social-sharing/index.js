/**
 * Internal dependencies
 */
import metadata from './block.json';

/**
 * Register our Social Links variation.
 */
wp.blocks.registerBlockVariation( 'core/social-links', {
    name: metadata.name,
    title: metadata.title,
    description: metadata.description,
    isActive: ( { namespace } ) => {
        return (
            namespace === metadata.name
        );
    },
	example: {
		innerBlocks: [{
			name: 'core/social-link',
			attributes: {
			service: 'facebook',
			url: 'https://www.facebook.com/WordPress/'
			}
		}]
	}
});
