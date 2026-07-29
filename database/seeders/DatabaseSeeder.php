<?php

namespace Database\Seeders;

use App\Models\Endroit;
use App\Models\Objet;
use App\Models\PackVersion;
use App\Models\QrCode;
use App\Models\Site;
use App\Models\User;
use App\Models\Wilaya;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate seed tables to avoid duplicates on re-seed
        QrCode::query()->delete();
        PackVersion::query()->delete();
        Objet::query()->delete();
        Endroit::query()->delete();
        Site::query()->delete();
        Wilaya::query()->delete();
        User::query()->delete();

        // --- Super Admin ---
        User::create([
            'name' => 'Karim Belkacem',
            'email' => 'k.belkacem@turathi.dz',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'lang' => 'fr',
            'is_active' => true,
        ]);

        // --- Local Admin (Directeur) ---
        $sara = User::create([
            'name' => 'Sara Djelloul',
            'email' => 's.djelloul@turathi.dz',
            'password' => Hash::make('password'),
            'role' => 'local_admin',
            'lang' => 'fr',
            'is_active' => true,
        ]);

        // --- Wilayas ---
        $tlemcen = Wilaya::create(['name' => ['fr' => 'Tlemcen', 'ar' => 'تلمسان', 'en' => 'Tlemcen']]);
        $alger = Wilaya::create(['name' => ['fr' => 'Alger', 'ar' => 'الجزائر', 'en' => 'Algiers']]);
        $tipasa = Wilaya::create(['name' => ['fr' => 'Tipasa', 'ar' => 'تيبازة', 'en' => 'Tipaza']]);

        // --- Sites ---
        $mechouar = Site::create([
            'wilaya_id' => $tlemcen->id,
            'name' => ['ar' => 'قصر المشور', 'fr' => 'Palais El Mechouar', 'en' => 'El Mechouar Palace'],
            'description' => [
                'ar' => 'قصر تاريخي في ولاية تلمسان',
                'fr' => 'Palais historique de Tlemcen',
                'en' => 'Historical palace in Tlemcen',
            ],
            'latitude' => 34.8781,
            'longitude' => -1.3150,
            'altitude' => 830,
            'is_published' => true,
        ]);

        $bardo = Site::create([
            'wilaya_id' => $alger->id,
            'name' => ['ar' => 'موزيوم البardo', 'fr' => 'Musée du Bardo', 'en' => 'Bardo Museum'],
            'description' => [
                'ar' => 'موزيوم تاريخي في الجزائر العاصمة',
                'fr' => 'Musée historique à Alger',
                'en' => 'Historical museum in Algiers',
            ],
            'latitude' => 36.7833,
            'longitude' => 3.0500,
            'is_published' => true,
        ]);

        $casbah = Site::create([
            'wilaya_id' => $alger->id,
            'name' => ['ar' => 'القصبة', 'fr' => "Casbah d'Alger", 'en' => 'Algiers Casbah'],
            'description' => [
                'ar' => 'القصبة التاريخية',
                'fr' => 'La Casbah historique',
                'en' => 'The historic Casbah',
            ],
            'latitude' => 36.7822,
            'longitude' => 3.0514,
            'is_published' => true,
        ]);

        $tipasa = Site::create([
            'wilaya_id' => $tipasa->id,
            'name' => ['ar' => 'أطلال تيبازة', 'fr' => 'Ruines de Tipasa', 'en' => 'Ruins of Tipasa'],
            'description' => [
                'ar' => 'موقع أثري في تيبازة',
                'fr' => 'Site archéologique à Tipasa',
                'en' => 'Archaeological site in Tipasa',
            ],
            'latitude' => 36.5897,
            'longitude' => 2.4475,
            'is_published' => true,
        ]);

        // --- Assign QR codes to sites (using explicit assignment, not fillable) ---
        $sites = [$mechouar, $bardo, $casbah, $tipasa];
        foreach ($sites as $site) {
            $site->qr_code_id = 'SITE-' . str_pad($site->id, 4, '0', STR_PAD_LEFT);
            $site->version_pack = '1.0';
            $site->save();

            QrCode::create([
                'qr_code_id' => $site->qr_code_id,
                'type' => 'site',
                'site_id' => $site->id,
            ]);
        }

        // --- Assign sites to Sara ---
        $sara->sites()->attach([$mechouar->id]);

        // --- Endroits pour Palais El Mechouar ---
        $endroits = [
            [
                'title' => ['ar' => 'البوابة الرئيسية', 'fr' => 'Porte principale', 'en' => 'Main Gate'],
                'description' => ['ar' => 'مدخل القصر', 'fr' => "Entrée du palais", 'en' => 'Palace entrance'],
                'latitude' => 34.8782,
                'longitude' => -1.3151,
            ],
            [
                'title' => ['ar' => 'ساحة الشرف', 'fr' => "Cour d'honneur", 'en' => 'Courtyard of Honor'],
                'description' => ['ar' => 'الساحة الرئيسية', 'fr' => 'La cour principale', 'en' => 'The main courtyard'],
                'latitude' => 34.8783,
                'longitude' => -1.3152,
            ],
            [
                'title' => ['ar' => 'قاعة العرش', 'fr' => 'Salle du Trône', 'en' => 'Throne Room'],
                'description' => [
                    'ar' => 'قاعة مبنية في عهد الدولة الزيانية',
                    'fr' => "Construite sous la dynastie zianide, la Salle du Trône illustre le raffinement de l'architecture andalouse-maghrébine.",
                    'en' => 'Built under the Zianid dynasty, the Throne Room illustrates the refinement of Andalusian-Maghrebi architecture.',
                ],
                'latitude' => 34.8784,
                'longitude' => -1.3153,
            ],
            [
                'title' => ['ar' => 'الحدائق الأندلسية', 'fr' => 'Jardins andalous', 'en' => 'Andalusian Gardens'],
                'description' => ['ar' => 'حدائق جميلة', 'fr' => 'Jardins magnifiques', 'en' => 'Beautiful gardens'],
                'latitude' => 34.8785,
                'longitude' => -1.3154,
            ],
        ];

        foreach ($endroits as $endroitData) {
            $endroit = Endroit::create(array_merge(['site_id' => $mechouar->id], $endroitData));
            $endroit->qr_code_id = 'END-' . str_pad($endroit->id, 4, '0', STR_PAD_LEFT);
            $endroit->save();

            QrCode::create([
                'qr_code_id' => $endroit->qr_code_id,
                'type' => 'endroit',
                'endroit_id' => $endroit->id,
                'site_id' => $mechouar->id,
            ]);
        }
    }
}
