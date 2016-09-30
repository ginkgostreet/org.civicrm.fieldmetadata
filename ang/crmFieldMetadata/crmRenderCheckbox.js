(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderCheckbox', function() {
    return {
      restrict: 'AE',
      scope: {
        field: '=',
        model: '=',
        prefix: '=',
      },
      templateUrl: '~/crmFieldMetadata/crmRenderCheckbox.html',
      controller: ['$scope', function crmRenderCheckboxController($scope) {
        $scope.formatMoney = CRM.formatMoney;
      }]
    };
  });
})(angular, CRM.$, CRM._);