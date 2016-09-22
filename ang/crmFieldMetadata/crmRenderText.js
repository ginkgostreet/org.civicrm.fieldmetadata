(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderText', function() {
    return {
      restrict: 'AE',
      scope: {
        field: '=',
        model: '='
      },
      templateUrl: '~/crmFieldMetadata/crmRenderText.html',

    };
  });
})(angular, CRM.$, CRM._);