<?php

use App\HargaProdukGroup;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\HargaProdukGroupController;
use App\Http\Controllers\HargaProdukUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ThesisGroupUserController;
use App\Http\Controllers\ThesisItemController;
use App\Http\Controllers\ThesisOrderController;
use App\Http\Controllers\ThesisUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
Auth::routes();
// Route::get('/page/{blade}', function ($blade) {
//     return view($blade);
// });

// Route::get('/', [CatalogueController::class, 'index']);
Route::get('/', 'CatalogueController@index');

Route::post('/search', [CatalogueController::class, 'search']);
Route::get('/filter', [CatalogueController::class, 'filter']);

Route::get('/index-tabel', [CatalogueController::class, 'tabel']);
Route::get('/not-found', [CatalogueController::class, 'notFound']);
Route::get('/product/{id}', [CatalogueController::class, 'show']);
Route::get('/tracking', [TrackingController::class, 'client']);

// ------------------faris------------------
Route::get('/detail/product/{barang_alias}', [CatalogueController::class, 'detailTable']);
Route::post('/detail/produk','CatalogueController@get_detailBarang')->name('get_detailBarang');
Route::post('/detail', [CatalogueController::class, 'detail']);
Route::post('delete_cart','cartController@delete_cart')->name('delete_cart');
Route::post('/cart','cartController@cartProcess')->name('addCart');
Route::post('cartData','cartController@cartData')->name('cartData');
Route::post('json_cartAll','cartController@json_cartAll')->name('json_cartAll');
Route::get('count_harganull','notifHandlerController@count_harganull')->name('count_harganull');
// --------------------------------------------
// IKI CUMA GE RETURN DATA JSON TULUNG LAH BEN RAPI NGONO BEN PENAK APAL"ANE
Route::prefix('data')->group(function () {

    Route::get('user', [UserController::class, 'authGetter'])->name('authGetter');
    Route::post('/create/group', [UserController::class, 'createGroup'])->name('createGroup');
    Route::post('/create/user', [UserController::class, 'createUser'])->name('createUser');
    Route::get('/delete/group/{id}', [UserController::class, 'destroyGroup']);
    Route::get('/delete/user/{id}', [UserController::class, 'destroyUser']);
    Route::post('/edit/group', [UserController::class, 'editGroup'])->name('editGroup');
    Route::post('/edit/user', [UserController::class, 'editUser'])->name('editUser');

    Route::get('user-group', [UserController::class, 'allGroup'])->name('allGroup');
    Route::post('user', [HargaProdukGroupController::class, 'store'])->name('storeGroupHarga');
    Route::post('user/harga/all', [HargaProdukUserController::class, 'store'])->name('storeUserHarga');
    Route::post('user/harga/deleteAll', [HargaProdukUserController::class, 'deleteByGroup'])->name('deleteAllUserHarga');
    Route::get('user/harga/byProduk/{produkId}', [HargaProdukUserController::class, 'usersWithPrices'])->name('usersWithPrices');
    Route::get('user/harga/byProduk/groupPriceSelection/{productId}', [HargaProdukUserController::class, 'groupPriceSelection'])->name('groupPriceSelection');
    Route::post('user/harga/byProduk/changeUserPrice', [HargaProdukUserController::class, 'changeUserPrice'])->name('changeUserPrice');
    // Route::delete('/user/{id}/delete', 'UserController@destroyUser')->name('userDelete');
    // Route::post('/user/create/group', [UserController::class, 'createGroup'])->name('createGroup');
    // Route::post('/user/create/user', [UserController::class, 'createUser'])->name('createUser');
    // Route::delete('/delete/group', [UserController::class, 'destroyGroup'])->name('deleteGroup');

    //setting harga user
    Route::post('setting_harga/user/setHargaUser', 'HargaUserController@setHarga')->name('setHargaUser');
    Route::get('setting_harga/user/hargaUser/{userId}/{groupId}', 'HargaUserController@hargaUser')->name('hargaUser');
    Route::get('setting_harga/user/pilihHarga/{userId}/{groupId}', 'HargaUserController@pilihHarga')->name('pilihHarga');
    Route::post('setting_harga/user/harga', 'HargaUserController@hargaCheckbox')->name('hargaCheckbox');
    Route::post('setting_harga/user/hargaAll', 'HargaUserController@saveAll')->name('saveAllHargaUser');

    Route::get('catalogue/products/detail/{barang_alias}', [CatalogueController::class, 'tableDetail']);
    Route::get('catalogue/products', [CatalogueController::class, 'catalogue']);
    Route::get('catalogue/harga-group', [CatalogueController::class, 'hargaGroup'])->name('hargaGroup');

    Route::get('/purchase-order/new', 'PurchaseOrderController@newPurchaseOrder');
    Route::get('/purchase-order/pending', 'PurchaseOrderController@pendingOrder');
    Route::get('/purchase-order/perintah-kirim', 'PurchaseOrderController@sentOrder');
    Route::get('purchase-order/info-gudang/{order_id}/{tagihan_id}', 'PurchaseOrderController@infoGudang');
    Route::get('purchase-order/pilih-gudang', 'PurchaseOrderController@pilihGudang');
    Route::get('/purchase-order/proses', 'PurchaseOrderController@getPesananProses');
    Route::get('/purchase-order/riwayat', 'PurchaseOrderController@riwayat');
    Route::get('/purchase-order/pesanan-selesai', 'PurchaseOrderController@pesananSelesai');
    Route::get('/purchase-order/order/pesanan-selesai', 'PurchaseOrderController@selesaiPesanan');
    Route::post('purchase-order/loadData_po','PurchaseOrderController@loadData_po')->name('loadData_po');

    Route::post('/purchase-order/kirim', 'PurchaseOrderController@sentPesanan');
    Route::get('/purchase-order/tagihan', 'PurchaseOrderController@dataTagihan');
    Route::post('/purchase-order/detailTagihan', 'PurchaseOrderController@detailTagihan');
    Route::get('/purchase-order/approval-bayar', 'PurchaseOrderController@approval');

    Route::get('/purchase-order/select_gudang', 'PurchaseOrderController@select_gudang');

    Route::get('/order/histori-return', [OrderController::class, 'returnHistori']);
    Route::get('/order/{id_user}/prosses', [OrderController::class, 'proses']);
    Route::get('/order/{id_user}/pesanan-proses', [OrderController::class, 'pesananProses']);
    Route::get('/order/{id_user}/pesanan-kirim', [OrderController::class, 'pesananKirim']);
    Route::get('/order/{id_user}/tertunda', [OrderController::class, 'tertunda']);
    Route::get('/order/{id_user}/return', [OrderController::class, 'return']);
    Route::get('/order/{id_user}/pesanan-selesai', [OrderController::class, 'pesananSelesai']);
    Route::get('/order/pesanan-diterima/{id_tagihan}', [OrderController::class, 'pesananDiterima']);
    // Route::get('/order/histori-return', [OrderController::class, 'Hreturn']);
    Route::get('/order/riwayat', [OrderController::class, 'dataRiwayat']);
    Route::get('/order/pesanan-selesai', [OrderController::class, 'dataSelesai']);
    Route::post('/order/return', [OrderController::class, 'storeReturn']);
    Route::post('/order/pickup', [OrderController::class, 'storePickup'])->name('storePickup');
    Route::post('/order/arrive', [OrderController::class, 'storeArrive'])->name('storeArrive');
    Route::post('/order/confirm', [OrderController::class, 'storeConfirm'])->name('storeConfirm');

    //menu
    Route::post('/setting/menu/create', 'MenuController@create');
    Route::get('/setting/menu/delete/{id}', 'MenuController@destroy');
    Route::post('/setting/menu/edit', 'MenuController@edit')->name('editMenu');

    //return
    Route::get('/return/data', 'ReturnController@getData')->name('return_barang');

    Route::get('/drivers', [DriverController::class, 'index']);
    Route::get('/tracking', 'TrackingController@data');

    // thesis
    Route::get('/getter', 'ThesisController@getter');
    Route::get('/rabin/{n}/{input}', 'ThesisController@rabinKarp');
});

