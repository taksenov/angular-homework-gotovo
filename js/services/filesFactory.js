/**
 * Created by admin on 10.04.2015.
 */
app.factory('filesFactory', function ($resource) {
    return $resource('action.php/files/:fileId', {fileId:'@id'}, {
        add: {method: 'PUT'}
    })
});