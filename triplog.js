(function() {
    
    // let's define our module
    var app = angular.module('denja', ['ngRoute']);





    app.factory('TeslaTripService', ['$interval', '$http', function($interval, $http) {
		var d = {
			trips: [{"id":4,"date":"19-04-2015","name":"La Vielle Ferme - Thuis(1)","status":"Gepland"}]
            ,
            trip: {
                id: 1,
                desc: 'La Vielle Ferme - Thuis',
                etape: 0,
                statusid: 3,
                waypoints: [
                    {name: 'SuC Macon - La Vielle Ferme',
                        id: 1,
                        location: '',
                        overview: {
                            distance: '',
                            totaldistance: '',
                            typical: '',
                            totaltypical: '',
                            consumption: '',
                            totalconsumption: '',
                            averageconsumption: '',
                            drivetime: '',
                            totaldrivetime: '',
                            chargetime: '',
                            totalchargetime: '',
                            chargeneeded: 340,
                            arrivaltime: '',
                            departuretime: '10:00'
                        },
                        theoretical: {
                            arrival: {
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: 0
                            },
                            chargestart: {
                                typical: 0,
                                time: 0
                            },
                            chargeend: {
                                typical: 0,
                                time: 0
                            },
                            departure:{
                                distance: 0,
                                typical: 340,
                                consumption: 0,
                                time: '10:00'
                            }
                        },
                        efffective: {
                            arrival: {
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: ''
                            },
                            chargestart: {
                                typical: 0,
                                time: ''
                            },
                            chargeend: {
                                typical: 0,
                                time: ''
                            },
                            departure:{
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: ''
                            }
                        }
                    },
                    {name: 'SuC Auxerre',
                        id: 2,
                        location: '',
                        overview: {
                            distance: 232.2,
                            totaldistance: 232.2,
                            typical: 323,
                            totaltypical: 323,
                            consumption: 60.0,
                            totalconsumption: 60.0,
                            averageconsumption: 258,
                            drivetime: '2:05',
                            totaldrivetime: '2:05',
                            chargetime: '0:40',
                            totalchargetime: '0:40',
                            chargeneeded: 280,
                            arrivaltime: '12:05',
                            departuretime: '12:45'
                        },
                        theoretical: {
                            arrival: {
                                distance: 232.2,
                                typical: 17,
                                consumption: 60.0,
                                time: '2:05'
                            },
                            chargestart: {
                                typical: 17,
                                time: '12:05'
                            },
                            chargeend: {
                                typical: 280,
                                time: '12:45'
                            },
                            departure:{
                                distance: 0,
                                typical: 280,
                                consumption: 0,
                                time: '12:45'
                            }
                        },
                        efffective: {
                            arrival: {
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: ''
                            },
                            chargestart: {
                                typical: 0,
                                time: ''
                            },
                            chargeend: {
                                typical: 0,
                                time: ''
                            },
                            departure:{
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: 0
                            }
                        }
                    },
                    {name: 'SuC Senlis',
                        id: 3,
                        location: '',
                        overview: {
                            distance: 209.6,
                            totaldistance: 441.8,
                            typical: 260,
                            totaltypical: 583,
                            consumption: 48.0,
                            totalconsumption: 108.0,
                            averageconsumption: 229,
                            drivetime: '2:10',
                            totaldrivetime: '4:15',
                            chargetime: '0:50',
                            totalchargetime: '1:30',
                            chargeneeded: 330,
                            arrivaltime: '14:55',
                            departuretime: '15:45'
                        },
                        theoretical: {
                            arrival: {
                                distance: 209.6,
                                typical: 20,
                                consumption: 48.0,
                                time: '2:10'
                            },
                            chargestart: {
                                typical: 20,
                                time: '14:55'
                            },
                            chargeend: {
                                typical: 330,
                                time: '15:45'
                            },
                            departure:{
                                distance: 0,
                                typical: 330,
                                consumption: 0,
                                time: '15:45'
                            }
                        },
                        efffective: {
                            arrival: {
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: 0
                            },
                            chargestart: {
                                typical: 0,
                                time: 0
                            },
                            chargeend: {
                                typical: 0,
                                time: 0
                            },
                            departure:{
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: 0
                            }
                        }
                    },
                    {name: 'SuC Gent',
                        id: 4,
                        location: '',
                        overview: {
                            distance: 238.4,
                            totaldistance: 680.2,
                            typical: 305,
                            totaltypical: 888,
                            consumption: 57.0,
                            totalconsumption: 165.0,
                            averageconsumption: 239,
                            drivetime: '2:15',
                            totaldrivetime: '6:30',
                            chargetime: '0:10',
                            totalchargetime: '1:40',
                            chargeneeded: 110,
                            arrivaltime: '18:00',
                            departuretime: '18:10'
                        },
                        theoretical: {
                            arrival: {
                                distance: 238.4,
                                typical: 15,
                                consumption: 57.0,
                                time: '2:15'
                            },
                            chargestart: {
                                typical: 15,
                                time: '18:00'
                            },
                            chargeend: {
                                typical: 110,
                                time: '18:10'
                            },
                            departure:{
                                distance: 0,
                                typical: 110,
                                consumption: 0,
                                time: '18:10'
                            }
                        },
                        efffective: {
                            arrival: {
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: 0
                            },
                            chargestart: {
                                typical: 0,
                                time: 0
                            },
                            chargeend: {
                                typical: 0,
                                time: 0
                            },
                            departure:{
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: 0
                            }
                        }
                    },
                    {name: 'thuis',
                        id: 5,
                        location: '',
                        overview: {
                            distance: '80.0',
                            totaldistance: 760.2,
                            typical: 96,
                            totaltypical: 984,
                            consumption: 18.0,
                            totalconsumption: 183.0,
                            averageconsumption: 225,
                            drivetime: '1:00',
                            totaldrivetime: '7:30',
                            chargetime: '',
                            totalchargetime: '',
                            chargeneeded: '',
                            arrivaltime: '19:10',
                            departuretime: ''
                        },
                        theoretical: {
                            arrival: {
                                distance: 80.0,
                                typical: 14,
                                consumption: 18.0,
                                time: '1:00'
                            },
                            chargestart: {
                                typical: 0,
                                time: ''
                            },
                            chargeend: {
                                typical: 0,
                                time: ''
                            },
                            departure:{
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: ''
                            }
                        },
                        efffective: {
                            arrival: {
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: 0
                            },
                            chargestart: {
                                typical: 0,
                                time: 0
                            },
                            chargeend: {
                                typical: 0,
                                time: 0
                            },
                            departure:{
                                distance: 0,
                                typical: 0,
                                consumption: 0,
                                time: 0
                            }
                        }
                    }
                ]
            },
            status:	[
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
            ],

            activeobject: 1,
			controllermethods: {}
			};

        d.controllermethods.gettrips = function () {
            //console.debug('gettrips method called');

            // build urlString
            var url = 'server.php?action=gettrips';
            console.debug('url=' + url);

            // get the data
            $http.get(url)
                .success(function (data) {
                    d.trips =  data;
                    //console.debug('after rows d.trips :' + d.trips.length + ' - ' + d.trips[0].id + ' - ' + d.trips[0].name);
                })
                .error(function (data) {
                    alert('error in ' + method + ' - ' + data);
                });
        };

        var callServiceForTrip;
        callServiceForTrip = function (method, url) {
            console.debug('url=' + url);
            console.debug('before rows d.trip : ' + d.trip.length + ' - ' + d.trip.id + ' - ' + d.trip.desc);
            $http.get(url)
                .success(function (data) {
                    //d.trip = data;
                    console.debug('after rows d.trip :' + + d.trip.length + ' - ' + d.trip.id + ' - ' + d.trip.desc);
                })
                .error(function (data) {
                    alert('error in ' + method + ' - ' + data);
                });
        };

        d.controllermethods.gettrip = function(tripid) {
            //console.debug('gettrip method called');

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
            // add more data from screen
            console.debug(d.trip.waypoints[d.trip.etape].theoretical.departure.typical);
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
            this.date = "";
            this.currentdate = function(){
                this.date = new Date();
            };

            this.gettrip = function(tripid){
                console.debug('gettrip ' + tripid);
                this.view = 'detail';
                this.activetrip = tripid;
                this.m.gettrip(tripid);
            };

            this.gotolist = function(){
                this.view = 'list';
                this.activetrip = 0;
                this.waypointview = 0;
                //this.m.gettrips();
            };


            // Controller process
            this.m = TeslaTripService.controllermethods;

            //console.debug('before get ' + this.trips.length);
            this.trips = TeslaTripService.trips;
            this.m.gettrips();
            //console.debug('after get ' + this.trips.length);

            this.trip = TeslaTripService.trip;
		    this.status = TeslaTripService.status;


			// initialisation
			this.gotolist();


        }
    ]);

    $(document).on("click", ".open-WayPointDetail", function () {
        var wpId = $(this).data('wpid');
        var wp = $(this).data('wp');
        $(".modal-header #wpTitle").text("Detail van (" + wpId + ") " + wp.name);
        $(".modal-body #wpId").val("Detail van (" + wpId + ") " + wp.name);
    });



})();