Route::prefix('analytics')->group(function()
{
    Route::get('/{string}', 'ThesisController@analytics');
    Route::get('/preprocessing/{string}', 'ThesisController@preprocessing');
    Route::post('/table', 'ThesisController@table');
    Route::post('/pre-casefolding', 'ThesisController@pre_casefolding');
    Route::post('/pre-punctuation', 'ThesisController@pre_punctuation');
    Route::post('/rabin-kgram', 'ThesisController@rabin_kgram');
    Route::post('/rabin-hashing', 'ThesisController@rabin_hashing');
    Route::post('/rabin-intersect', 'ThesisController@rabin_intersect');
    Route::post('/similarity', 'ThesisController@similarity');
});

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function ()
{
    Route::get('table-users', [ThesisUserController::class, 'tableUser']);
    Route::post('delete-user', [ThesisUserController::class, 'destroy'])->name('deleteUser');
    Route::get('get-group-user/{id}', [ThesisGroupUserController::class, 'show']);
    Route::post('set-group-user', [ThesisGroupUserController::class, 'update'])->name('setGroupUser');
    Route::get('listItem', [ThesisItemController::class, 'listData'])->name('data_barang');
    Route::get('items', [ThesisItemController::class, 'index']);
    Route::get('barang_list_harga/{id}', [ThesisItemController::class, 'list_harga']);
    Route::get('stock_table', [ThesisItemController::class, 'stockTable'])->name('stock_table');
    Route::get('all_item_stock', [ThesisItemController::class, 'allItemStock'])->name('all_item_stock');
    Route::post('import_stock', [ThesisItemController::class, 'importStock'])->name('import_stock');
    Route::get('export_barang', [ThesisItemController::class, 'export_excel'])->name('export_excel_item');
    Route::post('import_barang', [ThesisItemController::class, 'import_excel'])->name('import_excel_item');
    Route::get('export_stock', [ThesisItemController::class, 'exportStock'])->name('export_stock');
    Route::post('truncate_stock', [ThesisItemController::class, 'truncateStock'])->name('truncate_stock');

    // order thesis
    Route::get('incoming_order/{no_nota}', [ThesisOrderController::class, 'show']);
    Route::get('incoming_order', [ThesisOrderController::class, 'index'])->name('incoming_order');
    Route::post('incoming_order', [ThesisOrderController::class, 'store'])->name(('acc_order'));
});

