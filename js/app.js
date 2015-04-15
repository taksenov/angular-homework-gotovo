/**
 * Created by admin on 02.04.2015.
 */

var app = angular.module('app', ['ngRoute', 'firebase', 'ngResource', 'oi.list', 'oi.file', 'ui.sortable', 'ui.filters', 'ui.focusblur']);

app.value({
    'fbURL': 'https://contactsangular.firebaseio.com/'
});

app.config(['$routeProvider', function ($routeProvider) {
	$routeProvider
		.when('/', {
			templateUrl: 'view/lines-index.html',
			controller: 'LinesIndexCtrl'
		})
		.when('/mosaic', {
			templateUrl: 'view/mosaic.html',
			controller: 'MosaicCtrl'
		})
		.when('/new', {
			templateUrl: 'view/addcontact.html',
			controller: 'NewContactCtrl'
		})
		.when('/edit/:id', {
			templateUrl: 'view/editcontact.html',
			controller: 'EditContactCtrl'
		})
		.otherwise({ redirectTo: '/' });
}]);

// -- директива заменяет картинку с ошибкой на пустую.
app.directive('errSrc', function() {
  return {
    link: function(scope, element, attrs) {
      element.bind('error', function() {
        if (attrs.src != attrs.errSrc) {
          attrs.$set('src', attrs.errSrc);
        }
      });
    }
  }
});

