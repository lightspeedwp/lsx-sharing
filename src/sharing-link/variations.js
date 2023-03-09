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
		title: 'FB Share',
		icon: FacebookIcon
	},
	{
		name: 'mail',
		attributes: {
			service: 'mail'
		},
		title: 'Mail Share',
		keywords: ['email', 'e-mail'],
		icon: MailIcon
	},
	{
		name: 'pinterest',
		attributes: {
			service: 'pinterest'
		},
		title: 'Pin It',
		icon: PinterestIcon
	},
	{
		name: 'twitter',
		attributes: {
			service: 'twitter'
		},
		title: 'Tweet',
		icon: TwitterIcon
	},
	{
		name: 'whatsapp',
		attributes: {
			service: 'whatsapp'
		},
		title: 'Send',
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