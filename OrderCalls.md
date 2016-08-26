# Create precall 
Method: `POST`.

Url: `http://domain/api/orders/<ORDER_ID>/calls`.

If call with `order_id`, `time_called` and current driver are exists, then new data will override old data, not create new call.

### Params for sending:

Property | Type | Description
-------- | ---- | -----------
name | STRING | Name
phone | STRING | Format: `999.999.9999`
answered | BOOLEAN | `0`\|`1`
confirmed | BOOLEAN | `0`\|`1`
left_message | BOOLEAN | Left a message. `0`\|`1`
note | STRING | Note
time_called | STRING | Format: `h:i A` (10:15 AM)


### Returns properties of created or updated call:

Property | Type | Description
-------- | ---- | -----------
id | INT | Call identifier 
name | STRING | Name
phone | STRING | Format: `999.999.9999`
answered | BOOLEAN | `0`\|`1`
confirmed | BOOLEAN | `0`\|`1`
left_message | BOOLEAN | Left a message. `0`\|`1`
note | STRING | Note
time_called | STRING | Format: `h:i A` (10:15 AM)


### Example response:
```
{
  "id": 11,
  "name": "naaaame",
  "phone": "123.123.1231",
  "answered": 0,
  "note": "nooooot111111e",
  "confirmed": 0,
  "left_message": 0
}
```

### Example request: 

  $ curl -H "Content-Type: application/json" -H "Authorization: Bearer access_token" -X POST -d '{"time_called":"12:22 AM","phone":"123.123.1231","name":"naaaame","note":"nooooot111111e"}' http://domain/api/orders/13/calls

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

### Missing/Wrong format fields: 
```
[
  {
    "field": "name",
    "message": "Name cannot be blank."
  },
  {
    "field": "phone",
    "message": "Phone cannot be blank."
  },
  {
    "field": "time_called",
    "message": "Time Called cannot be blank."
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