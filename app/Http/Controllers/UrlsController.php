<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UrlsController extends Controller
{
    public function index()
    {
        $urls = DB::table('urls')->orderBy('id')-> paginate(15);
        $status = DB::table('url_checks')
            ->orderBy('url_id')
            ->latest()
            ->distinct('url_id')
            ->get()
            ->keyBy('url_id');
        return view('index', compact('urls', 'status'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => ['required', 'url', 'max:255', 'active_url']
        ]);

        if ($validator->fails()) {
            flash('Некорректный URL')->error();
            return response(View::make('welcome'), 422);
        }

        $parsedRequest = parse_url($request['url.name']);
        $normalizedUrl = strtolower("{$parsedRequest['scheme']}://{$parsedRequest['host']}");
        $data = ['name' => $normalizedUrl, 'created_at' => Carbon::now()];

        if (DB::table('urls')->where('name', $data['name'])->doesntExist()) {
            $queryInsert = DB::table('urls')->insertGetId($data);
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
        abort_unless($url, 404);
        $checks = DB::table('url_checks')->orderBy('created_at', 'desc')->where('url_id', $id)->get();
        return view('showurl', compact('url', 'checks'));
    }
}
