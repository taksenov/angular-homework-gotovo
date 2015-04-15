/**
 * Created by admin on 08.04.2015.
 */

app.controller('NewContactCtrl', ['$scope',
                                   '$routeParams',
                                   '$firebase',
                                   'fbURL',
                                   'firebaseFactory',
                                   '$rootScope',
                                   '$location',
                                   function (
                                       $scope,
                                       $routeParams,
                                       $firebase,
                                       fbURL,
                                       firebaseFactory,
                                       $rootScope,
                                       $location
                                   ) {

    $rootScope.pageName = 'addcontact';
    $scope.title = 'Контакт';

  	$scope.addContact = function(arr) {

        arr.userpic = $rootScope.objThumbLink;

  		firebaseFactory.addContact(arr);
        $location.path("/");
  	};

}]);