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
 * @api {post} /search Searching Keywords
 *
 * @apiGroup Search
 * @apiVersion 0.0.1
 *
 * @apiParam {String} keywords
 * @apiParam {Int} page
 * @apiParam {Int} limit
 * @apiSampleRequest /search
 */
Route::post('/search', 'ApiController@search');
