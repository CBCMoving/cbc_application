# Authenticate app
Methods: `POST`, `PUT`.

Authentication url: `http://domain/api/auth`.

### Params for sending:

Property | Type | Description
-------- | ---- | -----------
username | STRING | Driver username
password | STRING | Driver password

Required params: `username`, `password` transmitted to the body of the request in json format.
If authentication is successful, the server returns `username`, `access_token`, `office`. 

### Returns properties:

Property | Type | Description
-------- | ---- | -----------
username | STRING | Driver username
access_token | STRING | Token for bearer authentication
office | STRING | Current office of this driver

`access_token` must be sent in each request in bearer header: `Authorization: Bearer Hlu7qYqtWczJAOJccTal9ZlA97IgmcII`.

**Successful response:**
```
	{
		"username": "super_driver",
		"access_token": "Hlu7qYqtWczJAOJccTal9ZlA97IgmcII",
		"office":"Kent"
	}
```

# Errors

**Missing required fields:**
```
[
	{
		"field":"username",
		"message":"Username cannot be blank."
	},
	{
		"field":"password",
		"message":"Password cannot be blank."
	}
]
```

**Invalid username/password:**
```
	[
		{
			"field":"password",
			"message":"Incorrect username or password."
		}
	]
```

If the number of requests more 10 per 5 min, ip address will automatically locked on 20 min.
**Block ip:**
```
	{
		"name":"Forbidden",
		"message":"Too many failed authentications. Try again later.",
		"code":0,
		"status":403,
		"type":"yii\\web\\ForbiddenHttpException"
	}
```

# Example request:

### Authorization:

	$ curl -H "Content-Type: application/json" -X POST -d '{"username":"username","password":"password"}' http://domain/api/auth

	$ curl -H "Content-Type: application/json" -X PUT -d '{"username":"username","password":"password"}' http://domain/api/auth

### Other request:
	
	$curl -H "Content-Type: application/json" -H "Authorization: Bearer Hlu7qYqtWczJAOJccTal9ZlA97IgmcII" -X GET http://domain/api/action
