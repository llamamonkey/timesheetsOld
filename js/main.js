var app = angular.module('timesheetApp', ['ngMaterial'])
    .config(function ($mdThemingProvider) {
        $mdThemingProvider.theme('default')
            .primaryPalette('green')
            .accentPalette('blue');
    });


app.controller('mainApp', ['$scope', '$mdDialog', '$http', '$mdToast', function ($scope, $mdDialog, $http, $mdToast) {

    $scope.userInfo = [];
    $http.get('http://timesheets-lamamonkey.rhcloud.com/data/userInfo.php')
        .success(function (response) {
            $scope.userInfo = response;
            if ($scope.userInfo == "Not logged in") {
                $mdToast.show($mdToast.simple().content('Not Logged In'));

                $mdDialog.show({
                        controller: DialogController,
                        templateUrl: 'js/templates/login.html',
                        parent: angular.element(document.body),
                    })
                    .then(function (answer) {
                        $scope.alert = 'You said the information was "' + answer + '".';
                    }, function () {
                        $scope.alert = 'You cancelled the dialog.';
                    });
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
                data: {
                    "username": $scope.user.username,
                    "password": $scope.user.password
                }
            })
            .success(function (response) {
                console.log(response);
            });
    };
}