(function(angular, $, _) {
  // Example usage: <crm-profile-form name="myForm" profile-id="16" contact-id="1" post-save="controllerCallback"></crm-profile-form>
  // See the directive scope below for additional attribues and further detail.
  angular.module('crmFieldMetadata').directive('crmProfileForm', function() {
    return {
      restrict: 'E',
      templateUrl: '~/crmFieldMetadata/crmProfileForm.html',
      transclude: true,
      scope: {
        /**
         * A callback for retrieving contact data from cache rather than making
         * a round-trip to the server. Callback signature:
         *
         * myCallback(profileId, contactId)
         */
        callbackCheckContactCache: '&?checkContactCache',

        /**
         * A callback for retrieving field metadata from cache rather than
         * making a round-trip to the server. Callback signature:
         *
         * myCallback(profileId, contactId)
         */
        callbackCheckProfileCache: '&?checkProfileCache',

        /**
         *
         */
        callbackPostSave: '&?postSave',
        contactId: '=?',
        name: '@',
        profileId: '='
      },
      controller: ['$scope', '$q', 'crmApi', 'crmStatus', 'crmFieldMetadataFetch', function($scope, $q, crmApi, crmStatus, crmFieldMetadataFetch) {
        // Holds metadata about the form fields to render.
        $scope.fieldCollection = {};

        // A flag for toggling "Loading..." behavior
        $scope.loading = true;

        // Serves as the model for the form. Populated with data fetched from
        // the server and/or data entered by the user.
        $scope.model = {};

        // Internationalization helper.
        $scope.ts = CRM.ts('fieldmetadata');

        /**
         * TODO: Code doc
         */
        $scope.save = function save() {
          let params = angular.copy($scope.model);
          params.profile_id = $scope.profileId;
          if (angular.isDefined($scope.contactId)) {
            params.contact_id = $scope.contactId;
          }

          return crmStatus(
            // Status messages. For defaults, just use "{}"
            {start: ts('Saving...'), success: ts('Saved')},
            crmApi('Profile', 'submit', params))
              .then(function (result) {
                if (angular.isDefined($scope.callbackPostSave)) {
                  $scope.callbackPostSave({
                    params: params,
                    result: result
                  });
                }
              });
        };

        /**
         * Private helper function to fetch field metadata for the directive --
         * either from cache or the server.
         *
         * Called on initial rendering of the directive as well as by $watch
         * should $scope.profileId change.
         */
        function doFieldDataRefresh() {
          let cachedData = false;
          let promise;

          if (angular.isDefined($scope.callbackCheckProfileCache)) {
            cachedData = $scope.callbackCheckProfileCache({
              profileId: $scope.profileId,
              contactId: $scope.contactId
            });
          }

          if (cachedData !== false) {
            const deferred = $q.defer();
            deferred.promise.then(function (data) {
              $scope.fieldCollection = data;
            });
            deferred.resolve(cachedData);
            promise = deferred.promise;
          } else {
            promise = crmFieldMetadataFetch('UFGroup', {id: $scope.profileId}).then(function (data) {
              $scope.fieldCollection = data;
            });
          }

          return promise;
        }

        /**
         * Private helper function to fetch contact data for the directive --
         * either from cache or the server.
         *
         * Called on initial rendering of the directive as well as by $watch
         * should $scope.contactId change.
         */
        function doContactDataRefresh() {
          let cachedData = false;
          let promise;

          if (angular.isDefined($scope.callbackCheckContactCache)
            && angular.isDefined($scope.contactId)
          ) {
            cachedData = $scope.callbackCheckContactCache({
              profileId: $scope.profileId,
              contactId: $scope.contactId
            });
          }

          if (cachedData !== false) {
            const deferred = $q.defer();
            deferred.promise.then(function (data) {
              $scope.model = dedupeContactFieldNames(data);
            });
            deferred.resolve(cachedData);
            promise = deferred.promise;
          } else {
            const profileParams = {profile_id: $scope.profileId};
            // If no contact ID is passed, then defaults for a set of fields
            // (rather than values for a specific contact) are being requested.
            if (angular.isDefined($scope.contactId)) {
              profileParams.contact_id = $scope.contactId;
            }
            promise = crmApi('Profile', 'get', profileParams).then(function (result) {
              $scope.model = dedupeContactFieldNames(result.values);
            });
          }

          return promise;
        }

        /**
         * Removes duplicate keys from contact profile data.
         *
         * @param {object} data
         *   As returned by api.Profile.get.
         * @return {object}
         *   Data with the duplicate keys removed.
         *
         * Some CiviCRM APIs are inconsistent with regard to case when it comes
         * to retrieving information about profiles. Notably, api.Profile.get
         * returns duplicate data with key case variation; for example, it can
         * return an object with keys 'email-Primary' and 'email-primary' both
         * set to foo@example.org. Having both keys on the model with the form
         * bound to only one leads to unexpected results when submitting.
         *
         * This extension uses api.UFField.get for retrieving field metadata.
         * That API seems to consistently prefer the key with the capital letter
         * when api.Profile.get returns duplicates, so this function drops the
         * lowercase variations from the model.
         */
        function dedupeContactFieldNames(data) {
          for(property in data) {
            if (property.search(/[A-Z]/) !== -1) {
              const duplicateProperty = property.toLowerCase();
              delete data[duplicateProperty];
            }
          }
          return data;
        }

        // Due to a bug/quirk in AngularJS's $watch, the listener function is
        // called when the watcher is first registered with the scope, but
        // newValue and oldValue are identical. This is documented at
        // https://code.angularjs.org/1.5.11/docs/api/ng/type/$rootScope.Scope#$watch.
        // In my opinion, the watchGroupInitializing flag makes for more
        // readable code than the suggested alternative.
        var watchGroupInitializing = true;
        $scope.$watchGroup(['profileId', 'contactId'], function (newValue, oldValue) {
          $scope.loading = true;

          const promises = [];
          var profileIdChanged, contactIdChanged;

          if (watchGroupInitializing) {
            profileIdChanged = contactIdChanged = true;
            watchGroupInitializing = false;
          } else {
            profileIdChanged = (newValue[0] !== oldValue[0]);
            contactIdChanged = (newValue[1] !== oldValue[1]);
          }

          if (profileIdChanged) {
            promises.push(doFieldDataRefresh());
          }

          if (contactIdChanged) {
            promises.push(doContactDataRefresh());
          }

          $q.all(promises).then(function () {
            $scope.loading = false;
          });
        });

      }]
    };
  });
})(angular, CRM.$, CRM._);
