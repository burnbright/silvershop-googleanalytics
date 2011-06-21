<?php
DataObject::add_extension('Order', 'EcommerceGoogleAnaltyicsOrderDecorator');

Object::add_extension('CheckoutPage_Controller', 'EcommerceGoogleAnaltyics');
Object::add_extension('AccountPage_Controller', 'EcommerceGoogleAnaltyics');