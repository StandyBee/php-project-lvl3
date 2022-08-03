<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlsControllerTest extends TestCase
{
    public int $id;
    public function setUp(): void
    {
        parent::setUp();
        $data = ['name' => 'https://google.com', 'created_at' => Carbon::now()];
        $this->id = DB::table('urls')->insertGetId($data);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('urls.show', $this->id));
        $response->assertOk();
        $response->assertSee(DB::table('urls')->find($this->id)->name);
    }

    public function testStore(): void
    {
        $name = ['name' => 'https://google.com', 'created_at' => Carbon::now()];
        $response = $this->post(route('urls.store', ['url' => $name]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $name);
    }
}
