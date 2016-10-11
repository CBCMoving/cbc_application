# Create order note
Method: `POST`.

Url: `http://domain/api/orders/<ORDER_ID>/notes`.

### Params for sending:

Property | Type | Description
-------- | ---- | -----------
text | STRING | Text of note (Max length: 5000 characters)


### Returns properties of created note:

Property | Type | Description
-------- | ---- | -----------
id | INT | Note identifier 
text | STRING | Text note
image | null | `null` because attached separately
created_at | STRING | Created date, format: `D/m/Y` (Aug/25/2016)
created_by | STRING | Creator username


### Example response:
```
{
  "id": 18,
  "text": "some text",
  "image": null,
  "created_at": "Aug/02/2016",
  "created_by": "driver_username"
}
```

### Example request: 
```
  $ curl -H "Content-Type: application/json" -H "Authorization: Bearer access_token" -H "Dev-Token: dev_token" -X POST -d '{"text":"some text"}' http://domain/api/orders/4/notes
```

# Attach image to note
Image sent with using `multipart/form-data`.

Method: `POST`.

Url: `http://domain/api/orders/notes/<NOTE_ID>/image`.

> User can attach image only to self created note.

### Params for sending:

Property | Type | Description
-------- | ---- | -----------
image | FILE | Image not more 5 MiB (png, jpg, git, bmp, jpeg)


### Returns properties:

Property | Type | Description
-------- | ---- | -----------
id | INT | Note identifier 
text | STRING | Text note
image | STRING | `Url` to image without domain.
created_at | STRING | Created date, format: `D/m/Y` (Aug/25/2016)
created_by | STRING | Creator username


### Example response:
```
{
  "id": 18,
  "text": "some text",
  "image": "/uploads/notes/C-G7AauUSlt97pbgfedXoi1lmHxpfMIa.png",
  "created_at": "Aug/02/2016",
  "created_by": "driver_username"
}
```

### Example request:
```
  $ curl -H "Authorization: Bearer access_token" -H "Content-Type: multipart/form-data" -H "Dev-Token: dev_token" -X POST -F image='@/path/to/image.png' http://domain/api/orders/notes/28/image
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
		"field":"text",
		"message":"Text cannot be blank."
	}
]
```
```
[
  {
    "field": "image",
    "message": "Only files with these extensions are allowed: jpg, jpeg, png, gif, bmp."
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

### Note not found
```
{
  "name": "Not Found",
  "message": "Note not found",
  "code": 0,
  "status": 404,
  "type": "yii\\web\\NotFoundHttpException"
}
```
