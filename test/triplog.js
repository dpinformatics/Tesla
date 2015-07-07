(function () {
    angular.module('tesla', [])
        .factory('TeslaService', ['$http', function ($http) {
            var d = {
                trips: [],
                activetrip: 0,
                view: 'list'
            };


            d.controllermethods = {
                servercall: function (arguments) {
                    console.debug('in servercall');
                    //server should always return trip data...
                    $http({
                        method: 'GET',
                        url: '/server.php',
                        params: arguments
                    }).success(function (data) {
                        d.trips = data.trips;
                    }).error(function (data) {

                    });
                }
            };


            //return service
            return d;
        }])
        .controller('TeslaController', ['$scope', 'TeslaService', function ($scope, TeslaService) {
            $scope.trips = TeslaService.trips;
            $scope.view = TeslaService.view;
            $scope.methods = TeslaService.controllermethods;

            //call gettrips to initialise the data...
            TeslaService.controllermethods.servercall({action: "gettrips"});
        }]);

})();