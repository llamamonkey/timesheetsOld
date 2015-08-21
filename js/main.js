var app = angular.module('timesheetApp', ['ngMaterial'], function ($httpProvider) {
        // Use x-www-form-urlencoded Content-Type
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

        /**
         * The workhorse; converts an object to x-www-form-urlencoded serialization.
         * @param {Object} obj
         * @return {String}
         */
        var param = function (obj) {
            var query = '',
                name, value, fullSubName, subName, subValue, innerObj, i;

            for (name in obj) {
                value = obj[name];

                if (value instanceof Array) {
                    for (i = 0; i < value.length; ++i) {
                        subValue = value[i];
                        fullSubName = name + '[' + i + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                } else if (value instanceof Object) {
                    for (subName in value) {
                        subValue = value[subName];
                        fullSubName = name + '[' + subName + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                } else if (value !== undefined && value !== null)
                    query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
            }

            return query.length ? query.substr(0, query.length - 1) : query;
        };

        // Override $http service's default transformRequest
        $httpProvider.defaults.transformRequest = [function (data) {
            return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
  }];
    })
    .config(function ($mdThemingProvider) {
        $mdThemingProvider.theme('default')
            .primaryPalette('green')
            .accentPalette('blue');
    });


app.service('userinfoService', function ($mdToast, $mdDialog, $q, $http) {
    var userinfo = [];

    var setUser = function (userData) {
        userinfo = userData;
    }

    var getUser = function () {
        return userinfo;
    }

    var isLoggedIn = function () {
        if (userinfo !== []) {
            return true
        } else {
            return false
        }
    }

    var logOut = function () {
        userinfo = [];
    }

    var logIn = function () {
        var deffered = $q.defer();
        $mdToast.show($mdToast.simple().content('Not Logged In'));
        $mdDialog.show({
                controller: DialogController,
                templateUrl: 'js/templates/login.html',
                parent: angular.element(document.body),
            })
            .then(function (answer) {
                $http.get('testData/userInfo.php') //User Info
                    .success(function (response) {
                        userinfo = response;
                        $mdToast.show($mdToast.simple().content('Welcome ' + response.username));
                        deffered.resolve();
                    });
            }, function () {
                console.log("No");
                $scope.alert = 'You cancelled the dialog.';
            });
            return deffered.promise;
    }

    return {
        setUser: setUser,
        getUser: getUser,
        logOut: logOut,
        logIn: logIn,
        isLoggedIn: isLoggedIn
    }
});

app.controller('mainApp', ['$scope', '$mdDialog', '$http', '$mdToast', 'userinfoService', '$mdSidenav', function ($scope, $mdDialog, $http, $mdToast, userinfoService, $mdSidenav) {
    $scope.currentSection = 'Home';
    
    $scope.toggleSidenav = function(){
        $mdSidenav('main').toggle();
    }
}]);

app.controller('timeView', ['$scope', '$mdDialog', '$http', 'userinfoService', function ($scope, $mdDialog, $http, userinfoService) {
    $scope.days = [];
    $scope.startDay = '';
    $scope.endDay = '';

    if (userinfoService.isLoggedIn()) {
        $http.get('testData/dayInfo.php')
            .success(function (response) {
                $scope.days = response;
            });
    } else {
        userinfoService.logIn().then(function(){
            $http.get('testData/dayInfo.php')
                .success(function (response){
                    $scope.days = response; 
            });
        });
    }
    
    $scope.deleteDay = function(){
        
    }
    
    $scope.addDay = function(ev){
        $mdDialog.show({
                controller: DialogController,
                templateUrl: 'js/templates/addDay.html',
                parent: angular.element(document.body),
                targetEvent: ev
            })
            .then(function (answer) {
                
            }, function () {
                
            });
    }

$scope.confirmDelete = function(ev) {
    // Appending dialog to document.body to cover sidenav in docs app
    var confirm = $mdDialog.confirm()
          .title('Are you sure you want to delete this record?')
          .content('This cannot be undone')
          .ariaLabel('Lucky day')
          .ok('Yes')
          .cancel('No')
          .targetEvent(ev);
    $mdDialog.show(confirm).then(function() {
        //Ok Click
    }, function() {
        //Cancel Clicked
    });
  };
    
}]);

function DialogController($scope, $mdDialog, $http) {
    $scope.hide = function () {
        $mdDialog.hide();
    };
    $scope.close = function () {
        $mdDialog.cancel();
    };
    $scope.login = function () {
        $http.post('https://timesheets-lamamonkey.rhcloud.com/data/login.php', {
                "username": $scope.user.username,
                "password": $scope.user.password,
                "remmberMe": $scope.user.remmber,
                "submit": "1"
            })
            .success(function (response) {
                if (response == '"success"') {
                    $mdDialog.hide("success");
                }
            });
    };
}
