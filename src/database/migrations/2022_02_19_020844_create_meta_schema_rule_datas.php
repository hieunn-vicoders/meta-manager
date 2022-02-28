<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use VCComponent\Laravel\Meta\Entities\MetaSchemaRule;

class CreateMetaSchemaRuleDatas extends Migration
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
                "name" => "Email",
            ],
            [
                "name" => "Date",
            ],
            [
                "name" => "Nullable",
            ],
            [
                "name" => "File",
            ],
            [
                "name" => "Required",
            ],
        ])->each(function ($item) {
            MetaSchemaRule::create($item);
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
