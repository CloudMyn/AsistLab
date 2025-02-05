<?php

namespace App\Filament\Resources\UserResource\Pages;

// [1] IMPORT CLASS YANG DIPERLUKAN

use App\Filament\Resources\UserResource;
use App\Models\Praktikan; // Model data praktikan
use App\Models\User; // Model User
use Filament\Actions; // Komponen action Filament
use Filament\Notifications\Notification; // Sistem notifikasi Filament
use Filament\Resources\Pages\CreateRecord; // Class dasar untuk halaman create
use Illuminate\Database\Eloquent\Model; // Eloquent model
use Illuminate\Support\Facades\DB; // Database facade untuk transaction
use Illuminate\Support\Facades\Hash; // Enkripsi password
use Illuminate\Support\Str; // Helper string

class CreateUser extends CreateRecord // [2] EXTEND CLASS CREATE RECORD
{
    // [3] KONFIGURASI RESOURCE UTAMA
    protected static string $resource = UserResource::class; // Menghubungkan dengan resource User

    /**
     * [4] METHOD UTAMA UNTUK MEMPROSES PEMBUATAN USER
     * Override method handleRecordCreation untuk custom logic
     *
     * @param array $data Data dari form input
     * @return Model User yang baru dibuat
     */
    protected function handleRecordCreation(array $data): Model
    {
        // [5] DATABASE TRANSACTION - Memastikan operasi atomik
        return DB::transaction(function () use ($data) {
            try {
                // [6] ENKRIPSI PASSWORD
                $data['password'] = Hash::make($data['password']);

                // [7] MEMBUAT USER BARU
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'peran' => $data['peran'],
                    'status' => $data['status'],
                ]);

                // [8] MEMBUAT DATA PRAKTIKAN JIKA ROLE SESUAI
                if ($data['peran'] === 'PRAKTIKAN') {
                    Praktikan::create([
                        'user_id' => $user->id, // Relasi ke user yang baru dibuat
                        'kelas' => $data['praktikan']['kelas'],
                        'jurusan' => $data['praktikan']['jurusan'],
                        'frekuensi_id' => $data['praktikan']['frekuensi_id']
                    ]);
                }

                return $user;

            } catch (\Exception $e) {
                // [9] ERROR HANDLING - Menangani exception
                Notification::make()
                    ->title('Terjadi Kesalahan')
                    ->danger() // Tipe notifikasi error
                    ->body($e->getMessage()) // Pesan error dari exception
                    ->send(); // Mengirim notifikasi

                // [10] RE-THROW EXCEPTION - Untuk rollback transaction
                throw $e;
            }
        });
    }

    /**
     * [11] MENGATUR REDIRECT SETELAH CREATE BERHASIL
     *
     * @return string URL tujuan redirect
     */
    protected function getRedirectUrl(): string
    {
        // Mengarahkan ke halaman index UserResource
        return static::getResource()::getUrl('index');
    }
}
