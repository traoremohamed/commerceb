<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//--------------Route du application ------------------------------------------
Route::get('/', function () {return view('welcome');});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::match(['get', 'post'], '/login', [App\Http\Controllers\ConnexionController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/connexion', [App\Http\Controllers\ConnexionController::class, 'login'])->name('connexion');
Route::match(['get', 'post'], '/motdepasseoublie', [App\Http\Controllers\ConnexionController::class, 'motdepasseoublie'])->name('motdepasseoublie');
Route::get('/dashboard', [App\Http\Controllers\ConnexionController::class, 'dashboard'])->name('dashboard');
Route::get('/reload-captcha', [App\Http\Controllers\ConnexionController::class, 'reloadCaptcha'])->name('reloadCaptcha');

//--------------Route du parametrage systeme------------------------------------------
Route::group(['middleware' => ['auth']], function () {
    Route::resources([
        'roles' => App\Http\Controllers\RoleController::class,
        'users' => App\Http\Controllers\UserController::class,
        'permissions' => App\Http\Controllers\PermissionController::class,
        'menus' => App\Http\Controllers\MenuController::class,
        'sousmenus' => App\Http\Controllers\SousmenuController::class,
    ]);
    Route::match(['get', 'post'], '/menuprofil', [App\Http\Controllers\GenerermenuController::class, 'parametragemenu'])->name('menuprofil');
    Route::match(['get', 'post'], '/compteclient/{id}', [App\Http\Controllers\UserController::class, 'compteclient'])->name('compteclient');
    Route::match(['get', 'post'], '/profil', [App\Http\Controllers\HomeController::class, 'profil'])->name('profil');
    Route::match(['get', 'post'], '/modifiermotdepasse', [App\Http\Controllers\HomeController::class, 'updatepassword'])->name('modifier.mot.passe');
    Route::match(['get', 'post'], '/generermenu', [App\Http\Controllers\GenerermenuController::class, 'index'])->name('generermenu');
    Route::match(['get', 'post'], '/menuprofillayout/{id}', [App\Http\Controllers\GenerermenuController::class, 'menuprofillayout'])->name('menuprofillayout');
    //--------------Route du parametrage ------------------------------------------
    Route::resource('fournisseur', \App\Http\Controllers\FournisseurController::class);
    Route::resource('famille', \App\Http\Controllers\FamilleController::class);
    Route::resource('typeclient', \App\Http\Controllers\TypeClientController::class);
    Route::resource('agence', \App\Http\Controllers\AgenceController::class);
    Route::resource('client', \App\Http\Controllers\ClientController::class);
    Route::resource('produit', \App\Http\Controllers\ProduitController::class);
    Route::resource('sousfamille', \App\Http\Controllers\SousFamilleController::class);
    //--------------Route du  module achat commande------------------------------------------
    Route::match(['get', 'post'], '/comfour', [App\Http\Controllers\ComFourController::class, 'index'])->name('comfour');
    Route::match(['get', 'post'], '/comfour/create', [App\Http\Controllers\ComFourController::class, 'create'])->name('comfouradd');
    Route::match(['get', 'post'], '/comfour/edit/{id}', [App\Http\Controllers\ComFourController::class, 'edit'])->name('comfouredit');
    Route::match(['get', 'post'], '/comfour/delete/{id}', [App\Http\Controllers\ComFourController::class, 'delete'])->name('comfourdelete');
    Route::match(['get'], '/comfour/etatcomfour/{id}', [App\Http\Controllers\ComFourController::class, 'etat'])->name('etatcomfour');

    //--------------Route du  module achat reception------------------------------------------
    Route::match(['get', 'post'], '/receptionfour', [App\Http\Controllers\ReceptionfourController::class, 'index'])->name('receptionfour');
    Route::match(['get', 'post'], '/receptionfour/edit/{id}', [App\Http\Controllers\ReceptionfourController  ::class, 'edit'])->name('receptionfouredit');
    Route::match(['get', 'post'], '/receptionfour/etat/{id}', [App\Http\Controllers\ReceptionfourController  ::class, 'etat'])->name('receptionfouretat');
    Route::match(['get', 'post'], '/receptionfour/delete/{id}', [App\Http\Controllers\ComFourController::class, 'delete'])->name('receptionfourdelete');
//--------------Route du  module stock------------------------------------------
    Route::match(['get', 'post'], '/stockproduit', [App\Http\Controllers\StockController::class, 'index'])->name('stockproduit');
    Route::match(['get', 'post'], '/export', [App\Http\Controllers\StockController::class, 'export'])->name('export');
//--------------Route du  module vente commande client---------------------------------------
    Route::match(['get', 'post'], '/comclient', [App\Http\Controllers\ComclientController::class, 'index'])->name('comclient');
    Route::match(['get', 'post'], '/comclient/create', [App\Http\Controllers\ComclientController::class, 'create'])->name('comclientadd');
    Route::match(['get', 'post'], '/comclient/edit/{id}', [App\Http\Controllers\ComclientController::class, 'edit'])->name('comclientedit');
    Route::match(['get', 'post'], '/comclient/delete/{id}', [App\Http\Controllers\ComclientController::class, 'delete'])->name('comclientdelete');
    Route::match(['get'], '/comclient/etatcomcli/{id}', [App\Http\Controllers\ComclientController::class, 'etat'])->name('etatcomcli');
//--------------Route du  module vente livraison------------------------------------------
    Route::match(['get', 'post'], '/bonlivraison', [App\Http\Controllers\BonlivraisonController::class, 'index'])->name('bonlivraison');
    Route::match(['get', 'post'], '/bonlivraison/edit/{id}', [App\Http\Controllers\BonlivraisonController  ::class, 'edit'])->name('bonlivraisonedit');
    Route::match(['get', 'post'], '/bonlivraison/delete/{id}', [App\Http\Controllers\BonlivraisonController::class, 'delete'])->name('bonlivraisondelete');
    Route::match(['get', 'post'], '/bonlivraison/etatblcli/{id}', [App\Http\Controllers\BonlivraisonController::class, 'etat'])->name('etatblcli');
//--------------Route du  module vente facturation------------------------------------------
    Route::match(['get', 'post'], '/facture', [App\Http\Controllers\FactureController::class, 'index'])->name('facture');
    Route::match(['get', 'post'], '/facture/edit/{id}', [App\Http\Controllers\FactureController  ::class, 'edit'])->name('factureedit');
    Route::match(['get'], '/facture/etatfacture/{id}', [App\Http\Controllers\FactureController::class, 'etat'])->name('etatfacture');

//--------------Route du  module vente facturation------------------------------------------
    Route::match(['get', 'post'], '/rayon', [App\Http\Controllers\RayonController::class, 'rayon'])->name('rayon');
    Route::match(['get', 'post'], '/creerrayon', [App\Http\Controllers\RayonController::class, 'creerrayon'])->name('creerrayon');
    Route::match(['get', 'post'], '/modifierrayon/{id}', [App\Http\Controllers\RayonController::class, 'modifierrayon'])->name('modifierrayon');

    //--------------Route du  module vente facturation------------------------------------------
    Route::match(['get', 'post'], '/sousrayon', [App\Http\Controllers\SousRayonController::class, 'sousrayon'])->name('sousrayon');
    Route::match(['get', 'post'], '/creersousrayon', [App\Http\Controllers\SousRayonController::class, 'creersousrayon'])->name('creersousrayon');
    Route::match(['get', 'post'], '/modifiersousrayon/{id}', [App\Http\Controllers\SousRayonController::class, 'modifiersousrayon'])->name('modifiersousrayon');

//--------------Route du  module vente caisse------------------------------------------
    Route::match(['get', 'post'], '/ventecaisse', [App\Http\Controllers\CaisseController::class, 'ventecaisse'])->name('ventecaisse');
    Route::match(['get', 'post'], '/ventecaisse/edit/{id}', [App\Http\Controllers\CaisseController::class, 'ventecaisse'])->name('ventecaissedirect');
    Route::match(['get', 'post'], '/ventecaisse/edit/{id}/{id1}', [App\Http\Controllers\CaisseController::class, 'ventecaisse'])->name('ventecaissedirectfact');
    Route::match(['get'], '/ventecaisse/recu/{id}', [App\Http\Controllers\CaisseController::class, 'etat'])->name('recucaisse');
//-------------route parametrage logo et nom de l'application------------------------------
    Route::match(['get', 'post'], '/logo', [App\Http\Controllers\ParametreController::class, 'logo'])->name('logo');
    Route::match(['get', 'post'], '/creerlogo', [App\Http\Controllers\ParametreController::class, 'creerlogo'])->name('creerlogo');
    Route::match(['get', 'post'], '/modifierlogo/{id}', [App\Http\Controllers\ParametreController::class, 'modifierlogo'])->name('modifierlogo');
    Route::match(['get', 'post'], '/activelogo/{id}/{id1}', [App\Http\Controllers\ParametreController::class, 'activelogo'])->name('activelogo');
    Route::match(['get', 'post'], '/desactivelogo/{id}/{id1}', [App\Http\Controllers\ParametreController::class, 'desactivelogo'])->name('desactivelogo');

//--------------route statistique ------------------
    Route::match(['get', 'post'], '/mouvementstock', [App\Http\Controllers\TableaudebordController::class, 'mouvementstock'])->name('mouvementstock');
    Route::match(['get', 'post'], '/historiqueventedirecte', [App\Http\Controllers\TableaudebordController::class, 'historiqueventedirecte'])->name('historiqueventedirecte');
    Route::match(['get', 'post'], '/historiquecreanceclient', [App\Http\Controllers\TableaudebordController::class, 'historiquecreanceclient'])->name('historiquecreanceclient');
    Route::match(['get', 'post'], '/historiqueventeindirecte', [App\Http\Controllers\TableaudebordController::class, 'historiqueventeindirecte'])->name('historiqueventeindirecte');
    Route::match(['get', 'post'], '/historiquereglement', [App\Http\Controllers\TableaudebordController::class, 'historiquereglement'])->name('historiquereglement');
    Route::match(['get', 'post'], '/historiquebonreception', [App\Http\Controllers\TableaudebordController::class, 'historiquebonreception'])->name('historiquebonreception');
    Route::match(['get', 'post'], '/historiqueproformat', [App\Http\Controllers\TableaudebordController::class, 'historiqueproformat'])->name('historiqueproformat');
    Route::match(['get', 'post'], '/historiquebonlivraison', [App\Http\Controllers\TableaudebordController::class, 'historiquebonlivraison'])->name('historiquebonlivraison');

//-------------- route statistique apercu

    Route::match(['get', 'post'], '/apercuemouvementstock/{id}/{id1}/{id2}', [App\Http\Controllers\TableaudebordController::class, 'apercuemouvementstock'])->name('apercuemouvementstock');
    Route::match(['get', 'post'], '/apercuehistoriqueventedirecte/{id}/{id1}', [App\Http\Controllers\TableaudebordController::class, 'apercuehistoriqueventedirecte'])->name('apercuehistoriqueventedirecte');
    Route::match(['get', 'post'], '/apercuehistoriquecreanceclientt/{id}/{id1}/{id2}', [App\Http\Controllers\TableaudebordController::class, 'apercuehistoriquecreanceclientt'])->name('apercuehistoriquecreanceclientt');
    Route::match(['get', 'post'], '/apercuehistoriquereglement/{id}/{id1}/{id2}/{id3}', [App\Http\Controllers\TableaudebordController::class, 'apercuehistoriquereglement'])->name('apercuehistoriquereglement');
    Route::match(['get', 'post'], '/apercuehistoriquebonreception/{id}/{id1}/{id2}/{id3}', [App\Http\Controllers\TableaudebordController::class, 'apercuehistoriquebonreception'])->name('apercuehistoriquebonreception');
    Route::match(['get', 'post'], '/apercuehistoriqueproformat/{id}/{id1}/{id2}/{id3}', [App\Http\Controllers\TableaudebordController::class, 'apercuehistoriqueproformat'])->name('apercuehistoriqueproformat');
    Route::match(['get', 'post'], '/apercuehistoriquebonlivraison/{id}/{id1}/{id2}/{id3}', [App\Http\Controllers\TableaudebordController::class, 'apercuehistoriquebonlivraison'])->name('apercuehistoriquebonlivraison');
    Route::match(['get', 'post'], '/apercuehistoriqueventeindirecte/{id}/{id1}/{id2}/{id3}', [App\Http\Controllers\TableaudebordController::class, 'apercuehistoriqueventeindirecte'])->name('apercuehistoriqueventeindirecte');
    Route::match(['get', 'post'], '/detailbonreception/{id}', [App\Http\Controllers\TableaudebordController::class, 'detailbonreception'])->name('detailbonreception');
    Route::match(['get', 'post'], '/detailbonlivraison/{id}', [App\Http\Controllers\TableaudebordController::class, 'detailbonlivraison'])->name('detailbonlivraison');
    Route::match(['get', 'post'], '/detailprofromat/{id}', [App\Http\Controllers\TableaudebordController::class, 'detailprofromat'])->name('detailprofromat');


});

Route::get('/deconnexion', [App\Http\Controllers\HomeController::class, 'deconnexion']);


