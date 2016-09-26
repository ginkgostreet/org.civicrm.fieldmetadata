(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderTextarea', function() {
    return {
      restrict: 'AE',
      scope: {
        field: '=',
        model: '='
      },
      templateUrl: '~/crmFieldMetadata/crmRenderTextarea.html',

    };
  });
})(angular, CRM.$, CRM._);