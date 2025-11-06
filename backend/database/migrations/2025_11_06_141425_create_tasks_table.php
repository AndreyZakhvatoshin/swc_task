<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->string('title')
                ->comment('Заголовок задачи');

            $table->text('description')
                ->comment('Описание задачи');

            $table->enum('status', ['planned', 'in_progress', 'done'])
                ->default('planned')
                ->index()
                ->comment('Статус задачи: planned - запланирована, in_progress - в работе, done - завершена');

            $table->date('completion_date')
                ->nullable()
                ->index()
                ->comment('Дата завершения задачи');

            $table->foreignId('user_id')
                ->index()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. проект, заголовок, описание, статус (planned, in_progress, done), дата завершения (опционально), исполнитель (пользователь), вложение (опционально)
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
