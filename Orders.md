# Update order
Methods: `PATCH`, `PUT`.

Url: `http://domain/api/orders/<ID>`.

### Params for sending:

Property | Type | Description
-------- | ---- | -----------
status | INTEGER | Identifier of status


### Identifier => status comparison:

Identifier | Status | Description
-------- | ---- | -----------
1 | Unknown | Unknown
2 | PUDispatched | Dispatched for pickup
3 | PUArrived | Arrived at the shipper location (for pickup)
4 | PUInspected | Pickup Inspection Complete
5 | PUProof | Proof of Pickup
6 | PUAvailable | Available for Carrier to retrieve
7 | PUDelivered | Order has been transferred to the carrier (Delivered or Retrieved)
8 | Transfer | Transfer. For pickups and returns
9 | Inbound | On the way to CBC warehouse
10 | Available4PU | Available for pickup at the destination city
11 | OnHand | Received. At CBC warehouse
12 | InspCompleted | Order inspection completed
13 | Outbound | On board. Out for delivery
14 | DeliveryPartial | Proof of partial delivery
15 | Delivery | Proof of delivery
16 | FldDestroy | Field destroy
17 | Disposed | Disposed
18 | Exception | There has been an exception
19 | Refused | Refused by customer
20 | Cancelled | Order has been cancelled
21 | Lost | Lost
22 | Undelivered | Undelivered


### Returns changed properties:

Property | Type | Description
-------- | ---- | -----------
id | INT | Order identifier 
order_number | STRING | Order number 
address1 | STRING | Address 
city | STRING | City 
status |STRING | Status 
type | STRING | Type 
customer | STRING \| "" | Customer name 
address2 | STRING \| "" | Additional address 
zip | STRING \| "" | Zip 
phone | STRING \| "" | Phone number 
phone_home | STRING \| "" | Phone home 
phone_other | STRING \| "" | Phone other 
fax | STRING \| "" | Fax 
pieces | INT \| null | Pieces 
cartons | INT \| null | Cartons 
description | STRING \| "" | Order description 
precall | STRING \| "" | Precall date (custom format) 
time_from | STRING \| "" | Format: `h:i A` (10:10 AM) 
time_to | STRING \| "" | Format: `h:i A` (10:10 AM) 
spec_instruction | STRING \| "" | Special instruction 
service | STRING \| null | If exist: (`WG|T|RC`)


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
		"field":"status",
		"message":"Status cannot be blank."
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

Example request: 

	$ curl -H "Content-Type: application/json" -H "Authorization: Bearer access_token" -H "Dev-Token: dev_token" -X PUT -d '{"status":"4"}' http://domain/api/orders/4