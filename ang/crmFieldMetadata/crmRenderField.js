(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderField', function() {
    return {
      restrict: 'A',
      scope: {
        field: '=crmRenderField',
        model: '=',
        parentModel: '=',
        prefix: '='
      },
      templateUrl: '~/crmFieldMetadata/crmRenderField.html',
      controller: ['$scope', '$element', '$sce', function crmRenderFieldController($scope, $element, $sce) {
        // Because of differences between how PHP and JS evaluate strings (e.g.,
        // in JS '0' is considered truthy), the client is very strict in how it
        // determines whether or not a field is required. '1' means the
        // fieldmetadata API reported the field as required. Boolean true means
        // the client has already determined that the field is required (this
        // ensures idempotence). Anything else is considered not required.
        $scope.field.required = ($scope.field.required === '1' || $scope.field.required === true);
        $element.addClass('crm-section');
        $scope.preText = $sce.trustAsHtml($scope.field.preText);
        $scope.postText = $sce.trustAsHtml($scope.field.postText);

        $scope.help = null;
        $scope.$watch('field', function(field) {
          if (field && field.help) {
            scope.help = field.help.clone({}, {
              title: field.label
            });
          }
        });
      }],
    };
  });
})(angular, CRM.$, CRM._);