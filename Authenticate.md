# Authenticate app
Authentication url: `http://domain/api/auth`.
Avilable methods: `POST`, `PUT`.
Required params: `username`, `password` transmitted to the body of the request in json format.
If authentication is successful, the server returns `username`, `access_token`. 
`access_token` must be sent in each request: `http://domain/api/action?access-token=xxxxxxxxxxxxxxxxxx`.

**Successful response:**
```
	{
		"username": "super_driver",
		"access_token": "PyYdSlQOqoH_u-TBU_PeliF1xiZAiQxS"
	}
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

	$ curl -H "Content-Type: application/json" -X POST -d '{"username":"username","password":"password"}' http://domain/api/auth

	$ curl -H "Content-Type: application/json" -X PUT -d '{"username":"username","password":"password"}' http://domain/api/auth