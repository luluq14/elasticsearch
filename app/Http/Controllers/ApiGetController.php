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
        $term=$request->input('terms');

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
            $new_terms=json_decode($term,true);
            foreach ($new_terms as $key => $value){
                $params['body']['query']['bool']['filter']['bool']['must'][] =
                    [
                        "terms"=> [
                            $key =>explode(',',$value)
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


        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function Sctgr(Request $request,$keyword=""){
        $term=$request->input('terms');

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
                                    "_source"=> ["lctgr_no","lctgr_nm","mctgr_no","mctgr_nm","sctgr_no","sctgr_nm"],
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
                $params['body']['query']['bool']['filter']['bool']['must'][] =
                    [
                        "terms"=> [
                            $key =>explode(',',$value)
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

        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        return $response;
    }

    public function search(Request $request,$keywords=""){

        $sort=$request->input('sort');
        $term=$request->input('terms');
        $range=$request->input('range');
        $filter=$request->input('filter');
        $should=$request->input('should');
        $page=$request->input('page');
        $limit=$request->input('limit');

        $params = [
            'index' => 'oracle-prod',
            'from' => $page,
            'size' =>$limit,
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
                $params['body']['query']['function_score']['query']['bool']['must'][]['range'] =
                    [
                        $key => [
                            "gte" => $value['gte'],
                            "lte" => $value['lte'],
                        ]
                    ];
            }
        }

        if(!empty($should)) {
            $should=json_decode($should,true);
            foreach ($should as $key => $value) {
                $params['body']['query']['function_score']['query']['bool']['should'][] =
                    [
                        "terms" => [
                            $key => explode(',', $value)
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
                            $key => explode(',',$value)
                        ]
                    ];
            }
        }

        if(!empty($term)){
            $term=json_decode($term,true);
            foreach ($term as $key => $value){
                $params['body']['query']['function_score']['query']['bool']['must'][] =
                    [
                        "terms"=> [
                            $key =>explode(',',$value)
                        ]
                    ];
            }

        }

//        print_r($params);die();
        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        $response["key"]=$keywords;
        return $response;
    }
}
