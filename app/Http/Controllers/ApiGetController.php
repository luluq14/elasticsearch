<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiGetController extends BaseController
{
    private $host;
    public function __construct()
    {
        $this->host=[
            // This is effectively equal to: "https://username:password!#$?*abc@foo.com:9200/"
            [
                'host' =>  env('ELASTICSEARCH_HOST'),
                'port' =>  env('ELASTICSEARCH_PORT'),
                'scheme' =>  env('ELASTICSEARCH_SCHEME'),
                'user' => env('ELASTICSEARCH_USER'),
                'pass' => env('ELASTICSEARCH_PASS')
            ]
        ];
    }
    public function Mctgr(Request $request,$keyword=""){
        $sort=$request->input('sort');
        $term=$request->input('terms');
        $range=$request->input('range');
        $filter=$request->input('filter');

        $params = [
            'index' => 'oracle',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>[
                            [
                                "multi_match"=>[
                                    "query"=>$keyword,
                                    "type"=> "best_fields",
                                    "fields"=>[
                                        "prd_nm^10",
                                        "nck_nm",
                                        "lctgr_nm",
                                        "mctgr_nm",
                                        "sctgr_nm",
                                        "brand_nm"
                                    ],
                                    "operator"=> "and"
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "group_by_no"=> [
                        "terms"=> [
                            "field"=> "mctgr_no"
                        ],
                        "aggs"=> [
                            "tops"=> [
                                "top_hits"=>[
                                    "_source"=> ["lctgr_no","lctgr_nm","mctgr_no","mctgr_nm"],
                                    "size" => 1
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if(!empty($sort)) {
            $sort=json_decode($sort,true);
            foreach ($sort as $key => $value) {
                $params['body']['sort'][] =
                    [
                        $key => [
                            'order' => $value['order']
                        ]
                    ];
            }
        }

        if(!empty($range)) {
            $range=json_decode($range,true);
            foreach ($range as $key => $value) {
                $params['body']['query']['bool']['must'][]['range'] =
                    [
                        $key => [
                            "gte" => $value['gte'],
                            "lte" => $value['lte'],
                        ]
                    ];
            }
        }

        if(!empty($filter)) {
            $filter=json_decode($filter,true);
            foreach ($filter as $key => $value) {
                $params['body']['query']['bool']['filter']['bool']['should'][] =
                    [
                        "terms" => [
                            $key => $value
                        ]
                    ];
            }
        }

        if(!empty($term)){
            $term=json_decode($term,true);
            foreach ($term as $key => $value){
                $params['body']['query']['bool']['must'][]=
                    [
                        "terms"=> [
                            $key =>$value
                        ]
                    ];
            }

        }

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function Lctgr(Request $request,$keywords=""){
        $sort=$request->input('sort');
        $term=$request->input('terms');
        $range=$request->input('range');
        $filter=$request->input('filter');

        $params = [
            'index' => 'oracle',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                "multi_match"=>[
                                    "query"=>$keywords,
                                    "type"=> "best_fields",
                                    "fields"=>[
                                        "prd_nm^10",
                                        "nck_nm",
                                        "lctgr_nm",
                                        "mctgr_nm",
                                        "sctgr_nm",
                                        "brand_nm"
                                    ],
                                    "operator"=> "and"
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "group_by_no"=> [
                        "terms"=> [
                            "field"=> "lctgr_no"
                        ],
                        "aggs"=> [
                            "tops"=> [
                                "top_hits"=> [
                                    "_source"=> ["lctgr_no","lctgr_nm"],
                                    "size" => 1
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if(!empty($sort)) {
            $sort=json_decode($sort,true);
            foreach ($sort as $key => $value) {
                $params['body']['sort'][] =
                    [
                        $key => [
                            'order' => $value['order']
                        ]
                    ];
            }
        }

        if(!empty($range)) {
            $range=json_decode($range,true);
            foreach ($range as $key => $value) {
                $params['body']['query']['bool']['must'][]['range'] =
                    [
                        $key => [
                            "gte" => $value['gte'],
                            "lte" => $value['lte'],
                        ]
                    ];
            }
        }

        if(!empty($filter)) {
            $filter=json_decode($filter,true);
            foreach ($filter as $key => $value) {
                $params['body']['query']['bool']['filter']['bool']['should'][] =
                    [
                        "terms" => [
                            $key => $value
                        ]
                    ];
            }
        }

        if(!empty($term)){
            $term=json_decode($term,true);
            foreach ($term as $key => $value){
                $params['body']['query']['bool']['must'][]=
                    [
                        "terms"=> [
                            $key =>$value
                        ]
                    ];
            }

        }

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function Sctgr(Request $request,$keyword=""){
        $sort=$request->input('sort');
        $term=$request->input('terms');
        $range=$request->input('range');
        $filter=$request->input('filter');

        $params = [
            'index' => 'oracle',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>[
                            [
                                "multi_match"=>[
                                    "query"=>$keyword,
                                    "type"=> "best_fields",
                                    "fields"=>[
                                        "prd_nm^10",
                                        "nck_nm",
                                        "lctgr_nm",
                                        "mctgr_nm",
                                        "sctgr_nm",
                                        "brand_nm"
                                    ],
                                    "operator"=> "and"
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "group_by_no"=> [
                        "terms"=> [
                            "field"=> "sctgr_no"
                        ],
                        'aggs' =>[
                            "tops"=> [
                                "top_hits"=> [
                                    "_source"=> ["lctgr_no","lctgr_nm","mctgr_no","mctgr_nm","sctgr_no","sctgr_nm"],
                                    "size" => 1
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if(!empty($sort)) {
            $sort=json_decode($sort,true);
            foreach ($sort as $key => $value) {
                $params['body']['sort'][] =
                    [
                        $key => [
                            'order' => $value['order']
                        ]
                    ];
            }
        }
        if(!empty($range)) {
            $range=json_decode($range,true);
            foreach ($range as $key => $value) {
                $params['body']['query']['bool']['must'][]['range'] =
                    [
                        $key => [
                            "gte" => $value['gte'],
                            "lte" => $value['lte'],
                        ]
                    ];
            }
        }

        if(!empty($filter)) {
            $filter=json_decode($filter,true);
            foreach ($filter as $key => $value) {
                $params['body']['query']['bool']['filter']['bool']['should'][] =
                    [
                        "terms" => [
                            $key => $value
                        ]
                    ];
            }
        }

        if(!empty($term)){
            $term=json_decode($term,true);
            foreach ($term as $key => $value){
                $params['body']['query']['bool']['must'][]=
                    [
                        "terms"=> [
                            $key =>$value
                        ]
                    ];
            }

        }

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function checkSpell($keywords=""){

        $params = [
            'index' => 'oztmt-new',
            'from' =>0,
            'size' =>10,
            '_source'=> ['keyword'],
            'body' => [
                'query' => [
                    'match' => [
                        'keyword' => [
                            "query"=> $keywords,
                            "operator"=> "and"
                        ]
                    ]
                ]
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function checkSpell2($keywords=""){

        $params = [
            'index' => 'oztmt-new',
            '_source'=> ['keyword'],
            'from' =>0,
            'size' =>10,
            'body' => [
                'query' => [
                    'fuzzy' => [
                        'keyword' =>  $keywords,
                    ]
                ]
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function getSpell($keywords=""){

        $params = [
            'index' => 'oztmt-new',
            '_source'=> ['keyword'],
            'from' =>0,
            'size' =>10,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                "common"=> [
                                    "keyword"=>[
                                        "query"=>$keywords,
                                        "cutoff_frequency"=> 0.9
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function missSpell(Request $request,$keywords=""){
        $cek= $this->checkSpell($keywords);
        $cek2= $this->checkSpell2($keywords);
        $get=$this->getSpell($keywords);

        if($cek['hits']['total']==0){
            if($cek2['hits']['total']==0){
                $data=$get;
            }else{
                $data=$cek2;
            }
        }else{
            $data=$cek;
        }
        if(count($data['hits']['hits'])>0){
            foreach ($data['hits']['hits'] as $key => $value){
                $data['hits']['hits'][$key]['_source']['title']=$value['_source']['keyword'];
                unset( $data['hits']['hits'][$key]['_source']['keyword']);
            }
        }
        return $data;
    }

    public function ListBrand(Request $request,$keywords=""){
        $sort=$request->input('sort');
        $term=$request->input('terms');
        $range=$request->input('range');
        $filter=$request->input('filter');

        $params = [
            'index' => 'oracle',
            'size' =>0,
            'body' =>[
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                "multi_match"=>[
                                    "query"=>$keywords,
                                    "type"=> "best_fields",
                                    "fields"=>[
                                        "prd_nm^10",
                                        "nck_nm",
                                        "lctgr_nm",
                                        "mctgr_nm",
                                        "sctgr_nm",
                                        "brand_nm"
                                    ],
                                    "operator"=> "and"
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "group_by_no"=> [
                        "terms"=> [
                            "field"=> "brand_nm.keyword"
                        ],
                        'aggs' =>[
                            "tops"=> [
                                "top_hits"=> [
                                    "_source"=> ["brand_nm","brand_cd"],
                                    "size" => 1
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if(!empty($sort)) {
            $sort=json_decode($sort,true);
            foreach ($sort as $key => $value) {
                $params['body']['sort'][] =
                    [
                        $key => [
                            'order' => $value['order']
                        ]
                    ];
            }
        }

        if(!empty($range)) {
            $range=json_decode($range,true);
            foreach ($range as $key => $value) {
                $params['body']['query']['bool']['must'][]['range'] =
                    [
                        $key => [
                            "gte" => $value['gte'],
                            "lte" => $value['lte'],
                        ]
                    ];
            }
        }

        if(!empty($filter)) {
            $filter=json_decode($filter,true);
            foreach ($filter as $key => $value) {
                $params['body']['query']['bool']['filter']['bool']['should'][] =
                    [
                        "terms" => [
                            $key => $value
                        ]
                    ];
            }
        }

        if(!empty($term)){
            $term=json_decode($term,true);
            foreach ($term as $key => $value){
                $params['body']['query']['bool']['must'][]=
                    [
                        "terms"=> [
                            $key =>$value
                        ]
                    ];
            }

        }


        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }


    public function search(Request $request,$keyword=""){

        $sort=$request->input('sort');
        $term=$request->input('terms');
        $range=$request->input('range');
        $filter=$request->input('filter');
//        $should=$request->input('should');
        $page=$request->input('page');
        $limit=$request->input('limit');
        $from=$page*$limit;

        $keywords=$this->replace($keyword);
        $suggest=$this->cek($keyword);

//        $multi=[
//            [
//                "multi_match"=>[
//                    "query"=>$keywords,
//                    "type"=> "best_fields",
//                    "fields"=>[
//                        "prd_nm^10",
//                        "nck_nm",
//                        "lctgr_nm",
//                        "mctgr_nm",
//                        "sctgr_nm",
//                        "brand_nm"
//                    ],
//                    "operator"=> "and"
//                ]
//            ]
//        ];

//        if ((preg_match('/case/',$keywords)) || (preg_match('/ case /',$keywords))
//            || (preg_match('/casing/',$keywords)) || (preg_match('/ casing /',$keywords))
//            || (preg_match('/tempered glass/',$keywords)) || (preg_match('/ tempered glass /',$keywords))
//            || (preg_match('/baterai/',$keywords)) || (preg_match('/ baterai /',$keywords))
//            || (preg_match('/anti gores/',$keywords)) || (preg_match('/ anti gores /',$keywords))
//            || (preg_match('/screen protector/',$keywords)) || (preg_match('/ screen protector /',$keywords))
//            || (preg_match('/charger/',$keywords)) || (preg_match('/ charger /',$keywords))
//            || (preg_match('/sparepart/',$keywords)) || (preg_match('/ sparepart /',$keywords))
//            || (preg_match('/kabel data/',$keywords)) || (preg_match('/ kabel data /',$keywords))
//            || (preg_match('/powerbank/',$keywords)) || (preg_match('/ powerbank /',$keywords))
//            || (preg_match('/stand/',$keywords)) || (preg_match('/ stand /',$keywords))
//            || (preg_match('/tongsis/',$keywords)) || (preg_match('/ tongsis /',$keywords))
//            || (preg_match('/lensa/',$keywords)) || (preg_match('/ lensa /',$keywords))
//
//        ){
//            $hasil=[
//                'must' =>$multi
//            ];
//        }else{
//            $hasil=[
//                'must' =>$multi,
//                'must_not'=>[
//                    "term"=>[
//                        "mctgr_no"=>[
//                            "value"=> "363"
//                        ]
//                    ]
//                ]
//            ];
//        }

        $params = [
            'index' => 'oracle',
            'from' => $from,
            'size' =>$limit,
            'body' => [
                'query' => [
                    'function_score' =>[
                        'query'=>[
                            'bool'=>[
                                "must"=>[
                                    [
                                        "multi_match"=>[
                                            "query"=>$keywords,
                                            "type"=> "best_fields",
                                            "fields"=>[
                                                "prd_nm^10",
                                                "nck_nm",
                                                "lctgr_nm",
                                                "mctgr_nm",
                                                "sctgr_nm",
                                                "brand_nm"
                                            ],
                                            "operator"=> "and"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "boost" => "5",
                        "functions"=>[
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>360
                                    ]
                                ],
                                "weight"=>5
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>3691
                                    ]
                                ],
                                "weight"=>5
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>326
                                    ]
                                ],
                                "weight"=>4
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>363
                                    ]
                                ],
                                "weight"=>4
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>4995
                                    ]
                                ],
                                "weight"=>4
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>5047
                                    ]
                                ],
                                "weight"=>4
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>417
                                    ]
                                ],
                                "weight"=>4
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>382
                                    ]
                                ],
                                "weight"=>4
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>5012
                                    ]
                                ],
                                "weight"=>4
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>459
                                    ]
                                ],
                                "weight"=>4
                            ],
                            [
                                "filter"=>[
                                    "match"=>[
                                        "mctgr_no"=>445
                                    ]
                                ],
                                "weight"=>4
                            ]

                        ],
                        "max_boost"=>10,
                        "score_mode"=> "max",
                        "boost_mode"=>"multiply",
                        "min_score" => 1
                    ]
                ],
                'aggs' =>[
                    "max_price"=> [
                        "max"=> [
                            "field"=> "final_dsc_prc"
                        ]
                    ],
                    "min_price"=> [
                        "min"=> [
                            "field"=> "final_dsc_prc"
                        ]
                    ]
                ],
            ]
        ];


        if(!empty($sort)) {
            $sort=json_decode($sort,true);
            foreach ($sort as $key => $value) {
                $params['body']['sort'][] =
                    [
                        $key => [
                            'order' => $value['order']
                        ]
                    ];
                if($key=="ctgr_bstng") {
                    $params['body']['sort'][] =
                        [
                            "_script" => [
                                'script' => "doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('iPhone') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('IPHONE') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('Samsung') ? 90 :  doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('SAMSUNG') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('Xiaomi') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('XIAOMI') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('Vivo') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('VIVO') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('OPPO') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('Oppo') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('Asus') ? 90 : doc['mctgr_no'].value == 360 &&
                                            doc['prd_nm.keyword'].value.contains('ASUS') ? 90: 50",
                                "type" => "number",
                                "order" => $value['order']
                            ]
                        ];
                }
            }
        }

        if(!empty($range)) {
            $range=json_decode($range,true);
            foreach ($range as $key => $value) {
             //   $params['body']['query']['function_score']['query']['bool']['filter']['bool']['must'][]['range'] =
               $params['body']['query']['function_score']['query']['bool']['must'][]['range'] =
                    [
                        $key => [
                            "gte" => $value['gte'],
                            "lte" => $value['lte'],
                        ]
                    ];
            }
        }

        if(!empty($filter)) {
            $filter=json_decode($filter,true);
            foreach ($filter as $key => $value) {
                $params['body']['query']['function_score']['query']['bool']['filter']['bool']['should'][] =
                    [
                        "terms" => [
                            $key => $value
                        ]
                    ];
            }
        }

        if(!empty($term)){
            $term=json_decode($term,true);
            foreach ($term as $key => $value){
                $params['body']['query']['function_score']['query']['bool']['must'][] =
      //          $params['body']['query']['function_score']['query']['bool']['filter']['bool']['must'][]=
                    [
                        "terms"=> [
                            $key =>$value
                        ]
                    ];
            }

        }
        print_r($params);die();

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        $response["key"]=$keyword;
        $response["correct"]=$keywords;
        $response["suggest"]=$suggest;
        return $response;
    }


    public function checkSuggest($keywords=""){

        $params = [
            'index' => 'oztmt-new',
            'from' =>0,
            'size' =>3,
            '_source'=> ['keyword'],
            'body' => [
                'query' => [
                    'match' => [
                        'keyword' => [
                            "query"=> $keywords,
                            "operator"=> "and"
                        ]
                    ]
                ]
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function checkSuggest2($keywords=""){

        $params = [
            'index' => 'oztmt-new',
            '_source'=> ['keyword'],
            'from' =>0,
            'size' =>3,
            'body' => [
                'query' => [
                    'fuzzy' => [
                        'keyword' =>  $keywords,
                    ]
                ]
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function getSuggest($keywords=""){

        $params = [
            'index' => 'oztmt-new',
            '_source'=> ['keyword'],
            'from' =>0,
            'size' =>3,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                "common"=> [
                                    "keyword"=>[
                                        "query"=>$keywords,
                                        "cutoff_frequency"=> 0.9
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function suggest(Request $request,$keywords=""){
        $cek= $this->checkSuggest($keywords);
        $cek2= $this->checkSuggest2($keywords);
        $get=$this->getSuggest($keywords);

        if($cek['hits']['total']==0){
            if($cek2['hits']['total']==0){
                $data=$get;
            }else{
                $data=$cek2;
            }
        }else{
            $data=$cek;
        }
        if(count($data['hits']['hits'])>0){
            $keyw=[];
            foreach ($data['hits']['hits'] as $key => $value){
               $keyw[]=$value['_source']['keyword'];
            }
            $data['hits']['hits'][0]['_source']['suggest']=$keyw;
            unset( $data['hits']['hits'][0]['_source']['keyword']);
            return  $data['hits']['hits'][0];
        }
        return  $data;
    }

    public function cek($keywords=""){
        $cek= $this->checkSuggest($keywords);
        $cek2= $this->checkSuggest2($keywords);
        $get=$this->getSuggest($keywords);

        if($cek['hits']['total']==0){
            if($cek2['hits']['total']==0){
                $data=$get;
            }else{
                $data=$cek2;
            }
        }else{
            $data=$cek;
        }
        if(count($data['hits']['hits'])>0){
            $keyw=[];
            foreach ($data['hits']['hits'] as $key => $value){
                $keyw[]=$value['_source']['keyword'];
            }
            return  $keyw;
        }
        return $keywords;
    }

    public function replace($keywords=""){
        $params = [
            'index' => 'correct-key-new',
            '_source'=> 'correct',
            'from' =>0,
            'size' =>1,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                              "term"=>[
                                  "error"=>[
                                      "value"=> $keywords,
                                      "boost"=> 2.0
                                  ]
                              ]
                            ],
                            [
                                "term"=>[
                                    "status"=>"normal"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        if(count($response['hits']['hits'])>0){
            return $response['hits']['hits'][0]['_source']['correct'];
        }

        return $keywords;
    }
}
