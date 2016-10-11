# Create/update order's survey
Method: `POST`.

Url: `http://domain/api/orders/<ORDER_ID>/survey`.

### Params for sending:

Property | Type | Required | Description
-------- | ---- | --------| -----------
name | STRING | `Yes` | Name (max length: 200 characters)
satisfied_delivery_team | STRING | `Yes` | May be: `completely_unsatisfied`, `somewhat_unsatisfied`, `average`, `somewhat_satisfied`, `completely_satisfied`
exterior_packing | BOOLEAN | `No` | `1`\|`0`
two_people | BOOLEAN | `No` | `1`\|`0`
arrive_time_window | BOOLEAN | `No` | `1`\|`0`
comments | STRING | `No` | Comments (Max length: 10000 characters)
items | [] | `Yes` | [See below](https://github.com/CBCMoving/cbc_application/blob/master/OrderSurvey.md#items) &darr;


## Items 

Items should be sent as array of json data. 
Number of sent items should be equaled number of items at specified order.
All params listed below is required.

### Each element of items array should consist of following params:

Property | Type | Description
-------- | ---- | ------------
id | INTEGER | Item identifier [See items](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#returns-properties-4)
status | STRING | May be: `received`, `refused`, `short`
inititals | STRING | Length 2 characters


### Returns properties of created/updated survey:

Property | Type | Description
-------- | ---- | -----------
name | STRING | Name 
satisfied_delivery_team | STRING | `completely_unsatisfied`, `somewhat_unsatisfied`, `average`, `somewhat_satisfied`, `completely_satisfied`
exterior_packing | BOOLEAN\|null | Exterior packing `1`\|`0`
two_people | BOOLEAN\|null | Two people `1`\|`0`
arrive_time_window | BOOLEAN\|null | Arrive time window `1`\|`0`
comments | STRING\|"" | Comments 
items | [] | [See below](https://github.com/CBCMoving/cbc_application/blob/master/OrderSurvey.md#items-1) &darr;

### Items:
Property | Type | Description
-------- | ---- | -----------
id | INTEGER | Item identifier
quantity | INTEGER | Quantity
commodity | STRING\|null | Commodity
model | STRING\|null | Model
description | STRING\|null | Description
status | STRING | `received`, `refused`, `short`
inititals | STRING | Initials

### Example response:
```
{
  "name": "Ivan",
  "satisfied_delivery_team": "completely_unsatisfied",
  "signature": null,
  "exterior_packing": null,
  "two_people": null,
  "arrive_time_window": null,
  "comments": "",
  "items": [
    {
      "id": 3,
      "status": "received",
      "initials": "IA",
      "commodity": "asdasdasd",
      "model": "",
      "quantity": 123,
      "description": ""
    },
    {
      "id": 4,
      "status": "received",
      "initials": "BA",
      "commodity": "asdasdsad111111111",
      "model": "",
      "quantity": 123,
      "description": ""
    }
  ]
}
```

### Example request: 
```
  $ curl -H "Content-Type: application/json" -H "Authorization: Bearer Hlu7qYqtWczJAOJccTal9ZlA97IgmcII" -H "Dev-Token: dev_token" -X POST -d '{
  "name": "Ivan",
  "satisfied_delivery_team": "completely_unsatisfied",
  "exterior_packing": 1,
  "two_people": 1,
  "arrive_time_window": 0,
  "comments": "My comment"
  "items": [
    {
      "id": 3,
      "status": "received",
      "initials": "IA"
    },
    {
      "id": 4,
      "status": "received",
      "initials": "BA"
    }
  ]
}' http://cbc.com/api/orders/4/survey
```

# Attach signature's picture to survey
Image sent with using `multipart/form-data`.

Method: `POST`.

Url: `http://domain/api/orders/<ORDER_ID>/survey/signature`

### Params for sending:

Property | Type | Description
-------- | ---- | -----------
signature | FILE | Image not more 5 MiB (png, jpg, git, bmp, jpeg)


### Returns properties:

See above

### Example request:
```
  $ curl -H "Authorization: Bearer access_token" -H "Content-Type: multipart/form-data" -H "Dev-Token: dev_token" -X POST -F signature='@/path/to/image.png' http://domain/api/orders/4/survey/signature
```

# Errors

### Unauthorized ([See authenticate](https://github.com/CBCMoving/cbc_application/blob/master/Authenticate.md)):
```
{
  "name": "Unauthorized",
  "message": "You are requesting with an invalid credential.",
  "code": 0,
  "status": 401,
  "type": "yii\\web\\UnauthorizedHttpException"
}
```

### Developer token error:
```
{
  "name": "Forbidden",
  "message": "Dev token authentication has failed.",
  "code": 0,
  "status": 403,
  "type": "yii\\web\\ForbiddenHttpException"
}
```

### Missing/Wrong format fields: 
```
[
  {
    "field": "items",
    "message": "Items cannot be blank."
  },
  {
    "field": "name",
    "message": "Name cannot be blank."
  }
]
```

### Order not found
```
{
  "name": "Not Found",
  "message": "Order not found",
  "code": 0,
  "status": 404,
  "type": "yii\\web\\NotFoundHttpException"
}
```

### Survey not found
```
{
  "name": "Not Found",
  "message": "Survey not found",
  "code": 0,
  "status": 404,
  "type": "yii\\web\\NotFoundHttpException"
}
```
