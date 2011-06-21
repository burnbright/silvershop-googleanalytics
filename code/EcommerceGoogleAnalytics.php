<?php

class EcommerceGoogleAnaltyics extends Extension{
	
	static $account_id = null;
	static $noskuprefix = 'Product-'; //prefix used when no internal item is given to a product.
	
	static function set_account_id($id){
		self::$account_id = $id;
	} 
		
	function onAfterInit(){
		
		if(self::$account_id && $order = $this->owner->orderfromid("\"AnalyticsSubmitted\" = 0")){
			
			$accountid = self::$account_id;
			$orderid = $order->ID;
			$storename = $this->formatforjs($this->getStoreName());
			$total = $order->Total();
			
			//TODO: tax and shipping
			$tax = $this->formatforjs(null);
			$shipping = $this->formatforjs(null);
			
			$city = $this->formatforjs($order->City);
			$state = $this->formatforjs($order->State);
			$country = $this->formatforjs($order->Country);
			
			$orderitems = $order->Items();
			
			if(!$orderid || !$total || !$orderitems)
				return;
			
			$script = <<<JS
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', "$accountid"]);
				_gaq.push(['_trackPageview']);
				_gaq.push(['_addTrans',
					"$orderid",           // order ID - required
					$storename,  // affiliation or store name
					"$total",          // total - required
					$tax,           // tax
					$shipping,              // shipping
					$city,       // city
					$state,     // state or province
					$country             // country
				]);

JS;

			foreach($orderitems as $item){
								
				$sku = $this->getSKU($item);
				$name = $this->formatforjs($this->getName($item));
				$quantity = $this->formatforjs($item->Quantity);
				$unitprice = $item->UnitPrice();
				$variation = $this->formatforjs(null); //TODO: variations support
		
				// add item might be called for every item in the shopping cart
				// where your ecommerce engine loops through each item in the cart and
				// prints out _addItem for each
				$script .= <<<JS
					_gaq.push(['_addItem',
						"$orderid",     // order ID - required
						"$sku",         // SKU/code - required
						$name,        // product name
						$variation,   // category or variation
						"$unitprice",   // unit price - required
						$quantity     // quantity - required
					]);
											
JS;

			}
			
			$script .= <<<JS
			
				_gaq.push(['_trackTrans']); //submits transaction to the Analytics servers
				
				(function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
				
JS;
			$order->AnalyticsSubmitted = true;
			$order->write();

			Requirements::customScript($script,'ecommercegoogleanalytics');
		}		
	}
	
	protected function formatforjs($val){
		return ($val) ? "\"$val\"" : "null";
	}
	
	protected function getStoreName(){
		return null;
	}
	
	protected function getSKU($item){
		//TODO: make use of buyable
		$sku = null;
		if($item->ProductVariationID){
			$sku = $item->ProductVariation()->Product()->InternalItemID;
			if(!$sku) $sku = self::$noskuprefix.$item->ProductVariation()->Product()->ID;
		}else{
			$sku = $item->Product()->InternalItemID;
			if(!$sku) $sku = self::$noskuprefix.$item->Product()->ID;
		}
		return $sku;
	}
	
	protected function getName($item){
		//TODO: replace this with $item->Buyable()->Title;
		if($item->ProductVariationID){
			$item->ProductVariation()->Product()->Title;
		}
		return $item->Product()->Title;
	}
	
}


class EcommerceGoogleAnaltyicsOrderDecorator extends DataObjectDecorator{
	
	function extraStatics(){
		return array(
			'db' => array(
				'AnalyticsSubmitted' => 'Boolean'
			)
		);
	}
	
}