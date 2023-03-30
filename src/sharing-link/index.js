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
import Edit from './edit';

metadata.icon = icon;
metadata.variations = variations;
metadata.edit = Edit;

registerBlockType( 
	metadata.name, metadata
);
