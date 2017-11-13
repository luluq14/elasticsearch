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

    public function single(Request $request,$keywords="",$page=0,$limit=10){
        $query=$keywords;
        $params = [
            'index' => 'oracle-prod',
            'from' => $page,
            'size' =>$limit,
            '_source'=>[
                "prd_no",
                "prd_nm",
                "brand_nm",
                "lctgr_nm",
                "sctgr_nm",
                "mctgr_nm",
                "pop_score",
                "buy_satisfy",
                "create_dt",
                "sale_score",
                "sale_score2"
            ],
            'body' => [
                'sort' => [
                    'pop_score' => [
                        'order' => 'desc'
                    ]
                ],
                'min_score'=>1.0,
                'query' => [
                    'bool' => [
                        'must' => [
                            "multi_match"=>[
                                "query"=>$query,
                                "fields"=>[
                                    "sctgr_nm^10",
                                    "prd_nm^5"
                                ]
                            ]
                        ],
                        'must_not' => [
                            "multi_match"=>[
                                "query"=>"Aksesoris",
                                "fields"=>[
                                    "mctgr_nm^10"
                                ]
                            ]
                        ],
                        'filter' => [
                            "range"=>[
                                "buy_satisfy"=>[
                                    "gte"=> 0,
                                    "lte"=> 100
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
        $result=[];
        foreach ($response['hits']['hits'] as $key => $value){
//            print_r($value);die();
            $result[$key]['pop_score']= $value['_source']['pop_score'];
            $result[$key]['prd_nm']=  $value['_source']['prd_nm'];
            $result[$key]['prd_no']=  $value['_source']['prd_no'];
            $result[$key]['brand_nm']=  $value['_source']['brand_nm'];
            $result[$key]['lctgr_nm']= $value['_source']['lctgr_nm'];
            $result[$key]['sctgr_nm']= $value['_source']['sctgr_nm'];
            $result[$key]['mctgr_nm']=  $value['_source']['mctgr_nm'];
            $result[$key]['buy_satisfy']=  $value['_source']['buy_satisfy'];
        }

        return $response;
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
            || (preg_match('/casing /',$keywords)) || (preg_match('/ casing /',$keywords)) ){
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
            '_source'=>["prd_no","prd_nm","brand_nm","lctgr_nm","sctgr_nm","mctgr_nm","pop_score","buy_satisfy","create_dt","sale_score","sale_score2"],
            'body' => [
                'sort' => [
                    'pop_score' => [
                        'order' => 'desc'
                    ]
                ],
                'min_score'=>1.0,
                'query' => [
                    'bool' => $hasil
                ]
            ]
        ];


        $client = \Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($this->host)      // Set the hosts
        ->build();              // Build the client object

        $response = $client->search($params);
        $result=[];
        foreach ($response['hits']['hits'] as $key => $value){
            $result[$key]['pop_score']= $value['_source']['pop_score'];
            $result[$key]['prd_nm']=  $value['_source']['prd_nm'];
            $result[$key]['prd_no']=  $value['_source']['prd_no'];
            $result[$key]['brand_nm']=  $value['_source']['brand_nm'];
            $result[$key]['lctgr_nm']= $value['_source']['lctgr_nm'];
            $result[$key]['sctgr_nm']= $value['_source']['sctgr_nm'];
            $result[$key]['mctgr_nm']=  $value['_source']['mctgr_nm'];
            $result[$key]['buy_satisfy']=  $value['_source']['buy_satisfy'];
        }

        return $response;
    }
}
