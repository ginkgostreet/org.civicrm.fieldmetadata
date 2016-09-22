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
            return " - $ " + option.price;
          }
        }
      }]
    };
  });
})(angular, CRM.$, CRM._);