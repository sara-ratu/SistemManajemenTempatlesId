<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('honor_tutor', function (Blueprint $table) {
            // Kolom yang kemungkinan belum ada
            if (!Schema::hasColumn('honor_tutor', 'jumlah_bruto')) {
                $table->decimal('jumlah_bruto', 15, 2)->after('tutor_id');
            }
            if (!Schema::hasColumn('honor_tutor', 'komisi_platform')) {
                $table->integer('komisi_platform')->default(0)->after('jumlah_bruto');
            }
            if (!Schema::hasColumn('honor_tutor', 'jumlah_honor')) {
                $table->decimal('jumlah_honor', 15, 2)->after('komisi_platform');
            }
            if (!Schema::hasColumn('honor_tutor', 'rekening_bank')) {
                $table->string('rekening_bank')->nullable()->after('jumlah_honor');
            }
            if (!Schema::hasColumn('honor_tutor', 'no_rekening')) {
                $table->string('no_rekening')->nullable()->after('rekening_bank');
            }
            if (!Schema::hasColumn('honor_tutor', 'nama_rekening')) {
                $table->string('nama_rekening')->nullable()->after('no_rekening');
            }
            if (!Schema::hasColumn('honor_tutor', 'status')) {
                $table->enum('status', ['pending', 'ditransfer'])->default('pending')->after('nama_rekening');
            }
            if (!Schema::hasColumn('honor_tutor', 'bukti_transfer')) {
                $table->string('bukti_transfer')->nullable()->after('status');
            }
            if (!Schema::hasColumn('honor_tutor', 'catatan')) {
                $table->text('catatan')->nullable()->after('bukti_transfer');
            }
            if (!Schema::hasColumn('honor_tutor', 'tanggal_transfer')) {
                $table->timestamp('tanggal_transfer')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('honor_tutor', 'transfer_by')) {
                $table->foreignId('transfer_by')->nullable()
                      ->constrained('users')
                      ->after('tanggal_transfer');
            }
        });
    }

    public function down()
    {
        Schema::table('honor_tutor', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_bruto', 'komisi_platform', 'jumlah_honor',
                'rekening_bank', 'no_rekening', 'nama_rekening',
                'status', 'bukti_transfer', 'catatan',
                'tanggal_transfer', 'transfer_by'
            ]);
        });
    }
};
