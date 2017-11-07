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
    public function search(Request $request){
        (empty($request->input('keywords')))?$query="Asus":$query=$request->input('keywords');
        (empty($request->input('page')))?$page=1:$page=$request->input('page');
        (empty($request->input('limit')))?$limit=10:$limit=$request->input('limit');

        $params = [
            'index' => 'oracle-prod',
            'from' => $page,
            'size' =>$limit,
            '_source'=>["prd_no","prd_nm","brand_nm","lctgr_nm","sctgr_nm","mctgr_nm","pop_score"],
            'body' => [
                'query' => [
                    'dis_max' => [
                        'tie_breaker' =>  0.3,
                        'queries' => [
                            [   "common"=>[
                                "prd_nm"=>[
                                    "query"=>$query,
                                    "boost"=>10,
                                    "cutoff_frequency"=> 1.0
                                ]
                            ]
                            ],
                            [
                                "common"=>[
                                    "brand_nm"=>[
                                        "query"=>$query,
                                        "boost"=>8,
                                        "cutoff_frequency"=> 0.001
                                    ]
                                ]
                            ],
                            [
                                "common"=>[
                                    "sctgr_nm"=>[
                                        "query"=>$query,
                                        "boost"=>8,
                                        "cutoff_frequency"=> 0.001
                                    ]
                                ]
                            ],
                            [
                                "common"=>[
                                    "mctgr_nm"=>[
                                        "query"=>$query,
                                        "boost"=>8,
                                        "cutoff_frequency"=> 0.001
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
        }

        return $result;
    }
}
