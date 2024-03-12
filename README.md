<div id="header" align="center">
  <img src="https://media.giphy.com/media/M9gbBd9nbDrOTu1Mqx/giphy.gif" width="100"/>
</div>
<div id="badges" align="center">
  <a href="https://www.linkedin.com/in/kevin-christianto/">
    <img src="https://img.shields.io/badge/LinkedIn-blue?style=for-the-badge&logo=linkedin&logoColor=white" alt="LinkedIn Badge"/>
  </a>
</div>
<div id="github" align="center">
    <img src="https://komarev.com/ghpvc/?username=kevhoz&style=flat-square&color=blue" alt=""/>
</div>
<div id="body-header" align="center">
<h1>
  Laravel Unit Testing
</h1>
</div>
### :hammer_and_wrench: Languages and Tools:
<div>
  <img src="https://github.com/devicons/devicon/blob/master/icons/css3/css3-plain-wordmark.svg"  title="CSS3" alt="CSS" width="40" height="40"/>&nbsp;
  <img src="https://github.com/devicons/devicon/blob/master/icons/html5/html5-original.svg" title="HTML5" alt="HTML" width="40" height="40"/>&nbsp;
  <img src="https://github.com/devicons/devicon/blob/master/icons/mysql/mysql-original-wordmark.svg" title="MySQL"  alt="MySQL" width="40" height="40"/>&nbsp;
  <img src="https://github.com/devicons/devicon/blob/master/icons/php/php-original.svg" title="PHP"  alt="PHP" width="40" height="40"/>&nbsp;
  <img src="https://github.com/devicons/devicon/blob/master/icons/git/git-original-wordmark.svg" title="Git" **alt="Git" width="40" height="40"/>
</div>

### :woman_technologist: This repository cover:

1. Installation of Framework Laravel 10.
2. Installation of Authentication Sanctum for Laravel 10 API.
3. Creation Module Outlet for testing purpose.  
4. Creation Unit/Feature Testing based on Outlet Module.



<div id="Install-Laravel">
<h2>
  Installation Laravel 10 Framework
</h2>

- Install Laravel via Composer. Open a terminal and run the following command to create a new Laravel project named laravel_unit_testing:

```
composer create-project --prefer-dist laravel/laravel="10.*" laravel_unit_testing
```

- Navigate into your project directory:

```
cd laravel_unit_testing
```

</div>

<div id="Install-Sanctum">
<h2>
  Installation Sanctum Authentication
</h2>
Laravel 10 supports Sanctum for API token authentication, which is suitable for SPA (Single Page Application), mobile applications, and simple token-based API authentication.
<br></br>
    
- Install Laravel Sanctum:

```
composer require laravel/sanctum
```

- Publish the Sanctum configuration file:

```
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

- Run the migrations to create the Sanctum tables:

```
php artisan migrate
```
- Add Sanctum's middleware to your api middleware group within the app/Http/Kernel.php file:

```php
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

