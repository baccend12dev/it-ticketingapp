<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Location;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin User',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $it = User::firstOrCreate(['email' => 'it@example.com'], [
            'name' => 'IT Support',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'it',
            'is_active' => true,
        ]);

        $user = User::firstOrCreate(['email' => 'user@example.com'], [
            'name' => 'Regular User',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        // 2. Seed Departments
        $depts = [
            ['name' => 'Information Technology', 'description' => 'IT Department and Support Desk'],
            ['name' => 'Human Resources', 'description' => 'People Ops and HR'],
            ['name' => 'Finance & Accounting', 'description' => 'Billing, Accounts, and Payroll'],
            ['name' => 'Sales & Marketing', 'description' => 'Revenue and Outreach'],
            ['name' => 'Operations', 'description' => 'General Operations and Logistics']
        ];
        $deptModels = [];
        foreach ($depts as $d) {
            $deptModels[] = Department::firstOrCreate(['name' => $d['name']], $d);
        }

        // 3. Seed Locations
        $locs = [
            ['name' => 'Main Office - Floor 1', 'description' => 'Lobby and Finance'],
            ['name' => 'Main Office - Floor 2', 'description' => 'Operations and Sales'],
            ['name' => 'Main Office - Floor 3', 'description' => 'IT Hub and Executive suites'],
            ['name' => 'Branch Office - Jakarta', 'description' => 'Regional sales office'],
            ['name' => 'Warehouse A', 'description' => 'Logistics facility']
        ];
        $locModels = [];
        foreach ($locs as $l) {
            $locModels[] = Location::firstOrCreate(['name' => $l['name']], $l);
        }

        // 4. Seed Categories and SubCategories
        $cats = [
            'Jaringan & Internet' => [
                ['name' => 'Koneksi VPN', 'description' => 'Masalah saat menghubungkan ke VPN kantor'],
                ['name' => 'Akses Wi-Fi', 'description' => 'Masalah dengan jaringan nirkabel kantor'],
                ['name' => 'Masalah Kabel / Switch', 'description' => 'Kerusakan fisik pada colokan atau kabel jaringan']
            ],
            'Perangkat Keras / Printer / Komputer' => [
                ['name' => 'Printer Macet / Toner Habis', 'description' => 'Masalah fisik printer dan persediaan tinta/toner'],
                ['name' => 'Masalah Monitor', 'description' => 'Layar berkedip, tidak menyala, atau layar kosong'],
                ['name' => 'Permintaan Upgrade PC', 'description' => 'Permintaan upgrade memori RAM atau penyimpanan SSD']
            ],
            'Perangkat Lunak / Dukungan Aplikasi' => [
                ['name' => 'Lisensi MS Office', 'description' => 'Masalah aktivasi atau lisensi Office 365'],
                ['name' => 'Konfigurasi Email Client', 'description' => 'Kesalahan konfigurasi Outlook dan webmail'],
                ['name' => 'Dukungan ERP', 'description' => 'Masalah pada sistem SAP atau aplikasi internal perusahaan']
            ],
            'Autentikasi / Active Directory' => [
                ['name' => 'Reset Kata Sandi', 'description' => 'Terkunci atau permintaan pengaturan ulang kata sandi'],
                ['name' => 'Kegagalan Sinkronisasi LDAP', 'description' => 'Masalah sinkronisasi direktori pengguna']
            ],
            'Keamanan & Akses' => [
                ['name' => 'Akses Kartu RFID', 'description' => 'Masalah konfigurasi kartu akses fisik karyawan'],
                ['name' => 'Pengecualian Firewall', 'description' => 'Permintaan membuka port jaringan untuk aplikasi tertentu']
            ]
        ];

        foreach ($cats as $catName => $subCats) {
            $cat = Category::firstOrCreate(['name' => $catName], [
                'name' => $catName,
                'description' => 'Layanan dukungan ' . $catName,
            ]);
            foreach ($subCats as $sc) {
                SubCategory::firstOrCreate([
                    'category_id' => $cat->id,
                    'name' => $sc['name']
                ], [
                    'category_id' => $cat->id,
                    'name' => $sc['name'],
                    'description' => $sc['description']
                ]);
            }
        }

        // 5. Seed initial Tickets if none exist
        if (Ticket::count() === 0) {
            $networkCat = Category::where('name', 'Jaringan & Internet')->first();
            $vpnSub = SubCategory::where('name', 'Koneksi VPN')->first();
            
            $hardwareCat = Category::where('name', 'Perangkat Keras / Printer / Komputer')->first();
            $printerSub = SubCategory::where('name', 'Printer Macet / Toner Habis')->first();

            $authCat = Category::where('name', 'Autentikasi / Active Directory')->first();
            $ldapSub = SubCategory::where('name', 'Kegagalan Sinkronisasi LDAP')->first();

            Ticket::create([
                'title' => 'Koneksi VPN Terputus (Timeout) di Ruang 3B',
                'description' => 'Koneksi VPN terputus secara otomatis setelah 10 menit tidak aktif. Hal ini mengganggu pekerjaan jarak jauh di ruang rapat.',
                'priority' => 'high',
                'status' => 'active',
                'user_id' => $user->id,
                'department_id' => $deptModels[0]->id, // IT
                'location_id' => $locModels[2]->id, // Floor 3
                'category_id' => $networkCat->id,
                'sub_category_id' => $vpnSub->id
            ]);

            Ticket::create([
                'title' => 'Kegagalan Login LDAP pada Active Directory',
                'description' => 'Beberapa pengguna melaporkan kegagalan autentikasi pada layanan LDAP. Perlu tim IT untuk memeriksa domain controller.',
                'priority' => 'critical',
                'status' => 'pending',
                'user_id' => $admin->id,
                'department_id' => $deptModels[0]->id, // IT
                'location_id' => $locModels[2]->id, // Floor 3
                'category_id' => $authCat->id,
                'sub_category_id' => $ldapSub->id
            ]);

            Ticket::create([
                'title' => 'Printer Macet dan Eror Spooler di HR',
                'description' => 'Printer laser di divisi HR (Lantai 1) sering macet dan memunculkan pesan eror printer spooler di OS Windows.',
                'priority' => 'low',
                'status' => 'resolved',
                'user_id' => $user->id,
                'department_id' => $deptModels[1]->id, // HR
                'location_id' => $locModels[0]->id, // Floor 1
                'category_id' => $hardwareCat->id,
                'sub_category_id' => $printerSub->id
            ]);
        }
    }
}
