<?php

namespace Tests\Feature;

use Tests\TestCase;

class MainPageTest extends TestCase
{
    public function testMainPage(): void
    {
        $response = $this->get(route('welcome'));
        $response->assertOk();
    }
}
