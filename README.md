# php-nemanjac 

## Flight advisor API
- Implemented in symfony framework v5.2
- SQLite db used (.db file included in project)

### User info
- Admin is created with credentials admin/admin123
- No regular users created
- All tables created and empty (except client table)
- Admin can preform all client actions as well

### Add city:
```
POST /city/addCities HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 366

{
    "auth":{
        "username":"admin",
        "password":"admin123"
    },
    "cities":[
        {
            "name":"Sault Ste Marie",
            "country":"United States",
            "description":"Nice city"
        }
    ]
}
```

### Import airports:
```
POST /import/airports HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 85

{
    "auth":{
        "username":"admin",
        "password":"admin123"
    }
}
```


### Import routes:
```
POST /import/routes HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 85

{
    "auth":{
        "username":"admin",
        "password":"admin123"
    }
}
```

### Register:
- Password should have 1 letter and 1 number, min. 6 chars
```
POST /register HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 155

{
    "register":{
        "firstname":"Nemanja",
        "lastname":"Cirovic",
        "username":"nemanja",
        "password":"client123"
    }
}
```

### Get all cities
- In request "commentsNum" optional
```
POST /city/getAllCities HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 111

{
    "auth":{
        "username":"nemanja",
        "password":"client123"
    },
    "commentsNum": 2
}
```

### Search cities by name:
- In request "commentsNum" optional
```
POST /city/searchCitiesByName HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 174

{
    "auth":{
        "username":"nemanja",
        "password":"client123"
    },
    "search": {
        "city":"Watertown",
        "commentsNum": 2
    }
}
```

### Add comment:
```
POST /city/addCityComment HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 178

{
    "auth":{
        "username":"nemanja",
        "password":"client123"
    },
    "comment": {
            "cityId":1,
            "text":"Very nice city"
        }
}
```

### Update comment:
```
POST /city/updateCityComment HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 185

{
    "auth":{
        "username":"nemanja",
        "password":"client123"
    },
    "comment": {
            "id":1,
            "text":"Updated comment about city"
        }
}
```

### Delete comment:
```
POST /city/deleteCityComment HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 129

{
    "auth":{
        "username":"nemanja",
        "password":"client123"
    },
    "comment":{
        "id":1
    }
}
```

### Find cheapest flight:
- In request added country param, because multiple countries can have city with same name
- In request "unit" is optional, values "mi" or "km"
- In response added airport name, because airports from same city can have different route connections
```
POST /city/findCheapestFlight HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Content-Length: 292

{
    "auth": {
        "username":"nemanja",
        "password":"client123"
    },
    "flight": {
        "sourceCity": "London",
        "sourceCountry": "United Kingdom",
        "destinationCity": "Belgrade",
        "destinationCountry": "Serbia",
        "unit":"km"
    }
}
```