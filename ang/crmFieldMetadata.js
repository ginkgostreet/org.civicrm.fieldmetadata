(function(angular, $, _) {
  // Declare a list of dependencies.
  angular.module('crmFieldMetadata', ['crmUi', 'crmUtil']);

  angular.module('crmFieldMetadata').factory('crmFieldMetadataFetch', function($q, crmApi){
    var crmFieldMetadataFetch = function(entity, entityParams) {
      var deferred = $q.defer();

      crmApi('Fieldmetadata', 'get', {"entity": entity, "entity_params": entityParams, "context": "Angular"}).then(function(result) {
        deferred.resolve(result);
      }, function(status) {
        deferred.reject(status);
      });
      return deferred.promise;
    };

    return crmFieldMetadataFetch;
  });

})(angular, CRM.$, CRM._);