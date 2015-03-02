ga('require', 'ecommerce');

<% with $Order %>
	ga('ecommerce:addTransaction', {
		'id':           '$JS_val(Reference)',
		'affiliation':  '$Top.SiteConfig.JS_val(Title)',
		'revenue':      '$Total.RAW',
		'shipping':     '$GAShippingTotal',
		'tax':          '$GATaxTotal',
	});

	<% loop $Items %>
		ga('ecommerce:addItem', {
			'id':       '$Up.JS_val(Reference)',
			'name':     '$JS_val(TableTitle)',
			'sku':      '<% if $Buyable.InternalItemID %>$Buyable.JS_val(InternalItemID)<% else %>$Top.GANoSkuPrefix$Buyable.ID<% end_if %>',
			'category': '<% if $Product %><% if $Product.GACategory %>$Product.JS_val(GACategory)<% else %>$Product.Parent.NestedTitle(5,'/')<% end_if %><% end_if %>',
			'price':    '$UnitPrice.RAW',
			'quantity': '$Quantity'
		});
	<% end_loop %>
<% end_with %>

ga('ecommerce:send');
