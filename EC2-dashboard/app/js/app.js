var EC2Dash = EC2Dash || {};

 EC2Dash.helpers = {
    tallyServerState: function(servers) {

    serverState = { "running":0, "stopped":0, "total": 0 }

    $.each(servers, function(index, value) {

      if (value.state === "running") {
        serverState.running ++;

      } else if (value.state != "running") {
        serverState.stopped ++;
      }

      serverState.total ++;
    });

    return serverState;
   }
 };

var app = angular.module('dashboardApp', [])
  .controller('DashboardController', ['$scope', '$http', function($scope, $http) {
    $scope.servers = {};
    $scope.helpers = EC2Dash.helpers;

    $scope.sortorder = 'name';

    $http.get('data/servers.json')
      .then(function(res){
        $scope.servers = res.data;
        $scope.helpers.tallyServerState($scope.servers);
    });

    $scope.getServerTemplate = function () {
      return 'template/serverRows.html';
    }

    $scope.getServerTally = function () {
      return $scope.helpers.tallyServerState($scope.servers);
    }

    $scope.checkAll = function () {

      if ($scope.selectedAll) {
          $scope.selectedAll = true;

      } else {
          $scope.selectedAll = false;
      }

      angular.forEach($scope.servers, function (item) {
          item.Selected = $scope.selectedAll;
      });
    };

    $scope.groupStart = function () {

      angular.forEach($scope.servers, function (item) {

          if (item.Selected === true) {
            item.state = "running";
          }
      });
    }

    $scope.groupStop = function () {

      angular.forEach($scope.servers, function (item) {

          if (item.Selected === true) {
            item.state = "stopped";
          }
      });
    }

    $scope.countSelected = function () {
      var count = 0;
      angular.forEach($scope.servers, function (item) {

          if (item.Selected === true) {
            count ++
          }
      });

      return count;
    }

}]);
