# Get 5 the latest routes from today
Method: `GET` Url: `http://domain/api/route?access-token=xxxxxxxxxxxxxxxxxxx`
### Returns properties:
Property|Type|Description
    - | - | -
id| INT | Route identifier
date|STRING|Format: `M/d/Y` (Aug/25/2016)
name|STRING| Name
frame_open|STRING \| ""| Frame open
frame_close|STRING \| ""| Frame close
stops|INT \| null| Stops
time_start|STRING \| ""|Format `h:i A` (3:35 PM) 
time_end|STRING \| ""|Format `h:i A` (3:35 PM) 
door|STRING \| ""|Door 
truck|STRING \| ""|Truck 
miles_start|INT \| null| Miles start
miles_end|INT \| null| Miles end
limit_cub_ft|INT \| null| Limit cubic ft
limit_stops|INT \| null| Limit stops
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
Method: `GET` Url: `http://domain/api/route/<ID>?access-token=xxxxxxxxxxxxxxxxxxx`
### Returns properties:
Property|Type|Description
    - | - | -
id| INT | Route identifier
date|STRING|Format: `M/d/Y` (Aug/25/2016)
name|STRING| Name
frame_open|STRING \| ""| Frame open
frame_close|STRING \| ""| Frame close
stops|INT \| null| Stops
time_start|STRING \| ""|Format `h:i A` (3:35 PM) 
time_end|STRING \| ""|Format `h:i A` (3:35 PM) 
door|STRING \| ""|Door 
truck|STRING \| ""|Truck 
miles_start|INT \| null| Miles start
miles_end|INT \| null| Miles end
limit_cub_ft|INT \| null| Limit cubic ft
limit_stops|INT \| null| Limit stops
notes|[]|[See below](https://www.google.com) &darr;
orders|[]|[See below](https://www.google.com) &darr;
# Route notes
Returns array of json object with note.
### Returns properties:
Property|Type|Description
    - | - | -
id| INT | Note identifier
text| STRING | Text note
image| STRING \| null| `Url` to image without domain. If not exist `null`
### Example response:
```
  "notes": [
    {
      "id": 18,
      "text": "asdasd123",
      "image": "/uploads/notes/C-G7AauUSlt97pbgfedXoi1lmHxpfMIa.png",
      "created_at": "Aug/02/2016",
      "created_by": "asd"
    },
    {
      "id": 19,
      "text": "sdsasdfsdfsdfsdfsdf",
      "image": null,
      "created_at": "Aug/05/2016",
      "created_by": "serqio"
    },
    {
      "id": 25,
      "text": "sdfsdfsdfsdfsdf",
      "image": "/uploads/notes/sU-XHc3aTsjhDQ9BrI11VZ_AoGEcZGAn.jpg",
      "created_at": "Aug/20/2016",
      "created_by": "serqio"
    }
  ]
```
# Orders
With orders returns associated data: `items`, `notes`.
### Returns properties:
Property|Type|Description
    - | - | -
id| INT | Order identifier
order_number| STRING | Order number
address1 | STRING | Address
city | STRING | City
status|STRING|Status
type|STRING|Type
customer| STRING \| "" | Customer name
address2| STRING \| ""|Additional address
zip| STRING \| ""|Zip
phone|STRING \| ""| Phone number
phone_home|STRING \| ""| Phone home
phone_other|STRING \| ""| Phone other
fax|STRING \| ""| Fax
pieces| INT \| null| Pieces
cartons| INT \| null| Cartons
description | STRING \| ""| Order description
precall | STRING \| ""| Precall date (custom format)
time_from | STRING \| "" | Format: `h:i A` (10:10 AM)
time_to | STRING \| "" | Format: `h:i A` (10:10 AM)
spec_instruction| STRING \| "" | Special instruction
service | STRING \| ""| If exist: (`WG|T|RC`)
items | [] | [See below](https://www.google.com) &darr;
notes | [] | [See below](https://www.google.com) &darr;
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
      'notes': [],
    }
  ]
```
# Order items
### Returns properties:
Property|Type|Description
    - | - | -
id | INT | Item identifier
quantity| INT| Quantity
weight| INT \| null| Weight
cubic_feet| INT \| null| Cubic feet
commodity| STRING \| ""| Commodity
model| STRING \| "" | Model
description| STRING \| "" | Item description
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
          "description": "My description"
        },
        {
          "id": 7,
          "quantity": 1,
          "weight": null,
          "cubic_feet": null,
          "commodity": "Commodity prop",
          "model": "Model name",
          "description": ""
        },
        {
          "id": 8,
          "quantity": 123,
          "weight": null,
          "cubic_feet": null,
          "commodity": "",
          "model": "",
          "description": ""
        }
      ],
```
# Order notes
Will be loaded only notes for driver.
### Note properties:
Property|Type|Description
    - | - | -
id | INT | Note identifier
text| STRING| Text note
image| STRING \| null| `Url` to image without domain. If not exist `null`
created_at| STRING | Created date, format: `D/m/Y` (Aug/25/2016)
created_by | STRING | Creator username
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
          "description": "Description"
        },
        {
          "id": 7,
          "quantity": 1,
          "weight": null,
          "cubic_feet": null,
          "commodity": "commodity",
          "model": "Model",
          "description": ""
        },
        {
          "id": 8,
          "quantity": 123,
          "weight": null,
          "cubic_feet": null,
          "commodity": "",
          "model": "",
          "description": ""
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
      ]
    },
    {
      "id": 4,
      "order_number": "#0918-1231-3333",
      "customer": "",
      "address1": "Fourteenth St",
      "address2": "",
      "city": "Gotham city",
      "zip": "",
      "phone": "321.123.123",
      "phone_home": "111.132.543",
      "phone_other": "",
      "fax": "228.228.123",
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
          "description": ""
        },
        {
          "id": 4,
          "quantity": 123,
          "weight": null,
          "cubic_feet": null,
          "commodity": "asdasdsad111111111",
          "model": "",
          "description": ""
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
      ]
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
      "notes": []
    }
  ]
}
```
# Update route
Methods: `PATCH`, `PUT`. 

Url: `http://domain/api/route/<ID>?access-token=xxxxxxxxxxxxxxxxxxx`
All params transmitted to the body of the request in json format.
> Sent should be only parameters which you want to change. Empty or null parameter will overwrite current value.
### Avilable params:
Property|Type|Description
    - | - | -
miles_start|INTEGER|Miles start
miles_end|INTEGER|Miles end
time_start|STRING|Format: `h:i A` (10:15 PM)
time_end|STRING|Format: `h:i A` (10:15 PM)
truck| STRING| Truck
door| STRING| Door
### Example request
```
    $ curl -H "Content-Type: application/json" -X PUT -d '{"time_end":"10:20 AM"}' http://cbc.com/api/route/4?access-token=xxxxxxxxxxxxxxxxxxxxxxxxx
```
# Errors 
### Unauthorized:
```
{
  "name": "Unauthorized",
  "message": "You are requesting with an invalid credential.",
  "code": 0,
  "status": 401,
  "type": "yii\\web\\UnauthorizedHttpException"
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