(function() {
    
    // let's define our module
    var app = angular.module('denja', ['ngRoute']);


    app.factory('TeslaTripService', ['$interval', '$http', function($interval, $http) {
		var d = {controllermethods: {}
            , status:	[
                    {id: 0,
                        description: 'onderweg naar'
                    },
                    {id: 1,
                        description: 'aangekomen in'
                    },
                    {id: 2,
                        description: 'aan het laden in'
                    },
                    {id: 3,
                        description: 'klaar om te vertrekken in'
                    }
                ]
            , activeobject: 1
            //, trips: [{"id":5,"date":"2015-07-09","name":"Thuis - La Treve - via autoroute de soleil (1)","statusid":1,"status":"Gepland"}]
            //, trip: {"id":"1","desc":"test","statusid":4,"etape":-1,"waypoints":null}
			};

        //d.trips = '[{"id":5,"date":"2015-07-09","name":"Thuis - La Treve - via autoroute de soleil","statusid":1,"status":"Gepland"},{"id":6,"date":"2015-07-09","name":"Thuis - La Treve - via Auxerre","statusid":1,"status":"Gepland"}]';
        //d.trip = '';

        d.controllermethods.gettrips = function () {
            console.debug('gettrips method called');

            // build urlString
            var url = 'server.php?action=gettrips';
            console.debug('url=' + url);

            // get the data
            $http.get(url)
                .success(function (data) {
                    d.trips =  data;
                    console.debug('after rows d.trips : ' + d.trips.length + ' trips - trip 1 = ' + d.trips[0].id + ' - "' + d.trips[0].name +'"');
                })
                .error(function (data) {
                    alert('error in ' + method + ' - ' + data);
                });

        };

        var callServiceForTrip;
        callServiceForTrip = function (method, url) {
            console.debug('url=' + url);
            //console.debug('before rows d.trip : ' + d.trip.length + ' - ' + d.trip.id + ' - ' + d.trip.desc);
            $http.get(url)
                .success(function (data) {
                    d.trip = data;
                    console.debug('after rows d.trip :' + + d.trip.length + ' - ' + d.trip.id + ' - ' + d.trip.desc);
                })
                .error(function (data) {
                    alert('error in ' + method + ' - ' + data);
                });
        };


        d.controllermethods.gettrip = function(tripid) {
            console.debug('gettrip method called');

            // build urlString
            var url = 'server.php?action=gettrip&tripid=' + tripid;

            //call service and get updated data
            callServiceForTrip("GetTrip", url);
		};

        d.controllermethods.savearrival = function() {
            //console.debug('savearrival method called');

            // build urlString
            var url = 'server.php?action=savearrival&tripid=' + d.trip.id;
            url += '&wpid=' + d.trip.waypoints[d.trip.etape].id;
            // add more data from screen
            url += '&arrivaltypical=' + d.trip.waypoints[d.trip.etape].theoretical.arrival.typical;
            url += '&arrivaldistance=' + d.trip.waypoints[d.trip.etape].theoretical.arrival.distance;
            url += '&arrivalconsumption=' + d.trip.waypoints[d.trip.etape].theoretical.arrival.consumption;

            //call service and get updated data
            callServiceForTrip("SaveArrival", url);
            nextstep();

            // hide modal-form
            $('#myArrival').modal('hide');
        };

        d.controllermethods.savechargestart = function() {
            //console.debug('savechargestart method called');

            // build urlString
            var url = 'server.php?action=savechargestart&tripid=' + d.trip.id;
            url += '&wpid=' + d.trip.waypoints[d.trip.etape].id;
            // add more data from screen
            url += '&chargestarttypical=' + d.trip.waypoints[d.trip.etape].theoretical.chargestart.typical;

            //call save and get updated data
            callServiceForTrip("SaveChargeStart", url);
            nextstep();

            // hide modal-form
            $('#myChargeStart').modal('hide');
        };

        d.controllermethods.savechargeend = function() {
            //console.debug('savechargeend method called');

            // build urlString
            var url = 'server.php?action=savechargeend&tripid=' + d.trip.id;
            url += '&wpid=' + d.trip.waypoints[d.trip.etape].id;
            // add more data from screen
            url += '&chargeendtypical=' + d.trip.waypoints[d.trip.etape].theoretical.chargeend.typical;

            //call save and get updated data
            callServiceForTrip("SaveChargeEnd", url);
            nextstep();

            // hide modal-form
            $('#myChargeEnd').modal('hide');
        };

        d.controllermethods.savedeparture = function() {
            //console.debug('savedeparture method called');

            // build urlString
            var url = 'server.php?action=savedeparture&tripid=' + d.trip.id;
            url += '&wpid=' + d.trip.waypoints[d.trip.etape].id;
            console.debug(d.trip.waypoints[d.trip.etape]);
            // add more data from screen
            url += '&departuretypical=' + d.trip.waypoints[d.trip.etape].theoretical.departure.typical;
            url += '&departuredistance=' + d.trip.waypoints[d.trip.etape].theoretical.departure.distance;
            url += '&departureconsumption=' + d.trip.waypoints[d.trip.etape].theoretical.departure.consumption;

            //call save and get updated data
            callServiceForTrip("SaveDeparture", url);
            d.trip.statusid = 3;
            nextstep();

            // hide modal-form
            $('#myDeparture').modal('hide');
        };


        var nextstep = function(){
            d.trip.statusid ++;
            if (d.trip.statusid == 4){
                d.trip.statusid = 0;
                d.trip.etape ++;
            }
        };
        return d;



    }]);

    app.controller('TeslaTripController', ['$scope', 'TeslaTripService',                 
        function($scope, TeslaTripService) {

            // Controller methods

            this.gettrip = function(tripid){
                console.debug('gettrip ' + tripid);
                this.m.gettrip(tripid);
                this.activetrip = tripid;
                this.view = 'detail';
            };

            this.gotolist = function(){
                this.m.gettrips();
                this.m.gettrip(1);
                this.view = 'list';
                this.activetrip = 0;
                this.waypointview = 0;
            };


            // Controller process
            this.m = TeslaTripService.controllermethods;
            this.status = TeslaTripService.status;

            // initialisation
            this.gotolist();
            //this.trips = TeslaTripService.trips;
            //this.trip = TeslaTripService.trip;
        }
    ]);

    $(document).on("click", ".open-WayPointDetail", function () {
        var wpId = $(this).data('wpid');
        var wp = $(this).data('wp');
        $(".modal-header #wpTitle").text("Detail van (" + wpId + ") " + wp.name);
        $(".modal-body #wpId").val("Detail van (" + wpId + ") " + wp.name);
    });



})();

