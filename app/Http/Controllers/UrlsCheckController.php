<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Http\Client\HttpClientException;
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

        DB::table('url_checks')->insert(['url_id' => $id, 'created_at' => Carbon::now()]);

        DB::table('urls')->where('id', '=', $id)->update(['updated_at' => Carbon::now()]);

        return redirect()->route('urls.show', ['url' => $id]);
    }
}
