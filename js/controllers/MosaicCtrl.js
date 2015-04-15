/**
 * Created by admin on 08.04.2015.
 */

app.controller('MosaicCtrl', [
                                    '$scope',
                                    '$rootScope',
                                    '$firebase',
                                    'fbURL',
                                    'firebaseFactory',
                                    '$location',
                                    function (
                                        $scope,
                                        $rootScope,
                                        $firebase,
                                        fbURL,
                                        firebaseFactory,
                                        $location
                                    ){

    $scope.data = firebaseFactory.listContacts();

    $scope.title = 'Контакты';

    $rootScope.pageName = 'mosaic';

    $scope.deleteContact = function(id) {
        firebaseFactory.deleteContact(id);
    };

    $scope.redirectToAddContact = function () {
        $location.path("new");
    };

}]);