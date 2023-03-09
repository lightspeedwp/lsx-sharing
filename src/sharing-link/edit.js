/**
 * Wordpress Dependancies
 */
import { createElement, Fragment } from "@wordpress/element";
import { useBlockProps } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

/**
 * Internal Dependancies
 */
import { getIconBySite, getNameBySite } from './social-list';
import classNames from 'classnames';

export default function Edit( props ) {
	let {
		attributes,
		context,
		isSelected,
		setAttributes
	} = props;

	console.log(attributes);

	const {
		url,
		service,
		label,
		rel
	} = attributes;

	const {
		showLabels,
		iconColorValue,
		iconBackgroundColorValue
	} = context;

	const classes = classNames('wp-social-link', 'wp-social-link-' + service, {
		'wp-social-link__is-incomplete': !url
	  });

	const IconComponent = getIconBySite(service);
	const socialLinkName = getNameBySite(service);
	const socialLinkLabel = label !== null && label !== void 0 ? label : socialLinkName;

	const blockProps = useBlockProps({
		className: classes,
		style: {
			color: iconColorValue,
			backgroundColor: iconBackgroundColorValue
		}
	});

	let html = createElement(
		"li",
		blockProps, 
		createElement(
			Button,
			{
				className: "wp-block-social-link-anchor",
			},
			createElement( IconComponent, null),
			createElement( 
				"span",
				{
					className: classNames('wp-block-social-link-label', { 'screen-reader-text': !showLabels } )
	  			}, 
				socialLinkLabel
			),
		)
	);
	return html;
}