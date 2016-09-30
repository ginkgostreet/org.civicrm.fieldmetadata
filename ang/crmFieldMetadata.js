(function(angular, $, _) {
  // Declare a list of dependencies.
  angular.module('crmFieldMetadata', ['crmUi', 'crmUtil']);

  angular.module('crmFieldMetadata').factory('crmFieldMetadataFetch', function($q, crmApi){
    var crmFieldMetadataFetch = function(entity, entityParams) {
      var deferred = $q.defer();

      crmApi('Fieldmetadata', 'get', {"entity": entity, "entity_params": entityParams, "context": "Angular"}).then(function(result) {
        deferred.resolve(result.values);
      }, function(status) {
        deferred.reject(status);
      });
      return deferred.promise;
    };

    return crmFieldMetadataFetch;
  });


  angular.module('crmFieldMetadata').factory('crmFieldMetadataTotal', function(){
    var crmFieldMetadataTotal = function($scope, metadata, data, result) {
      var priceFields = {};

      $.each(metadata.fields, function(name, field) {
        if (field.quantity == 1 && field.price) {
          priceFields[name] = parseFloat(field.price);
        } else if (field.options) {
          $.each(field.options, function(index, option) {
            if (option.price) {
              priceFields[name + "_" + option.value] = parseFloat(option.price);
            }
          });
        }
      });

      $scope.$watch(data, function (newValue, oldValue, scope) {
        var total = 0.00;

        $.each(newValue, function(name, value) {
          if (metadata.fields[name] && metadata.fields[name].quantity && priceFields.hasOwnProperty(name)) {
            total = total + (parseInt(value) * priceFields[name]);
          } else {
            if(value && typeof value === "object") {
              $.each(value, function(optionId, checked) {
                if (checked && priceFields.hasOwnProperty(name + "_" + optionId)) {
                  total = total + priceFields[name + "_" + optionId];
                }
              });
            } else if (priceFields.hasOwnProperty(name + "_" + value)) {
              total = total + priceFields[name + "_" + value];
            }
          }
        });

        $scope[result] = total;
      }, true);
    };
    return crmFieldMetadataTotal;
  });

})(angular, CRM.$, CRM._);