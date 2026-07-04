<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// ============================================================
// ADMIN CONTROLLERS
// ============================================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategorieController as AdminCategorieController;
use App\Http\Controllers\Admin\OeuvreController as AdminOeuvreController;
use App\Http\Controllers\Admin\CommandeController as AdminCommandeController;
use App\Http\Controllers\Admin\PaiementController as AdminPaiementController;
use App\Http\Controllers\Admin\AvisController as AdminAvisController;

// ============================================================
// ARTISAN CONTROLLERS
// ============================================================
use App\Http\Controllers\Artisan\DashboardController as ArtisanDashboardController;
use App\Http\Controllers\Artisan\OeuvreController as ArtisanOeuvreController;
use App\Http\Controllers\Artisan\CommandeController as ArtisanCommandeController;
use App\Http\Controllers\Artisan\PaiementController as ArtisanPaiementController;
use App\Http\Controllers\Artisan\AvisController as ArtisanAvisController;
use App\Http\Controllers\Artisan\StatistiqueController as ArtisanStatistiqueController;
use App\Http\Controllers\Artisan\ProfilController as ArtisanProfilController;

// ============================================================
// CLIENT CONTROLLERS
// ============================================================
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\CatalogueController as ClientCatalogueController;
use App\Http\Controllers\Client\PanierController as ClientPanierController;
use App\Http\Controllers\Client\CommandeController as ClientCommandeController;
use App\Http\Controllers\Client\PaiementController as ClientPaiementController;
use App\Http\Controllers\Client\PaiementsController as ClientPaiementsController;
use App\Http\Controllers\Client\FavorisController as ClientFavorisController;
use App\Http\Controllers\Client\AvisController as ClientAvisController;
use App\Http\Controllers\Client\NotificationsController as ClientNotificationsController;
use App\Http\Controllers\Client\CatalogueController;
use App\Http\Controllers\Client\PaiementController;
use App\Http\Controllers\Client\CommandeController;




// ============================================================
// PAGE D'ACCUEIL
// ============================================================
Route::get('/', [CatalogueController::class, 'home'])->name('welcome');

Route::get('/catalogue', [CatalogueController::class, 'index'])->name('client.catalogue');
Route::get('/oeuvre/{slug}', [CatalogueController::class, 'show'])->name('client.oeuvre.show');
Route::get('/search', [CatalogueController::class, 'search'])->name('client.search');
// ============================================================
// DASHBOARD (REDIRECTION SELON RÔLE)
// ============================================================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/status', [DashboardController::class, 'status'])
    ->middleware('auth')
    ->name('dashboard.status');

// ============================================================
// PROFIL (COMMUN A TOUS)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// ROUTES AUTHENTIFICATION (BREEZ)
// ============================================================
require __DIR__ . '/auth.php';

