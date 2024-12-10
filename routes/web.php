<?php

// add class first
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuRoleController;
use App\Http\Controllers\MenuPermissionController;
use App\Http\Controllers\SettingController;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManifestController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


/* PoS Menus */
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\SalesTransaction;
use App\Http\Controllers\SalesListController;
use App\Http\Controllers\RolePermissionKasirController;
use App\Http\Controllers\ProductBarcodeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseListController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\BiayaKategoriController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\StockTransferListController;
use App\Http\Controllers\BiayaPengeluaranController;
use App\Http\Controllers\BiayaPengeluaranList;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\LabaRugiReportController;
use App\Http\Controllers\BankReportController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\DatabaseResetController;
use App\Http\Controllers\AppUpdateController;
use App\Http\Controllers\KatalogController;


// storage link / symlink
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);

    if (!File::exists($filePath)) {
        abort(404);
    }

    $file = File::get($filePath);
    $type = File::mimeType($filePath);

    return Response::make($file, 200)->header("Content-Type", $type);
})->where('path', '.*');


Route::get('/manifest.json', [ManifestController::class, 'show']);

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/sini', function () {
    dd("sini aja");
});

Route::get('/import', function () {
    return view('import');
});

//Impor produk
Route::get('/import-product', [ProductImportController::class, 'index'])->name('import-product.index'); // Tambah route untuk menampilkan halaman impor
Route::get('/download-template', [ProductImportController::class, 'downloadTemplate']);
Route::post('/import', [ProductImportController::class, 'import']);

//backup database
Route::get('/backup-database', [DatabaseBackupController::class, 'index'])->name('backup.database');
Route::post('/backup-database', [DatabaseBackupController::class, 'backup'])->name('backup.database.post');

//reset database
Route::get('/reset-database', [DatabaseResetController::class, 'index'])->name('reset.database');
Route::post('/reset-database', [DatabaseResetController::class, 'reset'])->name('reset.database.post');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/monthly-sales', [DashboardController::class, 'getMonthlySales'])->name('dashboard.monthly-sales');

//Update Aplikasi
Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::get('/update', [AppUpdateController::class, 'index'])->name('update.index');
    Route::post('/update/upload', [AppUpdateController::class, 'upload'])->name('update.upload');
    Route::post('/update/run', [AppUpdateController::class, 'run'])->name('update.run');
});


Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.delete');
});

Route::group(['middleware' => ['auth', 'permission:users.view']], function () {
    Route::get('/userroles', [UserRoleController::class, 'index'])->name('userroles.index');
    Route::get('/userroles/create', [UserRoleController::class, 'create'])->name('userroles.create');
    Route::post('/userroles/store', [UserRoleController::class, 'store'])->name('userroles.store');
    Route::get('/userroles/{id}/edit', [UserRoleController::class, 'edit'])->name('userroles.edit');
    Route::put('/userroles/{id}/update', [UserRoleController::class, 'update'])->name('userroles.update');
    Route::delete('/userroles/{id}/delete', [UserRoleController::class, 'destroy'])->name('userroles.delete');
    Route::get('/userroles/{roleId}/permissions', [UserRoleController::class, 'getPermissions'])->name('userroles.permissions');
});

Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/menus/store', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{id}/update', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{id}/delete', [MenuController::class, 'destroy'])->name('menus.delete');
});

Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::get('/menupermission', [MenuPermissionController::class, 'index'])->name('menupermission.index');
    Route::get('/menupermission/create', [MenuPermissionController::class, 'create'])->name('menupermission.create');
    Route::post('/menupermission/store', [MenuPermissionController::class, 'store'])->name('menupermission.store');
    Route::get('/menupermission/{id}/edit', [MenuPermissionController::class, 'edit'])->name('menupermission.edit');
    Route::put('/menupermission/{id}/update', [MenuPermissionController::class, 'update'])->name('menupermission.update');
    Route::put('/menupermission/{id}/updateMenuPermission', [MenuPermissionController::class, 'updateMenuPermission'])->name('menupermission.updateMenuPermission');
    Route::delete('/menupermission/{id}/delete', [MenuPermissionController::class, 'destroy'])->name('menupermission.delete');
});

Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

