<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DaftarUser;
use App\Models\DaftarLaboranLaboratorium;
use App\Models\DaftarLab;
use App\Models\StockGroup;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DosenLaboranLabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Dosen (Lecturers)
        $dosenData = [
            [
                'Nama' => 'Ir. Agus Aktawan, S.T., M.Eng.',
                'Phone' => '081234567001',
                'Email' => 'agus.aktawan@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200001',
                'nomor_identitas' => '198108152010121001',
                'Role_User' => 'Kaprodi',
                'extra_roles' => ['Dosen'],
            ],
            [
                'Nama' => 'Ir. Rachma Tia Evitasari, S.T., M.Eng.',
                'Phone' => '081234567002',
                'Email' => 'rachma.evitasari@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200002',
                'nomor_identitas' => '198503122015042002',
                'Role_User' => 'Dosen',
                'extra_roles' => ['Safety Officer'],
            ],
            [
                'Nama' => 'Prof. Ir. Maryudi, S.T., M.T., Ph.D., IPM.',
                'Phone' => '081234567003',
                'Email' => 'maryudi@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200003',
                'nomor_identitas' => '197305201998031001',
                'Role_User' => 'Dosen',
                'extra_roles' => ['Kepala Laboratorium'],
            ],
            [
                'Nama' => 'Ir. Firda Mahira Alfiata Chusna, S.T., M.Eng.',
                'Phone' => '081234567004',
                'Email' => 'firda.chusna@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200004',
                'nomor_identitas' => '199009182018082003',
                'Role_User' => 'Dosen',
                'extra_roles' => ['Kepala Laboratorium', 'Safety Officer'],
            ],
            [
                'Nama' => 'Gita Indah Budiarti, S.T., M.T.',
                'Phone' => '081234567005',
                'Email' => 'gita.budiarti@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200005',
                'nomor_identitas' => '198711052015092004',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Prof. Dr. Ir. Erna Astuti, S.T., M.T., IPM., ASEAN Eng.',
                'Phone' => '081234567006',
                'Email' => 'erna.astuti@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200006',
                'nomor_identitas' => '197004121996032001',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Dr. Ir. Martomo Setyawan, S.T., M.T.',
                'Phone' => '081234567007',
                'Email' => 'martomo.setyawan@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200007',
                'nomor_identitas' => '196805181995041002',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Prof. Dr. Ir. Zahrul Mufrodi, S.T., M.T., IPM., ASEAN Eng.',
                'Phone' => '081234567008',
                'Email' => 'zahrul.mufrodi@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200008',
                'nomor_identitas' => '197210081999031002',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Imam Santosa, S.T., M.T.',
                'Phone' => '081234567009',
                'Email' => 'imam.santosa@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200009',
                'nomor_identitas' => '197412252002121003',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Dr. Ir. Endah Sulistiawati, S.T., M.T., IPM.',
                'Phone' => '081234567010',
                'Email' => 'endah.sulistiawati@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200010',
                'nomor_identitas' => '197103281998032002',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Dr. Ir. Siti Salamah, M.Si.',
                'Phone' => '081234567011',
                'Email' => 'siti.salamah@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200011',
                'nomor_identitas' => '196508121992032001',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Prof. Dr. Ir. Siti Jamilatun, M.T., IPM.',
                'Phone' => '081234567012',
                'Email' => 'siti.jamilatun@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200012',
                'nomor_identitas' => '196311021990032001',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Aster Rahayu, S.Si., M.Si., Ph.D.',
                'Phone' => '081234567013',
                'Email' => 'aster.rahayu@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200013',
                'nomor_identitas' => '198006152008122002',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Shinta Amelia, S.T., M.Eng.',
                'Phone' => '081234567014',
                'Email' => 'shinta.amelia@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200014',
                'nomor_identitas' => '198904222016092005',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Dr.-Ing. Suhendra, S.T., M.Sc.',
                'Phone' => '081234567015',
                'Email' => 'suhendra@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200015',
                'nomor_identitas' => '198212152012041003',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Dr. Eng. Farrah Fadhillah Hanum, S.T., M.Eng.',
                'Phone' => '081234567016',
                'Email' => 'farrah.hanum@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200016',
                'nomor_identitas' => '199201302019032004',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Ir. Adi Permadi, S.T., M.T., M.Farm., Ph.D., IPM., ASEAN Eng., ACPE.',
                'Phone' => '081234567017',
                'Email' => 'adi.permadi@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200017',
                'nomor_identitas' => '197908052006041002',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Dr.rer.nat. Totok Eka Suharto, M.S.',
                'Phone' => '081234567018',
                'Email' => 'totok.suharto@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200018',
                'nomor_identitas' => '196110251988031001',
                'Role_User' => 'Dosen',
                'extra_roles' => [],
            ],
            [
                'Nama' => 'Dr. Dhias Cahya Hakika, S.T., M.Sc.',
                'Phone' => '081234567019',
                'Email' => 'dhias.hakika@tekkim.uad.ac.id',
                'UserID' => 'DSN-2605200019',
                'nomor_identitas' => '199307042021082005',
                'Role_User' => 'Dosen',
                'extra_roles' => ['Safety Officer'],
            ],
        ];

        // 2. Data Laboran (Technicians)
        $laboranData = [
            [
                'Nama' => 'Eko Susilowati',
                'Phone' => '089876543210',
                'Email' => 'eko.susilowati@tekkim.uad.ac.id',
                'UserID' => 'LBR-2605200001',
                'nomor_identitas' => '198402122008122001',
                'Role_User' => 'Laboran',
            ],
            [
                'Nama' => 'M Tamrin, A.Md.',
                'Phone' => '089876543211',
                'Email' => 'm.tamrin@tekkim.uad.ac.id',
                'UserID' => 'LBR-2605200002',
                'nomor_identitas' => '198606182010041002',
                'Role_User' => 'Laboran',
            ],
            [
                'Nama' => 'Nadiatika Amelia, S.Si.',
                'Phone' => '089876543212',
                'Email' => 'nadiatika.amelia@tekkim.uad.ac.id',
                'UserID' => 'LBR-2605200003',
                'nomor_identitas' => '199511122020082001',
                'Role_User' => 'Laboran',
            ],
        ];

        // 3. Data Laboratorium (Laboratories)
        $labsData = [
            [
                'Nama_Laboratorium' => 'Laboratorium Rekayasa Proses Kimia',
                'floor' => '2',
                'lab_type' => 'penelitian',
                'Kepala_Labolatorium' => 'Ir. Firda Mahira Alfiata Chusna, S.T., M.Eng.',
                'Admin_Laboratorium' => 'Eko Susilowati',
                'Safety_Officer' => 'Ir. Rachma Tia Evitasari, S.T., M.Eng.',
                'email_lab' => 'rkp@tekkim.uad.ac.id',
            ],
            [
                'Nama_Laboratorium' => 'Laboratorium Energi',
                'floor' => '2',
                'lab_type' => 'penelitian',
                'Kepala_Labolatorium' => 'Prof. Ir. Maryudi, S.T., M.T., Ph.D., IPM.',
                'Admin_Laboratorium' => 'Eko Susilowati',
                'Safety_Officer' => 'Dr. Dhias Cahya Hakika, S.T., M.Sc.',
                'email_lab' => 'energi@tekkim.uad.ac.id',
            ],
            [
                'Nama_Laboratorium' => 'Laboratorium Material',
                'floor' => '3',
                'lab_type' => 'penelitian',
                'Kepala_Labolatorium' => 'Prof. Ir. Maryudi, S.T., M.T., Ph.D., IPM.',
                'Admin_Laboratorium' => 'Eko Susilowati',
                'Safety_Officer' => 'Ir. Rachma Tia Evitasari, S.T., M.Eng.',
                'email_lab' => 'material@tekkim.uad.ac.id',
            ],
            [
                'Nama_Laboratorium' => 'Laboratorium Operasi Teknik Kimia',
                'floor' => '3',
                'lab_type' => 'pendidikan',
                'Kepala_Labolatorium' => 'Ir. Firda Mahira Alfiata Chusna, S.T., M.Eng.',
                'Admin_Laboratorium' => 'M Tamrin, A.Md.',
                'Safety_Officer' => 'Ir. Firda Mahira Alfiata Chusna, S.T., M.Eng.',
                'email_lab' => 'otk@tekkim.uad.ac.id',
            ],
            [
                'Nama_Laboratorium' => 'Laboratorium Lingkungan',
                'floor' => '4',
                'lab_type' => 'pendidikan',
                'Kepala_Labolatorium' => 'Prof. Ir. Maryudi, S.T., M.T., Ph.D., IPM.',
                'Admin_Laboratorium' => 'M Tamrin, A.Md.',
                'Safety_Officer' => 'Dr. Dhias Cahya Hakika, S.T., M.Sc.',
                'email_lab' => 'lingkungan@tekkim.uad.ac.id',
            ],
            [
                'Nama_Laboratorium' => 'Laboratorium Teknologi Proses Pangan',
                'floor' => '4',
                'lab_type' => 'pendidikan',
                'Kepala_Labolatorium' => 'Prof. Ir. Maryudi, S.T., M.T., Ph.D., IPM.',
                'Admin_Laboratorium' => 'M Tamrin, A.Md.',
                'Safety_Officer' => 'Ir. Rachma Tia Evitasari, S.T., M.Eng.',
                'email_lab' => 'pangan@tekkim.uad.ac.id',
            ],
            [
                'Nama_Laboratorium' => 'Laboratorium Analisis dan Instrumentasi',
                'floor' => '3',
                'lab_type' => 'penelitian',
                'Kepala_Labolatorium' => 'Prof. Ir. Maryudi, S.T., M.T., Ph.D., IPM.',
                'Admin_Laboratorium' => 'Nadiatika Amelia, S.Si.',
                'Safety_Officer' => 'Dr. Dhias Cahya Hakika, S.T., M.Sc.',
                'email_lab' => 'analisis@tekkim.uad.ac.id',
            ],
            [
                'Nama_Laboratorium' => 'Laboratorium Komputasi dan Desain Pabrik Kimia',
                'floor' => '4',
                'lab_type' => 'pendidikan',
                'Kepala_Labolatorium' => 'Ir. Firda Mahira Alfiata Chusna, S.T., M.Eng.',
                'Admin_Laboratorium' => 'Nadiatika Amelia, S.Si.',
                'Safety_Officer' => 'Ir. Firda Mahira Alfiata Chusna, S.T., M.Eng.',
                'email_lab' => 'komputasi@tekkim.uad.ac.id',
            ],
        ];

        // Seeding Dosen
        foreach ($dosenData as $dosen) {
            $user = DaftarUser::updateOrCreate(
                ['UserID' => $dosen['UserID']],
                [
                    'Nama' => $dosen['Nama'],
                    'Email' => $dosen['Email'],
                    'Phone' => $dosen['Phone'],
                    'Password' => Hash::make('DosenTekkim2026!'),
                    'Role_User' => $dosen['Role_User'],
                    'Nomor_Identitas' => $dosen['nomor_identitas'],
                    'is_primary' => true,
                    'status' => 'aktif',
                ]
            );

            // Sync multi-roles
            $allRoles = array_unique(array_merge([$dosen['Role_User']], $dosen['extra_roles']));
            $user->syncRoles($allRoles, $dosen['Role_User']);
        }

        // Seeding Laboran
        foreach ($laboranData as $laboran) {
            $user = DaftarUser::updateOrCreate(
                ['UserID' => $laboran['UserID']],
                [
                    'Nama' => $laboran['Nama'],
                    'Email' => $laboran['Email'],
                    'Phone' => $laboran['Phone'],
                    'Password' => Hash::make('LaboranTekkim2026!'),
                    'Role_User' => $laboran['Role_User'],
                    'Nomor_Identitas' => $laboran['nomor_identitas'],
                    'is_primary' => true,
                    'status' => 'aktif',
                ]
            );

            // Sync laboran role
            $user->syncRoles(['Laboran'], 'Laboran');

            // Insert into the detail table for laborans
            DaftarLaboranLaboratorium::updateOrCreate(
                ['UserID' => $laboran['UserID']],
                [
                    'Nama_Laboran' => $laboran['Nama'],
                    'Phone' => $laboran['Phone'],
                    'Email' => $laboran['Email'],
                    'Role_User' => 'Laboran',
                ]
            );
        }

        // Seeding Laboratories and linking them to Stock Groups & Laborans
        foreach ($labsData as $lab) {
            // Get or create Stock Group for Floor & Lab Type combination
            $stockGroup = StockGroup::firstOrCreate([
                'floor' => $lab['floor'],
                'lab_type' => $lab['lab_type'],
            ]);

            // Create/Update the Laboratory
            $daftarLab = DaftarLab::updateOrCreate(
                ['Nama_Laboratorium' => $lab['Nama_Laboratorium']],
                [
                    'floor' => $lab['floor'],
                    'lab_type' => $lab['lab_type'],
                    'stock_group_id' => $stockGroup->id,
                    'Kepala_Labolatorium' => $lab['Kepala_Labolatorium'],
                    'Admin_Laboratorium' => $lab['Admin_Laboratorium'],
                    'Safety_Officer' => $lab['Safety_Officer'],
                    'email_lab' => $lab['email_lab'],
                ]
            );

            // Link Laboran user to this Lab via the pivot table
            $laboranUser = DaftarUser::where('Nama', $lab['Admin_Laboratorium'])->first();
            if ($laboranUser) {
                DB::table('laboran_laboratorium')->updateOrInsert(
                    [
                        'user_id' => $laboranUser->UserID,
                        'daftar_lab_id' => $daftarLab->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Update the single-lab string column on the Laboran record for backward compatibility
                DaftarLaboranLaboratorium::where('UserID', $laboranUser->UserID)->update([
                    'Laboratorium' => $lab['Nama_Laboratorium']
                ]);
            }
        }

        // 4. Seeding Alat Laboratorium (Lab Tools)
        
        // Clear existing tools to prevent duplicate/dirty records from past seed runs
        \App\Models\AlatLab::query()->delete();
        
        // --- LANTAI 2 TOOLS ---
        // Floor 2 labs are research labs ('penelitian'), so we associate these tools with the Floor 2, Penelitian stock group.
        $stockGroup2 = StockGroup::where('floor', '2')->where('lab_type', 'penelitian')->first();
        if ($stockGroup2) {
            $lantai2Tools = [
                [
                    'nama_alat' => 'Buret kran kaca 50 mL',
                    'jumlah' => 7,
                    'deskripsi' => 'Alat laboratorium berbentuk silinder kaca bergraduasi dengan kran kaca berkapasitas 50 mL. Digunakan untuk meneteskan sejumlah reagen cair dalam eksperimen titrasi dengan presisi tinggi.'
                ],
                [
                    'nama_alat' => 'Buret Kran penjepit',
                    'jumlah' => 1,
                    'deskripsi' => 'Buret kaca bergraduasi yang dilengkapi dengan ujung karet dan kran penjepit (mohr). Digunakan untuk titrasi larutan basa dengan mengontrol aliran cairan secara manual melalui tekanan penjepit.'
                ],
                [
                    'nama_alat' => 'Buret 25 mL kran Teflon',
                    'jumlah' => 1,
                    'deskripsi' => 'Alat titrasi kaca berkapasitas 25 mL dengan kran berbahan teflon (PTFE). Kran teflon tidak memerlukan pelumas tambahan dan sangat tahan terhadap bahan kimia reaktif serta penyumbatan.'
                ],
                [
                    'nama_alat' => 'Buret kran Teflon 50mL',
                    'jumlah' => 5,
                    'deskripsi' => 'Buret bergraduasi berkapasitas 50 mL dengan kran teflon (PTFE) tahan macet. Memudahkan kontrol laju tetesan cairan reagen selama proses titrasi asam-basa maupun redoks secara akurat.'
                ],
                [
                    'nama_alat' => 'Botol timbang',
                    'jumlah' => 12,
                    'deskripsi' => 'Wadah kaca kecil bersumbat asah yang digunakan untuk menimbang sampel zat padat atau cair yang bersifat higroskopis atau mudah menguap agar massanya tidak berubah saat ditimbang.'
                ],
                [
                    'nama_alat' => 'Botol semprot 125 mL',
                    'jumlah' => 2,
                    'deskripsi' => 'Botol plastik lentur berkapasitas 125 mL dengan selang penyemprot. Digunakan untuk menyimpan dan menyemprotkan akuades atau pelarut dalam jumlah kecil untuk membilas alat gelas.'
                ],
                [
                    'nama_alat' => 'Botol semprot 250ml',
                    'jumlah' => 5,
                    'deskripsi' => 'Botol plastik fleksibel berkapasitas 250 mL yang digunakan untuk menyemprotkan air suling (akuades) atau cairan pembersih guna membilas sisa-sisa larutan di dalam tabung reaksi atau alat gelas lainnya.'
                ],
                [
                    'nama_alat' => 'Corong gelas',
                    'jumlah' => 14,
                    'deskripsi' => 'Corong berbahan kaca borosilikat untuk membantu memindahkan cairan dari wadah satu ke wadah lain agar tidak tumpah, serta menopang kertas saring dalam proses filtrasi campuran kimia.'
                ],
                [
                    'nama_alat' => 'Corong pisah 500 mL',
                    'jumlah' => 11,
                    'deskripsi' => 'Peralatan gelas laboratorium berkapasitas 500 mL berbentuk kerucut dengan kran di bagian bawah. Digunakan dalam proses ekstraksi cair-cair untuk memisahkan komponen fase pelarut dengan densitas berbeda.'
                ],
                [
                    'nama_alat' => 'Corong pisah 250 mL',
                    'jumlah' => 3,
                    'deskripsi' => 'Corong pemisah berkapasitas 250 mL dengan kran pembuka-penutup. Berfungsi untuk memisahkan dua fase cairan yang tidak saling bercampur (immiscible) berdasarkan perbedaan massa jenis dalam skala medium.'
                ],
                [
                    'nama_alat' => 'Corong pisah 100 mL',
                    'jumlah' => 1,
                    'deskripsi' => 'Corong pemisah skala kecil berkapasitas 100 mL yang digunakan untuk memisahkan campuran cairan heterogen dalam proses ekstraksi laboratorium dengan volume sampel yang terbatas.'
                ],
            ];

            foreach ($lantai2Tools as $tool) {
                \App\Models\AlatLab::updateOrCreate(
                    [
                        'nama_alat' => $tool['nama_alat'],
                        'stock_group_id' => $stockGroup2->id,
                        'daftar_lab_id' => null, // Shared across Floor 2 labs
                    ],
                    [
                        'jumlah_tersedia' => $tool['jumlah'],
                        'deskripsi' => $tool['deskripsi'],
                    ]
                );
            }
        }

        // --- LANTAI 3 TOOLS ---
        // Floor 3 research labs include Material & Instrumentation labs. We associate these with the Floor 3, Penelitian stock group.
        $stockGroup3 = StockGroup::where('floor', '3')->where('lab_type', 'penelitian')->first();
        if ($stockGroup3) {
            $lantai3Tools = [
                [
                    'nama_alat' => 'Inkubator',
                    'jumlah' => 2,
                    'deskripsi' => 'Alat pengondisian lingkungan suhu terkontrol yang digunakan untuk menginkubasi, menumbuhkan, dan memelihara kultur mikroorganisme atau sel biologi pada suhu konstan yang optimal.'
                ],
                [
                    'nama_alat' => 'Oven',
                    'jumlah' => 1,
                    'deskripsi' => 'Peralalan pemanas tertutup yang digunakan untuk mengeringkan alat-alat gelas laboratorium setelah dicuci, menghilangkan kadar air (kelembapan) dari sampel padat, atau memanaskan material tertentu.'
                ],
                [
                    'nama_alat' => 'Furnace',
                    'jumlah' => 1,
                    'deskripsi' => 'Tungku pemanas bersuhu sangat tinggi (tanur logam/keramik) yang digunakan untuk proses pengabuan sampel organik, kalsinasi, pembakaran material padat, atau perlakuan panas ekstrem lainnya.'
                ],
                [
                    'nama_alat' => 'Magnetic stirrer',
                    'jumlah' => 4,
                    'deskripsi' => 'Alat pengaduk mekanis menggunakan medan magnet berputar untuk memutar batang magnet (stir bar) yang dimasukkan ke dalam cairan. Berfungsi menghomogenkan larutan kimia secara konstan.'
                ],
                [
                    'nama_alat' => 'Pompa vakum',
                    'jumlah' => 2,
                    'deskripsi' => 'Perangkat mekanik untuk mengeluarkan molekul gas dari volume tertutup guna meninggalkan vakum sebagian. Digunakan untuk mempercepat proses filtrasi vakum, evaporasi, atau distilasi vakum.'
                ],
            ];

            foreach ($lantai3Tools as $tool) {
                \App\Models\AlatLab::updateOrCreate(
                    [
                        'nama_alat' => $tool['nama_alat'],
                        'stock_group_id' => $stockGroup3->id,
                        'daftar_lab_id' => null, // Shared across Floor 3 research labs
                    ],
                    [
                        'jumlah_tersedia' => $tool['jumlah'],
                        'deskripsi' => $tool['deskripsi'],
                    ]
                );
            }
        }

        // --- SPECIFIC LAB TOOLS: Lab Analisis dan Instrumentasi ---
        $analisisLab = DaftarLab::where('Nama_Laboratorium', 'Laboratorium Analisis dan Instrumentasi')->first();
        if ($analisisLab) {
            $instrumentasiTools = [
                [
                    'nama_alat' => 'Spektrofotometer UV Vis',
                    'jumlah' => 1,
                    'deskripsi' => 'Instrumen analisis kuantitatif untuk mengukur absorbansi atau transmitansi cahaya oleh suatu sampel cairan sebagai fungsi dari panjang gelombang sinar ultraviolet (UV) dan cahaya tampak (Vis).'
                ],
                [
                    'nama_alat' => 'Neraca analitik',
                    'jumlah' => 1,
                    'deskripsi' => 'Timbangan laboratorium tingkat presisi tinggi yang digunakan untuk mengukur massa zat padat atau sampel kimia dalam skala sangat kecil (hingga tingkat miligram atau sub-miligram) dengan pelindung angin.'
                ],
                [
                    'nama_alat' => 'Rotary Evaporator',
                    'jumlah' => 1,
                    'deskripsi' => 'Alat distilasi vakum berputar yang digunakan untuk menguapkan pelarut dari sampel cair secara cepat dan lembut melalui penurunan tekanan dan pemanasan terkontrol, menghasilkan konsentrat murni.'
                ],
                [
                    'nama_alat' => 'Sonicator',
                    'jumlah' => 1,
                    'deskripsi' => 'Alat yang memancarkan gelombang suara ultrasonik berfrekuensi tinggi di dalam cairan. Digunakan untuk proses sonikasi seperti melarutkan sampel padat sulit larut, dispersi partikel, atau lisis sel.'
                ],
                [
                    'nama_alat' => 'Viskometer',
                    'jumlah' => 1,
                    'deskripsi' => 'Instrumen pengukur kekentalan atau viskositas suatu fluida (cairan). Digunakan untuk menentukan daya alir dan karakteristik hambatan deformasi geser suatu sampel cairan kimia atau bahan pangan.'
                ],
            ];

            foreach ($instrumentasiTools as $tool) {
                \App\Models\AlatLab::updateOrCreate(
                    [
                        'nama_alat' => $tool['nama_alat'],
                        'daftar_lab_id' => $analisisLab->id,
                    ],
                    [
                        'stock_group_id' => $analisisLab->stock_group_id,
                        'jumlah_tersedia' => $tool['jumlah'],
                        'deskripsi' => $tool['deskripsi'],
                    ]
                );
            }
    }
}
}


