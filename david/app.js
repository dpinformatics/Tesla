(function () {
    angular.module('tesla', [])
        .factory('TeslaService', ['$http', function ($http) {
            var d = {
                trips: [],
                activetrip: 0,
                trip: []
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
                        angular.copy(data.data, d[data.key]);
                        //d.trips = data.trips;
                    }).error(function (data) {

                    });
                }
            };


            //return service
            return d;
        }])
        .controller('TeslaController', ['$scope', 'TeslaService', '$interval', function ($scope, TeslaService, $interval) {


            this.trips = TeslaService.trips;
            this.trip = TeslaService.trip;
            this.view = 'list';
            this.methods = TeslaService.controllermethods;
            this.activetrip = 0;
            this.status = [
                {
                    id: 0,
                    description: 'onderweg naar'
                },
                {
                    id: 1,
                    description: 'aangekomen in'
                },
                {
                    id: 2,
                    description: 'aan het laden in'
                },
                {
                    id: 3,
                    description: 'klaar om te vertrekken in'
                }
            ];

            this.changeview = function (v, id) {
                console.debug('changing view to ' + v + ' for id ' + id);
                this.view = v;
                this.activetrip = id;

                switch (v) {
                    case "view": // we want to see 1 trip...
                        TeslaService.controllermethods.servercall({action: "gettrip", tripid: id});
                        break;
                    default:
                    //Qnothing special to do here...
                }
                //this.methods.viewtrip(id);
            };



            //call gettrips to initialise the data...
            TeslaService.controllermethods.servercall({action: "gettrips"});
        }]);

})();