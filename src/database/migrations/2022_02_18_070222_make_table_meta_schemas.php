<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeTableMetaSchemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_schemas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key');
            $table->string('label');
            $table->unsignedBigInteger('schema_rule_id');
            $table->foreign('schema_rule_id')->references('id')->on('meta_schema_rules')->onDelete('cascade');
            $table->unsignedBigInteger('schema_type_id');
            $table->foreign('schema_type_id')->references('id')->on('meta_schema_types')->onDelete('cascade');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_schemas');
    }
}
