<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\StaffMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@maison-eclat.com'],
            ['name' => 'Gérante du salon', 'password' => 'password', 'is_admin' => true]
        );

        $staff = collect([
            ['name' => 'Awa Koné', 'role' => 'Coiffeuse & experte tresses', 'bio' => "12 ans d'expérience, spécialiste des tresses collées et des coiffures d'événement."],
            ['name' => 'Fatou Diarra', 'role' => 'Spécialiste soins & coloration', 'bio' => 'Diagnostic capillaire, soins profonds, colorations sur mesure.'],
            ['name' => 'Grace Mensah', 'role' => 'Prothésiste ongulaire', 'bio' => 'Manucure russe, pose gel, nail art et beauté des pieds.'],
            ['name' => 'Sarah Bamba', 'role' => 'Esthéticienne', 'bio' => 'Soins du visage, épilation, mise en beauté du regard.'],
        ])->map(fn ($row, $i) => StaffMember::create($row + ['position' => $i, 'is_active' => true]));

        $catalog = [
            ['Coiffure', 'ciseaux', 'Coupes, brushings et mises en forme pour sublimer votre nature de cheveu.', [
                ['Coupe & brushing', 12000, null, 60, 'Coupe personnalisée, shampooing et brushing lumière.', true],
                ['Brushing lissage', 8000, 12000, 45, 'Un lissage souple et brillant qui tient toute la semaine.', false],
                ['Coiffure de mariée', 45000, 75000, 150, 'Essai inclus, accessoires et pose de voile pour le grand jour.', true],
                ['Pose de perruque (lace)', 15000, 25000, 90, 'Pose, customisation et coiffage de votre lace wig.', false],
            ]],
            ['Tresses & Nattes', 'tresses', 'Tresses collées, twists et braids réalisées avec douceur, sans tension excessive.', [
                ['Tresses collées (cornrows)', 10000, 18000, 120, 'Motifs simples ou géométriques, avec ou sans mèches.', true],
                ['Box braids', 25000, 40000, 240, 'Braids longues ou mi-longues, finition brûlée ou nouée.', true],
                ['Twists & vanilles', 20000, 30000, 180, 'Twists souples pour un style naturel et protecteur.', false],
                ['Locks & entretien', 15000, 25000, 120, 'Création, reprise de racines et entretien de locks.', false],
            ]],
            ['Soins capillaires', 'soins', 'Diagnostic, soins profonds et rituels de réparation adaptés à votre cuir chevelu.', [
                ['Soin profond hydratant', 9000, null, 45, 'Masque vapeur, massage crânien et scellage des pointes.', true],
                ['Traitement anti-chute', 18000, null, 60, 'Cure ciblée pour densifier et fortifier la fibre.', false],
                ['Détox du cuir chevelu', 12000, null, 45, 'Gommage, lotion purifiante et massage relaxant.', false],
                ['Coloration & repigmentation', 20000, 35000, 120, 'Colorations sans ammoniaque, balayages et retouches.', false],
            ]],
            ['Onglerie', 'ongles', 'Manucure, pédicure et nail art dans un espace dédié, hygiène irréprochable.', [
                ['Manucure classique', 6000, null, 45, 'Mise en forme, cuticules, massage et vernis.', true],
                ['Pose de gel / capsules', 15000, 22000, 90, 'Pose complète avec French, couleur ou nail art.', true],
                ['Pédicure spa', 10000, null, 60, 'Bain de pieds, gommage, ponçage et vernis longue tenue.', true],
                ['Nail art (par ongle)', 1000, 2500, 20, 'Décors personnalisés, strass, chrome, effets marbrés.', false],
            ]],
            ['Beauté & visage', 'visage', 'Le petit plus qui illumine : regard, peau et épilation.', [
                ['Soin du visage éclat', 15000, null, 60, 'Nettoyage, gommage, masque et sérum vitaminé.', false],
                ['Pose de cils (volume russe)', 20000, 30000, 90, 'Extensions cil à cil, effet naturel ou volume.', true],
                ['Restructuration des sourcils', 5000, null, 30, 'Épilation, teinture et mise en forme.', false],
            ]],
        ];

        foreach ($catalog as $ci => [$name, $icon, $description, $services]) {
            $category = ServiceCategory::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'icon' => $icon,
                'description' => $description,
                'position' => $ci,
            ]);

            foreach ($services as $si => [$sName, $price, $priceMax, $duration, $desc, $featured]) {
                Service::create([
                    'service_category_id' => $category->id,
                    'name' => $sName,
                    'slug' => Str::slug($sName),
                    'description' => $desc,
                    'price' => $price,
                    'price_max' => $priceMax,
                    'duration' => $duration,
                    'is_featured' => $featured,
                    'is_active' => true,
                    'position' => $si,
                ]);
            }
        }

        $products = [
            ['Huile de ricin noire 100 ml', 'Soin', 6500, 24, 'Huile pressée à froid pour fortifier les longueurs et les sourcils.', true],
            ['Masque beurre de karité 300 ml', 'Soin', 8500, 18, 'Masque nourrissant pour cheveux crépus et bouclés.', true],
            ['Leave-in hydratant 250 ml', 'Soin', 7000, 20, 'Crème sans rinçage pour une définition souple des boucles.', false],
            ['Shampooing doux sans sulfate', 'Soin', 6000, 30, 'Nettoie sans dessécher, parfait entre deux soins.', false],
            ['Bonnet en satin', 'Accessoire', 3500, 40, 'Protège vos cheveux la nuit et limite la casse.', true],
            ['Taie d’oreiller satin', 'Accessoire', 5500, 15, 'Le geste anti-frisottis pendant votre sommeil.', false],
            ['Brosse démêlante souple', 'Accessoire', 4000, 25, 'Démêle sans arracher, même sur cheveux mouillés.', false],
            ['Peigne à queue en bois', 'Accessoire', 2500, 35, 'Idéal pour les séparations nettes avant les tresses.', false],
            ['Foulard wax premium', 'Accessoire', 6000, 12, 'Foulard imprimé pour accessoiriser vos coiffures.', true],
            ['Kit vernis semi-permanent', 'Onglerie', 12000, 8, 'Base, couleur et top coat longue tenue.', false],
            ['Huile pour cuticules', 'Onglerie', 3000, 22, 'Nourrit et assouplit le contour de l’ongle.', false],
            ['Coffret découverte soins', 'Coffret', 20000, 6, 'Huile, masque et leave-in dans un joli coffret cadeau.', true],
        ];

        foreach ($products as [$pName, $cat, $price, $stock, $desc, $featured]) {
            Product::create([
                'name' => $pName,
                'slug' => Str::slug($pName),
                'category' => $cat,
                'description' => $desc,
                'price' => $price,
                'stock' => $stock,
                'is_featured' => $featured,
                'is_active' => true,
            ]);
        }

        foreach ([
            ['Aminata T.', 'Box braids', 'Trois heures de douceur, aucune tension et un résultat impeccable. Je ne vais plus ailleurs.'],
            ['Chantal K.', 'Soin profond', "Mes cheveux ont retrouvé de la densité en deux séances. L'équipe prend vraiment le temps d'expliquer."],
            ['Mariam D.', 'Pédicure spa', "Un vrai moment de détente, l'hygiène est irréprochable et l'accueil chaleureux."],
            ['Estelle N.', 'Coiffure de mariée', 'Ils ont tenu compte de chaque détail pour mon mariage. Mes invités ont adoré.'],
            ['Rachelle B.', 'Manucure', "Prise de rendez-vous en ligne ultra simple, et jamais d'attente sur place."],
            ['Fanta S.', 'Coloration', 'Une couleur sur mesure, exactement la nuance que je voulais. Bravo !'],
        ] as [$author, $context, $content]) {
            Testimonial::create(compact('author', 'context', 'content') + ['rating' => 5]);
        }

        $this->seedDemoActivity($staff);
    }

    /** Génère 90 jours d'activité de démonstration (RDV + commandes). */
    private function seedDemoActivity($staff): void
    {
        $services = Service::all();
        $products = Product::all();
        $names = ['Awa Diallo', 'Mariam Cissé', 'Chantal Kouassi', 'Aïcha Traoré', 'Nadège Yao', 'Fatoumata Sylla',
            'Grace Adjei', 'Estelle Kouadio', 'Ruth Beugré', 'Sandrine Kabore', 'Léa Toure', 'Josiane Aka',
            'Bintou Kone', 'Marie-Ange Dje', 'Salimata Ouattara'];

        $statusesPast = ['completed', 'completed', 'completed', 'completed', 'cancelled', 'no_show'];

        for ($d = 90; $d >= -14; $d--) {
            $day = Carbon::today()->subDays($d);

            if (! (config('salon.hours')[(int) $day->isoWeekday()] ?? null)) {
                continue;
            }

            $count = $day->isoWeekday() >= 5 ? random_int(5, 9) : random_int(2, 6);

            for ($i = 0; $i < $count; $i++) {
                $service = $services->random();
                $start = $day->copy()->setTime(random_int(8, 17), collect([0, 30])->random());
                $isPast = $start->isPast();

                Appointment::create([
                    'service_id' => $service->id,
                    'staff_member_id' => $staff->random()->id,
                    'customer_name' => $names[array_rand($names)],
                    'customer_phone' => '+225 0'.random_int(1, 9).' '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99),
                    'customer_email' => null,
                    'starts_at' => $start,
                    'ends_at' => $start->copy()->addMinutes($service->duration),
                    'price' => $service->price,
                    'status' => $isPast ? $statusesPast[array_rand($statusesPast)] : collect(['pending', 'confirmed', 'confirmed'])->random(),
                ]);
            }

            if (random_int(0, 100) < 55) {
                $order = Order::create([
                    'customer_name' => $names[array_rand($names)],
                    'customer_phone' => '+225 07 '.random_int(10, 99).' '.random_int(10, 99).' '.random_int(10, 99),
                    'delivery_mode' => collect(['pickup', 'delivery'])->random(),
                    'address' => 'Abidjan',
                    'subtotal' => 0,
                    'delivery_fee' => 0,
                    'total' => 0,
                    'status' => $day->isPast() ? collect(['delivered', 'delivered', 'confirmed', 'cancelled'])->random() : 'pending',
                    'created_at' => $day->copy()->setTime(random_int(9, 18), 0),
                    'updated_at' => $day,
                ]);

                $subtotal = 0;
                foreach ($products->random(random_int(1, 3)) as $product) {
                    $qty = random_int(1, 2);
                    $order->items()->create([
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'unit_price' => $product->price,
                        'quantity' => $qty,
                        'line_total' => $product->price * $qty,
                    ]);
                    $subtotal += $product->price * $qty;
                }

                $fee = $order->delivery_mode === 'delivery' && $subtotal < config('salon.free_delivery_from')
                    ? config('salon.delivery_fee') : 0;

                $order->forceFill([
                    'subtotal' => $subtotal,
                    'delivery_fee' => $fee,
                    'total' => $subtotal + $fee,
                ])->save();
            }
        }
    }
}
