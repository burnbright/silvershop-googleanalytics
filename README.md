# Shop Google Analytics Tracking

## Setup

Add the following to your yaml config:
```yaml
ShopGoogleAnalytics:
  web_property_id: UA-XXXXXX-X
```

Some additional configuration options (all on the ShopGoogleAnalytics object):

* disable_on_dev - by default analytics code is not included unless Director::isLive() is true, setting this to false
                   causes it to show up any time
* no_sku_prefix - if an item doesn't have a SKU/InternalItemID set we need to send something to google using this prefix and the ID
* disable_pageviews - don't include the basic pageview code. set this to true if using another analytics module such
                      as silverstripe/googleanalytics
* use_requirements - default is true, which uses Requirements::customScript to insert the tracking code. Set this to false
                     and add $GoogleAnalyticsJS to your template to have more control over where the code goes.
* tracking_type - 'Universal' or 'Classic' - default is Classic to maintain backwards compatibility
* tracking_code - allows finer grained control over the templates used to generate the javascript


## TODO / Room for improvement:

 * Optionally allow recording actions: add to cart, remove, set quantity.
 * Integrate analtyics reporting into CMS for an order. This way site owners an see additional info for the customer.
