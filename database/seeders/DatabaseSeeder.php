<?php

namespace Database\Seeders;

use App\Models\Frekuensi;
use App\Models\MataKuliah;
use App\Models\Praktikan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([]);

        $users_ = [
            'Asisten' => [
                'Furqon Fatahillah',
                'Muhammad Dhani Arya Putra',
                'Imran Afdillah Dahlan'
            ],
            'Dosen' => [
                'Lukman, S.E., M.Acc',
                'Muhammad Arfah Asis, S.Kom., M.T.,MTA.',
                'Andi Widya Mufila Gaffar, S.T., M.Kom.,MTA.',
                'Herman, S.Kom.,M.Cs.,MTA.',
                'Andi Ulfah Tenripada, S.Kom.,M.Kom.,MTA.',
                'Fahmi, S.Kom. M.T'
            ],
            'Kepala_Lab' => [
                'Ir. Abdul Rachman Manga\', S.Kom., M.T., MTA.',
                'Ir. Huzain Azis, S.Kom., M.Cs. MTA',
                'Herdianti, S.Si. M.Eng, MTA'
            ],
            'Admin' => [
                'Fatimah AR. Tuasamu, S.Kom., MTA, MCF'
            ],
        ];

        $admin = User::factory()->create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@mail.io',
            'peran' => 'ADMIN',
        ]);

        foreach ($users_ as $peran => $users) {
            $index = 1;
            foreach ($users as $user_name) {
                $_x = strtolower($peran) . '_0' . $index;
                User::factory()->create([
                    'name' => $user_name,
                    'username' => str($_x)->slug(),
                    'email' => $_x . '@mail.io',
                    'peran' => strtoupper($peran),
                ]);
                $index++;
            }
        }

        $this->call([
            MataKuliahSeeder::class,
            FrekuensiSeeder::class,
        ]);

        Praktikan::factory(100)->create();

        $data = [
            'Angkatan_2023' => [
                'SI_JARKOM-1' => [
                    'Frekuensi' => 'Jaringan Komputer',
                    'Praktikan' => [
                        'FIQRI AHYAR MUBARAQ',
                        'MUH. ANFASYA DWINATA',
                        'MUHAMMAD I\'TISHAM REDANG',
                        'MUH. FIKRY HAIKAL',
                        'TAUFIKUR RAMADANI JUNA FIRMANSYAH RUMKEL',
                        'IFAN WAHYUDI',
                        'WAHYUDIN SARKOL',
                        'MUH. TAUFIQ HIDAYAT',
                        'DANY DWI KUNCORO',
                        'AKHMAD KACHFI',
                        'NURFAJRI MUKMIN SAPUTRA',
                        'MUH. RIZQULLAH RASUL',
                        'MUH ILYAS',
                        'MUH. FIKRI AMAR',
                        'MUH NAUFAL FIRJATULLAH',
                        'MUHAMMAD ARIL ANUGRAH SETIAWAN',
                        'MUH. FAIZ RIDHA',
                        'MUH FARHAN B',
                        'AHMAD ZAHRAN UPUOLAT',
                        'ANDI MUH. EGI PRATAMA ARMIN',
                        'MUH. ARJUN B.',
                        'MOHAMMAD HAQIL MAHDALI',
                        'ELSADIG AHMED ADAM MOALA',
                        'SURYATI',
                        'NURUL AZIZAH PUTERI',
                        'MILDA SRIANI'
                    ]
                ],
                'SI_BD2-1' => [
                    'Frekuensi' => 'Basis Data II',
                    'Praktikan' => [
                        'FIQRI AHYAR MUBARAQ',
                        'MUH. ANFASYA DWINATA',
                        'MUHAMMAD I\'TISHAM REDANG',
                        'MUH. FIKRY HAIKAL',
                        'TAUFIKUR RAMADANI JUNA FIRMANSYAH RUMKEL',
                        'IFAN WAHYUDI',
                        'WAHYUDIN SARKOL',
                        'MUH. TAUFIQ HIDAYAT',
                        'DANY DWI KUNCORO',
                        'AKHMAD KACHFI',
                        'NURFAJRI MUKMIN SAPUTRA',
                        'MUH. RIZQULLAH RASUL',
                        'MUH ILYAS',
                        'MUH. FIKRI AMAR',
                        'MUH NAUFAL FIRJATULLAH',
                        'MUHAMMAD ARIL ANUGRAH SETIAWAN',
                        'MUH. FAIZ RIDHA',
                        'MUH FARHAN B',
                        'AHMAD ZAHRAN UPUOLAT',
                        'ANDI MUH. EGI PRATAMA ARMIN',
                        'MUH. ARJUN B.',
                        'MUH. AZHARUL MUSA',
                        'FAISHAL NUR MACORA MACHZAN',
                        'MOHAMMAD HAQIL MAHDALI',
                        'AGIL PRASETYA A. RAZAK',
                        'ELSADIG AHMED ADAM MOALA'
                    ]
                ],
                'SI_WEB-2' => [
                    'Frekuensi' => 'Pemrograman Web',
                    'Praktikan' => [
                        'SURYATI',
                        'NURUL AZIZAH PUTERI',
                        'MILDA SRIANI',
                        'ITAMA',
                        'KIRANA WAHYU ASTRININGTYAS',
                        'NUR HIKMAH CAHYANI. HS',
                        'NURLAILA NABILA',
                        'DEWI APRILIANI PUTRI',
                        'DIANI AMALIA PUTRI ASHARI',
                        'ULAN SARI',
                        'NUR SYIFA ISNAINI',
                        'A. RISKA RAHAYU',
                        'YUYUN NURUL SRI REZKY BAHARUDDIN',
                        'KANAYA PUTERI MARYUNDA',
                        'NABILA ELFIRA WANTI',
                        'A. AMALIAH SYAHRIR',
                        'ANANDA ISMI PRATIWI',
                        'MIFTAFUL JANNAH',
                        'SOFIA LAJAHARI',
                        'YULIATI LEK',
                        'AFRILIA DWI SHADRINA',
                        'NUR SA\'ADAH',
                        'AINUL MARDHIYAH',
                        'ANDI FARISAH ZHAFARINA RIDWAN',
                        'HAURA USMAN',
                        'ANDI RESKI ISLAMI MUIN',
                        'NADIAH ALYA KHIRANI',
                        'RIFKA FAUZIAH ANNISA',
                        'NABILAH MUTHIAH JULIANI SAMSIR',
                        'NADITA AUDINA ASTARI',
                        'NURUL HIDAYAH',
                        'A. SYIFA NUR SAUQIYA',
                        'NUR IREN GASRAWATY'
                    ]
                ]
            ],
            'Angkatan_2022' => [
                'TI_MICRO-13' => [
                    'Frekuensi' => 'Microcontroller',
                    'Praktikan' => [
                        'ANDI NURUL FITRI TAQIAH PATARAI',
                        'SARTIKA WIDYA ARDINA',
                        'ANDI MAWARSI SALSABILA',
                        'NURUL FITRIANI',
                        'CATRI SYAFRIANA BINTO',
                        'NASRINA IMTIYAS ZAHRA',
                        'NURUL FATIHAH',
                        'PUTRI APRILIA',
                        'UMMUL ULYA HANDAYANI HAMID',
                        'NURSYAMSI',
                        'RISKA',
                        'NUR SYAFIQAH',
                        'NURUL CAHYANI WULANDARI',
                        'ADE HANDAYANI KADIR',
                        'FITRALIZANI ISHAK',
                        'SINARMIYANTI',
                        'RISTHA',
                        'NABILA WIDIYANTI',
                        'MAHARANI SAFWA ANDINI',
                        'SULISTRIAWATI RAMADHANI',
                        'DESI ISNATASYA',
                        'VIRKAYANTI ANDANI PUTRI'
                    ]
                ],
                'SI_SO-2' => [
                    'Frekuensi' => 'Sistem Operasi',
                    'Praktikan' => [
                        'NUR ANNISAH AULIA',
                        'RISA HASMILA SARI',
                        'SALSABILA AURELIA',
                        'AFIFAH KHAIRUNNISA NUGROHO',
                        'INTAN PUTRI NURASHILA TAJUDDIN',
                        'AMALYA REZKY FITRAYANA',
                        'A. RESKI RAMADANI RAHMAT',
                        'MUSTIKA OCTAVIA',
                        'NURUL INAYAH HERMANY',
                        'NUR ISLAMIA',
                        'PUTRI SALSABILA VIRIZCA',
                        'FITRI HERNANDA MAHARANI AMIR',
                        'BINTANG NURAINI',
                        'SELVIA',
                        'ANDI FATHIMATUZ ZAHRA',
                        'MUSDALIFAH',
                        'PUTRI NUR REZKY',
                        'RISDAYANTI A.GAMTINA',
                        'NABILA NUR SYAIDAH'
                    ]
                ],
                'SI_AA-2' => [
                    'Frekuensi' => 'Aplikasi Akuntansi',
                    'Praktikan' => [
                        'NUR ANNISAH AULIA',
                        'RISA HASMILA SARI',
                        'SALSABILA AURELIA',
                        'AFIFAH KHAIRUNNISA NUGROHO',
                        'INTAN PUTRI NURASHILA TAJUDDIN',
                        'AMALYA REZKY FITRAYANA',
                        'A. RESKI RAMADANI RAHMAT',
                        'MUSTIKA OCTAVIA',
                        'NURUL INAYAH HERMANY',
                        'NUR ISLAMIA',
                        'PUTRI SALSABILA VIRIZCA',
                        'FITRI HERNANDA MAHARANI AMIR',
                        'BINTANG NURAINI',
                        'SELVIA',
                        'ANDI FATHIMATUZ ZAHRA',
                        'MUSDALIFAH',
                        'PUTRI NUR REZKY',
                        'RISDAYANTI A.GAMTINA',
                        'NABILA NUR SYAIDAH'
                    ]
                ]
            ],
        ];

        $i_x = 1;

        foreach ($data as $angakatan => $value) {

            $arr = explode('_', $angakatan);

            $angkatan = $arr[1];

            foreach ($value as $frekuensi => $data_frekuensi) {
                $matkul = $data_frekuensi['Frekuensi'];
                $x_prkst = $data_frekuensi['Praktikan'] ?? [];

                $matkul_model = MataKuliah::where('nama', ucwords($matkul))->first();

                if (!$matkul_model) {
                    $matkul_model = MataKuliah::create([
                        'nama'              =>  ucwords($matkul),
                        'semester'          =>  'gasal',
                        'tahun_akademik'    =>  $angkatan,
                    ]);
                } else {
                    $matkul_model->update([
                        'semester'          =>  'gasal',
                        'tahun_akademik'    =>  $angkatan,
                    ]);
                }

                $frekuensi_model = Frekuensi::where('name', $frekuensi)->first();

                if (!$frekuensi_model) {
                    $frekuensi_model = Frekuensi::create([
                        'mata_kuliah_id'    =>  $matkul_model->id,
                        'name'              =>  $frekuensi,
                    ]);
                }

                foreach ($x_prkst as $praktikan_name) {

                    $user_model_p = User::where('username', str($praktikan_name)->slug())->first();

                    if (!$user_model_p) {
                        $user_model_p = User::factory()->create([
                            'name' => $praktikan_name,
                            'username' => str($praktikan_name)->slug(),
                            'email' => "praktikan_$i_x@mail.io",
                            'peran' => 'PRAKTIKAN'
                        ]);

                        Praktikan::create([
                            'user_id' => $user_model_p->id,
                            'kelas' =>  fake()->word(),
                            'jurusan' => fake()->word(),
                            'frekuensi_id' => $frekuensi_model->id
                        ]);
                    } else {

                        $user_model_p->praktikan->update([
                            'frekuensi_id' => $frekuensi_model->id
                        ]);
                    }



                    $i_x++;
                }
            }
        }
    }
}
