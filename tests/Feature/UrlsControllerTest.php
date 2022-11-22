<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlsControllerTest extends TestCase
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

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('urls.show', $this->id));
        $response->assertOk();
        $response->assertSee($this->data['name']);
    }

    public function testStore(): void
    {
        $response = $this->post(route('urls.store', ['url' => $this->data]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $this->data);
    }
}
