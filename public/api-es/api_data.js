define({ "api": [
  {
    "type": "get",
    "url": "/search/multiple/{keywords}/{page}/{limit}",
    "title": "Searching Multiple Keywords",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/search/multiple/iphone/0/10"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetSearchMultipleKeywordsPageLimit"
  },
  {
    "type": "get",
    "url": "/search/single/{keywords}/{page}/{limit}",
    "title": "Searching Single Keywords",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/search/single/iphone/0/10"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetSearchSingleKeywordsPageLimit"
  },
  {
    "type": "post",
    "url": "/search/multiple",
    "title": "Searching Multiple Keywords",
    "group": "Search_Post",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "keywords",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "page",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "limit",
            "description": ""
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "/search/multiple"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Post",
    "name": "PostSearchMultiple"
  },
  {
    "type": "post",
    "url": "/search/single",
    "title": "Searching Single Keywords",
    "group": "Search_Post",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "keywords",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "page",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "limit",
            "description": ""
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "/search/single"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Post",
    "name": "PostSearchSingle"
  }
] });
