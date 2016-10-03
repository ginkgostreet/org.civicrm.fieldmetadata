(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderChainSelect', function() {
    return {
      restrict: 'AE',
      scope: {
        field: '=',
        model: '=',
        parentModel: '=',
        prefix: '='
      },
      templateUrl: '~/crmFieldMetadata/crmRenderChainSelect.html',
      controller: ['$scope', '$http', function crmRenderChainSelectController($scope, $http) {
        if ($scope.field.attributes && $scope.field.attributes.watcher && $scope.parentModel && $scope.field.attributes['data-callback']) {

          $scope.watchField = $scope.field.attributes.watcher;
          $scope.$watch("parentModel[watchField]", function(newValue, oldValue, scope) {
            if(newValue) {
              $http.get($scope.field.attributes['data-callback'] + "?_value=" + newValue)
                .then(function(response) {
                  $scope.options = response.data;
                });
            }
          });
        }
      }]
    };
  });
})(angular, CRM.$, CRM._);