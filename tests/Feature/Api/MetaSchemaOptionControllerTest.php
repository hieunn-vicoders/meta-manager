<?php

namespace VCComponent\Laravel\Meta\Test\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchema;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchemaOption;
use VCComponent\Laravel\Meta\Test\TestCase;

class MetaSchemaOptionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_get_meta_schema_options_list()
    {
        $data = factory(MetaSchemaOption::class, 3)->create([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->each(function ($item) {
        
            unset($item['created_at']);
            unset($item['updated_at']);
            return $item;
        })->toArray();
        
        $data_id_column = array_column($data, 'id');
        array_multisort($data_id_column, SORT_DESC, $data);

        $response = $this->get("api/admin/meta/schema/options");
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);
    }

    /**
     * @test
     */
    public function can_get_meta_schema_option_detail()
    {
        $data = factory(MetaSchemaOption::class)->create([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->get("api/admin/meta/schema/options/".$data['id']);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);
    }

    /**
     * @test
     */
    public function can_create_meta_schema_option()
    {
        $data = factory(MetaSchemaOption::class)->make([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->post("api/admin/meta/schema/options", $data);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('meta_schema_options' ,$data);
    }

    /**
     * @test
     */
    public function can_update_meta_schema()
    {
        $data = factory(MetaSchemaOption::class)->create([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);
        
        $update_data = factory(MetaSchemaOption::class)->make([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        $response = $this->put("api/admin/meta/schema/options/".$data['id'], $update_data);
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $update_data
        ]);

        $this->assertDatabaseHas('meta_schema_options' ,$update_data);
    }

    /**
     * @test
     */
    public function can_delete_meta_schema()
    {
        $data = factory(MetaSchemaOption::class)->create([
            'schema_id' => factory(MetaSchema::class)->create()->id
        ])->toArray();

        unset($data['created_at']);
        unset($data['updated_at']);

        $response = $this->delete("api/admin/meta/schema/options/".$data['id']);
        $response->assertSuccessful();

        $this->assertDatabaseMissing('meta_schema_options' ,$data);
    }
}