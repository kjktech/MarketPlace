<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Jrean\UserVerification\Facades\UserVerification;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CreateUserTest extends TestCase
{

    use RefreshDatabase;
    public function setUp()
    {
      parent::setUp();
      $this->setupPermissions();
    }

    protected function setupPermissions()
    {

      Permission::create(['name' => 'edit listing']);
      Permission::create(['name' => 'publish listing']);
      Permission::create(['name' => 'unpublish listing']);
      Permission::create(['name' => 'disable listing']);
      Permission::create(['name' => 'ban user']);
      Permission::create(['name' => 'basic approval']);
      Role::create(['name' => 'member']);
      Role::create(['name' => 'editor'])->givePermissionTo(['edit listing', 'publish listing', 'unpublish listing']);
      Role::create(['name' => 'moderator'])->givePermissionTo(['edit listing', 'disable listing', 'publish listing', 'unpublish listing', 'ban user']);
      Role::create(['name' => 'admin'])->givePermissionTo(['edit listing', 'disable listing', 'publish listing', 'unpublish listing', 'ban user']);
      Role::create(['name' => 'super-admin'])->givePermissionTo(Permission::all());
      $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

         /*
         \Mail::fake();

        // Perform order shipping...
        $response = $this->call('POST', '/register', ['name' => 'Taylor Adedayo', 'email' => 'onileereo@gmail.com',
        'password' => 'changeme',
        'password_confirmation' => 'changeme',
         '_token' => csrf_token()]);
         //\Mail::assertSent(UserVerification::class, 1);
         //$this->assertEquals(302, $response->status());
         */
         //$this->assertEquals(true);
         $this->assertTrue(true);
    }

    public function testPostRegister()
    {
        \Session::start();
        $user = factory(\App\Models\User::class)->create();
        $user->assignRole('super-admin');
        //$this->assertSame('nnn', csrf_token());
        //$this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->actingAs($user)->post(route('panel.users.store'), [
            '_token' => csrf_token(),
            'name' => 'Olabayo Shayo',
            'email' => 'olabayo96@yahoo.com',
            'phone' => '08027382760'
        ]);
        /*
        $this->assertDatabaseHas('users', [
          'email' => 'olabayo96@yahoo.com'
        ]);
        */
        $response->assertRedirect(route('panel.users.index'));
        $response->assertStatus(302);
        //$this->assertNull($response);
    }

}
