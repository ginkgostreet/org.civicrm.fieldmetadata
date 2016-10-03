(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderSelect', function() {
    return {
      restrict: 'AE',
      scope: {
        field: '=',
        model: '=',
        prefix: '='
      },
      templateUrl: '~/crmFieldMetadata/crmRenderSelect.html',
      controller: ['$scope', function crmRenderSelectController($scope) {
        $scope.printPrice = function(option) {
          if (option.price) {
            return " - " + CRM.formatMoney(option.price);
          }
        };

        $scope.classes = "";
        if($scope.field.attributes && $scope.field.attributes.class) {
          $scope.classes = $scope.field.attributes.class;
        }
      }]
    };
  });
})(angular, CRM.$, CRM._);