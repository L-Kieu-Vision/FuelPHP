
var app = angular.module('myApp', ['ngRoute']);
	app.config(function($routeProvider) {
		$routeProvider
		.when('/', {
			templateUrl: 'themes/main/html/listusers/index.html',
			controller: 'userController',
		})
		
	});