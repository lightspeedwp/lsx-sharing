/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { share as icon } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import variations from './variations';
import edit from './edit';
import save from './save';

metadata.icon = icon;
metadata.variations = variations;
metadata.edit = edit;
metadata.edit = save;

registerBlockType( 
	metadata.name, metadata
);
