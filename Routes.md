# Get 5 the latest routes starting from today
Method: `GET` Url: `http://domain/api/routes`
### Returns properties:

Property | Type | Description
-------- | ---- | -----------
id | INT | Route identifier
date | STRING | Format: `M/d/Y` (Aug/25/2016)
name | STRING | Name
frame_open | STRING \| "" | Frame open
frame_close | STRING \| "" | Frame close
stops | INT \| null | Stops
time_start | STRING \| "" | Format `h:i A` (3:35 PM) 
time_end | STRING \| "" | Format `h:i A` (3:35 PM) 
door | STRING \| "" | Door 
truck | STRING \| "" | Truck 
miles_start | INT \| null | Miles start
miles_end | INT \| null | Miles end
limit_cub_ft | INT \| null | Limit cubic ft
limit_stops | INT \| null | Limit stops

### Example response:
```
[
  {
    "id": 4,
    "date": "Aug/25/2016",
    "frame_open": "",
    "frame_close": "",
    "stops": null,
    "time_start": "",
    "time_end": "",
    "miles_start": null,
    "miles_end": null,
    "door": "",
    "truck": "",
    "limit_cub_ft": null,
    "limit_stops": null,
    "name": "namename"
  },
  {
    "id": 5,
    "date": "Aug/27/2016",
    "frame_open": "123",
    "frame_close": "123",
    "stops": 1,
    "time_start": "12:31 AM",
    "time_end": "12:31 PM",
    "miles_start": 1,
    "miles_end": 3,
    "door": "door",
    "truck": "truck",
    "limit_cub_ft": 10,
    "limit_stops": 12,
    "name": "namename"
  }
]
```
# Load more information by route
Method: `GET` Url: `http://domain/api/routes/<ID>`
### Returns properties:

