<?php

// use App\HargaProdukGroup;
use App\Http\Controllers\CatalogueController;
// use App\Http\Controllers\MenuController;
// use App\Http\Controllers\DriverController;
// use App\Http\Controllers\HargaProdukGroupController;
// use App\Http\Controllers\HargaProdukUserController;
// use App\Http\Controllers\OrderController;
// use App\Http\Controllers\ProductController;
use App\Http\Controllers\ThesisAdminOrderController;
use App\Http\Controllers\ThesisCustomerController;
use App\Http\Controllers\ThesisGroupUserController;
use App\Http\Controllers\ThesisItemController;
use App\Http\Controllers\ThesisUserController;
// use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;

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
Route::get('/', 'CatalogueController@index');

Route::post('/search', [CatalogueController::class, 'search']);
Route::get('/filter', [CatalogueController::class, 'filter']);

Route::get('/index-tabel', [CatalogueController::class, 'tabel']);
Route::get('/not-found', [CatalogueController::class, 'notFound']);
Route::get('/product/{id}', [CatalogueController::class, 'show']);
// Route::get('/tracking', [TrackingController::class, 'client']);

Route::get('/detail/product/{barang_alias}', [CatalogueController::class, 'detailTable']);
Route::post('/detail/produk','CatalogueController@get_detailBarang')->name('get_detailBarang');
Route::post('/detail', [CatalogueController::class, 'detail']);
Route::post('delete_cart','cartController@delete_cart')->name('delete_cart');
Route::post('/cart','cartController@cartProcess')->name('addCart');
Route::post('cartData','cartController@cartData')->name('cartData');
Route::post('json_cartAll','cartController@json_cartAll')->name('json_cartAll');
// Route::get('count_harganull','notifHandlerController@count_harganull')->name('count_harganull');

Route::post('/login', 'ThesisUserController@login')->name('loginUser');
Route::post('/register', 'ThesisUserController@create')->name('registerUser');

// testing
Route::get('/user-cart-test', 'cartController@userCartTest');
// --------------------------------------------

