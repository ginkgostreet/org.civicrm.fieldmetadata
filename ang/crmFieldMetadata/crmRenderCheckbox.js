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
    };
  });
})(angular, CRM.$, CRM._);