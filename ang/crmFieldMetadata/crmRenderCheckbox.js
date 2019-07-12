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
          var i = Object.values($scope.model).indexOf(value);
          if (i === -1) {
            $scope.model[value] = value;
          } else {
            delete $scope.model[value];
          }
        };
        $scope.isChecked = function (value) {
          return (Object.values($scope.model).indexOf(value) === -1 ? false : true);
        };
        $scope.isRequired = function () {
          return $scope.field.required && ($scope.model.length === 0);
        };

        // Checkboxes are modeled as Arrays because multiple selections can be
        // made. However, if we are retrieving a set of checkboxes for which no
        // selections have been made, CiviCRM's API represents the field as an
        // empty string. We standardize the representation here.
        // When scope is not set then prepare empty object and set default value for that field.
        if(typeof $scope.model == "undefined") {
          $scope.model = {};
          //Handle defaults
          if($scope.field.options) {
            for(var i in $scope.field.options) {
              if ($scope.field.options[i].default) {
                $scope.model[$scope.field.options[i].value] = $scope.field.options[i].value;
              }
            }
          }
        }
        // If scope set but value saved empty in database,
        // Then For that field prepare blank object.
        if($scope.model.length == 0) {
          $scope.model = {};
        }
      }]
    };
  });
})(angular, CRM.$, CRM._);