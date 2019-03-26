# LSX Sharing

## Description
LSX Sharing adds buttons to your posts & pages that your readers can use to share your content on Facebook, Twitter and Pinterest.

## Works with the LSX Theme
We've also made a fantastic [theme](https://lsx.lsdev.biz/) that work well with the LSX Sharing plugin.

## Gutenberg Compatible
Have you updated to the new WordPress Gutenberg editor? We've got you covered! [The LSX Theme](https://lsx.lsdev.biz/) and all of its extensions have been optimised to work perfectly with the new Gutenberg update.

## It's free, and always will be.
We’re firm believers in open source - that’s why we’re releasing the LSX Sharing plugin for free, forever.

## Support
We offer premium support for this plugin. Premium support that can be purchased via [lsdev.biz](https://www.lsdev.biz/services/support/).

## Works with the LSX Theme
LSX Sharing is an [LSX Theme](http://lsx.lsdev.biz/) powered plugin. It integrates seamlessly with the core LSX functionality to provide you will powerful options for creating your online Sharing.

## Actively Developed
The LSX Sharing plugin is actively developed with new features and exciting enhancements all the time. Keep track on the LSX Sharing GitHub repository. Report any bugs via github issues.

## Installation
You can also download and install the extension directly from the backend of your website:

1. Login to the backend of your website.
2. Navigate to the “Plugins” dashboard item.
3. Select the “Add New” option on the plugins page.
4. Search for “LSX Sharing” in the plugin search bar.
5. Download and activate the plugin.

## Frequently Asked Questions

### Where can I find LSX Sharing plugin documentation and user guides?
For help setting up and configuring the Sharing plugin please refer to our [user guide](https://www.lsdev.biz/documentation/lsx/lsx-sharing-extension/)

### Where can I get support or talk to other users
For help with premium add-ons from LightSpeed, use [our contact page](https://www.lsdev.biz/contact-us/) to request a quote.

### Will the LSX Sharing plugin work with my theme?
No; the LSX Projects plugin requires some styling and functionality only available from the [The LSX Theme](http://lsx.lsdev.biz/). You need to install the [The LSX Theme](http://lsx.lsdev.biz) for this extension to work properly.

### Where can I report bugs or contribute to the project?
Bugs can be reported on the [LSX Sharing GitHub repository](https://github.com/lightspeeddevelopment/lsx/issues).

### The LSX Sharing plugin is awesome! Can I contribute?
Yes you can! Join in on our [GitHub repository](https://github.com/lightspeeddevelopment/lsx-sharing/) :)

### Credit
The LSX sharing plugin was developed with the use of Jetpack & Sharedaddy’s open source software.  

## Changelog

### 1.1.0
* Dev -  Removing API file.
* Dev - making sure that there are no code sniffer errors.

## Upgrade Notice

### 1.1.0
Removing API file and making sure that there are no code sniffer errors.

## Shortcode
```
[lsx_sharing_buttons buttons="email,facebook,twitter,pinterest"]
```

## PHP function
```
$buttons = array( 'email', 'facebook', 'twitter', 'pinterest' );
$echo = false;
$lsx_sharing->sharing_buttons( $buttons, $echo );
```
