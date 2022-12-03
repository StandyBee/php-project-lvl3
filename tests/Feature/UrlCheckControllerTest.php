<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlCheckControllerTest extends TestCase
{
    private int $id;
    private array $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'name' => 'https://google.com',
            'created_at' => Carbon::now(),
        ];
        $this->id = DB::table('urls')->insertGetId($this->data);
    }

    public function testStore(): void
    {
        $fakeHtml = file_get_contents(__DIR__ . '/../Fixtures/fake.html');

        if (!$fakeHtml) {
            throw new \Exception('failed to connect');
        }

        Http::fake([
            $this->data['name'] => Http::response($fakeHtml, 200)
        ]);

        $response = $this->post(route('urls.checks.store', $this->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $expected = [
            'url_id' => $this->id,
            'status_code' => 200,
            'h1' => 'h1',
            'title' => 'title',
            'description' => 'description',
        ];
        $this->assertDatabaseHas('url_checks', $expected);
    }
}