// POS MENUS
Route::resource('stores', StoreController::class)->middleware('auth');
Route::resource('brands', BrandController::class)->middleware('auth');
Route::resource('units', UnitController::class)->middleware('auth');
Route::resource('categories', ProductCategoryController::class)->middleware('auth');
Route::resource('customers', CustomerController::class)->middleware('auth');
Route::resource('suppliers', SupplierController::class)->middleware('auth');
Route::resource('pajak', PajakController::class)->middleware('auth');
Route::resource('bank', BankController::class)->middleware('auth');
Route::resource('biaya-kategori', BiayaKategoriController::class)->middleware('auth');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}/delete', [ProductController::class, 'destroy'])->name('products.delete');
    Route::delete('/products/{id}/deleteImage', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
    Route::get('/sales-transaction', [SalesTransaction::class, 'index'])->name('sales-transaction.index');
    Route::get('/sales-transaction/store-id={id}', [SalesTransaction::class, 'byStore'])->name('sales-transaction.byStore');
    Route::get('/get-latest-transaction/{storeId}', [SalesTransaction::class, 'getLatestTransaction'])->name('get-latest-transaction');
    Route::get('/get-cart-quantity/{productId}', [SalesTransaction::class, 'getCartQuantity'])->name('get-cart-quantity');
    Route::post('/search-products-by-store/{storeId}', [SalesTransaction::class, 'searchProductsByStore'])->name('search-products-by-store');
    Route::post('/add-to-cart/{id}', [SalesTransaction::class, 'addToCart'])->name('add-to-cart');
    Route::get('/cart-content', [SalesTransaction::class, 'cartContent'])->name('cart-content');
    Route::delete('/remove-from-cart/{rowId}', [SalesTransaction::class, 'removeFromCart'])->name('remove-from-cart');
    Route::get('/destroy-cart', [SalesTransaction::class, 'destroyCart'])->name('destroy-cart');
    Route::post('/delete-all-cart', [SalesTransaction::class, 'deleteAllCart'])->name('delete-all-cart');
    Route::post('/update-cart/{rowId}', [SalesTransaction::class, 'updateCart'])->name('update-cart');
    Route::post('/confirm-payment', [SalesTransaction::class, 'confirmPayment'])->name('confirm-payment');
    Route::get('/print-nota/{transactionId}', [SalesTransaction::class, 'printNota'])->name('print-nota');
    Route::post('/add-to-cart-by-barcode/{barcode}', [SalesTransaction::class, 'addToCartByBarcode'])->name('add-to-cart-by-barcode');
    Route::get('/get-cart-quantity-by-barcode/{barcode}', [SalesTransaction::class, 'getCartQuantityByBarcode'])->name('get-cart-quantity-by-barcode');

    Route::get('/sales-list', [SalesListController::class, 'index'])->name('sales-list.index');
    Route::get('/sales-list/{transactionId}/edit', [SalesListController::class, 'edit'])->name('sales-list.edit');
    Route::put('/sales-list/{transactionId}/update', [SalesListController::class, 'update'])->name('sales-list.update');
    Route::delete('/sales-list/{transactionId}', [SalesListController::class, 'destroy'])->name('sales-list.delete');
    Route::post('/confirm-payment-tempo', [SalesListController::class, 'confirmPaymentTempo'])->name('confirm-payment-tempo');
    Route::get('/sales-list/detail/{transactionId}', [SalesListController::class, 'detail'])->name('sales-list.detail');
    Route::get('/print-invoice/{transactionId}', [SalesListController::class, 'printInvoice'])->name('print-invoice');

    Route::get('/sales-return', [SalesReturnController::class, 'index'])->name('sales-return.index');
    Route::get('/sales-return/detail/{id}', [SalesReturnController::class, 'detail'])->name('sales-return.detail');
    Route::get('/sales-return/create', [SalesReturnController::class, 'create'])->name('sales-return.create');
    Route::post('/sales-return/store', [SalesReturnController::class, 'store'])->name('sales-return.store');
    Route::get('/sales-return/get-invoice-details/{transactionId}', [SalesReturnController::class, 'getInvoiceDetails'])->name('sales-return.get-invoice-details');
    Route::get('/sales-return/cetak-invoice/{id}', [SalesReturnController::class, 'cetakInvoice'])->name('sales-return.cetak-invoice');
    Route::delete('/sales-return/{id}', [SalesReturnController::class, 'destroy'])->name('sales-return.delete');
    Route::get('/sales-return/{id}/edit', [SalesReturnController::class, 'edit'])->name('sales-return.edit');
    Route::put('/sales-return/{id}/update', [SalesReturnController::class, 'update'])->name('sales-return.update');
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales-report.index');
    Route::get('/sales-report/export-excel', [SalesReportController::class, 'exportExcel'])->name('sales-report.export-excel');
    Route::get('/role-permission-kasir', [RolePermissionKasirController::class, 'index'])->name('rolepermissionkasir.index');
    Route::put('/role-permission-kasir/{id}', [RolePermissionKasirController::class, 'update'])->name('rolepermissionkasir.update');
    Route::get('/role-permission-kasir/{id}', [RolePermissionKasirController::class, 'edit'])->name('rolepermissionkasir.edit');

    Route::get('/product-barcode', [ProductBarcodeController::class, 'index'])->name('productbarcode.index');
    Route::get('/product-barcode/search/{name?}', [ProductBarcodeController::class, 'search'])->name('productbarcode.search');

    Route::get('/purchase-transaction', [PurchaseController::class, 'add'])->name('purchase.add');
    Route::get('/purchase-transaction/search/{name?}', [PurchaseController::class, 'search'])->name('purchase.search');
    Route::post('/purchase-transaction/store', [PurchaseController::class, 'store'])->name('purchase.store');

    Route::get('/purchase-list', [PurchaseListController::class, 'index'])->name('purchase-list.index');
    Route::get('/purchase-list/detail/{id}', [PurchaseListController::class, 'detail'])->name('purchase-list.detail');
    Route::get('/purchase-list/edit/{id}', [PurchaseListController::class, 'edit'])->name('purchase-list.edit');
    Route::put('/purchase-list/update/{id}', [PurchaseListController::class, 'update'])->name('purchase-list.update');
    Route::delete('/purchase-list/delete/{id}', [PurchaseListController::class, 'destroy'])->name('purchase-list.delete');
    Route::post('/purchase-list/confirm-payment-tempo', [PurchaseListController::class, 'confirmPaymentTempo'])->name('purchase-list.confirm-payment-tempo');
    Route::get('/print-nota-pembelian/{id}', [PurchaseListController::class, 'printNota'])->name('print-nota-pembelian');
    Route::get('/print-invoice-pembelian/{id}', [PurchaseListController::class, 'printInvoice'])->name('print-invoice-pembelian');
    Route::get('/purchase-return', [PurchaseReturnController::class, 'index'])->name('purchase-return.index');
    Route::get('/purchase-return/create', [PurchaseReturnController::class, 'create'])->name('purchase-return.create');
    Route::post('/purchase-return/store', [PurchaseReturnController::class, 'store'])->name('purchase-return.store');
    Route::get('/purchase-return/detail/{id}', [PurchaseReturnController::class, 'detail'])->name('purchase-return.detail');
    Route::get('/purchase-return/edit/{id}', [PurchaseReturnController::class, 'edit'])->name('purchase-return.edit');
    Route::put('/purchase-return/update/{id}', [PurchaseReturnController::class, 'update'])->name('purchase-return.update');
    Route::delete('/purchase-return/delete/{id}', [PurchaseReturnController::class, 'destroy'])->name('purchase-return.delete');
    Route::get('/purchase-return/get-invoice-details/{transactionId}', [PurchaseReturnController::class, 'getInvoiceDetails'])->name('purchase-return.get-invoice-details');
    Route::get('/purchase-return/cetak-invoice/{id}', [PurchaseReturnController::class, 'cetakInvoice'])->name('purchase-return.cetak-invoice');
    Route::get('/purchase-report', [PurchaseReportController::class, 'index'])->name('purchase-report.index');
    Route::get('/purchase-report/export-excel', [PurchaseReportController::class, 'exportExcel'])->name('purchase-report.export-excel');
    Route::get('/stock-transfer', [StockTransferController::class, 'index'])->name('stock-transfer.index');
    Route::get('/stock-transfer/search', [StockTransferController::class, 'search'])->name('stock-transfer.search');
    Route::post('/stock-transfer/store', [StockTransferController::class, 'store'])->name('stock-transfer.store');
    Route::get('/stocktransfer-list', [StockTransferListController::class, 'index'])->name('stocktransfer-list.index');
    Route::get('/stocktransfer-list/detail/{id}', [StockTransferListController::class, 'detail'])->name('stocktransfer-list.detail');
    Route::get('/stocktransfer-list/edit/{id}', [StockTransferListController::class, 'edit'])->name('stocktransfer-list.edit');
    Route::put('/stocktransfer-list/update/{id}', [StockTransferListController::class, 'update'])->name('stocktransfer-list.update');
    Route::delete('/stocktransfer-list/delete/{id}', [StockTransferListController::class, 'destroy'])->name('stocktransfer-list.delete');
    Route::get('/get-users-by-store', [StockTransferController::class, 'getUsersByStore'])->name('get-users-by-store');
    Route::post('/stocktransfer-list/lunasi/{id}', [StockTransferListController::class, 'lunasi'])->name('stocktransfer-list.lunasi');
    Route::get('/biaya-pengeluaran', [BiayaPengeluaranController::class, 'index'])->name('biaya-pengeluaran.index');
    Route::post('/biaya-pengeluaran/store', [BiayaPengeluaranController::class, 'store'])->name('biaya-pengeluaran.store');
    Route::get('/biaya-pengeluaran-list', [BiayaPengeluaranList::class, 'index'])->name('biaya-pengeluaran-list.index');
    Route::get('/biaya-pengeluaran-list/detail/{id}', [BiayaPengeluaranList::class, 'detail'])->name('biaya-pengeluaran-list.detail');
    Route::get('/biaya-pengeluaran-list/edit/{id}', [BiayaPengeluaranList::class, 'edit'])->name('biaya-pengeluaran-list.edit');
    Route::delete('/biaya-pengeluaran-list/delete/{id}', [BiayaPengeluaranList::class, 'destroy'])->name('biaya-pengeluaran-list.delete');
    Route::post('/biaya-pengeluaran-list/confirm-payment/{id}', [BiayaPengeluaranList::class, 'confirmPayment'])->name('biaya-pengeluaran-list.confirm-payment');
    Route::put('/biaya-pengeluaran-list/update/{id}', [BiayaPengeluaranList::class, 'update'])->name('biaya-pengeluaran-list.update');
    Route::get('/stockopname-add', [StockOpnameController::class, 'add'])->name('stockopname.add');
    Route::post('/stockopname-add/store', [StockOpnameController::class, 'store'])->name('stockopname.store');
    Route::get('/stockopname-list', [StockOpnameController::class, 'index'])->name('stockopname.index');
    Route::get('/stockopname-list/detail/{id}', [StockOpnameController::class, 'detail'])->name('stockopname.detail');
    Route::get('/stockopname-list/edit/{id}', [StockOpnameController::class, 'edit'])->name('stockopname.edit');
    Route::put('/stockopname-list/update/{id}', [StockOpnameController::class, 'update'])->name('stockopname.update');
    Route::delete('/stockopname-list/delete/{id}', [StockOpnameController::class, 'destroy'])->name('stockopname.delete');
    Route::get('/stock-report', [StockReportController::class, 'index'])->name('stock-report.index');
    Route::get('/stock-report/export-excel', [StockReportController::class, 'exportExcel'])->name('stock-report.export-excel');
    Route::get('/labarugi-report', [LabaRugiReportController::class, 'index'])->name('labarugi-report.index');
    Route::get('/labarugi-report/export-excel', [LabaRugiReportController::class, 'exportExcel'])->name('labarugi-report.export-excel');
    Route::get('/bank-report', [BankReportController::class, 'index'])->name('bank-report.index');
    Route::get('/bank-report/export-excel', [BankReportController::class, 'exportExcel'])->name('bank-report.export-excel');
});


require __DIR__ . '/auth.php';
