<?php

namespace App\Http\Controllers;

use App\Models\WordCategory;
use App\Http\Requests\StoreWordCategoryRequest;
use App\Http\Requests\UpdateWordCategoryRequest;

class WordCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWordCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWordCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WordCategory  $wordCategory
     * @return \Illuminate\Http\Response
     */
    public function show(WordCategory $wordCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WordCategory  $wordCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(WordCategory $wordCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWordCategoryRequest  $request
     * @param  \App\Models\WordCategory  $wordCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWordCategoryRequest $request, WordCategory $wordCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WordCategory  $wordCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(WordCategory $wordCategory)
    {
        //
    }
}
