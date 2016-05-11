var appContainer = (function () {
    return {

        loading: false,
        settings: {},
        sort: {
            field: 'name',
            order: 'asc',
            set: function (value) {
                this.sort.field = value;
                this.sort.order = (this.sort.order == 'asc') ? 'desc' : 'asc';

                $scope.sort();
            },
        },
        filter: {
            value: {},
            set: function (key, value) {
                this.filter.value[key].push(value);
            }
        },
        data: {
            tracks: [],
            filter: [],
        },

        init: function (settins) {
            this.settings = settins;
        }
    };
})();

var app = angular.module("app", []);
app
    .config(function ($interpolateProvider) {
        $interpolateProvider.startSymbol('[[').endSymbol(']]');
    })
    .controller('appController', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

        var app = appContainer;

        $scope.load = function () {

            // Если все треки загружены
            if (app.settings.tracksLoaded) return;

            app.loading = true;
            $timeout.cancel(app.settings.timeout);


            app.settings.timeout = $timeout(function () {


                $http
                    .get(app.settings.routing.loadTracks, {
                        params: {
                            page: app.settings.page++,
                            sort: app.sort.field,
                            order: app.sort.order,
                            filter: app.filter
                        }
                    })
                    .then(function (response) {

                        var data = app.data;


                        if (response.data.tracks.length < app.settings.itemsPerPage) {
                            app.settings.tracksLoaded = true;
                        }

                        data.tracks = data.tracks.concat(response.data.tracks);
                        data.filter = response.data.filter;

                        app.loading = false;

                    });

            }, app.settings.httpDelay);
        },
            $scope.sort = function () {

                app.loading = true;
                $http
                    .get(app.settings.routing.sortTracks, {
                        params: {
                            page: app.settings.page,
                            sort: app.sort.field,
                            order: app.sort.order,
                            filter: app.filter
                        }
                    })
                    .then(function (response) {

                        var data = app.data;
                        data.tracks = response.data.tracks;
                        app.loading = false;


                    });
            },
            $scope.filter = function () {

                app.loading = true;
                $timeout.cancel(app.settings.timeout);


                app.settings.timeout = $timeout(function () {


                    $http
                        .get(app.settings.routing.loadTracks, {
                            params: {
                                page: app.settings.page,
                                sort: app.sort.field,
                                order: app.sort.order,
                                filter: app.filter
                            }
                        })
                        .then(function (response) {

                            var data = app.data;
                            data.tracks = response.data.tracks;
                            app.loading = false;

                        });

                }, app.settings.httpDelay);
            }

        $scope.app = app;
    }])
    .directive("scroll", function ($window) {
        return function (scope) {
            angular.element($window).bind("scroll", function () {

                if (this.pageYOffset + this.outerHeight >= $(document).height() - 200) {

                    scope.load();
                }

                scope.$apply();
            });
        };
    })
    .filter('formatDuration', function () {
        return function (duration) {

            var minutes = parseInt(duration / 60, 10),
                seconds = duration % 60,
                seconds = (seconds < 10) ? "0" + seconds : seconds
                ;

            return minutes + ":" + seconds;
        }
    });


