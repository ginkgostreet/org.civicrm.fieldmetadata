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
        var childEl;
        switch (scope.field.widget) {
          case "crm-ui-datepicker":
            var tmp = $compile('<input crm-ui-datepicker="{time: false}" ng-model="model" ng-required="field.required" />')(scope);
            childEl = tmp.parent();
            break;
          case "crm-entityref":
            childEl = $compile('<span class="crm-entitiyref-wrapper"><input crm-entityref="{entity: \'' + scope.field.entity + '\', select: {allowClear:!field.required}}" field="field" ng-model="model" prefix="prefix" ng-required="field.required" /></span>')(scope);
            console.log(childEl);
            break;
          default:
            if (scope.field.widget.indexOf('crm-render') === -1) {
              childEl = $compile('<input ' + scope.field.widget + ' field="field" ng-model="model" prefix="prefix" ng-required="field.required" />')(scope);
            } else {
              childEl = $compile('<div ' + scope.field.widget + ' field="field" model="model" prefix="prefix" ng-required="field.required"></div>')(scope);
            }
        }

        elem.addClass("crmRenderWidget");
        elem.append(childEl);
      }
    };
  }]);
})(angular, CRM.$, CRM._);