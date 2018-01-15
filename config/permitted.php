<?php

$permitted_actions = [
	'User::saveLogged',
	'User::verify',
	'User::registrate',
	'Order::add',
	'Order::delete',
	'Callback::add',
	'Search::find',
	'Product::getFullPriceCookie',
	#

	'User::updateFavorite',
	'User::getFavorite',
	'User::getOrders',
	#
	'Callback::get',
	'Callback::delete',
	'Order::getUnaccepted',
	'Order::getAccepted',
	'Order::check',
	'Order::accept',
	'Product::get',
	'Product::update',
	'Product::upload',
	'Product::getApprox',
	'Product::setDiscount',
	'Product::offDiscount',
	'Input::excelUpload',
	'Category::getCategories',
	'Category::getParams',
	'Category::getValues',
	'Category::getFullCategory',
	'Category::addValues',
	'Category::addParams',
	'Category::newCategory',
	'Article::upload'
];

$logged_actions = [

];

$admin_actions = [

];
