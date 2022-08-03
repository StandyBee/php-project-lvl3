<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $response = $this->post(route('urls.check.store', $this->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', [
            'url_id' => $this->id,
            'created_at' => Carbon::now()
        ]);
    }
}
