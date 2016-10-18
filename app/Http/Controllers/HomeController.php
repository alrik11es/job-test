<?php

namespace App\Http\Controllers;

use App\Helpers\LocationConstants;
use App\Logic\Helper\ImageHelper;
use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->limit(4)->get();
        return view('welcome', ['posts'=>$posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(ImageHelper $helper, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'max:255',
            'image' => 'image',
        ]);

        try{

            $post = new Post();
            $post->title = $request->input('title');
            $file = $request->file('photo');
            $post->url = $helper->savePhoto($file);
            $post->save();
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['image not valid']);
        }

        if ($validator->fails()) {
            return redirect('/')->withErrors($validator);
        }
        return redirect('/')->with('saved', true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
