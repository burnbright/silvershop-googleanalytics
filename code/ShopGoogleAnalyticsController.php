<?php
/**
 * Adds various kinds of analytics events
 *
 * @author Mark Guinn <mark@adaircreative.com>
 * @date 09.10.2014
 * @package shop_googleanalytics
 */
class ShopGoogleAnalyticsController extends Extension {

	protected $snippets = array();

	/**
	 * Add in global analytics code as appropriate
	 */
	public function onAfterInit() {
		if ($this->GAWebPropertyID()) {
			// record page view if appropriate
			if (!ShopGoogleAnalytics::config()->disable_pageviews) {
				$this->addGASnippet('PageView');
			}

			// record conversion if appropriate
			if ($this->owner->hasMethod('orderfromid')) {
				$order = $this->owner->orderfromid();
				if ($order && $order->IsConversion() && !$order->AnalyticsSubmitted) {
					$this->addGASnippet('Conversion', $order);
					// NOTE: there's a little bit of a risk here that this could happen on a redirect page or something
//					$order->AnalyticsSubmitted = true;
//					$order->write();
				}
			}
		}
	}


	/**
	 * @param string $js
	 * @param Order $order [optional]
	 * @return $this
	 */
	public function addGASnippet($name, $order = false) {
		$cfg      = ShopGoogleAnalytics::config();
		$type     = $cfg->tracking_type;
		$snippets = $cfg->tracking_code;

		if (isset($snippets[$type]) && isset($snippets[$type][$name])) {
			$js = $this->owner->renderWith($snippets[$type][$name], array(
				'Order' => $order,
				'GAWebPropertyID' => $this->GAWebPropertyID(),
			));
		}

		if (!empty($js)) {
			if (ShopGoogleAnalytics::config()->use_requirements) {
				Requirements::customScript($js, md5($js));
			} else {
				$this->snippets[] = $js;
			}
		}

		return $this->owner;
	}


	/**
	 * @return string
	 */
	public function GAWebPropertyID() {
		return ShopGoogleAnalytics::get_property_id();
	}


	/**
	 * @return string
	 */
	public function GoogleAnalyticsJS() {
		if (empty($this->snippets)){
			return '';	
		}

		return "<script type=\"text/javascript\">\n" . implode("\n", $this->snippets) . "</script>\n";
	}
} 
