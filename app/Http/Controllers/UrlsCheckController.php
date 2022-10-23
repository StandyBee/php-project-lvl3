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
        try {
            $url = $this->getUrlName($id);
            $response = Http::get($url);
            $document = new Document($response->body());

            $checkData = [
                'url_id' => $id,
                'status_code' => $response->status(),
                'h1' => optional($document->first('h1'))->text(),
                'keywords' => optional($document->first('title'))->text(),
                'description' => optional($document->first('meta[name=description]'))->getAttribute('content'),
                'created_at' => Carbon::now(),
            ];
            DB::table('url_checks')->insert($checkData);

            flash('Страница успешно проверена')->success();
        } catch (Exception $exception) {
            flash($exception->getMessage())->error();
        }
        return redirect()->route('urls.show', $id);
    }

    private function getUrlName(int $id): string
    {
        $url = DB::table('urls')->find($id);
        abort_unless($url, 404);

        return $url->name;
    }
}
