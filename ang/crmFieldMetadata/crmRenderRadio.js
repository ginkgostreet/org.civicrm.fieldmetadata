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
    };
  });
})(angular, CRM.$, CRM._);