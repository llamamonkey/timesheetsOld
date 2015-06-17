var app = angular.module('timesheetApp', ['ngMaterial'])
    .config(function ($mdThemingProvider) {
        $mdThemingProvider.theme('default')
            .primaryPalette('green')
            .accentPalette('blue');
    });


app.controller('mainApp', ['$scope', '$mdDialog', '$http', function ($scope, $mdDialog, $http) {

    $scope.userInfo = [];
    $http.jsonp('http://timesheets-lamamonkey.rhcloud.com/data/userInfo.php', {
            params: {
                "callback": "JSON_CALLBACK"
            }
        })
        .success(function (response) {
            console.log('l');
            $scope.userInfo = response;
            console.log($scope.userInfo);
            if ($scope.userInfo == "Not logged in") {
                console.log('Not logged in');
            }
        });

    $scope.currentSection = 'Home';
}]);