<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AutenticacaoMiddleware;
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
Route::get('/', function () {
    return view('login');
});

Route::get('/login/{erro?}', 'LoginController@index')->name('viewLogin');

Route::middleware(AutenticacaoMiddleware::class)->get('/home', 'HomeController@index')->name('home');
Route::middleware(AutenticacaoMiddleware::class)->get('/cadastroTipos/{erro?}', 'TiposProdutoController@indexViewCadastro')->name('cadastroTipos');
Route::middleware(AutenticacaoMiddleware::class)->get('/cadastroProduto/{erro?}', 'ProdutoController@indexViewCadastro')->name('cadastroProdutos');
Route::middleware(AutenticacaoMiddleware::class)->get('/cadastroVenda/{erro?}', 'VendaController@indexViewCadastro')->name('cadastroVenda');
Route::middleware(AutenticacaoMiddleware::class)->get('/consultaTipos', 'TiposProdutoController@indexViewConsulta')->name('consultaTipos');
Route::middleware(AutenticacaoMiddleware::class)->get('/consultaProdutos', 'ProdutoController@indexViewConsulta')->name('consultaProdutos');
Route::middleware(AutenticacaoMiddleware::class)->get('/consultaVendas', 'VendaController@indexViewConsulta')->name('consultaVendas');
Route::middleware(AutenticacaoMiddleware::class)->get('/listarTipos', 'TiposProdutoController@listarTipos')->name('listarTipos');
Route::middleware(AutenticacaoMiddleware::class)->get('/listarProdutos', 'ProdutoController@listarProdutos')->name('listarProdutos');
Route::middleware(AutenticacaoMiddleware::class)->get('/listarVendas', 'VendaController@listarVendas')->name('listarVendas');
Route::middleware(AutenticacaoMiddleware::class)->get('/dashboardVendasMes', 'DashboardController@buscaTotalVendasMes')->name('dashboardVendasMes');
Route::middleware(AutenticacaoMiddleware::class)->get('/dashboardVendasSemana', 'DashboardController@buscaTotalVendasSemana')->name('dashboardVendasSemana');
Route::middleware(AutenticacaoMiddleware::class)->get('/dashboardVendasHoje', 'DashboardController@buscaTotalVendasHoje')->name('dashboardVendasHoje');
Route::middleware(AutenticacaoMiddleware::class)->get('/dashboardVendasMesaMes', 'DashboardController@buscaVendasMesAMes')->name('dashboardVendasMesaMes');
Route::get('/venda/itens/{idVenda}', 'VendaController@listarItens')->name('itensVenda');
Route::get('logout', 'LoginController@logout')->name('logout');


Route::post('/autenticar', 'LoginController@autenticar')->name('autenticar');
Route::post('/cadastrarTipo', 'TiposProdutoController@cadastrar')->name('cadastrarTipo');
Route::post('/cadastrarProduto', 'ProdutoController@cadastrar')->name('cadastrarProduto');

Route::post('/obter-precos', 'VendaController@obterPrecos')->name('obterPrecos');
Route::post('/cadastrarVenda', 'VendaController@cadastrar')->name('cadastrarVenda');
Route::post('/deletarItemVenda', 'VendaController@deletarItem')->name('deletarItemVenda');
Route::post('/finalizarVenda', 'VendaController@finalizarVenda')->name('finalizarVenda');
Route::post('/salvarEdicaoTipo', 'TiposProdutoController@salvarEdicao')->name('salvarEdicaoTipo');
Route::post('/alterarStatusTipo', 'TiposProdutoController@alterarStatus')->name('alterarStatusTipo');
Route::post('/salvarEdicaoProduto', 'ProdutoController@salvarEdicao')->name('salvarEdicaoProduto');
Route::post('/alterarStatusProduto', 'ProdutoController@alterarStatus')->name('alterarStatusProduto');