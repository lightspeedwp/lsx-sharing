/**
 * WordPress dependencies
 */
import { share as icon } from '@wordpress/icons';
/**
 * Internal dependencies
 */
import metadata from './block.json';
import variations from './variations';

metadata.icon = icon;
metadata.variations = variations;

registerBlockType( 
	metadata.name, metadata
);
