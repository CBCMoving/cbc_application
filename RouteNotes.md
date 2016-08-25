# Create route note
Method: `POST`.

Url: `http://domain/api/routes/<ROUTE_ID>/notes`.

### Params for sending:

Property | Type | Description
-------- | ---- | -----------
text | STRING | Text of note


### Returns properties of created note:

Property | Type | Description
-------- | ---- | -----------
id | INT | Note identifier 
text | STRING | Text note
image | null | `null` because attached separately


### Example response:
```
{
  "id": 18,
  "text": "some text",
  "image": null,
}
```

### Example request: 

  $ curl -H "Content-Type: application/json" -H "Authorization: Bearer access_token" -X POST -d '{"text":"some text"}' http://domain/api/routes/4/notes


# Attach image to note
Image sent with using `multipart/form-data`.

Method: `POST`.

Url: `http://domain/api/routes/notes/<NOTE_ID>/image`.

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


### Example response:
```
{
  "id": 18,
  "text": "some text",
  "image": "/uploads/notes/C-G7AauUSlt97pbgfedXoi1lmHxpfMIa.png",
}
```

### Example request

  $ curl -H "Authorization: Bearer Hlu7qYqtWczJAOJccTal9ZlA97IgmcII" -H "Content-Type: multipart/form-data" -X POST -F image='@/path/to/image.png' http://domain/api/routes/notes/28/image

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
		"field":"text",
		"message":"Status cannot be blank."
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

### Route not found
```
{
  "name": "Not Found",
  "message": "Route not found",
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
