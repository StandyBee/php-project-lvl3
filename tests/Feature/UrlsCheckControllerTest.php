<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Tests\TestCase;
use Exception;

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
        $path = __DIR__ . '/../Fixtures/fake.html';
        $html = file_get_contents($path);
        if ($html === false) {
            throw new Exception("file path: {$path} - is incorrect");
        }

        Http::fake([
            $this->url => Http::response($html, 200)
        ]);

        $response = $this->post(route('urls.checks.store', $this->id));
        $response->assertRedirect()->assertStatus(302);
        $response->assertSessionHasNoErrors();

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
