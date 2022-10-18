<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\ConnectionException;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;

class UrlsCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $id)
    {
        $url = DB::table('urls')->find($id);

        try {
            $response = Http::timeout(5)->get($url->name);
        } catch (RequestException | HttpClientException | ConnectionException $exception) {
            flash($exception->getMessage())->error();
            return redirect()->route('urls.show', $id);
        }

        $urlStatus = Http::get($url->name)->status();
        $document = new Document($url->name, true);

        DB::table('url_checks')->insert([
            'url_id' => $id,
            'h1' => optional($document->first('h1'))->text(),
            'keywords' => optional($document->first('title'))->text(),
            'description' => optional($document->first('meta[name=description]'))->attr('content'),
            'status_code' => $urlStatus,
            'created_at' => Carbon::now()]);

        DB::table('urls')->where('id', '=', $id)->update(['updated_at' => Carbon::now()]);
        flash("Страница успешно проверена")->info();
        return redirect()->route('urls.show', ['url' => $id]);
    }
}
