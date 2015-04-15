app.factory('firebaseFactory', ['fbURL','$firebaseArray', '$firebaseObject', function (fbURL, $firebaseArray, $firebaseObject) {
	var fb = {},
		ref = new Firebase(fbURL + 'users'),
        syncArr = $firebaseArray(ref),
        syncObj = $firebaseObject(ref);

	fb.listContacts = function() {
  		return syncArr;
	};
	
	fb.editContact = function(id) {

		var url = fbURL + 'users/' + id,
		    ref = new Firebase(url),
     		sync = $firebaseObject(ref);

     	return sync;

	};


	fb.addContact = function(arr) {
		var date = new Date();
		arr.date = +date;
		return syncArr.$add(arr);
	};

	fb.saveContact = function(obj) {

		var urlOfUser = fbURL + 'users/' + obj.$id,
            ref = new Firebase(urlOfUser);

        ref.set({
            email: obj.email,
            phone: obj.phone,
            userLastName: obj.userLastName,
            userName: obj.userName,
            userpic: obj.userpic
        });

		return ref;
	};


	fb.deleteContact = function(obj) {

		var urlOfUser = fbURL + 'users/' + obj.$id,
            ref = new Firebase(urlOfUser);

		return ref.remove();

	};

	return fb;
}]);
