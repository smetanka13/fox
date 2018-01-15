<?php

$permitted_actions = [
	'User::saveLogged',
	'User::verifyEmail',
	'User::registrate',
	'Order::add',
	'Callback::add',
	'Search::find',
	'Product::getFullPriceCookie',
	// --
	'User::updateFavorite',
	'User::getFavorite',
	// --
	'Order::getUnaccepted',
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
