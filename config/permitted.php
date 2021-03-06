<?php

$permitted_actions = [
	'User::stayLogged',
	'User::verify',
	'User::registrate',
	'User::recoverPass',
	'User::changePass',
	'Order::add',
	'Order::delete',
	'Callback::add',
	'Search::find',
	'Product::getFullPriceCookie',
	'Exchange::get'
];

$logged_actions = [
	'User::updateFavorite',
	'User::getFavorite',
	'User::getOrders',
];

$admin_actions = [
	'Exchange::set',
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
