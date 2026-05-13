public function up()
{
    Schema::table('tutor_profiles', function (Blueprint $table) {
        $table->enum('status', ['pending', 'verified', 'rejected'])
              ->default('pending')->after('bio');
        $table->foreignId('verified_by')->nullable()->constrained('users');
        $table->timestamp('verified_at')->nullable();
        $table->foreignId('rejected_by')->nullable()->constrained('users');
        $table->timestamp('rejected_at')->nullable();
        $table->text('alasan_reject')->nullable();

        // Dokumen
        $table->string('ijazah_path')->nullable();
        $table->string('sertifikat_path')->nullable();
        $table->string('file_silabus_path')->nullable();
    });
}
