define({ "api": [
  {
    "type": "get",
    "url": "/brand/{keywords}",
    "title": "List Brand",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/brand/samsung"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetBrandKeywords"
  },
  {
    "type": "get",
    "url": "/lctgr/{keywords}",
    "title": "List Lctgr",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/lctgr/iphone"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetLctgrKeywords"
  },
  {
    "type": "get",
    "url": "/mctgr/{keyword}",
    "title": "List Mctgr",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keyword} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/mctgr/samsung?terms={\"lctgr_no\":\"359,390\"}"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetMctgrKeyword"
  },
  {
    "type": "get",
    "url": "/miss/{keywords}",
    "title": "Miss Spell",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/miss/samsun"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetMissKeywords"
  },
  {
    "type": "get",
    "url": "/sctgr/{keywords}",
    "title": "List Sctgr",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/sctgr/samsung?terms={\"mctgr_no\":\"363,5047\"}"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetSctgrKeywords"
  },
  {
    "type": "get",
    "url": "/search/{keywords}",
    "title": "Search",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/search/samsung?sort={\"pop_score\":{\"order\":\"desc\"},\"sel_prc\":{\"order\":\"asc\"}}&&terms={\"lctgr_nm.keyword\":[\"Mobile Phone / Smartwatch\",\"Handphone Android\"],\"mctgr_nm.keyword\":[\"Mobile Phone\",\"Mobile Phone / Smartwatch\"],\"sctgr_nm.keyword\":[\"Handphone Android\"]}&&range={\"sel_prc\":{\"gte\":\"500000\",\"lte\":\"1000000\"},\"buy_satisfy\":{\"gte\":\"0\",\"lte\":\"100\"}}&&filter={\"free_shipping_yn.keyword\":[\"Y\"],\"app_cdt_free_yn.keyword\":[\"Y\"]}&&page=0&&limit=10&&should={\"brand_nm.keyword\":[\"xiaomi\",\"asus\"],\"brand_cd.keyword\":[\"20007\"]}"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetSearchKeywords"
  }
] });
