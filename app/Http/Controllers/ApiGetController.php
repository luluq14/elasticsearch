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
        $term=$request->input('term');
        $params = [
            'index' => 'oracle-prod',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>[
                            [
                                "common"=>[
                                    "prd_nm"=>[
                                        "query"=> $keyword,
                                        "cutoff_frequency"=> 1.0
                                    ]
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

        if(!empty($term)){
            $new_terms=json_decode($term);
            foreach ($new_terms as $key => $value){
                $params['body']['query']['bool']['filter']['bool']['should'][] =
                    [
                        "term"=> [
                            "lctgr_no" =>$value
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


    public function searchByMctgr(Request $request,$prdnm="",$mctgr="",$page=0,$limit=10){

        $params = [
            'index' => 'oracle-prod',
            'from' => $page,
            'size' =>$limit,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                "common"=>[
                                    "prd_nm"=>[
                                        "query"=> $prdnm,
                                        "cutoff_frequency"=> 1.0
                                    ]
                                ]
                            ],
                            [
                                "common"=>[
                                    "mctgr_nm"=>[
                                        "query"=> $mctgr,
                                        "cutoff_frequency"=> 1.0
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "max_price"=> [
                        "max"=> [
                            "field"=> "sel_prc"
                        ]
                    ],
                    "min_price"=> [
                        "min"=> [
                            "field"=> "sel_prc"
                        ]
                    ]
                ],
                'sort' => [
                    'pop_score' => [
                        'order' => 'desc'
                    ]
                ]
            ]
        ];


        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        $response["key"]=$prdnm;
        return $response;
    }

    public function Lctgr(Request $request,$keywords=""){

        $params = [
            'index' => 'oracle-prod',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            "common"=>[
                                "prd_nm"=>[
                                    "query"=> $keywords,
                                    "cutoff_frequency"=> 0.0001
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "group_by_lctgr"=> [
                        "terms"=> [
                            "field"=> "lctgr_no"
                        ],
                        "aggs"=> [
                            "group_by_mctgr"=> [
                                "terms"=> [
                                    "field"=> "mctgr_no"
                                ],
                                "aggs"=> [
                                    "group_by_sctgr"=> [
                                        "terms"=> [
                                            "field"=> "sctgr_no"
                                        ],
                                        "aggs"=> [
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

    public function searchByLctgr(Request $request,$keyword="",$keyword_lct="",$page=0,$limit=10){

        $params = [
            'index' => 'oracle-prod',
            'from' => $page,
            'size' =>$limit,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                "common"=>[
                                    "prd_nm"=>[
                                        "query"=> $keyword,
                                        "cutoff_frequency"=> 1.0
                                    ]
                                ]
                            ],
                            [
                                "common"=>[
                                    "lctgr_nm"=>[
                                        "query"=> $keyword_lct,
                                        "cutoff_frequency"=> 1.0
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "max_price"=> [
                        "max"=> [
                            "field"=> "sel_prc"
                        ]
                    ],
                    "min_price"=> [
                        "min"=> [
                            "field"=> "sel_prc"
                        ]
                    ]
                ],
                'sort' => [
                    'pop_score' => [
                        'order' => 'desc'
                    ]
                ]
            ]
        ];


        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        $response["key"]=$keyword;
        return $response;
    }

    public function Sctgr(Request $request,$keyword=""){
        $term=$request->input('term');

        $params = [
            'index' => 'oracle-prod',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>[
                            [
                                "common"=>[
                                    "prd_nm"=>[
                                        "query"=> $keyword,
                                        "cutoff_frequency"=> 1.0
                                    ]
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
                                    "_source"=> ["mctgr_no","mctgr_nm","sctgr_no","sctgr_nm"],
                                    "size" => 1
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if(!empty($term)){
            $new_terms=json_decode($term);
            foreach ($new_terms as $key => $value){
                $params['body']['query']['bool']['filter']['bool']['should'][] =
                    [
                        "term"=> [
                            "mctgr_no" =>$value
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

    public function SearchBySctgr(Request $request,$prdnm="",$sctgr="",$page=0,$limit=10){

        $params = [
            'index' => 'oracle-prod',
            'from' => $page,
            'size' =>$limit,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>[
                            [
                                "common"=>[
                                    "prd_nm"=>[
                                        "query"=> $prdnm,
                                        "cutoff_frequency"=> 1.0
                                    ]
                                ]
                            ],
                            [
                                "common"=>[
                                    "sctgr_nm"=>[
                                        "query"=> $sctgr,
                                        "cutoff_frequency"=> 1.0
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "max_price"=> [
                        "max"=> [
                            "field"=> "sel_prc"
                        ]
                    ],
                    "min_price"=> [
                        "min"=> [
                            "field"=> "sel_prc"
                        ]
                    ]
                ],
                'sort' => [
                    'pop_score' => [
                        'order' => 'desc'
                    ]
                ]
            ]
        ];


        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        $response["key"]=$prdnm;
        return $response;
    }


    public function checkSpell($keywords=""){

        $params = [
            'index' => 'categories',
            'body' => [
                'query' => [
                    'match' => [
                        'title' => [
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
            'index' => 'categories',
            'body' => [
                'query' => [
                    'fuzzy' => [
                        'title' => [
                            "value"=> $keywords,
                            "boost"=> 1.0,
                            "fuzziness"=> 1,
                            "prefix_length"=> 0,
                            "max_expansions"=> 50
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

    public function getSpell($keywords=""){

        $params = [
            'index' => 'categories',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                "common"=> [
                                    "title"=>[
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
                return $get;
            }else{
                return $cek2;
            }
        }else{
            return $cek;
        }
    }

    public function ListBrand(Request $request,$keywords=""){
        $params = [
            'index' => 'oracle-prod',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'common' => [
                                'prd_nm' => [
                                    "query"=> $keywords,
                                    "cutoff_frequency"=> 1.0
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' =>[
                    "group_by_lctgr"=> [
                        "terms"=> [
                            "field"=> "brand_nm.keyword"
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

    public function search(Request $request,$keywords=""){

        $sort=$request->input('sort');
        $order=$request->input('order');
        $term=$request->input('term');
        $range=$request->input('range');
        $filter=$request->input('filter');
        $brand=$request->input('brand');
        $page=$request->input('page');
        $limit=$request->input('limit');

        $params = [
            'index' => 'oracle-prod',
            'from' => $page,
            'size' =>$limit,
            "_source"=> ["prd_no","prd_nm","brand_nm","lctgr_nm","sctgr_nm","mctgr_nm","pop_score","buy_satisfy","create_dt","sale_score","sale_score2","sel_prc"],
            'body' => [
                'query' => [
                    'function_score' =>[
                        'query'=>[
                            'bool'=>[
                                'must' =>[
                                    [
                                        "common"=>[
                                            "prd_nm"=>[
                                                "query"=>$keywords,
                                                "cutoff_frequency" => 1.0
                                            ]
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
                            "field"=> "sel_prc"
                        ]
                    ],
                    "min_price"=> [
                        "min"=> [
                            "field"=> "sel_prc"
                        ]
                    ]
                ],
            ]
        ];


        if(!empty($sort)) {
            if(empty($order))$order="desc";
            $params['body']['sort'] =
                [
                    'pop_score' => [
                        'order' => $order
                    ]
                ];
        }

        if(!empty($range)) {
            $range = json_decode($range, true);
            foreach ($range as $key => $value) {
                $params['body']['query']['function_score']['query']['bool']['must'][] =
                    [
                        "range" => [
                            $key => [
                                "gte" => $value
                            ]
                        ]
                    ];
            }
        }

        if(!empty($brand)) {
            $params['body']['query']['function_score']['query']['bool']['should'] =
                [
                    "term"=>[
                        "brand_nm"=>$brand
                    ]
                ];
        }

        if(!empty($filter)) {
            $filter=json_decode($filter,true);
            foreach ($filter as $key => $value) {
                $params['body']['query']['function_score']['query']['bool']['filter']['bool']['should'][] =
                    [
                        "term" => [
                            $key => $value
                        ]
                    ];
            }
        }

        if(!empty($term)){
            $term=json_decode($term,true);
            foreach ($term as $key => $value){
                $params['body']['query']['function_score']['query']['bool']['must'][] =
                    [
                        "term"=> [
                            $key =>$value
                        ]
                    ];
            }

        }


        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        $response["key"]=$keywords;
        return $response;
    }
}