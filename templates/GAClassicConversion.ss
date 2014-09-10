<% with $Order %>
	_gaq.push(['_addTrans',
		'$JS_val(Reference)'",            // order ID - required
		'$Top.SiteConfig.JS_val(Title)',  // affiliation or store name
		'$Total.RAW',                     // total - required
		'$GATaxTotal',                    // tax
		'$GAShippingTotal',               // shipping
		'$BillingAddress.JS_val(City)',   // city
		'$BillingAddress.JS_val(State)',  // state or province
		'$BillingAddress.JS_val(Country)',// country
	]);

	<% loop $Items %>
		_gaq.push(['_addItem',
			'$Up.JS_val(Reference)'",            // order ID - required
			'<% if $Buyable.InternalItemID %>$Buyable.JS_val(InternalItemID)<% else %>$Top.GANoSkuPrefix$Buyable.ID<% end_if %>', // SKU/code - required
			'$JS_val(TableTitle)',        // product name
			'<% if $Product %><% if $Product.GACategory %>$Product.JS_val(GACategory)<% else %>$Product.Parent.NestedTitle(5,'/')<% end_if %><% end_if %>', // category or variation
			'$UnitPrice.RAW',   // unit price - required
			'$Quantity'         // quantity - required
		]);
	<% end_loop %>

	_gaq.push(['_trackTrans']); //submits transaction to the Analytics servers
<% end_with %>
