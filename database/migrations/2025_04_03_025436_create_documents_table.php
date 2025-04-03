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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do arquivo
            $table->string('file_path'); // Caminho do arquivo armazenado
            $table->enum('status', ['Aprovado', 'Reprovado', 'Em Liberação'])->default('Em Liberação'); // Status do documento
            $table->foreignId('macro_id')->constrained()->onDelete('cascade'); // Relacionamento com Macro
            $table->integer('revision')->default(1); // Número da revisão do documento
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuário que enviou o documento
            $table->timestamps();
        });

        // Tabela pivô para setores e documentos (muitos-para-muitos)
        Schema::create('document_sector', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('sector_id')->constrained()->onDelete('cascade');
        });

        // Tabela pivô para empresas e documentos (muitos-para-muitos)
        Schema::create('document_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_company');
        Schema::dropIfExists('document_sector');
        Schema::dropIfExists('documents');
    }
};
