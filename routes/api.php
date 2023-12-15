<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProveedorController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DetalleProductoController;
use App\Http\Controllers\ProductoProveedorController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::put('user/update', [UserController::class, 'updatee']);
Route::post('user/upload', [UserController::class, 'upload'])->middleware(ApiAuthMiddleware::class);
Route::get('getusers', [UserController::class, 'index']);
Route::get('getproviders', [ProveedorController::class, 'index']);
Route::get('getproveedores', [ProveedorController::class, 'getProviders']);
Route::get('getcategoria', [CategoryController::class, 'index']);
Route::post('savecategoria', [CategoryController::class,'store']);
Route::put('updatecategoria', [CategoryController::class,'update']);
Route::post('categoriadelete', [CategoryController::class, 'destroy']);
Route::get('getdetalleproducto', [DetalleProductoController::class, 'index']);
Route::get('getproviders/unabled', [ProveedorController::class, 'showInactivos']);
Route::post('saveprovider', [ProveedorController::class, 'store']);
Route::put('ChangeState', [ProveedorController::class, 'ChangeState']);
Route::put('venta/ChangeState', [VentaController::class, 'ChangeState']);
Route::put('provider/update', [ProveedorController::class, 'update']);
Route::get('getproducts',[ProductoController::class, 'index']);
Route::get('getproductsDetails',[ProductoController::class, 'getDetailWithProducts']);
Route::post('deactivate',[ProductoController::class, 'deactivate']);
Route::delete('deleteproduct/{id}',[ProductoController::class, 'destroy']);
Route::get('getnotificacioncont', [NotificacionController::class, 'indexs']);
Route::put('putnotificacion', [NotificacionController::class, 'update']);
Route::post('savecategoria', [CategoryController::class,'store']);
Route::post('createorupdateproducts',[ProductoController::class, 'createOrUpdate']);
Route::get('getdeproducts',[DetalleProductoController::class, 'index']);
Route::get('getnotificacion',[NotificacionController::class, 'index']);
Route::get('getventa',[VentaController::class, 'index']);
Route::post('venta',[VentaController::class, 'store']);
Route::post('compra',[CompraController::class, 'store']);
Route::get('ventasAll',[VentaController::class, 'VentasAll']);
Route::get('gananciasMes',[VentaController::class, 'gananciasMes']);
Route::get('getLatest',[DetalleProductoController::class, 'getLatest']);
Route::get('comprasAll',[CompraController::class, 'ComprasAll']);
Route::get('comprasAllInactivas',[CompraController::class, 'ComprasAllInactivas']);
Route::put('compra/changeState',[CompraController::class, 'ChangeState']);
Route::get('ventasInactivas',[VentaController::class, 'VentasInactivas']);
Route::post('venta/report',[VentaController::class, 'factura'])->name('reports.newfactura');
Route::post('venta/reportemail',[VentaController::class, 'facturacorreo'])->name('reports.newfactura');
Route::get('product/report',[ProductoController::class, 'productosReporte'])->name('reports.productos');
Route::get('productmonth/report',[ProductoController::class, 'productosReportefindemes'])->name('reports.productos');
Route::post('reportAll',[VentaController::class, 'Reporte'])->name('reports.reporteventas');
Route::post('reportAllCompras',[CompraController::class, 'Reporte'])->name('reports.reportecompras');
Route::get('getcompra',[CompraController::class, 'ComprasAll']);
Route::get('provider',[ProductoProveedorController::class, 'index']);
Route::post('getProductsById',[ProductoProveedorController::class, 'getByProductId']);
Route::post('deletedetail',[DetalleProductoController::class, 'destroy']);
Route::post('saveDetail',[DetalleProductoController::class, 'store']);
Route::put('RecoveryPassword',[UserController::class, 'RecoveryPassword']);
Route::get('/images/{filename}', function ($nombre) {
    $archivo = Storage::disk('public')->get($nombre);
    if ($archivo) {
        return response($archivo, 200);
    } else {
        return response('', 404);
    }
});

/*Route::get('/images/{filename}', function ($filename) {
    $path = public_path('uploads/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    } else {
        abort(404);
    }
})->where('filename', '.*');*/
/*Route::get('/images/{filename}', function ($nombre) {
        $archivo = Storage::disk('public')->get($nombre);
        dd($archivo);
        if ($archivo) {
            return response($archivo, 200)->header('Content-Type', mime_content_type($nombre));
        } else {
            return response('', 404);
        }
    });*/
