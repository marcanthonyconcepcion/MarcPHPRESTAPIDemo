# MARC'S PHP REST API Demo
### Demonstrating REST API using PHP

## HOW TO TEST

Client Test Tool Used: 
HTTPie https://httpie.org/

MySQL Script used to populate the initial subscriber test records in the Subscriber Database.


## FUNCTIONAL TEST SAMPLES

### Requirement 1: Fetch All Subscriber User Information

#### Demonstrates GET without ID  and Retrieve all
```
C:\> http get http://127.0.0.1:8080/subscribers/1/
HTTP/1.1 200 OK
Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE
Access-Control-Allow-Origin: *
Access-Control-Max-Age: 3600
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 04 Apr 2021 13:30:07 GMT
Host: 127.0.0.1:8080
X-Powered-By: PHP/8.0.1

{
    "activation_flag": 0,
    "email_address": "marcanthonyconcepcion@gmail.com",
    "first_name": "Marc Anthony",
    "index": 1,
    "last_name": "Concepcion"
}
```

For more inquiries, please feel free to e-mail me at marcanthonyconcepcion@gmail.com.
Thank you.

# END
