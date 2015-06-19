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


app.controller('mainApp', ['$scope', '$mdDialog', '$http', '$mdToast', function ($scope, $mdDialog, $http, $mdToast) {

    $scope.userInfo = [];
    $http.get('https://timesheets-lamamonkey.rhcloud.com/data/userInfo.php')
        .success(function (response) {
            if (response == "Not logged in") {
                $mdToast.show($mdToast.simple().content('Not Logged In'));

                $mdDialog.show({
                        controller: DialogController,
                        templateUrl: 'js/templates/login.html',
                        parent: angular.element(document.body),
                    })
                    .then(function (answer) {
                        console.log("Yes");
                         $http.get('https://timesheets-lamamonkey.rhcloud.com/data/userInfo.php')
                            .success(function (response){
                                console.log(response);
                         });
                    }, function () {
                        console.log("No");
                        $scope.alert = 'You cancelled the dialog.';
                    });
            } else {
                $scope.userInfo = response;   
            }
        });

    $scope.currentSection = 'Home';
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
                if (response == '"success"'){
                    $mdDialog.hide("success");
                }
            });
    };
}