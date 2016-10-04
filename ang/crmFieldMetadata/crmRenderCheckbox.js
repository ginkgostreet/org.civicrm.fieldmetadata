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
        //if the model is undefined (eg an undefined key on an object
        //AND we have multiple options, set the base model to an object
        //so that the bindings work.
        if (typeof($scope.model) === "undefined" && $scope.field.options.length > 0) {
          $scope.model = {};
          _.each($scope.field.options, function(option) {
            $scope.model[option.value] = !!option.default;
          });

        }
      }]
    };
  });
})(angular, CRM.$, CRM._);