// admin
Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::resources([
        'merek' => 'MerekController',
        'kategori' => 'KategoriController',
        'tracking' => 'TrackingController',
        'forecasting' => 'ForecastController'
    ]);

    // produk
    Route::get('produk/prices/user-prices/{id}/{name}', 'ProductController@userPriceList')->name('userPriceList');
    // Route::get('produk/prices/{id}/{name}/{category}', 'ProductController@prices')->name('productPrices');
    Route::get('produk/prices/{id}/{name}', 'ProductController@prices')->name('productPrices');
    Route::resource('produk', 'ProductController');
    // Route::get('produk/manajemen_produk', 'ProductController@view')->name('manajemen_produk');
    Route::post('produk/{produk}/visible', 'ProductController@setVisible');
    Route::get('produk/inventory/stock', 'ProductController@inventory');
    Route::post('produk/inventory/stock', 'ProductController@updateStock');
    Route::get('produk/inventory/stock/datatables', 'ProductController@inventoryDatatables');
    Route::get('produk/inventory/stock/{type}/{id}/datatables', 'ProductController@inventoryInOutDatatables');

    //setting-harga-user
    Route::get('setting-harga-user', 'HargaUserController@index');
    Route::get('setting-harga-user/harga-user/{id}/{name}/{id_group}', 'HargaUserController@setting_harga')->name('setting_harga_user');

    // ---------setting opsi user
    Route::post('setting_opsiHarga','settingController@settingHarga')->name('settingHarga');
    Route::post('setting_opsiStok','settingController@settingStok')->name('settingStok');

    Route::get('/order/purchase-order', 'PurchaseOrderController@index');
    Route::get('/order/perintah-kirim', 'PurchaseOrderController@perintahKirim');
    Route::get('/order/tagihan', 'PurchaseOrderController@tagihan');
    Route::get('/order/proses', 'PurchaseOrderController@pesananProses');
    Route::get('/order/pesanan-selesai', 'PurchaseOrderController@pesananSelesai');
    // Route::get('/order/pending', 'PendingOrderController@index');
    Route::get('/order/pending', 'PurchaseOrderController@pending');
    Route::post('/order/update', 'OrderController@update');
    Route::post('/order/store-to-pending', 'OrderController@storeToPending');
    Route::get('/order/riwayat', 'OrderController@viewRiwayat');
    Route::get('/order/histori_return', 'OrderController@historiReturn');

    // payment controller
    Route::post('/payment/validateTransfer', 'PaymentController@validateTransfer')->name('validateTransfer');

    // return
    Route::get('return/approval', 'ReturnController@returns');

    // harga produk group
    Route::get('/harga/{id}', [HargaProdukGroupController::class, 'show']);
    Route::post('/harga/{id}', [HargaProdukGroupController::class, 'edit']);
    Route::post('delete', 'HargaProdukGroupController@deleteHargaGroup')->name('deleteHarga');

    // Route::get('/return/approval', 'ReturnOrderController@show');

    //user
    Route::resource('user', 'UserController');
    Route::get('/account_user', 'UserController@accountUser');
    Route::get('/group_user', 'UserController@groupUser');

    // tagihan
    Route::get('/tagihan/approval', 'TagihanController@approval');
    Route::get('/tagihan/kirim', 'TagihanController@index');
    Route::get('/tagihan/lihat', 'TagihanController@show');
    Route::get('/tagihan/bayar', 'TagihanController@bayar')->name('pembayaran');
    Route::get('/tagihan/cetak', 'TagihanController@cetak_tagihan')->name('cetak_tagihan');
    Route::get('/surat_jalan/cetak', 'TagihanController@cetak_surat_jalan')->name('cetak_surat_jalan');
