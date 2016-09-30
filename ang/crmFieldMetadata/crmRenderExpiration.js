(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderExpiration', function() {
    return {
      restrict: 'A',
      scope: {
        field: '=crmRenderExpiration',
        model: '=',
        prefix: '='
      },
      templateUrl: '~/crmFieldMetadata/crmRenderExpiration.html',
      controller: ['$scope', '$element', function crmRenderExpirationController($scope, $element) {
        $scope.ts = CRM.ts(null);

        var d = new Date();
        var year = d.getFullYear();

        $scope.months = _.range(1,12);
        $scope.years = _.range(year, year + 10);

        //$scope.$watch('field', function(field) {});
      }],
    };
  });
})(angular, CRM.$, CRM._);