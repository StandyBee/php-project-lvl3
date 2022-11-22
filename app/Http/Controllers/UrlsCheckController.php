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
    public function store(int $id)
    {
        $url = DB::table('urls')->find($id);
        abort_unless($url, 404);

        try {
            $response = Http::timeout(5)->get($url->name);
            $document = new Document($response->body());

            DB::table('url_checks')->insert([
                'url_id' => $id,
                'h1' => optional($document->first('h1'))->text(),
                'title' => optional($document->first('title'))->text(),
                'description' => optional($document->first('meta[name=description]'))->attr('content'),
                'status_code' => $response->status(),
                'created_at' => Carbon::now()]);

            flash("Страница успешно проверена")->success();
        } catch (RequestException | HttpClientException | ConnectionException $exception) {
            flash($exception->getMessage())->error();
        }

        return redirect()->route('urls.show', $id);
    }
}
