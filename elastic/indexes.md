## Criação dos índices

    $ curl -X PUT http://localhost:9200/documents?pretty -d '{
        "aliases" : { },
        "mappings" : {
            "minute" : {
                "properties" : {
                    "agenda" : {
                        "type" : "text"
                    },
                    "created_at" : {
                        "type" : "date",
                        "format": "yyyy-MM-dd HH:mm:ss"
                    },
                    "date" : {
                        "type" : "date",
                        "format": "yyyy-MM-dd HH:mm:ss"
                    },
                    "discussion" : {
                        "type" : "text"
                    },
                    "neo4jId" : {
                        "type" : "long"
                    },
                    "referrals" : {
                        "type" : "text"
                    },
                    "reports" : {
                        "type" : "text"
                    },
                    "tags" : {
                        "type" : "keyword"
                    },
                    "title" : {
                        "type" : "text"
                    },
                    "type" : {
                        "type" : "text"
                    },
                    "updated_at" : {
                        "type" : "date",
                        "format": "yyyy-MM-dd HH:mm:ss"
                    }
                }
            }
        }
    }' -u elastic:changeme