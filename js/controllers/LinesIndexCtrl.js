/**
 * Created by admin on 08.04.2015.
 */

app.controller('LinesIndexCtrl', [
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

    $rootScope.pageName = 'lines-index';

    $scope.deleteContact = function(id) {
        firebaseFactory.deleteContact(id);
    };

    $scope.redirectToAddContact = function () {
        $location.path("new");
    };

}]);





















//contactsangular
// users
// 2
// email:
// phone:
// userLastName:
// userName:
// userpic:
// 3
// email:
// phone:
// userLastName:
// userName:
// userpic:
// uid:1
// email:
// phone:
// userLastName:
// userName:
// userpic: