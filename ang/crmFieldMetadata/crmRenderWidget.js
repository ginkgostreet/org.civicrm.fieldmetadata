(function (angular, $, _) {
  angular.module('crmFieldMetadata').directive('crmRenderWidget', ['$compile', function($compile) {
    return {
      replace: true,
      restrict: 'AE',
      scope: {
        field: '=',
        model: '=',
        prefix: '='
      },
      link: function(scope, elem, attrs) {
        //console.log(scope.field.widget);
        scope.field.displayPrice = (scope.field.displayPrice == 1);
        switch (scope.field.widget) {
          default:
            var childEl = $compile('<div ' + scope.field.widget + ' field="field" model="model" prefix="prefix"></div>')(scope);
        }

        elem.addClass("crmRenderWidget");
        elem.append(childEl);
      }
    };
  }]);
})(angular, CRM.$, CRM._);