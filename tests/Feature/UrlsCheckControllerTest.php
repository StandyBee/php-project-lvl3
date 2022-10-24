<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class UrlsCheckControllerTest extends TestCase
{
    public int $id;
    public function setUp(): void
    {
        parent::setUp();
        $data = ['name' => 'https://google.com'];
        $this->id = DB::table('urls')->insertGetId($data);
    }

    public function testChecks(): void
    {
        $fakeHtml = file_get_contents(__DIR__ . "/../Fixtures/fake.html");
        $name = DB::table('urls')->where('id', '=', $this->id)->value('name');

        Http::fake([$name => Http::response($fakeHtml)]);

        $response = $this->post(route('urls.checks.store', $this->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', [
            'url_id' => $this->id,
            'created_at' => Carbon::now()
        ]);
    }
}
