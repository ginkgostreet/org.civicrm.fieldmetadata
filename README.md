# org.civicrm.fieldmetadata
This extension is a utility for normalizing and returning metadata for collections of fields, associated with various entities in CiviCRM.

## APIs
### Fieldmetadata.get
**Params:**

| Param | Required | Options | Description | 
| --- | --- | --- | --- |
| `entity` | yes | PriceSet, UFGroup, PaymentBlock, BillingBlock | The entity you want to fetch a collection of field metadata for |
| `entity_params` | yes | | Whatever params need to be sent to the fetcher class to find the fields you are looking for. This usually means an associative array with an `id` key. |
| `context` | no | null, Angular | This parameter tells the normalizer to munge the widget type for a specific display, eg, for the Angular context it will translate an html_type of text to crm-render-text. It is used to get the proper widget types for the context in which you want to render the fields. |

**Example**
```php
civicrm_api3("Fieldmetadata", "get", array(
    "entity" => "PriceSet",
    "entity_params" => array("id" => 9),
    "context" => "Angular"
  ));
```
```json
{
    "is_error": 0,
    "version": 3,
    "count": 6,
    "values": {
        "collectionType": "PriceSet",
        "title": "Test PriceSet",
        "name": "Test_PriceSet",
        "preText": "<p>This is Pre-Form Help Text<\/p>",
        "postText": "This is Post-Form Help Text",
        "fields": {
            "price_10": {
                "entity": "Contribution",
                "label": "Quantity Entry",
                "name": "price_10",
                "order": "1",
                "required": "0",
                "default": "",
                "options": [],
                "price": "5.00",
                "displayPrice": "1",
                "quantity": "1",
                "preText": "",
                "postText": "This is Field Help",
                "collectionType": "PriceSet",
                "defaultValue": "",
                "widget": "crm-render-text"
            },
            "price_11": {
                "entity": "Contribution",
                "label": "Price Field Select",
                "name": "price_11",
                "order": "2",
                "required": "0",
                "default": "",
                "options": [
                    {
                        "label": "Label 1",
                        "name": "price_11",
                        "value": "23",
                        "order": "1",
                        "required": "",
                        "default": "",
                        "price": "1.00",
                        "preText": "",
                        "postText": ""
                    },
                    {
                        "label": "Label 2",
                        "name": "price_11",
                        "value": "24",
                        "order": "2",
                        "required": "",
                        "default": "",
                        "price": "2.00",
                        "preText": "",
                        "postText": ""
                    }
                ],
                "price": [],
                "displayPrice": "1",
                "quantity": "",
                "preText": "",
                "postText": "Field Help",
                "collectionType": "PriceSet",
                "defaultValue": "",
                "widget": "crm-render-select"
            },
            "price_12": {
                "entity": "Contribution",
                "label": "Price Set Radio",
                "name": "price_12",
                "order": "3",
                "required": "0",
                "default": "",
                "options": [
                    {
                        "label": "BBC Radio 1",
                        "name": "price_12",
                        "value": "25",
                        "order": "1",
                        "required": "",
                        "default": "",
                        "price": "11",
                        "preText": "",
                        "postText": ""
                    },
                    {
                        "label": "BBC Radio 2",
                        "name": "price_12",
                        "value": "26",
                        "order": "2",
                        "required": "",
                        "default": "",
                        "price": "22",
                        "preText": "",
                        "postText": ""
                    }
                ],
                "price": [],
                "displayPrice": "1",
                "quantity": "",
                "preText": "",
                "postText": "Radio Button Field Help",
                "collectionType": "PriceSet",
                "defaultValue": "",
                "widget": "crm-render-radio"
            },
            "price_13": {
                "entity": "Contribution",
                "label": "PriceSet Checkboxes Test",
                "name": "price_13",
                "order": "4",
                "required": "0",
                "default": "",
                "options": [
                    {
                        "label": "First Day of Christmas",
                        "name": "price_13[27]",
                        "value": "27",
                        "order": "1",
                        "required": "",
                        "default": "",
                        "price": "4.01",
                        "preText": "Pre",
                        "postText": "Post"
                    },
                    {
                        "label": "Second Day of Christmas",
                        "name": "price_13[28]",
                        "value": "28",
                        "order": "2",
                        "required": "",
                        "default": "1",
                        "price": "4.02",
                        "preText": "",
                        "postText": ""
                    }
                ],
                "price": [],
                "displayPrice": "1",
                "quantity": "",
                "preText": "",
                "postText": "Checkbox Field Help",
                "collectionType": "PriceSet",
                "defaultValue": "",
                "widget": "crm-render-checkbox"
            }
        }
    }
}
```


## Hooks

#### civicrm_fieldmetadata_registerFetcher
Used to register a new Fetcher class for an entity.
A reference to an associative array of classes is passed, where the key is the entity name, and the value is the class for the fetcher.
**note** The class used must extend `CRM_Fieldmetadata_Fetcher`
```php
function fieldmetadata_civicrm_fieldmetadata_registerFetcher(&$classes) {
  $classes['UFGroup'] = "CRM_Fieldmetadata_Fetcher_UFGroup";
  $classes['PriceSet'] = "CRM_Fieldmetadata_Fetcher_PriceSet";
  $classes['PaymentBlock'] = "CRM_Fieldmetadata_Fetcher_PaymentBlock";
  $classes['BillingBlock'] = "CRM_Fieldmetadata_Fetcher_BillingBlock";
}
```

#### civicrm_fieldmetadata_registerNormalizer
Used to register a new Normalizer class for an entity.
A reference to an associative array of classes is passed, where the key is the entity name, and the value is the class for the normalizer.
**note** The class used must extend `CRM_Fieldmetadata_Normalizer`
```php
function fieldmetadata_civicrm_fieldmetadata_registerNormalizer(&$classes) {
  $classes['UFGroup'] = "CRM_Fieldmetadata_Normalizer_UFGroup";
  $classes['PriceSet'] = "CRM_Fieldmetadata_Normalizer_PriceSet";
  $classes['PaymentBlock'] = "CRM_Fieldmetadata_Normalizer_PaymentBlock";
  $classes['BillingBlock'] = "CRM_Fieldmetadata_Normalizer_BillingBlock";
}
```


## Angular Directives
| Directive | Options | Description |
| --- | --- | --- |
| crmRenderFieldCollection | model, prefix  | Renders an entire collection of fields including the collect pre and post help and a title. |
| crmRenderField | field, model, prefix | Renders a whole field label, pre and post text and the widget itself. |
| crmRenderWidget | field, model, prefix | This is a delegation helper that spawns a widget. Used in conjunction with crmRenderField |
| crmRenderChainSelect | field, model, parentModel, prefix | Renders a chain-select such as connecting a country and state from field metadata |
| crmRenderCheckbox | field, model, prefix | Renders a checkbox or group of checkboxes from field metadata |
| crmRenderExpiration | field, model, prefix | Renders a month/year input like that used in credit card expiration |
| crmRenderRadio | field, model, prefix | Renders a group of radio buttons from field metadata |
| crmRenderSelect | field, model, prefix | Renders a Select2 widget from field metadata |
| crmRenderText | field, model, prefix | Renders a text field from field metadata |
| crmRenderTextarea | field, model, prefix | Renders a text-area from field metadata |
