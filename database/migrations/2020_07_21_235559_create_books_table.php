<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('title')->unique();
            $table->string('slug')->unique()->index();
            $table->string('description');
            $table->string('author');
            $table->string('publisher');
            $table->string('cover')->nullable();
            $table->integer('price')->unsigned();
            $table->integer('views')->default(0)->unsigned();
            $table->float('weight');
            $table->integer('stock')->unsigned()->default(0);
            $table->enum('status', ['PUBLISH', 'DRAFT'])->index();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
