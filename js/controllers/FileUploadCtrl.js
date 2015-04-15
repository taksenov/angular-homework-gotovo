/**
 * Created by admin on 10.04.2015.
 */
app.controller('FileUploadCtrl', ['$scope',
                                    'filesFactory',
                                    'oiList',
                                    '$rootScope',
                                    function (
                                        $scope,
                                        filesFactory,
                                        oiList,
                                        $rootScope
                                    ) {

    var url = 'action.php/files/',
        addOrEdit = 1;

    oiList($scope, url, filesFactory, {fields: {thumb: 'files_thumb/preloader.gif'}});

    // -- ссылка на несуществующую картинку для ошибки
    $scope.objThumbLink = 'static/img/none.png';

    $scope.uploadoptions = {
        change: function (file) {
            //Создаем пустой элемент для будущего файла
            $scope.add('after', function (i, data) {

                file.$preview($scope.items[i]).then(
                    function (data) {
                        console.log('preview success', data)
                    },
                    function (data) {
                        console.log('preview error', data);
                    }
                );

                file.$upload(url + data.id, $scope.items[i], data.settings).then(
                    function (data) {
                        var jsonString = data['response'],
                            obj = jQuery.parseJSON(jsonString);

                        $scope.objThumbLink = obj.thumb;
                        $rootScope.objThumbLink = obj.thumb;

                        if ( addOrEdit === 0 ) {
                            $scope.data.userpic = obj.thumb;
                        }

                        console.log('upload success', data);
                    },
                    function (data) {
                          console.log('upload error', data);
                          $scope.errors = angular.isArray($scope.errors) ? $scope.errors.concat(data.response) : [].concat(data.response);
                          $scope.del($scope.getIndexById(data.item.id));
                    },
                    function (data) {
                        console.log('upload notify', data)
                    }
                )

            })
        }
    }

  	$scope.addNewUserpic = function() {
        addOrEdit = 1;
        $scope.objThumbLink = '';
        // -- открыть FileOpenDialog
        document.getElementById("file1").click();
  	};

    $scope.newContactSetNonePNGUserpic = function() {
        addOrEdit = 1;
        // -- ссылка на несуществующую картинку для ошибки
        $scope.objThumbLink = 'static/img/none.png';
        $rootScope.objThumbLink = 'static/img/none.png';
    }

  	$scope.addNewUserpicEdit = function() {
        addOrEdit = 0;
        $scope.objThumbLink = '';
        // -- открыть FileOpenDialog
        document.getElementById("file1").click();
  	};

    $scope.newContactSetNonePNGUserpicEdit = function() {
        addOrEdit = 0;
        // -- ссылка на несуществующую картинку для ошибки
        $scope.objThumbLink = 'static/img/none.png';
        $rootScope.objThumbLink = 'static/img/none.png';
        $scope.data.userpic = 'static/img/none.png';
    }


}]);
