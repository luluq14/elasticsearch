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
 * @api {get} /search/multiple/{keywords}/{page}/{limit} Searching
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /search/multiple/iphone/0/10
 */
Route::get('/search/multiple/{keywords}/{page}/{limit}', 'ApiGetController@multiple');

/**
 * @api {get} /mctgr/{keywords}/{page}/{limit} List Mctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /mctgr/iphone/0/10
 */
Route::get('/mctgr/{keywords}/{page}/{limit}', 'ApiGetController@Mctgr');

/**
 * @api {get} /search/mctgr/{prdnm}/{mctgr}/{page}/{limit} Searching Mctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {prdnm} type string, not empty parameter {prdnm} to search document
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /search/mctgr/iphone/mobile%20phone/0/10
 */
Route::get('/search/mctgr/{prdnm}/{mctgr}/{page}/{limit}', 'ApiGetController@searchByMctgr');

/**
 * @api {get} /sctgr/{mctgr}/{prdnm}/{page}/{limit} List Sctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {mctgr} type string, not empty parameter {mctgr} to search document
 * @apiDescription {prdnm} type string, not empty parameter {prdnm} to search document
 * @apiSampleRequest /sctgr/mobile%20phone/samsung/0/10
 */
Route::get('/sctgr/{prdnm}/{sctgr}/{page}/{limit}', 'ApiGetController@Sctgr');

/**
 * @api {get} /search/sctgr/{prdnm}/{sctgr}/{page}/{limit} Searching Sctgr 
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {prdnm} type string, not empty parameter {prdnm} to search document
 * @apiDescription {sctgr} type string, not empty parameter {sctgr} to search document
 * @apiSampleRequest /search/sctgr/iphone%206/iphone/0/10
 */
Route::get('/search/sctgr/{prdnm}/{sctgr}/{page}/{limit}', 'ApiGetController@SearchBySctgr');