// ------------------------------------------------
    Route::post('/tagihan/add', 'TagihanController@store')->name('addTagihan');
// ------------------------------------------------
    Route::get('/order/cetak', 'TagihanController@cetak_pesanan')->name('cetak_pesanan');

    // Bonus
    Route::get('/bonus/sales', 'BonusController@getSales')->name('salesList');
    Route::get('/bonus/sales/{id}/{name}', 'BonusController@getSalesBonus');
    Route::get('/bonus/sales_detail/{tagihan}/detail', 'BonusController@getTagihanDetail');

    //menu
    Route::get('/setting/menu', 'MenuController@index');
});

// untuk user: agent dan customer
Route::group(['middleware' => 'auth'], function () {


    Route::resource('/profile', 'ProfilController');
    Route::post('/profile_update/{id?}', 'ProfilController@update')->name('profile_update');

    Route::get('/profile_pending', 'ProfilController@pending');
    Route::get('/profile_return', 'ProfilController@return');

    // purchase-order
    Route::get('/order', 'OrderController@index');
    Route::post('/order/purchase-order', 'PurchaseOrderController@store')->name('storeOrder');
    Route::post('/order/upload', 'TagihanController@upload');
    Route::get('/order/payment', 'PaymentController@index');
    Route::get('/order/getPayment', 'PaymentController@getPayment');
    //return
    Route::post('/return/upload', 'ReturnController@reasonsReturn')->name('reasons');

    Route::get('/session/{key}', 'SessionController@retrieve');
    Route::post('/session/save', 'SessionController@store');
    Route::post('/session/remove', 'SessionController@remove');
});
