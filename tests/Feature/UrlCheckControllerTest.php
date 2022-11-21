<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlCheckControllerTest extends TestCase
{
    public function testStore(): void
    {
        //$this->withoutMiddleware();
        $data = [
            'name' => 'https://google.com',
            'created_at' => Carbon::now(),
        ];
        $id = DB::table('urls')->insertGetId($data);

        $fakeHtml = file_get_contents(__DIR__ . '/../Fixtures/fake.html');

        if (!$fakeHtml) {
            throw new \Exception('failed to connect');
        }

        Http::fake([
            $data['name'] => Http::response($fakeHtml, 200)
        ]);

        $response = $this->post(route('urls.checks.store', $id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $expected = [
            'url_id' => $id,
            'status_code' => 200,
            'h1' => 'h1',
            'title' => 'title',
            'description' => 'description',
        ];
        $this->assertDatabaseHas('url_checks', $expected);
    }
}
