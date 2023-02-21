<?php

use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('files')->nullable();
            $table->enum('priority',[
                PriorityEnum::CRITICAL_HIGH->value,PriorityEnum::HIGH->value,PriorityEnum::MEDIUM->value,
                PriorityEnum::LOW->value]);
            $table->enum('status',[
                StatusEnum::AWAITING->value,StatusEnum::OPEN->value,StatusEnum::CLOSED->value])->default(StatusEnum::AWAITING->value);
            $table->foreignId("agent_id")->nullable()->constrained('users');
            $table->foreignId("author_id")->constrained('users');
            $table->foreignId("category_id")->constrained('categories');
            $table->foreignId("label_id")->constrained('labels');
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
        Schema::dropIfExists('tickets');
    }
};
