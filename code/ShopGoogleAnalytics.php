<?php
/**
 * This is really only used for configuration
 *
 * @author Mark Guinn <mark@adaircreative.com>
 * @date 09.10.2014
 * @package shop_googleanalytics
 */
class ShopGoogleAnalytics extends SS_Object
{
    /** @var string - UA-XXXXX-X */
    private static $web_property_id = '';

    /** @var bool - if this is true, analytics will be disabled until the site is live */
    private static $disable_on_dev = true;

    /** @var string - if an item in the cart is not tied to a product, this is prepended to the sku */
    private static $no_sku_prefix = 'Product-';

    /** @var bool - in case another analytics module is in use for other pages */
    private static $disable_pageviews = false;

    /** @var bool - if false, you must include a $GoogleAnalyticsJS tag in your layout template */
    private static $use_requirements = true;

    /** @var string - allows different types of analytics code to be used - in theory even non-google */
    private static $tracking_type = 'Classic';

    /** @var array - this maps different actions to template files containing the actual javascript */
    private static $tracking_code = array(
        'Classic' => array(
            'PageView'      => 'GAClassicPageView',
            'Conversion'    => 'GAClassicConversion',
        ),
        'Universal' => array(
            'PageView'      => 'GAPageView',
            'Conversion'    => 'GAConversion',
        ),
        // TODO: add features for "enhanced ecommerce" version
    );

    /**
     * It's preferable to use this as an access point in case we
     * add support for multiple property id's in the future (subsites for example)
     * @return string
     */
    public static function get_property_id()
    {
        if (self::config()->disable_on_dev && !Director::isLive()) {
            return '';
        }

        return self::config()->web_property_id;
    }
}
