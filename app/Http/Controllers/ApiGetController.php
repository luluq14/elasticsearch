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

    public function multiple(Request $request,$keywords="",$page=0,$limit=10){

        $multi=[
            "common"=>[
                "prd_nm"=>[
                    "query"=>$keywords,
                    "cutoff_frequency" => 1.0
                ]
            ],
        ];

        if ((preg_match('/case /',$keywords)) || (preg_match('/ case /',$keywords))
            || (preg_match('/casing /',$keywords)) || (preg_match('/ casing /',$keywords))
            || (preg_match('/tempered glass /',$keywords)) || (preg_match('/ tempered glass /',$keywords))
            || (preg_match('/baterai /',$keywords)) || (preg_match('/ baterai /',$keywords))
            || (preg_match('/anti gores /',$keywords)) || (preg_match('/ anti gores /',$keywords))
            || (preg_match('/screen protector /',$keywords)) || (preg_match('/ screen protector /',$keywords))
            || (preg_match('/charger /',$keywords)) || (preg_match('/ charger /',$keywords))
            || (preg_match('/sparepart /',$keywords)) || (preg_match('/ sparepart /',$keywords))
            || (preg_match('/kabel data /',$keywords)) || (preg_match('/ kabel data /',$keywords))
            || (preg_match('/powerbank /',$keywords)) || (preg_match('/ powerbank /',$keywords))
            || (preg_match('/stand handphone /',$keywords)) || (preg_match('/ stand handphone /',$keywords))
            || (preg_match('/tongsis /',$keywords)) || (preg_match('/ tongsis /',$keywords))
            || (preg_match('/lensa handphone /',$keywords)) || (preg_match('/ lensa handphone /',$keywords))

        ){
            $hasil=[
                'must' =>$multi,
            ];
        }else{
            $hasil=[
                'must' =>$multi,
                'must_not'=>[
                    "common"=>[
                        "mctgr_nm"=>[
                            "query"=>"Aksesoris",
                            "cutoff_frequency"=> 1.0
                        ]
                    ]
                ]
            ];
        }

//        print_r($hasil);die();

        $params = [
            'index' => 'oracle-prod',
            'from' => $page,
            'size' =>$limit,
            'body' => [
                'sort' => [
                    'pop_score' => [
                        'order' => 'desc'
                    ]
                ],
                'min_score'=>1.0,
                'query' => [
                    'bool' => $hasil
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


        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        $response["key"]=$keywords;
        return $response;
    }

    public function Mctgr(Request $request,$keyword_lct="",$keyword=""){

        $params = [
            'index' => 'oracle-prod',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>[
                            [
                                "common"=>[
                                    "lctgr_nm"=>[
                                        "query"=> $keyword_lct,
                                        "cutoff_frequency"=> 1.0
                                    ]
                                ]
                            ],
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
                    "group_by_nm"=> [
                        "terms"=> [
                            "field"=> "mctgr_nm.keyword"
                        ]
                    ],
                    "group_by_no"=> [
                        "terms"=> [
                            "field"=> "mctgr_no"
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
                    "group_by_nm"=> [
                        "terms"=> [
                            "field"=> "lctgr_nm.keyword"
                        ]
                    ],
                    "group_by_no"=> [
                        "terms"=> [
                            "field"=> "lctgr_no"
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

    public function LctgrAll(Request $request,$keywords=""){

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
                            "field"=> "lctgr_nm.keyword"
                        ],
                        "aggs"=> [
                            "group_by_mctgr"=> [
                                "terms"=> [
                                    "field"=> "mctgr_nm.keyword"
                                ],
                                "aggs"=>[
                                    "group_by_sctgr"=> [
                                        "terms"=> [
                                            "field"=> "sctgr_nm.keyword"
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

    public function Sctgr(Request $request,$keyword_mct="",$keyword=""){

        $params = [
            'index' => 'oracle-prod',
            'size' =>0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' =>[
                            [
                                "common"=>[
                                    "mctgr_nm"=>[
                                        "query"=> $keyword_mct,
                                        "cutoff_frequency"=> 1.0
                                    ]
                                ]
                            ],
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
                    "group_by_nm"=> [
                        "terms"=> [
                            "field"=> "sctgr_nm.keyword"
                        ]
                    ],
                    "group_by_no"=> [
                        "terms"=> [
                            "field"=> "sctgr_no"
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
            'index' => 'oracle-prod',
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


    public function getSpell($keywords=""){

        $params = [
            'index' => 'oracle-prod',
            'body' => [
                'query' => [
                    'fuzzy' => [
                        'title' => [
                            "value"=> $keywords,
                            "boost"=> 1.0,
                            "fuzziness"=> 1,
                            "prefix_length"=> 0,
                            "max_expansions"=> 100
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
        $get=$this->getSpell($keywords);

        if($cek['_shards']['total']==0){
            return $get;
        }else{
            return $cek;
        }
    }
}