Route::prefix('data')->group(function () {

    Route::get('user', [UserController::class, 'authGetter'])->name('authGetter');
    // Route::post('/create/group', [UserController::class, 'createGroup'])->name('createGroup');
    // Route::post('/create/user', [UserController::class, 'createUser'])->name('createUser');
    // Route::get('/delete/group/{id}', [UserController::class, 'destroyGroup']);
    // Route::get('/delete/user/{id}', [UserController::class, 'destroyUser']);
    // Route::post('/edit/group', [UserController::class, 'editGroup'])->name('editGroup');
    // Route::post('/edit/user', [UserController::class, 'editUser'])->name('editUser');

    // Route::get('user-group', [UserController::class, 'allGroup'])->name('allGroup');
    // Route::post('user', [HargaProdukGroupController::class, 'store'])->name('storeGroupHarga');
    // Route::post('user/harga/all', [HargaProdukUserController::class, 'store'])->name('storeUserHarga');
    // Route::post('user/harga/deleteAll', [HargaProdukUserController::class, 'deleteByGroup'])->name('deleteAllUserHarga');
    // Route::get('user/harga/byProduk/{produkId}', [HargaProdukUserController::class, 'usersWithPrices'])->name('usersWithPrices');
    // Route::get('user/harga/byProduk/groupPriceSelection/{productId}', [HargaProdukUserController::class, 'groupPriceSelection'])->name('groupPriceSelection');
    // Route::post('user/harga/byProduk/changeUserPrice', [HargaProdukUserController::class, 'changeUserPrice'])->name('changeUserPrice');

    //setting harga user
    // Route::post('setting_harga/user/setHargaUser', 'HargaUserController@setHarga')->name('setHargaUser');
    // Route::get('setting_harga/user/hargaUser/{userId}/{groupId}', 'HargaUserController@hargaUser')->name('hargaUser');
    // Route::get('setting_harga/user/pilihHarga/{userId}/{groupId}', 'HargaUserController@pilihHarga')->name('pilihHarga');
    // Route::post('setting_harga/user/harga', 'HargaUserController@hargaCheckbox')->name('hargaCheckbox');
    // Route::post('setting_harga/user/hargaAll', 'HargaUserController@saveAll')->name('saveAllHargaUser');

    Route::get('catalogue/products/detail/{barang_alias}', [CatalogueController::class, 'tableDetail']);
    Route::get('catalogue/products', [CatalogueController::class, 'catalogue']);
    Route::get('catalogue/harga-group', [CatalogueController::class, 'hargaGroup'])->name('hargaGroup');

    // Route::get('/order/histori-return', [OrderController::class, 'returnHistori']);
    // Route::get('/order/{id_user}/prosses', [OrderController::class, 'proses']);
    // Route::get('/order/{id_user}/pesanan-proses', [OrderController::class, 'pesananProses']);
    // Route::get('/order/{id_user}/pesanan-kirim', [OrderController::class, 'pesananKirim']);
    // Route::get('/order/{id_user}/tertunda', [OrderController::class, 'tertunda']);
    // Route::get('/order/{id_user}/return', [OrderController::class, 'return']);
    // Route::get('/order/{id_user}/pesanan-selesai', [OrderController::class, 'pesananSelesai']);
    // Route::get('/order/pesanan-diterima/{id_tagihan}', [OrderController::class, 'pesananDiterima']);
    // Route::get('/order/riwayat', [OrderController::class, 'dataRiwayat']);
    // Route::get('/order/pesanan-selesai', [OrderController::class, 'dataSelesai']);
    // Route::post('/order/return', [OrderController::class, 'storeReturn']);
    // Route::post('/order/pickup', [OrderController::class, 'storePickup'])->name('storePickup');
    // Route::post('/order/arrive', [OrderController::class, 'storeArrive'])->name('storeArrive');
    // Route::post('/order/confirm', [OrderController::class, 'storeConfirm'])->name('storeConfirm');

    //menu
    // Route::post('/setting/menu/create', 'MenuController@create');
    // Route::get('/setting/menu/delete/{id}', 'MenuController@destroy');
    // Route::post('/setting/menu/edit', 'MenuController@edit')->name('editMenu');

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
    Route::post('/speed', 'ThesisController@speedPage');
    Route::get('/speed/rabin', 'ThesisController@speedRabin');
    Route::get('/speed/sql', 'ThesisController@speedSQL');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'admin']], function ()
{
    Route::get('/', 'DashboardController@index')->name('dashboard');
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
    Route::get('incoming_order/{no_nota}', [ThesisAdminOrderController::class, 'show']);
    Route::get('incoming_order', [ThesisAdminOrderController::class, 'index'])->name('incoming_order');
    Route::post('incoming_order', [ThesisAdminOrderController::class, 'store'])->name(('acc_order'));
    Route::get('to_send', [ThesisAdminOrderController::class, 'sendPage'])->name('send_page');
    Route::get('to_send/page/list', [ThesisAdminOrderController::class, 'sendList'])->name('send_list');
    Route::post('to_send/page/send', [ThesisAdminOrderController::class, 'sendOrder'])->name('send_order');
    Route::get('sending', [ThesisAdminOrderController::class, 'sendingPage'])->name('sending_page');
    Route::get('sending/page/list', [ThesisAdminOrderController::class, 'sendingList'])->name('sending_list');
    Route::get('completed', [ThesisAdminOrderController::class, 'completedPage'])->name('completed_page');
    Route::get('completed/page/list', [ThesisAdminOrderController::class, 'completedList'])->name('completed_list');

    // approval thesis
    Route::post('approval/get_url', [ThesisAdminOrderController::class, 'approvalUrl'])->name('approval_url');
    Route::post('approval/acceptance', [ThesisAdminOrderController::class, 'approvalBill'])->name('bill_acceptance');
});

Route::group(['middleware' => ['auth', 'customer']], function()
{
    Route::get('order', [ThesisCustomerController::class, 'index']);
    Route::get('order/req/unaccepted', [ThesisCustomerController::class, 'orderUnaccepted'])->name('pesananBelumDisetujui');
    Route::get('order/req/unpaid', [ThesisCustomerController::class, 'orderUnpaid'])->name('pesananBelumDibayar');
    Route::get('order/req/paid', [ThesisCustomerController::class, 'orderPaid'])->name('pesananLunas');
    Route::post('order/req/upload-transfer', [ThesisCustomerController::class, 'uploadTransfer'])->name('uploadTransfer');
    Route::post('order/req/confirm-order', [ThesisCustomerController::class, 'confirmOrder'])->name('confirmOrder');
    Route::get('order/req/completed', [ThesisCustomerController::class, 'completedList'])->name('orderCompleted');

    Route::get('/profile', 'ThesisCustomerController@customerProfile')->name('profile.index');
    Route::post('/profile_update', 'ThesisCustomerController@customerUpdateProfile')->name('profile_update');
});

// untuk user: customer
Route::group(['middleware' => ['auth', 'customer']], function () {

    // Route::resource('/profile', 'ProfilController');
    // Route::post('/profile_update/{id?}', 'ProfilController@update')->name('profile_update');

    // Route::get('/profile_pending', 'ProfilController@pending');
    // Route::get('/profile_return', 'ProfilController@return');

    // purchase-order
    Route::post('/order/purchase-order', 'PurchaseOrderController@store')->name('storeOrder');
    // Route::post('/order/upload', 'TagihanController@upload');
});
