<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\GalleryItem;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Post;
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

        $staff = $this->seedStaff();
        $this->seedCatalog();
        $this->seedProducts();
        $this->seedGallery();
        $this->seedOffers();
        $this->seedPosts();
        $this->seedTestimonials();
        $this->seedDemoActivity($staff);
    }

    private function seedStaff()
    {
        return collect([
            ['name' => 'Awa Koné', 'role' => 'Coiffeuse & experte tresses', 'photo' => 'images/team/awa.jpg',
                'bio' => "12 ans d'expérience, spécialiste des tresses collées et des coiffures d'événement."],
            ['name' => 'Fatou Diarra', 'role' => 'Spécialiste soins & coloration', 'photo' => 'images/team/fatou.jpg',
                'bio' => 'Diagnostic capillaire, soins profonds, colorations sur mesure.'],
            ['name' => 'Grace Mensah', 'role' => 'Prothésiste ongulaire', 'photo' => 'images/team/grace.jpg',
                'bio' => 'Manucure russe, pose gel, nail art et beauté des pieds.'],
            ['name' => 'Sarah Bamba', 'role' => 'Esthéticienne & make-up artist', 'photo' => 'images/team/sarah.jpg',
                'bio' => 'Soins du visage, épilation, mise en beauté du regard et maquillage.'],
        ])->map(fn ($row, $i) => StaffMember::create($row + ['position' => $i, 'is_active' => true]));
    }

    private function seedCatalog(): void
    {
        $catalog = [
            ['Coiffure', 'ciseaux', 'images/categories/coiffure.jpg',
                'Coupes, brushings et mises en forme pour sublimer votre nature de cheveu.', [
                    ['Coupe & brushing', 12000, null, 60, 'Coupe personnalisée, shampooing et brushing lumière.', true, 'images/services/coupe-brushing.jpg'],
                    ['Brushing lissage', 8000, 12000, 45, 'Un lissage souple et brillant qui tient toute la semaine.', false, 'images/services/brushing-lissage.jpg'],
                    ['Coiffure de mariée', 45000, 75000, 150, 'Essai inclus, accessoires et pose de voile pour le grand jour.', true, 'images/services/coiffure-mariee.jpg'],
                    ['Pose de perruque (lace)', 15000, 25000, 90, 'Pose, customisation et coiffage de votre lace wig.', false, 'images/services/perruque.jpg'],
                ]],
            ['Tresses & Nattes', 'tresses', 'images/categories/tresses.jpg',
                'Tresses collées, twists et braids réalisées avec douceur, sans tension excessive.', [
                    ['Tresses collées (cornrows)', 10000, 18000, 120, 'Motifs simples ou géométriques, avec ou sans mèches.', true, 'images/services/tresses-collees.jpg'],
                    ['Box braids', 25000, 40000, 240, 'Braids longues ou mi-longues, finition brûlée ou nouée.', true, 'images/services/box-braids.jpg'],
                    ['Twists & vanilles', 20000, 30000, 180, 'Twists souples pour un style naturel et protecteur.', false, 'images/services/twists.jpg'],
                    ['Locks & entretien', 15000, 25000, 120, 'Création, reprise de racines et entretien de locks.', false, 'images/services/locks.jpg'],
                ]],
            ['Soins capillaires', 'soins', 'images/categories/soins.jpg',
                'Diagnostic, soins profonds et rituels de réparation adaptés à votre cuir chevelu.', [
                    ['Soin profond hydratant', 9000, null, 45, 'Masque vapeur, massage crânien et scellage des pointes.', true, 'images/services/soin-profond.jpg'],
                    ['Traitement anti-chute', 18000, null, 60, 'Cure ciblée pour densifier et fortifier la fibre.', false, 'images/services/anti-chute.jpg'],
                    ['Détox du cuir chevelu', 12000, null, 45, 'Gommage, lotion purifiante et massage relaxant.', false, 'images/services/detox.jpg'],
                    ['Coloration & repigmentation', 20000, 35000, 120, 'Colorations sans ammoniaque, balayages et retouches.', false, 'images/services/coloration.jpg'],
                ]],
            ['Onglerie', 'ongles', 'images/categories/onglerie.jpg',
                'Manucure, pédicure et nail art dans un espace dédié, hygiène irréprochable.', [
                    ['Manucure classique', 6000, null, 45, 'Mise en forme, cuticules, massage et vernis.', true, 'images/services/manucure.jpg'],
                    ['Pose de gel / capsules', 15000, 22000, 90, 'Pose complète avec French, couleur ou nail art.', true, 'images/services/pose-gel.jpg'],
                    ['Pédicure spa', 10000, null, 60, 'Bain de pieds, gommage, ponçage et vernis longue tenue.', true, 'images/services/pedicure.jpg'],
                    ['Nail art (par ongle)', 1000, 2500, 20, 'Décors personnalisés, strass, chrome, effets marbrés.', false, 'images/services/nail-art.jpg'],
                ]],
            ['Soins du visage', 'visage', 'images/categories/visage.jpg',
                'Nettoyage, éclat et hydratation : des protocoles doux adaptés aux peaux métissées.', [
                    ['Soin du visage éclat', 15000, null, 60, 'Nettoyage, gommage, masque et sérum vitaminé.', true, 'images/services/soin-visage.jpg'],
                    ['Soin anti-taches', 22000, null, 75, 'Protocole unifiant pour estomper les marques post-acné.', false, 'images/services/soin-visage.jpg'],
                    ['Nettoyage de peau profond', 18000, null, 60, 'Extraction douce, vapeur et masque purifiant.', false, 'images/services/soin-visage.jpg'],
                ]],
            ['Beauté du regard', 'regard', 'images/categories/regard.jpg',
                'Cils, sourcils et maquillage : le détail qui illumine tout le visage.', [
                    ['Pose de cils (volume russe)', 20000, 30000, 90, 'Extensions cil à cil, effet naturel ou volume.', true, 'images/services/cils.jpg'],
                    ['Restructuration des sourcils', 5000, null, 30, 'Épilation, teinture et mise en forme.', false, 'images/services/sourcils.jpg'],
                    ['Maquillage jour / soirée', 12000, 18000, 60, 'Teint sur mesure, regard travaillé, longue tenue.', true, 'images/services/maquillage.jpg'],
                    ['Mise en beauté mariée', 35000, 50000, 90, 'Essai, maquillage longue tenue et retouches le jour J.', false, 'images/services/maquillage-mariee.jpg'],
                ]],
            ['Spa & bien-être', 'spa', 'images/categories/spa.jpg',
                'Une parenthèse pour souffler : massages, gommages et rituels du corps.', [
                    ['Massage crânien relaxant', 8000, null, 30, 'Détente du cuir chevelu, huiles tièdes et pressions douces.', true, 'images/services/massage-cranien.jpg'],
                    ['Gommage & rituel corps', 20000, null, 75, 'Gommage au karité, enveloppement et modelage relaxant.', false, 'images/services/rituel-corps.jpg'],
                    ['Épilation visage', 4000, 8000, 30, 'Cire tiède ou fil, finition apaisante.', false, 'images/services/sourcils.jpg'],
                ]],
        ];

        foreach ($catalog as $ci => [$name, $icon, $image, $description, $services]) {
            $category = ServiceCategory::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'icon' => $icon,
                'image' => $image,
                'description' => $description,
                'position' => $ci,
            ]);

            foreach ($services as $si => [$sName, $price, $priceMax, $duration, $desc, $featured, $img]) {
                Service::create([
                    'service_category_id' => $category->id,
                    'name' => $sName,
                    'slug' => Str::slug($sName),
                    'description' => $desc,
                    'price' => $price,
                    'price_max' => $priceMax,
                    'duration' => $duration,
                    'image' => $img,
                    'is_featured' => $featured,
                    'is_active' => true,
                    'position' => $si,
                ]);
            }
        }
    }

    private function seedProducts(): void
    {
        $products = [
            ['Huile de ricin noire 100 ml', 'Soin', 6500, 24, 'Huile pressée à froid pour fortifier les longueurs et les sourcils.', true, 'images/products/huile-ricin.jpg'],
            ['Masque beurre de karité 300 ml', 'Soin', 8500, 18, 'Masque nourrissant pour cheveux crépus et bouclés.', true, 'images/products/masque-karite.jpg'],
            ['Leave-in hydratant 250 ml', 'Soin', 7000, 20, 'Crème sans rinçage pour une définition souple des boucles.', false, 'images/products/leave-in.jpg'],
            ['Shampooing doux sans sulfate', 'Soin', 6000, 30, 'Nettoie sans dessécher, parfait entre deux soins.', false, 'images/products/shampooing.jpg'],
            ['Duo hydratation intense', 'Soin', 13000, 12, 'Shampooing + après-shampooing pour cheveux assoiffés.', false, 'images/products/duo-hydratation.jpg'],
            ['Spray thermo-protecteur', 'Soin', 7500, 16, 'Protège la fibre avant le brushing ou le lissage.', false, 'images/products/spray-thermo.jpg'],
            ['Bonnet en satin', 'Accessoire', 3500, 40, 'Protège vos cheveux la nuit et limite la casse.', true, 'images/products/bonnet-satin.jpg'],
            ['Taie d’oreiller satin', 'Accessoire', 5500, 15, 'Le geste anti-frisottis pendant votre sommeil.', false, 'images/products/taie-satin.jpg'],
            ['Brosse démêlante souple', 'Accessoire', 4000, 25, 'Démêle sans arracher, même sur cheveux mouillés.', false, 'images/products/brosse.jpg'],
            ['Peigne à queue en bois', 'Accessoire', 2500, 35, 'Idéal pour les séparations nettes avant les tresses.', false, 'images/products/peigne.jpg'],
            ['Foulard en soie premium', 'Accessoire', 6000, 12, 'Foulard imprimé pour accessoiriser vos coiffures.', true, 'images/products/foulard.jpg'],
            ['Kit vernis semi-permanent', 'Onglerie', 12000, 8, 'Base, couleur et top coat longue tenue.', false, 'images/products/kit-vernis.jpg'],
            ['Huile pour cuticules', 'Onglerie', 3000, 22, 'Nourrit et assouplit le contour de l’ongle.', false, 'images/products/huile-cuticules.jpg'],
            ['Coffret découverte soins', 'Coffret', 20000, 6, 'Huile, masque et leave-in dans un joli coffret cadeau.', true, 'images/products/coffret.jpg'],
        ];

        foreach ($products as [$name, $category, $price, $stock, $description, $featured, $image]) {
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'category' => $category,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image' => $image,
                'is_featured' => $featured,
                'is_active' => true,
            ]);
        }
    }

    private function seedGallery(): void
    {
        $items = [
            ['Queue tressée lustrée', 'Tresses', 'images/gallery/queue-tressee.jpg', 'portrait', 'Tresses collées vers l’arrière, finition gel brillant.'],
            ['Box braids longueur taille', 'Tresses', 'images/gallery/box-braids.jpg', 'portrait', 'Braids fines posées en 4 heures, sans tension sur les tempes.'],
            ['Cornrows géométriques', 'Tresses', 'images/gallery/cornrows.jpg', 'paysage', 'Motif tracé sur mesure pour un mariage traditionnel.'],
            ['Tresses colorées', 'Tresses', 'images/gallery/tresses-couleur.jpg', 'paysage', 'Mèches bleu nuit intégrées à des tresses collées.'],
            ['Chignon de mariée', 'Coiffure', 'images/gallery/chignon-mariee.jpg', 'portrait', 'Chignon haut tressé, accessoirisé de perles dorées.'],
            ['Coloration cuivre', 'Coiffure', 'images/gallery/coloration.jpg', 'portrait', 'Repigmentation cuivrée sur cheveux naturels.'],
            ['Boucles définies', 'Soins', 'images/gallery/boucles.jpg', 'portrait', 'Routine hydratation + définition au diffuseur.'],
            ['Soin visage éclat', 'Beauté', 'images/gallery/soin-visage.jpg', 'paysage', 'Protocole éclat en cabine, peau repulpée.'],
            ['Mise en beauté', 'Beauté', 'images/gallery/mise-en-beaute.jpg', 'paysage', 'Teint lumineux et regard intense pour une soirée.'],
            ['Nail art floral', 'Onglerie', 'images/gallery/nail-art.jpg', 'paysage', 'Décors peints à la main sur base nude.'],
            ['Pose gel bleu nuit', 'Onglerie', 'images/gallery/pose-gel.jpg', 'paysage', 'Forme amande, French inversée pailletée.'],
            ['Manucure rouge signature', 'Onglerie', 'images/gallery/manucure-rouge.jpg', 'portrait', 'Rouge profond longue tenue, finition brillante.'],
        ];

        foreach ($items as $i => [$title, $category, $image, $format, $description]) {
            GalleryItem::create(compact('title', 'category', 'image', 'format', 'description') + [
                'position' => $i,
                'is_published' => true,
            ]);
        }
    }

    private function seedOffers(): void
    {
        $offers = [
            [
                'name' => 'Rituel Mariée',
                'tagline' => 'Le grand jour, de la tête aux ongles',
                'description' => 'Un accompagnement complet : essai coiffure, mise en beauté le jour J, manucure et soin du visage la veille.',
                'price' => 95000,
                'compare_price' => 125000,
                'duration' => 300,
                'includes' => "Essai coiffure + coiffure du jour J\nMise en beauté mariée (teint longue tenue)\nSoin du visage éclat la veille\nManucure ou pose gel\nRetouches sur place en fin de cérémonie",
                'image' => 'images/offers/mariee.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'Abonnement Cheveux Sains',
                'tagline' => 'Trois mois de suivi capillaire',
                'description' => 'Un rendez-vous soin par mois, avec diagnostic de départ et routine maison ajustée à chaque visite.',
                'price' => 45000,
                'compare_price' => 60000,
                'duration' => 60,
                'includes' => "Diagnostic capillaire complet\n3 soins profonds hydratants\n1 détox du cuir chevelu offerte\n-10 % sur la boutique pendant 3 mois\nRoutine maison personnalisée",
                'image' => 'images/offers/abonnement.jpg',
                'is_featured' => true,
            ],
            [
                'name' => 'Parenthèse Duo',
                'tagline' => 'À deux, mère-fille ou entre amies',
                'description' => 'Deux fauteuils côte à côte, thé offert : soin capillaire et beauté des mains pour chacune.',
                'price' => 38000,
                'compare_price' => 46000,
                'duration' => 120,
                'includes' => "2 soins profonds hydratants\n2 manucures classiques\nMassage crânien pour chacune\nThé et douceurs offerts",
                'image' => 'images/offers/duo.jpg',
                'is_featured' => false,
            ],
        ];

        foreach ($offers as $i => $offer) {
            Offer::create($offer + [
                'slug' => Str::slug($offer['name']),
                'is_active' => true,
                'position' => $i,
            ]);
        }
    }

    private function seedPosts(): void
    {
        $posts = [
            [
                'title' => 'Tresses : les 5 gestes qui évitent la casse',
                'category' => 'Tresses',
                'excerpt' => 'Des tresses bien posées protègent le cheveu. Mal serrées ou mal entretenues, elles fragilisent les tempes. Voici ce que nous conseillons à nos clientes.',
                'image' => 'images/journal/routine-tresses.jpg',
                'reading_time' => 4,
                'body' => "Les tresses sont une coiffure protectrice : elles mettent la fibre au repos pendant plusieurs semaines. Encore faut-il respecter quelques règles simples, avant, pendant et après la pose.\n\n## Avant la pose\nUn cheveu propre et hydraté tient mieux la tresse. Nous recommandons un shampooing doux et un soin profond 48h avant le rendez-vous, jamais le matin même : le cheveu a besoin de retrouver sa souplesse.\n\n## Pendant la pose\nLa tension est la première cause d'alopécie de traction. Une tresse ne doit jamais provoquer de picotements ni tirer sur les tempes. Si c'est le cas, dites-le immédiatement : nous reprenons la natte, c'est normal et cela évite des mois de repousse.\n\n## Au quotidien\n- Vaporisez un spray hydratant sur les longueurs deux fois par semaine\n- Massez le cuir chevelu avec une huile légère pour éviter les démangeaisons\n- Dormez avec un bonnet en satin pour limiter les frisottis\n- Évitez les queues de cheval trop serrées sur des tresses fraîches\n\n## La durée\nHuit semaines maximum. Au-delà, les repousses tirent sur la natte et les nœuds se forment à la racine. Mieux vaut reposer deux semaines entre deux séries de tresses.\n\n## Après la dépose\nDémêlez toujours sur cheveux humides et enduits d'après-shampooing, mèche par mèche, en partant des pointes. Enchaînez avec un soin profond : c'est le moment idéal pour refaire le plein d'hydratation.",
            ],
            [
                'title' => 'La routine hydratation en 4 étapes',
                'category' => 'Soins',
                'excerpt' => "Cheveux secs, pointes qui cassent, boucles qui ne se dessinent plus : neuf fois sur dix, c'est un problème d'hydratation, pas de nutrition.",
                'image' => 'images/journal/hydratation.jpg',
                'reading_time' => 3,
                'body' => "Le cheveu crépu ou bouclé a une structure en spirale : le sébum descend difficilement le long de la fibre. Résultat, les longueurs manquent d'eau bien avant de manquer de gras.\n\n## 1. L'eau d'abord\nToute routine commence par de l'eau : brume hydratante, spray maison ou simple vaporisateur. C'est la seule chose qui hydrate réellement.\n\n## 2. Une crème pour retenir\nUn leave-in appliqué sur cheveux humides emprisonne l'eau dans la fibre. Travaillez par petites sections, des pointes vers les racines.\n\n## 3. Une huile pour sceller\nQuelques gouttes d'huile végétale referment l'écaille et ralentissent l'évaporation. Ricin ou karité pour les cheveux épais, jojoba pour les cheveux fins.\n\n## 4. La protection de nuit\nBonnet ou taie en satin : sans cette étape, le coton absorbe en une nuit tout ce que vous avez appliqué.\n\nRépétez ce cycle deux à trois fois par semaine, et faites un soin profond en cabine une fois par mois. En six semaines, la différence au toucher est nette.",
            ],
            [
                'title' => 'Pourquoi le satin change tout la nuit',
                'category' => 'Conseils',
                'excerpt' => 'Un accessoire à 3 500 FCFA qui prolonge une coiffure de plusieurs semaines : le bonnet en satin est le meilleur rapport qualité-prix de la routine capillaire.',
                'image' => 'images/journal/nuit-satin.jpg',
                'reading_time' => 2,
                'body' => "Le coton est une fibre absorbante. Posé contre le cheveu pendant sept heures, il capte l'eau et les soins appliqués la veille, et sa surface irrégulière crée des frictions.\n\n## Ce que le satin change\nLa surface du satin est lisse : le cheveu glisse au lieu d'accrocher. Moins de frottements, donc moins de frisottis, moins de nœuds et une coiffure qui tient plus longtemps.\n\n## Bonnet ou taie d'oreiller ?\nLe bonnet protège mieux si vous bougez beaucoup la nuit. La taie est plus confortable et profite aussi à la peau du visage. L'idéal reste les deux, mais commencez par le bonnet.\n\n## Entretien\nLavez à la main à l'eau tiède, séchez à plat. Un bonnet propre évite les irritations du cuir chevelu, surtout après une pose de tresses.\n\nNos clientes qui adoptent le satin espacent en moyenne leurs rendez-vous coiffage d'une à deux semaines. Le calcul est vite fait.",
            ],
        ];

        foreach ($posts as $i => $post) {
            Post::create($post + [
                'slug' => Str::slug($post['title']),
                'is_published' => true,
                'published_at' => now()->subDays(($i + 1) * 9),
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        foreach ([
            ['Aminata T.', 'Box braids', 'Trois heures de douceur, aucune tension et un résultat impeccable. Je ne vais plus ailleurs.'],
            ['Chantal K.', 'Soin profond', "Mes cheveux ont retrouvé de la densité en deux séances. L'équipe prend vraiment le temps d'expliquer."],
            ['Mariam D.', 'Pédicure spa', "Un vrai moment de détente, l'hygiène est irréprochable et l'accueil chaleureux."],
            ['Estelle N.', 'Rituel mariée', 'Ils ont tenu compte de chaque détail pour mon mariage. Mes invités ont adoré.'],
            ['Rachelle B.', 'Manucure', "Prise de rendez-vous en ligne ultra simple, et jamais d'attente sur place."],
            ['Fanta S.', 'Coloration', 'Une couleur sur mesure, exactement la nuance que je voulais. Bravo !'],
        ] as [$author, $context, $content]) {
            Testimonial::create(compact('author', 'context', 'content') + ['rating' => 5]);
        }
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
