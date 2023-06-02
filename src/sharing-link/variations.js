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
			url: 'https://www.facebook.com/sharer.php?display=page&u=lsx_sharing_url&t=lsx_sharing_title',
		},
		title: 'Facebook Share',
		icon: FacebookIcon
	},
	{
		name: 'mail',
		attributes: {
			service: 'mail',
			url: 'mailto@lsx_sharing_email',
		},
		title: 'Mail (popup)',
		keywords: ['email', 'e-mail'],
		icon: MailIcon
	},
	{
		name: 'pinterest',
		attributes: {
			service: 'pinterest',
			url: 'https://www.pinterest.com/pin/create/button/?url=lsx_sharing_url&media=lsx_sharing_image&description=lsx_sharing_title',
		},
		title: 'Pinterest Share',
		icon: PinterestIcon
	},
	{
		name: 'twitter',
		attributes: {
			service: 'twitter',
			url: 'https://twitter.com/intent/tweet?text=lsx_sharing_title&url=lsx_sharing_url',
		},
		title: 'Tweet Share',
		icon: TwitterIcon
	},
	{
		name: 'whatsapp',
		attributes: {
			service: 'whatsapp',
			url: 'https://api.whatsapp.com/send?lsx_sharing_title - lsx_sharing_url',
		},
		title: 'Whatsapp Share',
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