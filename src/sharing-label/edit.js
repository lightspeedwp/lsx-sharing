import { TextControl, CheckboxControl, Panel, PanelBody } from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import classnames from 'classnames';

import { __ } from '@wordpress/i18n';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps({
		className: classnames({
			'has-icon': !!attributes.icon,
		})
	});

	return (
		<li { ...blockProps }>
			<InspectorControls>
				<Panel>
					<PanelBody title={ __('Icon','lsx-sharing') }>
						<CheckboxControl
							label={ __('Display Icon','lsx-sharing') }
							help={ __('Display the sharing icon before the label','lsx-sharing') }
							checked={ attributes.icon }
							onChange={ ( val ) => setAttributes( { icon: val } ) }
						/>
					</PanelBody>
				</Panel>
			</InspectorControls>
			<TextControl
				value={ attributes.label }
				onChange={ ( val ) => setAttributes( { label: val } ) }
				placeholder={ __('Share to','lsx-sharing') }
			/>
		</li>
	);
}
