<?php
/**
 * Adds a field to the order to mark if a conversion has been recorded.
 *
 * @author Mark Guinn <mark@adaircreative.com>
 * @date 09.10.2014
 * @package apluswhs.com
 */
class ShopGoogleAnalyticsOrder extends DataExtension
{
	private static $db = array(
		'AnalyticsSubmitted' => 'Boolean'
	);


	/**
	 * @return bool
	 */
	public function IsConversion() {
		$state = !$this->owner->IsCart();

		// different systems may have some statuses that don't count as a conversion (quotation, etc)
		$this->owner->extend('updateIsConversion', $state);

		return $state;
	}


	/**
	 * This is a very rudimentary method but it should allow
	 * for a variety of site configurations. If it's incorrect,
	 * it's easy enough to override by decorating the Order class.
	 * @return float
	 */
	public function GAShippingTotal() {
		$total = false;
		$this->owner->extend('overrideGAShippingTotal', $total);

		if ($total === false) {
			$total = 0;

			foreach ($this->owner->Modifiers() as $mod) {
				if (strpos($mod->ClassName, 'Shipping') !== false) {
					$total += $mod->Amount();
				}
			}

			$this->owner->extend('updateGAShippingTotal', $total);
		}

		return $total;
	}


	/**
	 * This is a very rudimentary method but it should allow
	 * for a variety of site configurations. If it's incorrect,
	 * it's easy enough to override by decorating the Order class.
	 * @return float
	 */
	public function GATaxTotal() {
		$total = false;
		$this->owner->extend('overrideGATaxTotal', $total);

		if ($total === false) {
			$total = 0;

			foreach ($this->owner->Modifiers() as $mod) {
				if (strpos($mod->ClassName, 'Tax') !== false) {
					$total += $mod->Amount();
				}
			}

			$this->owner->extend('updateGATaxTotal', $total);
		}

		return $total;
	}
}

