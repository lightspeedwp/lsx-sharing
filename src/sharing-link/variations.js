/**
 * Internal dependencies
 */
import { FacebookIcon, MailIcon, PinterestIcon, TwitterIcon, WhatsAppIcon } from './icons';

const variations = [
	{
		isDefault: true,
		name: 'facebook',
		attributes: {
			service: 'facebook',
		},
		title: 'Facebook',
		icon: FacebookIcon
	},
	{
		name: 'mail',
		attributes: {
			service: 'mail'
		},
		title: 'Mail',
		keywords: ['email', 'e-mail'],
		icon: MailIcon
	},
	{
		name: 'pinterest',
		attributes: {
			service: 'pinterest'
		},
		title: 'Pinterest',
		icon: PinterestIcon
	},
	{
		name: 'twitter',
		attributes: {
			service: 'twitter'
		},
		title: 'Twitter',
		icon: TwitterIcon
	},
	{
		name: 'whatsapp-share',
		attributes: {
			service: 'whatsapp'
		},
		title: 'WhatsApp Share',
		icon: WhatsAppIcon
	}
];


/**
 * Add `isActive` function to all `social link` variations, if not defined.
 * `isActive` function is used to find a variation match from a created
 *  Block by providing its attributes.
 */

variations.forEach(variation => {
  if (variation.isActive) return;

  variation.isActive = (blockAttributes, variationAttributes) => blockAttributes.service === variationAttributes.service;
});
export default variations;
//# sourceMappingURL=variations.js.map