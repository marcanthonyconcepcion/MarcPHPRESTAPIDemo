# MARC'S PHP REST API Demo
### Demonstrating REST API using PHP

## HOW TO TEST

Client Test Tool Used: 
HTTPie 
https://httpie.org/

## FUNCTIONAL TEST SAMPLES

### Requirement 1: Create a new subscriber user record

#### Demonstrates POST without ID and CREATE a specified single record
```
C:\>http post http://127.0.0.1:8080/subscribers?email_address=riseofskywalker@starwars.com"&"last_name=Palpatine"&"first_name=Rey
HTTP/1.1 201 Created
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 16:29:52 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "subscriber": {
        "email_address": "riseofskywalker@starwars.com",
        "first_name": "Rey",
        "last_name": "Palpatine"
    },
    "success": "Record created."
}
```

### Requirement 2-1: Fetch a subscriber user record

#### Demonstrates GET with ID and RETRIEVE a specified single record
```
C:\>http get http://127.0.0.1:8080/subscribers/4/
HTTP/1.1 200 OK
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 16:39:29 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "activation_flag": 0,
    "email_address": "riseofskywalker@starwars.com",
    "first_name": "Rey",
    "index": 4,
    "last_name": "Palpatine"
}
```

### Requirement 2-2: Fetch all subscriber user records

#### Demonstrates GET without ID and RETRIEVE all single records
```
C:\>http get http://127.0.0.1:8080/subscribers
HTTP/1.1 200 OK
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 16:38:40 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

[
    {
        "activation_flag": 0,
        "email_address": "marcanthonyconcepcion@gmail.com",
        "first_name": "Marc Anthony",
        "index": 1,
        "last_name": "Concepcion"
    },
    {
        "activation_flag": 0,
        "email_address": "marcanthonyconcepcion@email.com",
        "first_name": null,
        "index": 2,
        "last_name": null
    },
    {
        "activation_flag": 0,
        "email_address": "kevin.andrews@email.com",
        "first_name": null,
        "index": 3,
        "last_name": null
    },
    {
        "activation_flag": 0,
        "email_address": "riseofskywalker@starwars.com",
        "first_name": "Rey",
        "index": 4,
        "last_name": "Palpatine"
    }
]
```

### Requirement 3: Edit an existing subscriber user record

#### Demonstrates PUT with ID and UPDATE a specified single record
```
C:\>http put http://127.0.0.1:8080/subscribers/3?last_name=Andrews"&"first_name=Kevin
HTTP/1.1 200 OK
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 16:41:24 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "success": "Record of subscriber # 3 updated.",
    "updates": {
        "first_name": "Kevin",
        "last_name": "Andrews"
    }
}
```

### Requirement 4: Delete an existing subscriber user record

#### Demonstrates DELETE with ID and DELETE a specified single record
```
C:\>http delete http://127.0.0.1:8080/subscribers/4
HTTP/1.1 200 OK
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 16:42:18 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "success": "Record of subscriber # 4 deleted."
}
```

### Error Test Case 1: Get a record of a subscriber who does not exist.
```
C:\>http get http://127.0.0.1:8080/subscribers/4
HTTP/1.1 404 Not Found
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 16:49:25 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "Subscriber does not exist"
}
```

### Error Test Case 2: Call an API without the prescribed 'subscribers' model
```
C:\>http get http://127.0.0.1:8080/
HTTP/1.1 400 Bad Request
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 17:25:25 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1
```

### Error Test Case 2: Call an API with a model that is not 'subscribers'
```
C:\>http get http://127.0.0.1:8080/notsubscribers
HTTP/1.1 400 Bad Request
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 17:27:09 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1
```

### Error Test Case 3: Call HTTP commands that are not being used by the API.
```
C:\>http trace http://127.0.0.1:8080/subscribers
HTTP/1.1 405 Method Not Allowed
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 17:51:30 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "Cannot use TRACE command."
}
```
For more inquiries, please feel free to e-mail me at marcanthonyconcepcion@gmail.com.

Thank you.

# END
