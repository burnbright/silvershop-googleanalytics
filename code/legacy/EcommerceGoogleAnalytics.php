<?php

class EcommerceGoogleAnaltyics extends Extension {

	static function set_account_id($id){
		Config::inst()->update('ShopGoogleAnalytics', 'web_property_id', $id);
	}

}


class EcommerceGoogleAnaltyicsOrderDecorator extends DataExtension {
}
