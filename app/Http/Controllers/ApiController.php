<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiController extends BaseController
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

    public function single(Request $request){
        (empty($request->input('keywords')))?$query="iphone":$query=$request->input('keywords');
        (empty($request->input('page')))?$page=1:$page=$request->input('page');
        (empty($request->input('limit')))?$limit=10:$limit=$request->input('limit');

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

    public function multiple(Request $request){
        (empty($request->input('keywords')))?$keywords="iphone":$keywords=$request->input('keywords');
        (empty($request->input('page')))?$page=1:$page=$request->input('page');
        (empty($request->input('limit')))?$limit=10:$limit=$request->input('limit');

        $res=explode(" ",$keywords);
        $total=count($res);
        $multi=[];

        if($total==2){
            $query=@$res[0].' '.@$res[1];
            $multi[0]= [
                "multi_match"=>[
                    "query"=>$query,
                    "type" => 'phrase_prefix',
                    "fields"=> [ "prd_nm^10","brand_nm^10","sctgr_nm^10"],
                    "tie_breaker" => 1.0
                ]
            ];
            $multi[1]= [
                "multi_match"=>[
                    "query"=>$query,
                    "type" => 'cross_fields',
                    "fields"=> [ "prd_nm^10","brand_nm^10","sctgr_nm^10"],
                    "tie_breaker" => 1.0
                ]
            ];
        }else{
            for($i=0;$i<$total;$i++){
                if($i==0){
                    $type='phrase_prefix';
                }else{
                    $type='cross_fields';
                }

                $query=@$res[$i].' '.@$res[$i+1];

                $multi[$i]= [
                    "multi_match"=>[
                        "query"=>$query,
                        "type" => $type,
                        "fields"=> [ "prd_nm^10","brand_nm^10","sctgr_nm^10"],
                        "tie_breaker" => 1.0
                    ]
                ];
            }
        }

        if ((preg_match('/case /',$keywords)) || (preg_match('/ case /',$keywords))
            || (preg_match('/casing /',$keywords)) || (preg_match('/ casing /',$keywords)) ){
            $hasil=[
                'must' =>$multi,
                'filter' => [
                    "range"=>[
                        "buy_satisfy"=>[
                            "gte"=> 0,
                            "lte"=> 100
                        ]
                    ]
                ]
            ];
        }else{
            $hasil=[
                'must' =>$multi,
                'must_not'=>[
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
