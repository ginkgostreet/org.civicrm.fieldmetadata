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
        $scope.handleToggle = function (value) {
          var i = $scope.model.indexOf(value);
          if (i === -1) {
            $scope.model.push(value);
          } else {
            $scope.model.splice(i, 1);
          }
        };
        $scope.isChecked = function (value) {
          return ($scope.model.indexOf(value) === -1 ? false : true);
        };
        $scope.isRequired = function () {
          return $scope.field.required && ($scope.model.length === 0);
        };

        // Checkboxes are modeled as Arrays because multiple selections can be
        // made. However, if we are retrieving a set of checkboxes for which no
        // selections have been made, CiviCRM's API represents the field as an
        // empty string. We standardize the representation here.
        if (!Array.isArray($scope.model)) {
          $scope.model = [];
        }
      }]
    };
  });
})(angular, CRM.$, CRM._);