protected $middlewareGroups = [
    'api' => [
        EnsureFrontendRequestsAreStateful::class,
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```
- Ensure your config/auth.php is set up to use the token guard for APIs:

```
'guards' => [
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```
- Create the authentication controller using Artisan:

```
php artisan make:controller AuthController
```
- Inside your routes/api.php, define the routes:

```php
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
```
- Implement in your AuthController, implement the register and login methods:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('appToken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('appToken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }
}
```

### :woman_technologist: Test it with postman:

1. Start your Laravel application by running php artisan serve.
2. Open Postman and create a new request.
3. Set the request type to POST.
4. Enter the URL for registration, e.g., http://127.0.0.1:8000/api/register.
5. In the Body tab, select raw and choose JSON from the dropdown.
6. Enter the JSON data for the new user:
```JSON
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```
7. Send the request. You should receive a response containing the user's information and their API token.
8. Repeat similar steps for the login endpoint to test logging in an existing user.

</div>

<div id="Create-Module">
<h2>
  Creating Outlets CRUD Module
</h2>

- Create a migration for the "outlets" table:

```
php artisan make:migration create_outlets_table
```

- Update the migration file in database/migrations with the specified fields:

```php
Schema::create('outlets', function (Blueprint $table) {
    $table->id();
    $table->string('nama_outlet');
    $table->string('lokasi_outlet');
    $table->string('pic_outlet');
    $table->timestamps();
});
```
- Then, run the migration:

```
php artisan migrate
```
- Create a model for the outlet:

```
php artisan make:model Outlet
```
- Update app/Models/Outlet.php to fillable properties:

```php
protected $fillable = ['nama_outlet', 'lokasi_outlet', 'pic_outlet'];
```
- Create a controller for the CRUD operations:

```
php artisan make:controller OutletController --api
```
- Open the OutletController file located at app/Http/Controllers/OutletController.php and fill it with the following CRUD operations:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutletController extends Controller
{
    // Display a listing of the outlet.
    public function index()
    {
        $outlets = Outlet::all();
        return response()->json($outlets);
    }

    // Store a newly created outlet in storage.
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet' => 'required|string|max:255',
            'lokasi_outlet' => 'required|string|max:255',
            'pic_outlet' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $outlet = Outlet::create($validator->validated());

        return response()->json($outlet, 201);
    }

    // Display the specified outlet.
    public function show(Outlet $outlet)
    {
        return response()->json($outlet);
    }

    // Update the specified outlet in storage.
    public function update(Request $request, Outlet $outlet)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet' => 'required|string|max:255',
            'lokasi_outlet' => 'required|string|max:255',
            'pic_outlet' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $outlet->update($validator->validated());

        return response()->json($outlet);
    }

    // Remove the specified outlet from storage.
    public function destroy(Outlet $outlet)
    {
        $outlet->delete();
        return response()->json(null, 204);
    }
}
```
- Define the API routes in routes/api.php:

```php
use App\Http\Controllers\OutletController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('outlets', OutletController::class);
});
```

### :woman_technologist: Test it with postman:
1. Start your Laravel application by running php artisan serve.
2. Open Postman and create a new request.
3. Test the API with Create, Read, Update, Delete
</div>

<div id="Create-Testing">
<h2>
  Creating Unit Testing for Outlets Module
</h2>

- Create a test file for the Outlet CRUD operations:

```
php artisan make:test OutletTest
```

- Here's how you can adjust your OutletTest.php to include token-based authentication with Sanctum:

```php
<?php

namespace Tests\Feature;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OutletTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user for authentication
        $this->user = User::factory()->create();
        
        // Simulate login and get the token
        $token = $this->user->createToken('TestToken')->plainTextToken;
        
        // Set the default Authorization header for all requests
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);
    }

    /** @test */
    public function can_create_an_outlet()
    {
        $formData = [
            'nama_outlet' => $this->faker->company,
            'lokasi_outlet' => $this->faker->address,
            'pic_outlet' => $this->faker->name,
        ];

        $this->postJson('/api/outlets', $formData)
             ->assertStatus(201)
             ->assertJson($formData);

        $this->assertDatabaseHas('outlets', $formData);
    }

    /** @test */
    public function can_update_an_outlet()
    {
        $outlet = Outlet::factory()->create();

        $updateData = [
            'nama_outlet' => 'Updated Name',
            'lokasi_outlet' => 'Updated Location',
            'pic_outlet' => 'Updated PIC',
        ];

        $this->putJson("/api/outlets/{$outlet->id}", $updateData)
             ->assertStatus(200)
             ->assertJson($updateData);
    }

    /** @test */
    public function can_show_an_outlet()
    {
        $outlet = Outlet::factory()->create();

        $this->getJson("/api/outlets/{$outlet->id}")
             ->assertStatus(200)
             ->assertJson($outlet->toArray());
    }

    /** @test */
    public function can_delete_an_outlet()
    {
        $outlet = Outlet::factory()->create();

        $this->deleteJson("/api/outlets/{$outlet->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('outlets', ['id' => $outlet->id]);
    }

    /** @test */
    public function can_list_outlets()
    {
        $outlets = Outlet::factory()->count(5)->create();

        $this->getJson("/api/outlets")
             ->assertStatus(200)
             ->assertJsonCount(5);
    }
}
```

- Generate Factory, Run the following Artisan command to generate a factory for the Outlet model:

```
php artisan make:factory OutletFactory --model=Outlet
```

- Define Factory, Open the newly created OutletFactory.php file and define the default set of fields for your Outlet model. Here's an example based on your model fields:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OutletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_outlet' => $this->faker->word,
            'lokasi_outlet' => $this->faker->address,
            'pic_outlet' => $this->faker->name,
        ];
    }
}
```

- Finally, time to testing the unit test:

```
php artisan test
```

</div>
