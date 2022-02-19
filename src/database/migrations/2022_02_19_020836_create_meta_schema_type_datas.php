<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use VCComponent\Laravel\Meta\Entities\MetaSchemaType;

class CreateMetaSchemaTypeDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        collect([
            [
                "name" => "text",
            ],
            [
                "name" => "textarea",
            ],
            [
                "name" => "tinyMCE",
            ],
            [
                "name" => "checkbox",
            ],
            [
                "name" => "select",
            ],
            [
                "name" => "image",
            ],
            [
                "name" => "audio",
            ],
            [
                "name" => "video",
            ],
            [
                "name" => "file",
            ],
        ])->each(function ($item) {
            MetaSchemaType::create($item);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
