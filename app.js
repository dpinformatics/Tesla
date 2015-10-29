(function () {

    // module - application
    angular.module('tesla', [])

        // create the factory - service
        .factory('TeslaService', ['$http', '$interval', function ($http, $interval) {

            // declare data
            var d = {
                trips: [],
                trip: [],
                logindata: {login:'nok'},
                loading: false,
                loadedcar: 0 // car id for which trips are loaded
            };
            
            // function to update trips data for the car we are logged in for.
            d.updatecar = function () {
               if(d.logindata.login != "nok" && d.logindata.car <> d.loadedcar) {
                    d.controllermethods.servercall({action: "gettrips"});  
                    d.loadedcar = d.logindata.car;
               }
            }();
            $interval(d.updatecar, 1000);

            // add service methods
            d.controllermethods = {
                servercall: function (arguments) {
                    angular.copy(true, d.loading);
                    //d.loading = true;
                    console.debug('in servercall' + arguments);
                    // server returns data of type 'key'...
                    $http({
                        method: 'GET',
                        url: '/server.php',
                        params: arguments
                    }).success(function (data) {
                        console.debug(data.key);
                        angular.copy(data.data, d[data.key]);
                        console.debug(d[data.key]);
                        d.loading = false;
                    }).error(function (data) {
                        alert('error in call server ' + arguments.action);
                        d.loading = false;
                    });
                }
            };

            //return service
            return d;
        }])

        // Create the controller
        .controller('TeslaController', ['$scope', 'TeslaService', '$interval', function ($scope, TeslaService, $interval) {

            // link sercice to controller
            this.trips = TeslaService.trips;
            this.trip = TeslaService.trip;
            this.methods = TeslaService.controllermethods;
            this.loading = TeslaService.loading;
            this.logindata = TeslaService.logindata;

            // initialize controller data
            this.view = '';
            this.activetrip = 0;
            this.activewaypoint = 0;
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

            this.description = '';
            this.date = '';

            this.password = '';

            // add controller methods
            this.changeview = function (v, id) {
                console.debug('changing view to ' + v + ' for id ' + id);
                this.view = v;
                this.activetrip = id;

                switch (v) {
                    case "list": // we want to see all trips...
                        TeslaService.controllermethods.servercall({action: "gettrips", car: this.logindata.car});
                        break;

                    case "view": // we want to see 1 trip...
                        TeslaService.controllermethods.servercall({action: "gettrip", tripid: id});
                        break;

                    default:
                    // Nothing special to do here...
                }
            };

            this.login = function (c, p) {
                console.debug('login for car ' + c + ' and password ' + p);
                TeslaService.controllermethods.servercall({action: 'login', car: c, password: p});
                if (this.logindata. login != 'nok'){
                    this.changeview('list', 0);
                }
            };



            // initialize view
            this.changeview('login', 0);

        }]);

})();
