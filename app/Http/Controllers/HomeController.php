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
        $posts = Post::orderBy('id', 'desc')->paginate(4);
        $post_count = Post::count();
        return view('welcome', ['posts' => $posts, 'post_count'=>$post_count]);
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
            'image' => 'image|max:2000000',
        ]);

        try{
            $post = new Post();
            $post->title = $request->input('title');
            $file = $request->file('photo');
            $post->url = $helper->savePhoto($file);
            $post->save();
        } catch (\Exception $e) {
            return redirect('/')->withErrors([$e->getMessage()]);
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
    public function export()
    {
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"export.csv\"");

        $posts = Post::orderBy('id', 'desc')->get();

        $fh = fopen('php://output', 'w');
        $values['title'] = 'Title';
        $values['image'] = 'Filename';
        fputcsv($fh, $values);
        foreach($posts as $post){

            $values['title'] = $post->title;
            $values['image'] = base_path().'/storage/photos/'.$post->url;
            fputcsv($fh, $values);
        }

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
