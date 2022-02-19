<?php

namespace VCComponent\Laravel\Meta\Test;

use Cviebrock\EloquentSluggable\ServiceProvider;
use Dingo\Api\Provider\LaravelServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use VCComponent\Laravel\Meta\Providers\MetaServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelServiceProvider::class,
            ServiceProvider::class,
            MetaServiceProvider::class
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../tests/Stubs/Factory');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:TEQ1o2POo+3dUuWXamjwGSBx/fsso+viCCg9iFaXNUA=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('meta.namespace', 'meta-management');
        $app['config']->set('meta.models', [
            'meta' => \VCComponent\Laravel\Meta\Test\Stubs\Entities\Meta::class,
            'meta-schema' => \VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchema::class,
            'meta-schema-rule' => \VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchemaRule::class,
            'meta-schema-type' => \VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchemaType::class,
            'meta-schema-option' => \VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchemaOption::class,
        ]);
        $app['config']->set('meta.transformers', [
            'meta' => \VCComponent\Laravel\Meta\Transformers\MetaTransformer::class,
            'meta-schema' => \VCComponent\Laravel\Meta\Transformers\MetaSchemaTransformer::class,
            'meta-schema-rule' => \VCComponent\Laravel\Meta\Transformers\MetaSchemaRuleTransformer::class,
            'meta-schema-type' => \VCComponent\Laravel\Meta\Transformers\MetaSchemaTypeTransformer::class,
            'meta-schema-option' => \VCComponent\Laravel\Meta\Transformers\MetaSchemaOptionTransformer::class,
        ]);
        $app['config']->set('meta.auth_middleware', [
            'admin' => [
                [
                    'middleware' => '',
                    'except' => [],
                ],
            ],
            'frontend' => [
                'middleware' => '',
            ],
        ]);
        $app['config']->set('api', [
            'standardsTree' => 'x',
            'subtype' => '',
            'version' => 'v1',
            'prefix' => 'api',
            'domain' => null,
            'name' => null,
            'conditionalRequest' => true,
            'strict' => false,
            'debug' => true,
            'errorFormat' => [
                'message' => ':message',
                'errors' => ':errors',
                'code' => ':code',
                'status_code' => ':status_code',
                'debug' => ':debug',
            ],
            'middleware' => [
            ],
            'auth' => [
            ],
            'throttling' => [
            ],
            'transformer' => \Dingo\Api\Transformer\Adapter\Fractal::class,
            'defaultFormat' => 'json',
            'formats' => [
                'json' => \Dingo\Api\Http\Response\Format\Json::class,
            ],
            'formatsOptions' => [
                'json' => [
                    'pretty_print' => false,
                    'indent_style' => 'space',
                    'indent_size' => 2,
                ],
            ],
        ]);
        $app['config']->set('jwt.secret', '5jMwJkcDTUKlzcxEpdBRIbNIeJt1q5kmKWxa0QA2vlUEG6DRlxcgD7uErg51kbBl');
        // $app['config']->set('auth.providers.users.model', \VCComponent\Laravel\User\Entities\User::class);
        // $app['config']->set('user', ['namespace' => 'user-management']);
        // $app['config']->set('repository.cache.enabled', false);
        // $app['config']->set('roles.models.role', \NF\Roles\Models\Role::class);
        // $app['config']->set('roles.models.permission', \NF\Roles\Models\Permission::class);

    }
    public function assertExits($response, $error_message)
    {
        $response->assertStatus(400);
        $response->assertJson([
            'message' => $error_message,
        ]);
    }
    public function assertValidator($response, $field, $error_message)
    {
        $response->assertStatus(422);
        $response->assertJson([
            'message' => "The given data was invalid.",
            "errors" => [
                $field => [
                    $error_message,
                ],
            ],
        ]);
    }
    public function assertRequired($response, $error_message)
    {
        $response->assertStatus(500);
        $response->assertJsonFragment([
            'message' => $error_message,
        ]);
    }
    protected function loginToken()
    {
        $dataLogin = ['username' => 'admin', 'password' => '123456789', 'email' => 'admin@test.com'];
        $user = factory(User::class)->make($dataLogin);
        $user->save();

        $admin_role = factory(Role::class)->create([
            'name' => 'admin',
            'slug' => 'admin'
        ]); 

        $user->attachRole($admin_role);
        $login = $this->json('POST', 'api/user-management/login', $dataLogin);

        $token = $login->Json()['token'];
        return $token;

    }
}
