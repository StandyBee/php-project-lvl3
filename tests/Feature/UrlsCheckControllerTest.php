<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use WithoutMiddleware;

class UrlsCheckControllerTest extends TestCase
{
    private int $id;
    private string $url;
    public function setUp(): void
    {
        parent::setUp();

        $this->url = 'https://google.com';
        $data = ['name' => $this->url];
        $this->id = DB::table('urls')->insertGetId($data);
    }

    public function testStore(): void
    {
        $this->withoutMiddleware();
        $fakeHtml = file_get_contents(__DIR__ . "/../Fixtures/fake.html");
        $name = DB::table('urls')->where('id', '=', $this->id)->value('name');
        Http::fake(['google.com/*' => Http::response($fakeHtml, 200)]);

        $response = $this->post(route('urls.checks.store', $this->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $data = [
            'url_id' => $this->id,
            'status_code' => 200,
            'h1' => 'h1',
            'title' => 'title',
            'description' => 'description',
            'created_at' => Carbon::now(),
        ];

        $this->assertDatabaseHas('url_checks', $data);
    }
}
