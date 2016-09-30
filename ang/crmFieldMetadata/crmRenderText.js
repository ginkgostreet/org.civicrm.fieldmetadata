(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderText', function() {
    return {
      restrict: 'AE',
      scope: {
        field: '=',
        model: '='
      },
      templateUrl: '~/crmFieldMetadata/crmRenderText.html',
      controller: ['$scope', function crmRenderTextController($scope) {
        $scope.formatMoney = CRM.formatMoney;
      }]
    };
  });
})(angular, CRM.$, CRM._);