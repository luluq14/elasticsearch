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
 * @api {get} /mctgr/{keyword_lct}/{keyword} List Mctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keyword} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /mctgr/mobile%20phone/samsung
 */
Route::get('/mctgr/{keyword_lct}/{keyword}', 'ApiGetController@Mctgr');

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
 * @api {get} /sctgr/{keyword_mct}/{keyword} List Sctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {mctgr} type string, not empty parameter {mctgr} to search document
 * @apiDescription {prdnm} type string, not empty parameter {prdnm} to search document
 * @apiSampleRequest /sctgr/mobile%20phone/samsung
 */
Route::get('/sctgr/{keyword_mct}/{keyword}', 'ApiGetController@Sctgr');

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

/**
 * @api {get} /lctgr/{keywords} List Lctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /lctgr/iphone
 */
Route::get('/lctgr/{keywords}', 'ApiGetController@Lctgr');

/**
 * @api {get} /search/lctgr/{keyword}/{keyword_lct}/{page}/{limit} Searching Lctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {prdnm} type string, not empty parameter {prdnm} to search document
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /search/lctgr/iphone/mobile%20phone/0/10
 */
Route::get('/search/lctgr/{keyword}/{keyword_lct}/{page}/{limit}', 'ApiGetController@searchByLctgr');

/**
 * @api {get} /lctgr-all/{keywords} List All Lctgr
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /lctgr-all/iphone
 */
Route::get('/lctgr-all/{keywords}', 'ApiGetController@LctgrAll');

/**
 * @api {get} /miss/{keywords} Miss Spell
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /miss/samsun
 */
Route::get('/miss/{keywords}', 'ApiGetController@missSpell');