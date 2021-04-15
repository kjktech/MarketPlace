<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
       $response = $this->get(route('home'));
       //print_r($response->getOriginalContent(), TRUE);
       $response->assertStatus(200);
    }
}
