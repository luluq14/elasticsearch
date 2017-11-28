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
        "url": "/search/samsung?sort=pop_score&&order=asc&&term={\"lctgr_nm.keyword\":\"Mobile Phone / Smartwatch,Handphone Android\",\"mctgr_nm.keyword\":\"Mobile Phone,Mobile Phone / Smartwatch\",\"sctgr_nm.keyword\":\"Handphone Android\"}&&range={\"sel_prc\":\"500000\"}&&filter={\"free_shipping_yn.keyword\":\"Y\",\"app_cdt_free_yn.keyword\":\"Y\"}&&page=0&&limit=10&&brand=\"xiaomi,asus\""
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetSearchKeywords"
  },
  {
    "type": "get",
    "url": "/search/lctgr/{keyword}/{keyword_lct}/{page}/{limit}",
    "title": "Searching Lctgr",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/search/lctgr/iphone/mobile%20phone/0/10"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetSearchLctgrKeywordKeyword_lctPageLimit"
  },
  {
    "type": "get",
    "url": "/search/mctgr/{prdnm}/{mctgr}/{page}/{limit}",
    "title": "Searching Mctgr",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{keywords} type string, not empty parameter {keywords} to search document</p>",
    "sampleRequest": [
      {
        "url": "/search/mctgr/iphone/mobile%20phone/0/10"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetSearchMctgrPrdnmMctgrPageLimit"
  },
  {
    "type": "get",
    "url": "/search/sctgr/{prdnm}/{sctgr}/{page}/{limit}",
    "title": "Searching Sctgr",
    "group": "Search_Get",
    "version": "0.0.1",
    "description": "<p>{sctgr} type string, not empty parameter {sctgr} to search document</p>",
    "sampleRequest": [
      {
        "url": "/search/sctgr/iphone%206/iphone/0/10"
      }
    ],
    "filename": "routes/web.php",
    "groupTitle": "Search_Get",
    "name": "GetSearchSctgrPrdnmSctgrPageLimit"
  }
] });
