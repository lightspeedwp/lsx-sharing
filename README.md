# lsx-sharing

LSX Sharing adds buttons to your posts & pages that your readers can use to share your content on Facebook, Twitter and Pinterest.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/lsx-sharing` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


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
