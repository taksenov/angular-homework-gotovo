/**
 * Created by admin on 08.04.2015.
 */

app.controller('EditContactCtrl', ['$scope',
                                   '$routeParams',
                                   '$firebase',
                                   'fbURL',
                                   'firebaseFactory',
                                   '$location',
                                   'oiList',
                                   function (
                                       $scope,
                                       $routeParams,
                                       $firebase,
                                       fbURL,
                                       firebaseFactory,
                                       $location,
                                       oiList
                                   ) {

	var id = $routeParams.id;

    $scope.title = 'Контакт';
    $scope.id = id;
  	$scope.data = firebaseFactory.editContact(id);

    $scope.saveContact = function(id) {
        firebaseFactory.saveContact(id);
    };

    $scope.deleteContact = function(id) {
        firebaseFactory.deleteContact(id);
        $location.path("/");
    };

}]);











