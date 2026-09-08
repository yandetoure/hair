<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\GalleryItem;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class SalonTest extends TestCase
{
    use RefreshDatabase;

    private function service(int $duration = 60): Service
    {
        $category = ServiceCategory::create(['name' => 'Coiffure', 'slug' => 'coiffure']);

        return Service::create([
            'service_category_id' => $category->id,
            'name' => 'Coupe & brushing',
            'slug' => 'coupe-brushing',
            'price' => 12000,
            'duration' => $duration,
            'is_active' => true,
        ]);
    }

    private function product(int $stock = 5): Product
    {
        return Product::create([
            'name' => 'Huile de ricin',
            'slug' => 'huile-de-ricin',
            'category' => 'Soin',
            'price' => 6500,
            'stock' => $stock,
            'is_active' => true,
        ]);
    }

    private function galleryItem(array $attributes = []): GalleryItem
    {
        return GalleryItem::create($attributes + [
            'title' => 'Cornrows géométriques',
            'category' => 'Tresses',
            'image' => 'images/gallery/cornrows.jpg',
            'format' => 'paysage',
            'is_published' => true,
        ]);
    }

    private function offer(array $attributes = []): Offer
    {
        return Offer::create($attributes + [
            'name' => 'Rituel Mariée',
            'slug' => 'rituel-mariee-'.Str::lower(Str::random(4)),
            'tagline' => 'Le grand jour',
            'price' => 95000,
            'includes' => "Coiffure\nManucure",
            'is_active' => true,
        ]);
    }

    private function article(array $attributes = []): Post
    {
        return Post::create($attributes + [
            'title' => 'La routine hydratation',
            'slug' => 'routine-hydratation-'.Str::lower(Str::random(4)),
            'category' => 'Soins',
            'excerpt' => 'Quatre étapes simples.',
            'body' => "Premier paragraphe.\n\n## Un sous-titre\n\n- Point un\n- Point deux",
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
    }

    private function admin(): User
    {
        return User::create([
            'name' => 'Gérante',
            'email' => 'admin@test.com',
            'password' => 'password',
            'is_admin' => true,
        ]);
    }

    /** Le prochain jour ouvré à 10h00. */
    private function nextOpenSlot(): Carbon
    {
        $date = Carbon::tomorrow();

        while (! (config('salon.hours')[$date->isoWeekday()] ?? null)) {
            $date->addDay();
        }

        return $date->setTime(10, 0);
    }

    public function test_les_pages_publiques_repondent(): void
    {
        $service = $this->service();
        $product = $this->product();
        $post = $this->article();
        $this->galleryItem();
        $this->offer();

        foreach ([
            '/', '/le-salon', '/contact', '/prestations', '/boutique', '/panier', '/rendez-vous',
            '/realisations', '/realisations?univers=Tresses', '/forfaits', '/journal',
            '/prestations/'.$service->slug, '/boutique/'.$product->slug, '/journal/'.$post->slug,
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_la_galerie_affiche_les_realisations_publiees(): void
    {
        $this->galleryItem(['title' => 'Box braids longues']);
        $this->galleryItem(['title' => 'Brouillon non publié', 'is_published' => false]);

        $this->get('/realisations')
            ->assertOk()
            ->assertSee('Box braids longues')
            ->assertDontSee('Brouillon non publié');
    }

    public function test_un_article_en_brouillon_est_introuvable(): void
    {
        $post = $this->article(['is_published' => false]);

        $this->get('/journal/'.$post->slug)->assertNotFound();
        $this->get('/journal')->assertOk()->assertDontSee($post->title);
    }

    public function test_le_forfait_calcule_l_economie_et_les_lignes(): void
    {
        $offer = $this->offer([
            'price' => 95000,
            'compare_price' => 125000,
            'includes' => "Coiffure\nManucure\nSoin du visage",
            'duration' => 300,
        ]);

        $this->assertSame(30000, $offer->saving);
        $this->assertCount(3, $offer->lines);
        $this->assertSame('5h', $offer->duration_label);

        $this->get('/forfaits')->assertOk()->assertSee('Manucure');
    }

    public function test_les_creneaux_disponibles_sont_calcules(): void
    {
        $service = $this->service();
        $date = $this->nextOpenSlot();

        $response = $this->getJson(route('booking.slots', [
            'service_id' => $service->id,
            'date' => $date->toDateString(),
        ]))->assertOk();

        $this->assertNotEmpty($response->json('slots'));
        $this->assertTrue(collect($response->json('slots'))->contains('time', '10:00'));
    }

    public function test_une_cliente_peut_reserver_un_rendez_vous(): void
    {
        $service = $this->service();
        $slot = $this->nextOpenSlot();

        $this->post(route('booking.store'), [
            'service_id' => $service->id,
            'date' => $slot->toDateString(),
            'time' => '10:00',
            'customer_name' => 'Awa Diallo',
            'customer_phone' => '+225 07 00 00 00 00',
        ])->assertRedirect();

        $appointment = Appointment::firstOrFail();

        $this->assertSame('Awa Diallo', $appointment->customer_name);
        $this->assertSame('pending', $appointment->status);
        $this->assertSame(12000, $appointment->price);
        $this->assertSame('11:00', $appointment->ends_at->format('H:i'));
    }

    public function test_un_creneau_complet_est_refuse(): void
    {
        config()->set('salon.capacity', 1);

        $service = $this->service();
        $slot = $this->nextOpenSlot();

        Appointment::create([
            'service_id' => $service->id,
            'customer_name' => 'Déjà réservé',
            'customer_phone' => '0102030405',
            'starts_at' => $slot,
            'ends_at' => $slot->copy()->addHour(),
            'price' => 12000,
            'status' => 'confirmed',
        ]);

        $this->post(route('booking.store'), [
            'service_id' => $service->id,
            'date' => $slot->toDateString(),
            'time' => '10:00',
            'customer_name' => 'Awa Diallo',
            'customer_phone' => '+225 07 00 00 00 00',
        ])->assertSessionHasErrors('time');

        $this->assertSame(1, Appointment::count());
    }

    public function test_une_commande_boutique_decremente_le_stock(): void
    {
        $product = $this->product(5);

        $this->post(route('cart.add', $product), ['quantity' => 2])->assertRedirect(route('cart.index'));

        $this->post(route('checkout.store'), [
            'customer_name' => 'Mariam Cissé',
            'customer_phone' => '0700000000',
            'delivery_mode' => 'delivery',
            'address' => 'Cocody, Abidjan',
        ])->assertRedirect();

        $order = Order::with('items')->firstOrFail();

        $this->assertSame(13000, $order->subtotal);
        $this->assertSame(2000, $order->delivery_fee);
        $this->assertSame(15000, $order->total);
        $this->assertSame(2, $order->items->sum('quantity'));
        $this->assertSame(3, $product->fresh()->stock);
    }

    public function test_l_espace_de_gestion_est_protege(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));

        $client = User::create(['name' => 'Cliente', 'email' => 'c@test.com', 'password' => 'password']);
        $this->actingAs($client)->get('/admin')->assertForbidden();
    }

    public function test_les_ecrans_de_gestion_repondent(): void
    {
        $service = $this->service();
        $product = $this->product();
        $slot = $this->nextOpenSlot();

        $appointment = Appointment::create([
            'service_id' => $service->id,
            'customer_name' => 'Awa Diallo',
            'customer_phone' => '0700000000',
            'starts_at' => $slot,
            'ends_at' => $slot->copy()->addHour(),
            'price' => 12000,
            'status' => 'confirmed',
        ]);

        $this->actingAs($this->admin());

        foreach ([
            '/admin',
            '/admin/agenda?vue=jour',
            '/admin/agenda?vue=semaine',
            '/admin/agenda?vue=mois',
            '/admin/appointments',
            '/admin/appointments/create',
            '/admin/appointments/'.$appointment->id.'/edit',
            '/admin/orders',
            '/admin/services',
            '/admin/services/create',
            '/admin/products',
            '/admin/products/'.$product->id.'/edit',
            '/admin/staff',
            '/admin/gallery',
            '/admin/gallery/create',
            '/admin/offers',
            '/admin/offers/create',
            '/admin/journal',
            '/admin/journal/create',
            '/admin/rapports?periode=mois',
            '/admin/rapports/impression?periode=semaine',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_le_rapport_est_exportable_en_csv(): void
    {
        $this->actingAs($this->admin());

        $response = $this->get('/admin/rapports/export?periode=mois')->assertOk();

        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('SYNTHÈSE', $response->streamedContent());
    }

    public function test_le_statut_d_un_rendez_vous_peut_etre_modifie(): void
    {
        $service = $this->service();
        $slot = $this->nextOpenSlot();

        $appointment = Appointment::create([
            'service_id' => $service->id,
            'customer_name' => 'Awa Diallo',
            'customer_phone' => '0700000000',
            'starts_at' => $slot,
            'ends_at' => $slot->copy()->addHour(),
            'price' => 12000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin())
            ->patch(route('admin.appointments.status', $appointment), ['status' => 'completed'])
            ->assertRedirect();

        $this->assertSame('completed', $appointment->fresh()->status);
    }
}
