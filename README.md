# MARC'S PHP REST API Demo
### Demonstrating REST API using PHP

## HOW TO TEST

### Client Tool: 
Use [HTTPie](https://httpie.org/).

### Run this PHP demo program as a server
```
C:\>php -S 127.0.0.1:8080
[Thu Apr  1 12:12:12 2021] PHP 8.0.1 Development Server (http://127.0.0.1:8080) started
```

### SQL Script to create the test database scheme
[CreateSubscribersDatabase.sql](resources/CreateSubsribersDatabase.sql)

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

If there are no records in the database, the API shall return an *HTTP 204: No Content* status code.
```
C:\>http get http://127.0.0.1:8080/subscribers
HTTP/1.1 204 No Content
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1
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
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "success": "Record of subscriber # 4 deleted."
}
```

### Error Test Case 1: Get a record of a subscriber who does not exist.
```
C:\>http get http://127.0.0.1:8080/subscribers/400
HTTP/1.1 404 Not Found
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "Subscriber does not exist"
}
```

### Error Test Case 2: Call an API without the prescribed 'subscribers' model
```
C:\>http get http://127.0.0.1:8080
HTTP/1.1 400 Bad Request
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "Resource does not exist. Please provide a valid REST API resource."
}
```

### Error Test Case 3: Call an API with a model that is not 'subscribers'
```
C:\>http get http://127.0.0.1:8080/notsubscribers/1/
HTTP/1.1 400 Bad Request
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "Resource notsubscribers does not exist. Please provide a valid REST API resource."
}
```
### Error Test Case 4: Call HTTP commands that are not being used by the API.
```
C:\>http trace http://127.0.0.1:8080/subscribers
HTTP/1.1 405 Method Not Allowed
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "HTTP command TRACE without specified ID is not allowed. Please provide an acceptable HTTP command."
}
```

### Error Test Case 5: POST an already existing record
```
C:\>http post http://127.0.0.1:8080/subscribers/?email_address=riseofskywalker@starwars.com"&"last_name=Palpatine"&"first_name=Rey
HTTP/1.1 409 Conflict Error
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "Posting/creating an already existing record. Please put/update an existing record or post/create a totally new record."
}
```

### Error Test Case 6-1: POST with specified ID.
```
C:\>http post http://127.0.0.1:8080/subscribers/1?email_address=riseofskywalker@starwars.com"&"last_name=Palpatine"&"first_name=Rey
HTTP/1.1 405 Method Not Allowed
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "HTTP command POST with specified ID is not allowed. Please provide an acceptable HTTP command."
}
```

### Error Test Case 6-2: POST without required parameters
```
C:\>http post http://127.0.0.1:8080/subscribers/
HTTP/1.1 405 Method Not Allowed
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "HTTP command POST without providing parameters is not allowed. Please provide an acceptable HTTP command."
}
```

### Error Test Case 6-3: PUT without specified ID.
```
C:\>http put http://127.0.0.1:8080/subscribers/?last_name=Skywalker
HTTP/1.1 405 Method Not Allowed
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "HTTP command PUT without specified ID is not allowed. Please provide an acceptable HTTP command."
}
```

### Error Test Case 6-4: PUT without required parameters
```
C:\>http put http://127.0.0.1:8080/subscribers/1
HTTP/1.1 405 Method Not Allowed
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "HTTP command PUT without providing parameters is not allowed. Please provide an acceptable HTTP command."
}
```

### Error Test Case 6-5: DELETE without specified ID
```
C:\>http delete http://127.0.0.1:8080/subscribers/
HTTP/1.1 405 Method Not Allowed
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "error": "HTTP command DELETE without specified ID is not allowed. Please provide an acceptable HTTP command."
}
```

For more inquiries, please feel free to e-mail me at marcanthonyconcepcion@gmail.com.

Thank you.

:copyright: 2021 Marc Concepcion

### END
