<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UrlsController extends Controller
{
    public function index()
    {
        $urls = DB::table('urls')->orderBy('id')-> paginate(15);
        $status = DB::table('url_checks')->get()->keyBy('url_id');
        return view('index', compact('urls', 'status'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => ['required', 'url', 'max:255']
        ]);

        if ($validator->fails()) {
            flash('Некорректный URL')->error();
            return response()->view('welcome', [], 422);
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

    public function show(int $id)
    {
        $url = DB::table('urls')->find($id);
        $checks = DB::table('url_checks')->orderBy('created_at', 'desc')->where('url_id', $id)->get();
        return view('showurl', compact('url', 'checks'));
    }
}
