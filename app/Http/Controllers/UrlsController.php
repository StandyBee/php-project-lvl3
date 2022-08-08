<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UrlsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = DB::table('urls')->orderBy('id')-> paginate(15);
        $status = DB::table('url_checks')->get()->keyBy('url_id');
        return view('index', compact('urls', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => ['required', 'url', 'max:255']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $parsedRequest = parse_url($request['url.name']);
        $data = ['name' => "{$parsedRequest['scheme']}://{$parsedRequest['host']}", 'created_at' => Carbon::now()];

        if (DB::table('urls')->where('name', $data['name'])->doesntExist()) {
            $query_insert = DB::table('urls')->insertGetId($data);
            $id = DB::table('urls')->where('name', $data['name'])->value('id');
            flash('Страница успешно добавлена')->success();
            return redirect()->route('urls.show', $id);
        }

        flash('Страница уже существует')->warning();
        $id = DB::table('urls')->where('name', $data['name'])->value('id');
        return redirect()->route('urls.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $url = DB::table('urls')->find($id);
        $checks = DB::table('url_checks')->orderBy('created_at', 'desc')->where('url_id', $id)->get();
        return view('showurl', compact('url', 'checks'));
    }
}
