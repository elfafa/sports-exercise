---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://sports.localdev/docs/collection.json)

<!-- END_INFO -->

#Get match statistics
<!-- START_eb9cbaf4526f36fdc9ee1b657924d95b -->
## Get match infos

> Example request:

```bash
curl -X GET "http://sports.localdev/api/match-data/football/{externalId}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://sports.localdev/api/match-data/football/{externalId}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "Unknow match"
}
```

### HTTP Request
`GET api/match-data/football/{externalId}`

`HEAD api/match-data/football/{externalId}`


<!-- END_eb9cbaf4526f36fdc9ee1b657924d95b -->

<!-- START_b12bec494e929c79c3fa5b835e5b5b1b -->
## Get top matches (best goals)

> Example request:

```bash
curl -X GET "http://sports.localdev/api/match-top/football" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://sports.localdev/api/match-top/football",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[
    {
        "competition": "English Barclays Premier League",
        "match_id": "2723",
        "season": "2017",
        "sport": "Football",
        "teams": {
            "home": "Arsenal",
            "away": "Leicester City"
        },
        "match_length": 96,
        "match_date": "2017-08-11 19:45:00",
        "created_at": "2018-02-13 17:09:20",
        "updated_at": "2018-02-13 17:09:20",
        "complete": true,
        "stats": {
            "top_scorer": "Jamie Vardy",
            "winner": "home",
            "total_goals": 7,
            "red_cards": 0,
            "yellow_cards": 0,
            "home": {
                "total_tackles": 0,
                "total_touches": 859,
                "total_fouls": 9
            },
            "away": {
                "total_tackles": 0,
                "total_touches": 457,
                "total_fouls": 12
            }
        }
    }
]
```

### HTTP Request
`GET api/match-top/football`

`HEAD api/match-top/football`


<!-- END_b12bec494e929c79c3fa5b835e5b5b1b -->

<!-- START_c113f9a1988077b05fffbda4f5063249 -->
## Get top matches (best goals)

> Example request:

```bash
curl -X GET "http://sports.localdev/api/match-top/football/{minimumGoals}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://sports.localdev/api/match-top/football/{minimumGoals}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[
    {
        "competition": "English Barclays Premier League",
        "match_id": "2723",
        "season": "2017",
        "sport": "Football",
        "teams": {
            "home": "Arsenal",
            "away": "Leicester City"
        },
        "match_length": 96,
        "match_date": "2017-08-11 19:45:00",
        "created_at": "2018-02-13 17:09:20",
        "updated_at": "2018-02-13 17:09:20",
        "complete": true,
        "stats": {
            "top_scorer": "Jamie Vardy",
            "winner": "home",
            "total_goals": 7,
            "red_cards": 0,
            "yellow_cards": 0,
            "home": {
                "total_tackles": 0,
                "total_touches": 859,
                "total_fouls": 9
            },
            "away": {
                "total_tackles": 0,
                "total_touches": 457,
                "total_fouls": 12
            }
        }
    }
]
```

### HTTP Request
`GET api/match-top/football/{minimumGoals}`

`HEAD api/match-top/football/{minimumGoals}`


<!-- END_c113f9a1988077b05fffbda4f5063249 -->

<!-- START_4897106b4c1f1778f4fa49404e647eff -->
## Get team matches

> Example request:

```bash
curl -X GET "http://sports.localdev/api/match-team/football/{team}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://sports.localdev/api/match-team/football/{team}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET api/match-team/football/{team}`

`HEAD api/match-team/football/{team}`


<!-- END_4897106b4c1f1778f4fa49404e647eff -->

<!-- START_0755327716876ee528cd4548ae823341 -->
## Get team matches

> Example request:

```bash
curl -X GET "http://sports.localdev/api/match-team/football/{team}/{quantity}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://sports.localdev/api/match-team/football/{team}/{quantity}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET api/match-team/football/{team}/{quantity}`

`HEAD api/match-team/football/{team}/{quantity}`


<!-- END_0755327716876ee528cd4548ae823341 -->

#Import match datas
<!-- START_608f1fe474014eccc297f7d6fdb8a2bf -->
## Save a match

> Example request:

```bash
curl -X POST "http://sports.localdev/api/match-create/football" \
-H "Accept: application/json" \
    -d "competition"="consequuntur" \
    -d "match_id"="18" \
    -d "season"="18" \
    -d "sport"="consequuntur" \
    -d "teams.home"="consequuntur" \
    -d "teams.away"="consequuntur" \
    -d "created_at"="1976-02-10" \
    -d "updated_at"="1976-02-10" \
    -d "feed_file"="http://dickens.info/" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://sports.localdev/api/match-create/football",
    "method": "POST",
    "data": {
        "competition": "consequuntur",
        "match_id": 18,
        "season": 18,
        "sport": "consequuntur",
        "teams.home": "consequuntur",
        "teams.away": "consequuntur",
        "created_at": "1976-02-10",
        "updated_at": "1976-02-10",
        "feed_file": "http:\/\/dickens.info\/"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/match-create/football`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    competition | string |  required  | 
    match_id | integer |  required  | 
    season | integer |  required  | 
    sport | string |  required  | 
    teams.home | string |  required  | 
    teams.away | string |  required  | 
    created_at | date |  required  | 
    updated_at | date |  required  | 
    feed_file | url |  required  | 

<!-- END_608f1fe474014eccc297f7d6fdb8a2bf -->

