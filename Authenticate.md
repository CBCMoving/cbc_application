# Authenticate app
Methods: `POST`, `PUT`.

Authorization url: `http://domain/api/auth`.

In each request to api should be additional header `Dev-Token`. At which transmitted Developer token, token give from customer application.

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
username | STRING | Driver username or email
access_token | STRING | Token for bearer authentication
office | STRING | Current office of this driver

`access_token` must be sent in each request in bearer header: `Authorization: Bearer access_token`.

### Successful response:
```
	{
		"username": "super_driver",
		"access_token": "Hlu7qYqtWczJAOJccTal9ZlA97IgmcII",
		"office":"Kent"
	}
```

# Errors

### Missing required fields: 
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

### Invalid username/password:
```
	[
		{
			"field":"password",
			"message":"Incorrect username or password."
		}
	]
```

If the number of requests more 10 per 5 min, ip address will automatically locked on 20 min.
### Block ip: 
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
```
	$ curl -H "Content-Type: application/json" -H "Dev-Token: dev_token" -X POST -d '{"username":"username","password":"password"}' http://domain/api/auth

	$ curl -H "Content-Type: application/json" -H "Dev-Token: dev_token" -X PUT -d '{"username":"username","password":"password"}' http://domain/api/auth
```

### Other request:
```
	$curl -H "Content-Type: application/json" -H "Authorization: Bearer access_token" -H "Dev-Token: dev_token" -X GET http://domain/api/action
```