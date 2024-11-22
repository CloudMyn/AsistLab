<?php

return [
    'navigation' => [
        'group' => 'Sistem',
        'label' => 'Editor .Env',
    ],

    'page' => [
        'title' => 'Editor .Env',
    ],
    'tabs' => [
        'current-env' => [
            'title' => 'Current .env',
        ],
        'backups' => [
            'title' => 'Backups',
        ],
    ],
    'actions' => [
        'add' => [
            'title' => 'Tambahkan entri baru',
            'modalHeading' => 'Tambahkan entri baru',
            'success' => [
                'title' => 'Kunci ":Nama", berhasil ditulis',
            ],
            'form' => [
                'fields' => [
                    'key' => 'Kunci',
                    'value' => 'Nilai',
                    'index' => 'Masukkan setelah kunci yang ada (opsional)',
                ],
                'helpText' => [
                    'index' => 'Jika Anda perlu meletakkan entri baru ini, setelah kunci yang ada, Anda dapat memilih salah satu kunci yang ada ',
                ],
            ],
        ],
        'edit' => [
            'tooltip' => 'Edit entri ":nama"',
            'modal' => [
                'text' => 'Edit entri',
            ],
        ],
        'delete' => [
            'tooltip' => 'Hapus entri ":nama"',
            'confirm' => [
                'title' => 'Anda akan menghapus ":nama". Apakah Anda yakin dengan penghapusan ini?',
            ],
        ],
        'clear-cache' => [
            'title' => 'Bersihkan cache',
            'tooltip' => 'Kadang-kadang Laravel meng-cache variabel ENV, jadi Anda perlu membersihkan semua cache ("artisan optimize:clear"), agar dapat membaca perubahan .env',
        ],

        'backup' => [
            'title' => 'Buat backup baru',
            'success' => [
                'title' => 'Backup, berhasil dibuat',
            ],
        ],
        'download' => [
            'title' => 'Unduh .env saat ini',
            'tooltip' => 'Unduh file backup ":nama"',
        ],
        'upload-backup' => [
            'title' => 'Unggah file backup',
        ],
        'show-content' => [
            'modalHeading' => 'Konten mentah dari backup ":nama"',
            'tooltip' => 'Tampilkan konten mentah',
        ],
        'restore-backup' => [
            'confirm' => [
                'title' => 'Anda akan mengembalikan ":nama", sebagai file .env saat ini. Harap konfirmasi pilihan Anda',
            ],
            'modalSubmit' => 'Kembalikan',
            'tooltip' => 'Kembalikan ":nama", sebagai ENV saat ini',
        ],
        'delete-backup' => [
            'tooltip' => 'Hapus file backup ":nama"',
            'confirm' => [
                'title' => 'Anda akan menghapus file backup ":nama". Apakah Anda yakin dengan penghapusan ini?',
            ],
        ],
    ],
];

