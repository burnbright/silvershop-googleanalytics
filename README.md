Ecommerce Google Analytics Tracking
===================================

Setup
-----

Enable ecommerce tracking in your google analytics profile settings for the site you want to track on.

Add the following configuration to your _config.php file:

	EcommerceGoogleAnaltyics::set_account_id("UA-XXXXXXX-X");


Google documentation links:
---------------------------

http://code.google.com/apis/analytics/docs/tracking/gaTrackingEcommerce.html
http://code.google.com/apis/analytics/docs/gaJS/gaJSApiEcommerce.html


TODO / Room for improvement:
----------------------------

 * Optionally allow recording actions: add to cart, remove, set quantity.
 * Integrate analtyics reporting into CMS for an order. This way site owners an see additional info for the customer.