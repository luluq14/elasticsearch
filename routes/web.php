<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->to('/api-es/index.html');
});

/**
 * @api {get} /mctgr/{keyword} List Mctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keyword} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /mctgr/samsung?terms={"lctgr_no":["359"]}
 */
Route::get('/mctgr/{keyword}', 'ApiGetController@Mctgr');

/**
 * @api {get} /sctgr/{keywords} List Sctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /sctgr/samsung?terms={"mctgr_no":["363","5047"]}
 */
Route::get('/sctgr/{keyword}', 'ApiGetController@Sctgr');

/**
 * @api {get} /lctgr/{keywords} List Lctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /lctgr/iphone
 */
Route::get('/lctgr/{keywords}', 'ApiGetController@Lctgr')->where('keywords', '.*');


/**
 * @api {get} /miss/{keywords} Miss Spell
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /miss/samsun
 */
Route::get('/miss/{keywords}', 'ApiGetController@missSpell')->where('keywords', '.*');

/**
 * @api {get} /brand/{keywords} List Brand
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /brand/samsung
 */
Route::get('/brand/{keywords}', 'ApiGetController@ListBrand')->where('keywords', '.*');

/**
 * @api {get} /search/{keywords} Search
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /search/samsung?sort={"ctgr_bstng":{"order":"desc"},"pop_score":{"order":"desc"},"sel_prc":{"order":"asc"}}&&match={"prd_nm":"s8%20plus"}&&page=0&&limit=10
 */
Route::get('/search/{keywords}', 'ApiGetController@search')->where('keywords', '.*');


//Route::get('/sinonim/{keywords}', 'ApiGetController@searchSinonim');
