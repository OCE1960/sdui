<?php

namespace App\Http\Controllers;


use App\Events\NewsCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::all();

        $data = ['news' => $news];

        return $this->sendSuccessResponse('All News Successfully Retrived', $data);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNewsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsRequest $request)
    {
        $user = Auth::user();
        $news = new News();
        $news->title = $request->title;
        $news->body = $request->body;
        $news->user_id = $user->id;
        $news->save();

        NewsCreated::dispatch($user);

        return $this->sendSuccessMessage('News Record Successfully Created', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::find($id);

        if (empty($news)) { 
           return $this->send404Response('News Record does not exist');
        }

       $data = ['news' => $news];

       return $this->sendSuccessResponse('News Record Successfully Retrived',$data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNewsRequest  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNewsRequest $request, $id)
    {
        $news= News::find($id);
        $user = Auth::user();

        if (empty($news)) { 
           return $this->sendErrorResponse(['News Record does not exist']);
        }

        $news->title = $request->title;
        $news->body = $request->body;
        $news->user_id = $user->id;
        $news->save();

        return $this->sendSuccessMessage('News Record Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news= News::find($id);
        $user = Auth::user();

        if (empty($news)) { 
           return $this->sendErrorResponse(['News Record does not exist']);
        }

        $news->delete();

        return $this->sendSuccessMessage('News Record Successfully deleted');
    }
}