Property | Type | Description
-------- | ---- | -----------
id | INT | Route identifier
date | STRING | Format: `M/d/Y` (Aug/25/2016)
name | STRING | Name
frame_open | STRING \| "" | Frame open
frame_close | STRING \| "" | Frame close
stops | INT \| null | Stops
time_start | STRING \| "" | Format `h:i A` (3:35 PM) 
time_end | STRING \| "" | Format `h:i A` (3:35 PM) 
door | STRING \| "" | Door 
truck | STRING \| "" | Truck 
miles_start | INT \| null | Miles start
miles_end | INT \| null | Miles end
limit_cub_ft | INT \| null | Limit cubic ft
limit_stops | INT \| null | Limit stops
notes | [] | [See below](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#route-notes) &darr;
orders | [] | [See below](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#orders) &darr;


# Route notes
Returns array of json object with note.
### Returns properties:

Property | Type | Description
-------- | ---- | -----------
id | INT | Note identifier
text | STRING | Text note
image | STRING \| null | `Url` to image without domain. If not exist `null`

### Example response:
```
  "notes": [
    {
      "id": 18,
      "text": "asdasd123",
      "image": "/uploads/notes/C-G7AauUSlt97pbgfedXoi1lmHxpfMIa.png",
    },
    {
      "id": 19,
      "text": "sdsasdfsdfsdfsdfsdf",
      "image": null,
    },
    {
      "id": 25,
      "text": "sdfsdfsdfsdfsdf",
      "image": "/uploads/notes/sU-XHc3aTsjhDQ9BrI11VZ_AoGEcZGAn.jpg",
    }
  ]
```
# Orders
With orders returns associated data: `items`, `notes`.
### Returns properties:

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
items | [] | [See below](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#order-items) &darr; 
notes | [] | [See below](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#order-notes) &darr; 
call | {} \| null | [See below](https://github.com/CBCMoving/cbc_application/blob/master/Routes.md#call) &darr;

### Example response:
```
"orders": [
    {
      "id": 3,
      "order_number": "3123123123",
      "customer": "",
      "address1": "123123",
      "address2": "",
      "city": "12313123",
      "zip": "",
      "phone": "",
      "phone_home": "",
      "phone_other": "",
      "fax": "",
      "pieces": null,
      "cartons": null,
      "description": "",
      "precall": "",
      "time_from": "7:00 AM",
      "time_to": "",
      "spec_instructions": "",
      "status": "Unknown",
      "type": "Delivery",
      "service":"WG",
      "items": [],
      "notes": [],
      "call": null
    }
  ]
```
# Order items
### Returns properties:

Property | Type | Description
-------- | ---- | -----------
id | INT | Item identifier
quantity | INT | Quantity
weight | INT \| null | Weight
cubic_feet | INT \| null | Cubic feet
commodity | STRING \| "" | Commodity
model | STRING \| "" | Model
description | STRING \| "" | Item description
cartons | INT \| null | Cartons

### Example response:
```
"items": [
        {
          "id": 2,
          "quantity": 12,
          "weight": 123,
          "cubic_feet": null,
          "commodity": "Commodity prop",
          "model": "Model name",
          "description": "My description",
          "cartons": 1
        },
        {
          "id": 7,
          "quantity": 1,
          "weight": null,
          "cubic_feet": null,
          "commodity": "Commodity prop",
          "model": "Model name",
          "description": "",
          "cartons": null
        },
        {
          "id": 8,
          "quantity": 123,
          "weight": null,
          "cubic_feet": null,
          "commodity": "",
          "model": "",
          "description": "",
          "cartons": 1
        }
      ],
```
# Order notes
> Will be loaded only notes for driver.

### Note properties:

Property | Type | Description
---------| ---- | -----------
id | INT | Note identifier
text | STRING | Text note
image | STRING \| null | `Url` to image without domain. If not exist `null`
created_at | STRING | Created date, format: `M/d/Y` (Aug/25/2016)
created_by | STRING | Creator username

### Example response:
```
      "notes": [
        {
          "id": 18,
          "text": "some text",
          "image": "/uploads/notes/C-G7AauUSlt97pbgfedXoi1lmHxpfMIa.png",
          "created_at": "Aug/02/2016",
          "created_by": "asd"
        },
        {
          "id": 19,
          "text": "texte",
          "image": null,
          "created_at": "Aug/05/2016",
          "created_by": "serqio"
        },
        {
          "id": 25,
          "text": "teeext",
          "image": "/uploads/notes/sU-XHc3aTsjhDQ9BrI11VZ_AoGEcZGAn.jpg",
          "created_at": "Aug/20/2016",
          "created_by": "serqio"
        }
      ]
```
# Call
Last precall (See - [Order calls](https://github.com/CBCMoving/cbc_application/blob/master/OrderCalls.md "Watch more"))

### Note properties:

Property | Type | Description
---------| ---- | -----------
id | INT | Call identifier
name | STRING\|"" | Name
phone | STRING | Format: `999-999-9999`
answered | BOOLEAN | `0`\|`1`
confirmed | BOOLEAN | `0`\|`1`
left_message | BOOLEAN | Left a message. `0`\|`1`
note | STRING\|"" | Note
time_called | STRING\|"" | Format: `h:i A` (10:15 AM)

### Example response
```
      "call": {
        "id": 13,
        "name": "naaaame",
        "phone": "123-123-1231",
        "answered": 0,
        "note": "nooooot111111e",
        "confirmed": 1,
        "left_message": 0,
        "time_called": "10:51 AM"
      }
```

# Common route json
```
{
  "id": 4,
  "date": "Aug/25/2016",
  "frame_open": "123123",
  "frame_close": "123123",
  "stops": 123,
  "time_start": "12:31 AM",
  "time_end": "10:15 AM",
  "miles_start": 10,
  "miles_end": null,
  "door": "asdasd",
  "truck": "",
  "limit_cub_ft": null,
  "limit_stops": null,
  "name": "namename",
  "notes": [
    {
      "id": 8,
      "text": "dsfsdfsdfsdgsdfsdf",
      "image": "/uploads/routenotes/2FKLXgA6h_gLyU5-eM9escvM64eQ3M2k.jpg"
    },
    {
      "id": 7,
      "text": "nondonosndosnonsodn",
      "image": "/uploads/routenotes/Mo_iC2EIPTWog_6oBhUPSvYoDfG_LvT_.jpg"
    },
    {
      "id": 3,
      "text": "123123123123",
      "image": "/uploads/routenotes/rvK2AU9J37puCKQz8dfcIfCs5--knAnb.png"
    },
    {
      "id": 2,
      "text": "фывфывфывфывфыв",
      "image": null
    },
    {
      "id": 1,
      "text": "dfsfsdfsdfsdf",
      "image": "/uploads/routenotes/P4FTnC_xyAubZi2cgTMI7fn4Ou3Owf3e.jpg"
    }
  ],
  "orders": [
    {
      "id": 3,
      "order_number": "#1234-124-21",
      "customer": "",
      "address1": "221B Baker St",
      "address2": "",
      "city": "London",
      "zip": "",
      "phone": "",
      "phone_home": "",
      "phone_other": "",
      "fax": "",
      "pieces": null,
      "cartons": null,
      "description": "",
      "precall": "",
      "time_from": "7:00 AM",
      "time_to": "",
      "spec_instructions": "",
      "status": "Unknown",
      "type": "Delivery",
      "service": "WG",
      "items": [
        {
          "id": 2,
          "quantity": 12,
          "weight": 123,
          "cubic_feet": null,
          "commodity": "commodity",
          "model": "Model",
          "description": "Description",
          "cartons": 1
        },
        {
          "id": 7,
          "quantity": 1,
          "weight": null,
          "cubic_feet": null,
          "commodity": "commodity",
          "model": "Model",
          "description": "",
          "cartons": null
        },
        {
          "id": 8,
          "quantity": 123,
          "weight": null,
          "cubic_feet": null,
          "commodity": "",
          "model": "",
          "description": "",
          "cartons": 1
        }
      ],
      "notes": [
        {
          "id": 24,
          "text": "My order note",
          "image": "/uploads/notes/EiCTF_kMf36DdQtBoYHwOChhQgpc1bJk.png",
          "created_at": "Aug/19/2016",
          "created_by": "serqio"
        }
      ],
      "call" : null
    },
    {
      "id": 4,
      "order_number": "#0918-1231-3333",
      "customer": "",
      "address1": "Fourteenth St",
      "address2": "",
      "city": "Gotham city",
      "zip": "",
      "phone": "321-123-1123",
      "phone_home": "111-132-1543",
      "phone_other": "",
      "fax": "",
      "pieces": null,
      "cartons": null,
      "description": "",
      "precall": "",
      "time_from": "1:00 PM",
      "time_to": "",
      "spec_instructions": "",
      "status": "DeliveryPartial",
      "type": "Transfer",
      "service":"T",
      "items": [
        {
          "id": 3,
          "quantity": 123,
          "weight": null,
          "cubic_feet": null,
          "commodity": "asdasdasd",
          "model": "",
          "description": "",
          "cartons": 1
        },
        {
          "id": 4,
          "quantity": 123,
          "weight": null,
          "cubic_feet": null,
          "commodity": "asdasdsad111111111",
          "model": "",
          "description": "",
          "cartons": 12
        }
      ],
      "notes": [
        {
          "id": 18,
          "text": "some text",
          "image": "/uploads/notes/C-G7AauUSlt97pbgfedXoi1lmHxpfMIa.png",
          "created_at": "Aug/02/2016",
          "created_by": "asd"
        },
        {
          "id": 19,
          "text": "texte",
          "image": null,
          "created_at": "Aug/05/2016",
          "created_by": "serqio"
        },
        {
          "id": 25,
          "text": "teeext",
          "image": "/uploads/notes/sU-XHc3aTsjhDQ9BrI11VZ_AoGEcZGAn.jpg",
          "created_at": "Aug/20/2016",
          "created_by": "serqio"
        }        
      ],
      "call": {
        "id": 13,
        "name": "naaaame",
        "phone": "123-123-1231",
        "answered": 0,
        "note": "nooooot111111e",
        "confirmed": 1,
        "left_message": 0,
        "time_called": "10:51 AM"
      }      
    },
    {
      "id": 9,
      "order_number": "1312123",
      "customer": "",
      "address1": "421",
      "address2": "",
      "city": "124",
      "zip": "",
      "phone": "",
      "phone_home": "",
      "phone_other": "",
      "fax": "",
      "pieces": null,
      "cartons": null,
      "description": "",
      "precall": "",
      "time_from": "",
      "time_to": "",
      "spec_instructions": "",
      "status": "Unknown",
      "type": "Delivery",
      "service":"WG",
      "items": [],
      "notes": [],
      "call": null
    }
  ]
}
```
# Update route
Methods: `PATCH`, `PUT`. 

Url: `http://domain/api/routes/<ID>`
All params transmitted to the body of the request in json format.
> Sent should be only parameters which you want to change. Empty or null parameter will overwrite current value.

### Avilable params:

 Property | Type | Description 
 -------- | ---- | ----------- 
miles_start | INTEGER | Miles start 
miles_end | INTEGER | Miles end
time_start | STRING | Format: `h:i A` (10:15 PM)
time_end | STRING | Format: `h:i A` (10:15 PM)
truck | STRING | Truck (Max length: 300 characters)
door | STRING | Door (Max length: 300 characters)

### Example request
```
    $ curl -H "Content-Type: application/json" -H "Authorization: Bearer access_token" -H "Dev-Token: dev_token" -X PUT -d '{"time_end":"10:20 AM"}' http://cbc.com/api/routes/4
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

### Route not found
```
{
  "name": "Not Found",
  "message": "The requested route does not exist.",
  "code": 0,
  "status": 404,
  "type": "yii\\web\\NotFoundHttpException"
}
```
### Validation errors on update route
```
[
  {
    "field": "miles_start",
    "message": "Miles Start must be an integer."
  },
  {
    "field": "time_end",
    "message": "Wrong format. Allow: 99:99 AM or 99:99 PM"
  }
]
```