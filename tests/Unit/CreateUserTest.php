<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use App\Mail\AdminCreateUser;

use Modules\Panel\Helpers\UserValidator;
use Modules\Panel\Helpers\UserCreateUtil;
use Modules\Panel\Helpers\UtilHelper;

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
     * A basic Validation of user create post request.
     *
     * @return void
     */
    public function testValidation()
    {
      //$user = factory(\App\Models\User::class)->create();
      $request = new \Illuminate\Http\Request();
      $request->replace(["name" => "Bayo Leave", "email" => "ode@gmail.com"]);
      $result = UserValidator::make($request);
      $this->assertTrue($result["success"]);
    }

    /**
     * A basic Validation of user create post request.
     *
     * @return void
     */
    public function testUserCreateUtil()
    {
      //$user = factory(\App\Models\User::class)->create();
      $request = new \Illuminate\Http\Request();
      $request->replace(["name" => "Bayo Best", "email" => "yankee@gmail.com"]);
      $result = UserCreateUtil::create($request);
      $this->assertDatabaseHas('users', [
        'email' => 'yankee@gmail.com'
      ]);
    }

    /**
     * A basic Validation of user create post request.
     *
     * @return void
     */
    public function testUserCreateUser()
    {
      //$user = factory(\App\Models\User::class)->create();
      $request = new \Illuminate\Http\Request();
      $request->replace(["name" => "Bayo Besty", "email" => "yankeeme@gmail.com"]);
      $user = UtilHelper::createUser($request, new UserValidator(), new UserCreateUtil());
      $this->assertDatabaseHas('users', [
        'email' => 'yankeeme@gmail.com'
      ]);
    }

    public function testUserCreate()
    {
        Mail::fake();

        // Assert that no mailables were sent...
        Mail::assertNothingSent();

        // Perform order shipping...
        $request = new \Illuminate\Http\Request();
        $request->replace(["name" => "Bayo Bestui", "email" => "olabayo96@yahoo.com"]);
        $result = UserCreateUtil::create($request);
        $user = $result["user"];
        $password = $result["password"];
        $data = Array("user" => $user, "password" => $password);
        $mail= Mail::to($user->email)->send(new AdminCreateUser($data));

        Mail::assertSent(AdminCreateUser::class, function ($mail) use ($user) {
            return $mail->data["user"]->id === $user->id;
        });

        // Assert a message was sent to the given users...
        Mail::assertSent(AdminCreateUser::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        // Assert a mailable was sent twice...
        Mail::assertSent(AdminCreateUser::class, 1);
    }

}