// ============================================================
// ROUTES ADMIN (SANS MIDDLEWARE role)
// ============================================================
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

       // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // ============================================================
        // UTILISATEURS
        // ============================================================
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/users/{id}/verify', [AdminUserController::class, 'verify'])->name('users.verify');
        Route::get('/users/export', [AdminUserController::class, 'export'])->name('users.export');

        // ============================================================
        // CATÉGORIES
        // ============================================================
        Route::get('/categories', [AdminCategorieController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [AdminCategorieController::class, 'create'])->name('categories.create');
        Route::post('/categories', [AdminCategorieController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}', [AdminCategorieController::class, 'show'])->name('categories.show');
        Route::get('/categories/{id}/edit', [AdminCategorieController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{id}', [AdminCategorieController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminCategorieController::class, 'destroy'])->name('categories.destroy');
        Route::get('/categories/{id}/toggle-active', [AdminCategorieController::class, 'toggleActive'])->name('categories.toggle-active');
        Route::get('/categories/export', [AdminCategorieController::class, 'export'])->name('categories.export');

        // ============================================================
        // ŒUVRES
        // ============================================================
        Route::get('/oeuvres', [AdminOeuvreController::class, 'index'])->name('oeuvres.index');
        Route::get('/oeuvres/{id}', [AdminOeuvreController::class, 'show'])->name('oeuvres.show');
        Route::get('/oeuvres/{id}/edit', [AdminOeuvreController::class, 'edit'])->name('oeuvres.edit'); 
        Route::put('/oeuvres/{id}', [AdminOeuvreController::class, 'update'])->name('oeuvres.update'); 
        Route::post('/oeuvres/{id}/status', [AdminOeuvreController::class, 'changeStatus'])->name('oeuvres.change-status');
        Route::get('/oeuvres/{id}/toggle-featured', [AdminOeuvreController::class, 'toggleFeatured'])->name('oeuvres.toggle-featured');
        Route::delete('/oeuvres/{id}', [AdminOeuvreController::class, 'destroy'])->name('oeuvres.destroy');

        // ============================================================
        // COMMANDES
        // ============================================================
        Route::get('/commandes', [AdminCommandeController::class, 'index'])->name('commandes.index');
        Route::get('/commandes/{id}', [AdminCommandeController::class, 'show'])->name('commandes.show');
        Route::post('/commandes/{id}/status', [AdminCommandeController::class, 'updateStatus'])->name('commandes.update-status');
        Route::delete('/commandes/{id}', [AdminCommandeController::class, 'destroy'])->name('commandes.destroy');
        Route::get('/commandes/export', [AdminCommandeController::class, 'export'])->name('commandes.export');

        // ============================================================
        // PAIEMENTS
        // ============================================================
        Route::get('/paiements', [AdminPaiementController::class, 'index'])->name('paiements.index');
        Route::get('/paiements/{id}', [AdminPaiementController::class, 'show'])->name('paiements.show');
        Route::post('/paiements/{id}/refund', [AdminPaiementController::class, 'refund'])->name('paiements.refund');

        // ============================================================
        // AVIS
        // ============================================================
        Route::get('/avis', [AdminAvisController::class, 'index'])->name('avis.index');
        Route::get('/avis/{id}/approve', [AdminAvisController::class, 'approve'])->name('avis.approve');
        Route::get('/avis/{id}/reject', [AdminAvisController::class, 'reject'])->name('avis.reject');
        Route::delete('/avis/{id}', [AdminAvisController::class, 'destroy'])->name('avis.destroy');
    });

// ============================================================
// ROUTES ARTISAN (SANS MIDDLEWARE role)
// ============================================================
Route::middleware(['auth', 'verified'])
    ->prefix('artisan')
    ->name('artisan.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [ArtisanDashboardController::class, 'index'])->name('dashboard');

        // Page d'attente
        Route::get('/waiting', function () {
            return view('artisan.waiting');
        })->name('waiting');

        // Œuvres
        Route::get('/oeuvres', [ArtisanOeuvreController::class, 'index'])->name('oeuvres.index');
        Route::get('/oeuvres/create', [ArtisanOeuvreController::class, 'create'])->name('oeuvres.create');
        Route::post('/oeuvres', [ArtisanOeuvreController::class, 'store'])->name('oeuvres.store');
        Route::get('/oeuvres/{id}', [ArtisanOeuvreController::class, 'show'])->name('oeuvres.show');
        Route::get('/oeuvres/{id}/edit', [ArtisanOeuvreController::class, 'edit'])->name('oeuvres.edit');
        Route::put('/oeuvres/{id}', [ArtisanOeuvreController::class, 'update'])->name('oeuvres.update');
        Route::delete('/oeuvres/{id}', [ArtisanOeuvreController::class, 'destroy'])->name('oeuvres.destroy');
        Route::delete('/oeuvres/{id}/images/{image}', [ArtisanOeuvreController::class, 'deleteImage'])->name('oeuvres.delete-image');
        Route::get('/oeuvres/{id}/images/{image}/set-principal', [ArtisanOeuvreController::class, 'setPrincipalImage'])->name('oeuvres.set-principal');
        Route::post('/oeuvres/{id}/status', [ArtisanOeuvreController::class, 'changeStatus'])->name('oeuvres.change-status');
        Route::get('/oeuvres/{id}/toggle-featured', [ArtisanOeuvreController::class, 'toggleFeatured'])->name('oeuvres.toggle-featured');
        Route::get('/oeuvres/{id}/duplicate', [ArtisanOeuvreController::class, 'duplicate'])->name('oeuvres.duplicate');
        Route::get('/oeuvres/export', [ArtisanOeuvreController::class, 'export'])->name('oeuvres.export');

          // Commandes
        Route::get('/commandes', [ArtisanCommandeController::class, 'index'])->name('commandes.index');
        Route::get('/commandes/{id}', [ArtisanCommandeController::class, 'show'])->name('commandes.show');
        Route::get('/commandes/{id}/edit', [ArtisanCommandeController::class, 'edit'])->name('commandes.edit'); // AJOUTER
        Route::put('/commandes/{id}', [ArtisanCommandeController::class, 'update'])->name('commandes.update'); // AJOUTER
        Route::post('/commandes/{id}/status', [ArtisanCommandeController::class, 'updateStatus'])->name('commandes.update-status');
        Route::delete('/commandes/{id}', [ArtisanCommandeController::class, 'destroy'])->name('commandes.destroy');
        Route::get('/commandes/en-attente', [ArtisanCommandeController::class, 'getEnAttente'])->name('commandes.en-attente');
        Route::get('/commandes/export', [ArtisanCommandeController::class, 'export'])->name('commandes.export');
    
         // Paiements
        Route::get('/paiements', [ArtisanPaiementController::class, 'index'])->name('paiements.index');
        Route::get('/paiements/{id}', [ArtisanPaiementController::class, 'show'])->name('paiements.show');
        Route::get('/paiements/export', [ArtisanPaiementController::class, 'export'])->name('paiements.export');

      
        // Statistiques
        Route::get('/statistiques', [ArtisanStatistiqueController::class, 'index'])->name('statistiques');

        // Profil
        Route::get('/profil', [ArtisanProfilController::class, 'index'])->name('profil.index');
        Route::get('/profil/edit', [ArtisanProfilController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [ArtisanProfilController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [ArtisanProfilController::class, 'updatePassword'])->name('profil.update-password');
        Route::delete('/profil', [ArtisanProfilController::class, 'destroy'])->name('profil.destroy');
    });

// ============================================================
// ROUTES CLIENT
// ============================================================
Route::middleware(['auth', 'verified'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        
      
        // ... le reste de tes routes
    
        // Filtres AJAX
        Route::get('/filter/categorie/{id}', [ClientCatalogueController::class, 'filterByCategorie'])->name('filter.categorie');
        Route::post('/filter/prix', [ClientCatalogueController::class, 'filterByPrix'])->name('filter.prix');

        // ===== PANIER =====
        Route::get('/panier', [ClientPanierController::class, 'index'])->name('panier.index');
        Route::post('/panier/add', [ClientPanierController::class, 'add'])->name('panier.add');
        Route::patch('/panier/{key}', [ClientPanierController::class, 'update'])->name('panier.update');
        Route::delete('/panier/{key}', [ClientPanierController::class, 'remove'])->name('panier.remove');
        Route::delete('/panier/clear', [ClientPanierController::class, 'clear'])->name('panier.clear');
        Route::get('/panier/count', [ClientPanierController::class, 'count'])->name('panier.count');

        // ===== COMMANDES =====
        Route::get('/commandes', [ClientCommandeController::class, 'index'])->name('commandes.index');
        Route::get('/commandes/{id}', [ClientCommandeController::class, 'show'])->name('commandes.show');
        Route::post('/commandes', [ClientCommandeController::class, 'store'])->name('commande.store');
        Route::get('/commandes/{id}/confirmation', [ClientCommandeController::class, 'confirmation'])->name('commande.confirmation');
        Route::post('/commandes/{id}/annuler', [ClientCommandeController::class, 'annuler'])->name('commande.annuler');
        Route::get('/commandes/export', [ClientCommandeController::class, 'export'])->name('commandes.export');
          // ROUTE POUR TÉLÉCHARGER LE REÇU (AJOUTÉE)
        Route::get('/commande/{id}/recu', [ClientCommandeController::class, 'telechargerRecu'])->name('commande.recu');

        // ===== PAIEMENT (SHWARY) =====
        Route::get('/paiement/checkout/{commande}', [ClientPaiementController::class, 'checkout'])->name('paiement.checkout');
        Route::post('/paiement/initiate', [ClientPaiementController::class, 'initiate'])->name('paiement.initiate');
        Route::post('/paiement/callback', [ClientPaiementController::class, 'callback'])->name('paiement.callback');
        Route::get('/paiement/confirmation/{commande}', [ClientPaiementController::class, 'confirmation'])->name('paiement.confirmation');
        Route::get('/paiement/status/{paiement}', [ClientPaiementController::class, 'status'])->name('paiement.status');

        // ===== HISTORIQUE PAIEMENTS =====
      //  Route::get('/paiements', [ClientPaiementsController::class, 'index'])->name('paiements.index');
       // Route::get('/paiements/{id}', [ClientPaiementsController::class, 'show'])->name('paiements.show');
       // Route::get('/paiements/export', [ClientPaiementsController::class, 'export'])->name('paiements.export');

        // ===== FAVORIS =====
        Route::get('/favoris', [ClientFavorisController::class, 'index'])->name('favoris.index');
        Route::post('/favoris/toggle/{oeuvre}', [ClientFavorisController::class, 'toggle'])->name('favoris.toggle');
        Route::delete('/favoris/{id}', [ClientFavorisController::class, 'destroy'])->name('favoris.destroy');
        Route::delete('/favoris/clear', [ClientFavorisController::class, 'clear'])->name('favoris.clear');
        Route::get('/favoris/check/{oeuvre}', [ClientFavorisController::class, 'check'])->name('favoris.check');

        // ===== AVIS =====
      //  Route::get('/avis', [ClientAvisController::class, 'index'])->name('avis.index');
        //Route::get('/avis/create', [ClientAvisController::class, 'create'])->name('avis.create');
        //Route::post('/avis', [ClientAvisController::class, 'store'])->name('avis.store');
        //Route::get('/avis/{id}', [ClientAvisController::class, 'show'])->name('avis.show');
        //Route::get('/avis/{id}/edit', [ClientAvisController::class, 'edit'])->name('avis.edit');
       // Route::put('/avis/{id}', [ClientAvisController::class, 'update'])->name('avis.update');
        //Route::delete('/avis/{id}', [ClientAvisController::class, 'destroy'])->name('avis.destroy');
        //Route::get('/avis/oeuvres/{commande}', [ClientAvisController::class, 'getOeuvresByCommande'])->name('avis.get-oeuvres');

        // ===== NOTIFICATIONS =====
        Route::get('/notifications', [ClientNotificationsController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{id}', [ClientNotificationsController::class, 'show'])->name('notifications.show');
        Route::post('/notifications/{id}/read', [ClientNotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [ClientNotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [ClientNotificationsController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/notifications/clear-read', [ClientNotificationsController::class, 'clearRead'])->name('notifications.clear-read');
        Route::get('/notifications/count', [ClientNotificationsController::class, 'countUnread'])->name('notifications.count');
        Route::get('/notifications/latest', [ClientNotificationsController::class, 'latest'])->name('notifications.latest');
    });