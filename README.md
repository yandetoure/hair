# Maison Éclat — Site vitrine & gestion de salon de coiffure

Application Laravel 13 complète pour un salon de coiffure & beauté :

- **Côté client** : présentation du salon, carte des prestations avec tarifs, réservation de rendez-vous
  en ligne (coiffure, tresses, soins, manucure, pédicure…), boutique de produits et accessoires avec panier
  et commande.
- **Côté salon** : tableau de bord chiffré, agenda jour / semaine / mois, gestion des rendez-vous et des
  commandes, catalogue des prestations et des produits, équipe, rapports avec export CSV et impression PDF.

---

## Démarrage

```bash
composer install && npm install
```

```bash
php artisan migrate:fresh --seed && npm run build
```

```bash
php artisan serve
```

Le site est alors disponible sur http://localhost:8000 et l'espace de gestion sur http://localhost:8000/admin.

En développement, lancez plutôt Vite en parallèle pour le rechargement à chaud :

```bash
npm run dev
```

### Compte de démonstration

| Rôle | Identifiant | Mot de passe |
|------|-------------|--------------|
| Gérante (accès complet) | `admin@maison-eclat.com` | `password` |

Le seeder crée aussi ~90 jours d'activité fictive (rendez-vous et commandes) pour que le tableau de bord
et les rapports soient immédiatement parlants. Pour repartir d'une base vide : `php artisan migrate:fresh`.

---

## Personnaliser le salon

Tout ce qui identifie le salon est regroupé dans [`config/salon.php`](config/salon.php) :

| Clé | Rôle |
|-----|------|
| `name`, `tagline`, `baseline` | Nom et accroches affichés sur le site |
| `phone`, `whatsapp`, `email`, `address` | Coordonnées (pied de page, contact, bouton WhatsApp) |
| `currency` | Devise affichée (FCFA par défaut) |
| `hours` | Horaires d'ouverture par jour (1 = lundi … 7 = dimanche, `null` = fermé) |
| `slot_interval` | Pas des créneaux de réservation, en minutes |
| `min_notice_hours` | Délai minimum entre la réservation et le rendez-vous |
| `max_days_ahead` | Horizon de réservation |
| `capacity` | Nombre de rendez-vous simultanés (postes de travail) |
| `delivery_fee`, `free_delivery_from` | Livraison boutique |

Les horaires et la capacité alimentent directement le moteur de créneaux : un créneau est proposé
uniquement si le salon est ouvert, si la prestation se termine avant la fermeture et si moins de
`capacity` rendez-vous se chevauchent.

Le nom de l'application et le fuseau horaire se règlent dans `.env` (`APP_NAME`, `APP_TIMEZONE`).

---

## Parcours client

| Page | Route | Contenu |
|------|-------|---------|
| Accueil | `/` | Héro, univers de soins, prestations phares, présentation, boutique, équipe, avis |
| Le salon | `/le-salon` | Histoire, approche en 4 étapes, équipe, témoignages |
| Prestations & tarifs | `/prestations` | Carte complète par univers, durée et tarif de chaque prestation |
| Fiche prestation | `/prestations/{slug}` | Détail, tarif, durée, réservation directe |
| Boutique | `/boutique` | Filtres par catégorie, recherche, ajout au panier |
| Panier / commande | `/panier`, `/commande` | Retrait au salon ou livraison, frais calculés automatiquement |
| Rendez-vous | `/rendez-vous` | Choix de la prestation, du jour, du créneau, coordonnées |
| Contact | `/contact` | Adresse, horaires, téléphone, WhatsApp |

Aucun paiement en ligne : les rendez-vous et commandes arrivent au statut « en attente », le salon
confirme ensuite par téléphone. Chaque commande décrémente le stock du produit.

## Espace de gestion (`/admin`)

| Écran | Ce qu'il apporte |
|-------|------------------|
| **Tableau de bord** | KPI du jour, de la semaine et du mois (rendez-vous, CA prestations, CA boutique, ticket moyen, occupation), graphique 14 jours, agenda du jour, commandes à traiter, top prestations/produits, alertes de stock |
| **Agenda** | Vue **jour** (déroulé horaire avec changement de statut), **semaine** (7 colonnes avec CA par jour), **mois** (calendrier avec CA quotidien) |
| **Rendez-vous** | Recherche, filtres statut et période, création manuelle, modification, changement de statut, suppression |
| **Commandes** | Liste filtrable, détail article par article, suivi du statut (à traiter → confirmée → prête → livrée) |
| **Prestations** | CRUD complet : catégorie, tarif (ou fourchette), durée, photo, mise en avant, visibilité |
| **Boutique** | CRUD produits : prix, stock, photo, mise en avant, visibilité |
| **Équipe** | Fiches des praticiennes affichées sur le site et proposées à la réservation |
| **Rapports** | Période au choix (jour, semaine, mois, trimestre, année ou dates personnalisées), comparaison avec la période précédente, évolution du CA, heures de pointe, top prestations/produits, détail journalier — **export CSV** et **version imprimable / PDF** |

Une prestation ou un produit déjà lié à un rendez-vous ou une commande est désactivé plutôt que
supprimé, afin de préserver l'historique et les rapports.

---

## Architecture

```
app/
├── Http/Controllers/          Site public (accueil, prestations, boutique, panier, réservation)
│   ├── Admin/                 Tableau de bord, agenda, RDV, commandes, catalogue, rapports
│   └── Auth/LoginController   Connexion de l'équipe
├── Http/Middleware/EnsureUserIsAdmin.php
├── Models/                    Service, ServiceCategory, Product, Appointment, Order, OrderItem, StaffMember…
├── Services/
│   ├── AvailabilityService    Calcul des créneaux disponibles
│   ├── Cart                   Panier en session
│   └── ReportService          KPI, séries temporelles, tops, taux d'occupation
└── Support/helpers.php        fcfa(), fr_date()

resources/views/
├── layouts/{public,admin}     Les deux gabarits
├── components/                Cartes prestation/produit, icônes SVG, stats et graphiques admin
├── public/                    Pages vitrine
├── admin/                     Écrans de gestion
└── auth/login.blade.php
```

Le design (palette crème/espresso/or, typographies Cormorant Garamond + Jost) est défini dans
[`resources/css/app.css`](resources/css/app.css) via les variables de thème Tailwind 4 ; les polices sont
auto-hébergées au build, aucune dépendance externe à l'exécution.

Les photos sont optionnelles : sans image, les cartes affichent un dégradé décoratif. Les visuels
téléversés depuis l'admin sont stockés dans `storage/app/public` (lien créé par `php artisan storage:link`).

---

## Tests

```bash
php artisan test
```

Couvre : affichage des pages publiques, calcul des créneaux, réservation d'un rendez-vous, refus d'un
créneau complet, commande boutique avec décrément du stock, protection de l'espace de gestion, affichage
de tous les écrans admin, export CSV et changement de statut.

---

## Passer en production

1. `.env` : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` sur le vrai domaine.
2. Base de données : SQLite convient pour un salon ; pour MySQL, renseignez `DB_*` puis `php artisan migrate --force`.
3. `npm run build`, `php artisan storage:link`, puis `php artisan optimize` (cache config, routes et vues).
4. Créer le vrai compte gérante puis supprimer le compte de démonstration.
5. Remplacer les textes de présentation et ajouter les photos du salon depuis l'espace de gestion.
