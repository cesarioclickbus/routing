var app = angular.module('routing', ['ui.bootstrap']);

app.controller('TypeaheadCtrl', function ($scope, $http, limitToFilter) {
  $scope.maxDist = 2;

  $scope.cities = function(cityName) {
    return $http.get("../php/getCities.php?q="+cityName).then(function(response){
      return limitToFilter(response.data, 15);
    });
  };  
  $scope.idByCity = function(cityName,id) {
    return $http.get("../php/getIdByCity.php?city="+cityName).then(function(response){
      return response.data;
    });
  };
  
  $scope.getOriginId = function(cityName) {
        var response = $http.get("../php/getIdByCity.php?city="+cityName);
        response.success(function(data, status, headers, config) {
			$scope.originId = data;
        });
    };  
	
	$scope.getDestId = function(cityName) {
        var response = $http.get("../php/getIdByCity.php?city="+cityName);
        response.success(function(data, status, headers, config) {
			$scope.destId = data;
        });
    };
	
    $scope.routing = function() {
    return $http.get("../php/routing.php?originId="+ $scope.originId.valueOf() +"&destId=" + $scope.destId.valueOf() + "&maxDist=" + $scope.maxDist.valueOf()).then(function(response){
      $scope.path = response.data;
    });
  };  
});