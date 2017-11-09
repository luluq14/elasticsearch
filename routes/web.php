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
 * @api {post} /search/single Searching Single Keywords
 *
 * @apiGroup Search Post
 * @apiVersion 0.0.1
 *
 * @apiParam {String} keywords
 * @apiParam {Int} page
 * @apiParam {Int} limit
 * @apiSampleRequest /search/single
 */
Route::post('/search/single', 'ApiController@single');

/**
 * @api {post} /search/multiple Searching Multiple Keywords
 *
 * @apiGroup Search Post
 * @apiVersion 0.0.1
 *
 * @apiParam {String} keywords
 * @apiParam {Int} page
 * @apiParam {Int} limit
 * @apiSampleRequest /search/multiple
 */
Route::post('/search/multiple', 'ApiController@multiple');

/**
 * @api {get} /search/single/{keywords}/{page}/{limit} Searching Single Keywords
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /search/single/iphone/0/10
 *
 */
Route::get('/search/single/{keywords}/{page}/{limit}', 'ApiGetController@single');

/**
 * @api {get} /search/multiple/{keywords}/{page}/{limit} Searching Multiple Keywords
 *
 * @apiGroup Search Get
 * @apiVersion 0.0.1
 *
 * @apiDescription {keywords} type string, not empty parameter {keywords} to search document
 * @apiSampleRequest /search/multiple/iphone/0/10
 */
Route::get('/search/multiple/{keywords}/{page}/{limit}', 'ApiGetController@multiple');