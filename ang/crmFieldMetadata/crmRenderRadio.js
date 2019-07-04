(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderRadio', function() {
    return {
      restrict: 'AE',
      scope: {
        field: '=',
        model: '=',
        prefix: '='
      },
      templateUrl: '~/crmFieldMetadata/crmRenderRadio.html',
      controller: ['$scope', function crmRenderRadioController($scope) {
        $scope.clear = function clear() {
          $scope.model = null;
        };

        $scope.formatMoney = CRM.formatMoney;
        // Handle default when $scope.model is not set(create page).
        if(typeof $scope.model == "undefined") {
          //Handle defaults
          if(!$scope.model && $scope.field.options) {
            for(var i in $scope.field.options) {
              if ($scope.field.options[i].default) {
                $scope.model = $scope.field.options[i].value;
                break;
              }
            }
          }
        }
      }]
    };
  });
})(angular, CRM.$, CRM._);