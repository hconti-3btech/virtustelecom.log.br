<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogAcessoMiddleware;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(LogAcessoMiddleware::class);

Route::prefix('/app')->group(function(){
    ######################### Produtos
    Route::get('/viewProduto', [App\Http\Controllers\app\ProdutoController::class, 'viewProduto'])->name('viewProduto');
    Route::get('/delProduto', [App\Http\Controllers\app\ProdutoController::class, 'delProduto'])->name('delProduto');
    Route::get('/desativaProduto', [App\Http\Controllers\app\ProdutoController::class, 'desativaProduto'])->name('desativaProduto');
    Route::get('/cadProduto', [App\Http\Controllers\app\ProdutoController::class, 'cadProduto'])->name('cadProduto');
    Route::post('/cadProduto', [App\Http\Controllers\app\ProdutoController::class, 'cadProdutoSave'])->name('cadProdutoSave');
    Route::get('/edtProduto', [App\Http\Controllers\app\ProdutoController::class, 'edtProduto'])->name('edtProduto');
    Route::post('/edtProduto', [App\Http\Controllers\app\ProdutoController::class, 'edtProdutoSave'])->name('edtProduto');

    ######################## Container
    Route::get('/viewContainer', [App\Http\Controllers\app\ContainerController::class, 'viewContainer'])->name('viewContainer');
    Route::get('/delContainer', [App\Http\Controllers\app\ContainerController::class, 'delContainer'])->name('delContainer');
    Route::get('/desativaContainer', [App\Http\Controllers\app\ContainerController::class, 'desativaContainer'])->name('desativaContainer');
    Route::get('/estoqueContainer', [App\Http\Controllers\app\ContainerController::class, 'estoqueContainer'])->name('estoqueContainer');
    Route::post('/edtContainer', [App\Http\Controllers\app\ContainerController::class, 'edtContainerSave'])->name('edtContainerSave');
    Route::get('/edtContainer', [App\Http\Controllers\app\ContainerController::class, 'edtContainer'])->name('edtContainer');
    Route::post('/cadContainer', [App\Http\Controllers\app\ContainerController::class, 'cadContainerSave'])->name('cadContainerSave');
    Route::get('/cadContainer', [App\Http\Controllers\app\ContainerController::class, 'cadContainer'])->name('cadContainer');
    
    ######################### Servicos
    Route::get('/viewServico', [App\Http\Controllers\app\ServicoController::class, 'viewServico'])->name('viewServico');
    Route::get('/cadServico', [App\Http\Controllers\app\ServicoController::class, 'cadServico'])->name('cadServico');
    Route::post('/cadServico', [App\Http\Controllers\app\ServicoController::class, 'cadServicoSave'])->name('cadServicoSave');
    Route::get('/desativaServico', [App\Http\Controllers\app\ServicoController::class, 'desativaServico'])->name('desativaServico');
    Route::get('/edtServico', [App\Http\Controllers\app\ServicoController::class, 'edtServico'])->name('edtServico');
    Route::post('/edtServico', [App\Http\Controllers\app\ServicoController::class, 'edtServicoSave'])->name('edtServicoSave');
    Route::get('/delServico', [App\Http\Controllers\app\ServicoController::class, 'delServico'])->name('delServico');

    ######################### Movimentos
    Route::get('/addEstoque', [App\Http\Controllers\app\MovimentoController::class, 'addEstoque'])->name('addEstoque');
    Route::post('/addEstoque', [App\Http\Controllers\app\MovimentoController::class, 'addEstoqueSave'])->name('addEstoqueSave');
    Route::get('/viewMovimento', [App\Http\Controllers\app\MovimentoController::class, 'viewMovimento'])->name('viewMovimento');
    Route::get('/delMovimento', [App\Http\Controllers\app\MovimentoController::class, 'delMovimento'])->name('delMovimento');
    Route::get('/requisicaoMovimento/{requisicao}/{msg?}', [App\Http\Controllers\app\MovimentoController::class, 'requisicaoMovimento'])->name('requisicaoMovimento');
    Route::post('/requisicaoMovimento}', [App\Http\Controllers\app\MovimentoController::class, 'requisicaoMovimentoSave'])->name('requisicaoMovimentoSave');
    Route::get('/viewRequisicaoMovimento/{requisicao}', [App\Http\Controllers\app\MovimentoController::class, 'viewRequisicaoMovimento'])->name('viewRequisicaoMovimento');
    Route::get('/addMovimento', [App\Http\Controllers\app\MovimentoController::class, 'addMovimento'])->name('addMovimento');
    Route::post('/addMovimento', [App\Http\Controllers\app\MovimentoController::class, 'addMovimentoSave'])->name('addMovimentoSave');

    ######################### Requisicao
    Route::get('/addRequisicao/{msg?}', [App\Http\Controllers\app\RequisicaoController::class, 'addRequisicao'])->name('addRequisicao');
    Route::post('/addRequisicao', [App\Http\Controllers\app\RequisicaoController::class, 'addRequisicaoSave'])->name('addRequisicaoSave');
    Route::get('/viewRequisicao', [App\Http\Controllers\app\RequisicaoController::class, 'viewRequisicao'])->name('viewRequisicao');
    Route::get('/aprovRequisicao', [App\Http\Controllers\app\RequisicaoController::class, 'aprovRequisicao'])->name('aprovRequisicao');
    Route::get('/delRequisicao', [App\Http\Controllers\app\RequisicaoController::class, 'delRequisicao'])->name('delRequisicao');
    
    ######################### Usuarios
    Route::get('/viewUsuario', [App\Http\Controllers\app\UsuarioController::class, 'viewUsuario'])->name('viewUsuario');
    Route::get('/desativaUser', [App\Http\Controllers\app\UsuarioController::class, 'desativaUser'])->name('desativaUser');
    Route::get('/edtUser', [App\Http\Controllers\app\UsuarioController::class, 'edtUser'])->name('edtUser');
    Route::post('/edtUser', [App\Http\Controllers\app\UsuarioController::class, 'edtUserSave'])->name('edtUserSave');
    Route::get('/delUser', [App\Http\Controllers\app\UsuarioController::class, 'delUser'])->name('delUser');

    ######################### OS
    Route::get('/viewOrdem', [App\Http\Controllers\app\OrdemController::class, 'viewOrdem'])->name('viewOrdem');
    Route::get('/cadOrdem', [App\Http\Controllers\app\OrdemController::class, 'cadOrdem'])->name('cadOrdem');
    Route::post('/cadOrdem', [App\Http\Controllers\app\OrdemController::class, 'cadOrdemSave'])->name('cadOrdem');
    Route::get('/edtOrdem', [App\Http\Controllers\app\OrdemController::class, 'edtOrdem'])->name('edtOrdem');
    Route::post('/edtOrdem', [App\Http\Controllers\app\OrdemController::class, 'edtOrdemUp'])->name('edtOrdemUp');
    Route::get('/delOrdem', [App\Http\Controllers\app\OrdemController::class, 'delOrdem'])->name('delOrdem');
    Route::get('/atribOrdem', [App\Http\Controllers\app\OrdemController::class, 'atribOrdem'])->name('atribOrdem');
    Route::post('/atribOrdem', [App\Http\Controllers\app\OrdemController::class, 'atribOrdemUp'])->name('atribOrdemUp');
    Route::get('/viewExecOrdem/{dt_ini?}/{dt_fim?}/{tec?}', [App\Http\Controllers\app\OrdemController::class, 'viewExecOrdem'])->name('viewExecOrdem');
    Route::POST('/viewExecOrdem/{dt_ini?}/{dt_fim?}/{tec?}', [App\Http\Controllers\app\OrdemController::class, 'viewExecOrdem'])->name('viewExecOrdem');
    Route::get('/execOrdem', [App\Http\Controllers\app\OrdemController::class, 'execOrdem'])->name('execOrdem');
    Route::post('/execOrdem', [App\Http\Controllers\app\OrdemController::class, 'execOrdemSave'])->name('execOrdemSave');
    Route::get('/loadControle', [App\Http\Controllers\app\OrdemController::class, 'loadControle'])->name('loadControle');
    Route::get('/loadMac', [App\Http\Controllers\app\OrdemController::class, 'loadMac'])->name('loadMac');
    Route::get('/viewItensOrdem', [App\Http\Controllers\app\OrdemController::class, 'viewItensOrdem'])->name('viewItensOrdem');

    ################## Import
    Route::get('/importOrdem', [App\Http\Controllers\app\ImportController::class, 'importOrdem'])->name('importOrdem');
    Route::post('/importOrdemSave', [App\Http\Controllers\app\ImportController::class, 'importOrdemSave'])->name('importOrdemSave');
    Route::get('/importMaterial', [App\Http\Controllers\app\ImportController::class, 'importMaterial'])->name('importMaterial');
    Route::post('/importMaterialSave', [App\Http\Controllers\app\ImportController::class, 'importMaterialSave'])->name('importMaterialSave');

    ################## Teste
    Route::get('/testeHora', [App\Http\Controllers\app\OrdemController::class, 'testeHora'])->name('testeHora');

    ################## Relatorios
    Route::get('/reportOrdem', [App\Http\Controllers\app\ReportOrdemController::class, 'reportOrdem'])->name('reportOrdem');
    Route::get('/reportFinanceiro', [App\Http\Controllers\app\ReportFinanceiroController::class, 'reportFinanceiro'])->name('reportFinanceiro');
    Route::get('/reportProducao/{dt_ini?}/{dt_fim?}/{tec?}', [App\Http\Controllers\app\ReportProducaoController::class, 'reportProducao'])->name('reportProducao');
    Route::get('/reportComissao', [App\Http\Controllers\app\ReportComissaoController::class, 'reportComissao'])->name('reportComissao');
    Route::get('/reportUltimaPosicao', [App\Http\Controllers\app\ReportUltimaPosicaoController::class, 'reportUltimaPosicao'])->name('reportUltimaPosicao'); 

    ################## Valor Servico
    Route::get('/viewValorServico', [App\Http\Controllers\app\ValoreServicoController::class, 'viewValorServico'])->name('viewValorServico');
    Route::get('/cadValorServico', [App\Http\Controllers\app\ValoreServicoController::class, 'cadValorServico'])->name('cadValorServico');
    Route::post('/cadValorServico', [App\Http\Controllers\app\ValoreServicoController::class, 'cadValorServicoSave'])->name('cadValorServicoSave');
    Route::get('/delValorServico', [App\Http\Controllers\app\ValoreServicoController::class, 'delValorServico'])->name('delValorServico');
    Route::get('/copyValorServico', [App\Http\Controllers\app\ValoreServicoController::class, 'copyValorServico'])->name('copyValorServico');
    Route::post('/copyValorServico', [App\Http\Controllers\app\ValoreServicoController::class, 'copyValorServicoSave'])->name('copyValorServicoSave');

    ################## Link Container
    Route::get('/viewLinkContainer', [App\Http\Controllers\app\LinkContainerController::class, 'viewLinkContainer'])->name('viewLinkContainer');
    Route::get('/cadLinkContainer', [App\Http\Controllers\app\LinkContainerController::class, 'cadLinkContainer'])->name('cadLinkContainer');
    Route::post('/cadLinkContainer', [App\Http\Controllers\app\LinkContainerController::class, 'cadLinkContainerSave'])->name('cadLinkContainerSave');
    Route::get('/delLinkContainer', [App\Http\Controllers\app\LinkContainerController::class, 'delLinkContainer'])->name('delLinkContainer');
    Route::get('/copyLinkContainer', [App\Http\Controllers\app\LinkContainerController::class, 'copyLinkContainer'])->name('copyLinkContainer');
    Route::post('/copyLinkContainer', [App\Http\Controllers\app\LinkContainerController::class, 'copyLinkContainerSave'])->name('copyLinkContainer');

    ################## Custo
    Route::get('/viewCusto', [App\Http\Controllers\app\CustoController::class, 'index'])->name('viewCusto');
    Route::get('/cadCusto', [App\Http\Controllers\app\CustoController::class, 'create'])->name('cadCusto');
    Route::post('/cadCusto', [App\Http\Controllers\app\CustoController::class, 'store'])->name('cadCusto');
    Route::get('/delCusto/{id?}', [App\Http\Controllers\app\CustoController::class, 'destroy'])->name('delCusto');

    ################## Meta
    Route::get('/viewMeta', [App\Http\Controllers\app\MetaController::class, 'index'])->name('viewMeta');
    Route::get('/cadMeta', [App\Http\Controllers\app\MetaController::class, 'create'])->name('cadMeta');
    Route::post('/cadMeta', [App\Http\Controllers\app\MetaController::class, 'store'])->name('cadMeta');
    Route::get('/delMeta/{id?}', [App\Http\Controllers\app\MetaController::class, 'destroy'])->name('delMeta');


    ################## Indicadores
    Route::get('/indicador', [App\Http\Controllers\app\IndicadorController::class, 'index'])->name('indicador.index');
    Route::get('/indicador/create', [App\Http\Controllers\app\IndicadorController::class, 'create'])->name('indicador.create');
    Route::post('/indicador', [App\Http\Controllers\app\IndicadorController::class, 'store'])->name('indicador.store');
    Route::get('/indicador/{id}', [App\Http\Controllers\app\IndicadorController::class, 'edit'])->name('indicador.edit');
    Route::put('/indicador', [App\Http\Controllers\app\IndicadorController::class, 'update'])->name('indicador.update');
    Route::get('/indicador/destroy/{id}', [App\Http\Controllers\app\IndicadorController::class, 'destroy'])->name('indicador.destroy');


    ################## Pontos
    Route::get('/ponto', [App\Http\Controllers\app\PontoController::class, 'index'])->name('ponto.index');
    Route::get('/ponto/create', [App\Http\Controllers\app\PontoController::class, 'create'])->name('ponto.create');
    Route::post('/ponto', [App\Http\Controllers\app\PontoController::class, 'store'])->name('ponto.store');
    Route::get('/ponto/{id}', [App\Http\Controllers\app\PontoController::class, 'edit'])->name('ponto.edit');
    Route::put('/ponto', [App\Http\Controllers\app\PontoController::class, 'update'])->name('ponto.update');
    Route::get('/ponto/destroy/{id}', [App\Http\Controllers\app\PontoController::class, 'destroy'])->name('ponto.destroy');



    ################## Regras
    Route::get('/regra', [App\Http\Controllers\app\RegraController::class, 'index'])->name('regra.index');
    Route::get('/regra/create', [App\Http\Controllers\app\RegraController::class, 'create'])->name('regra.create');
    Route::post('/regra', [App\Http\Controllers\app\RegraController::class, 'store'])->name('regra.store');
    Route::get('/regra/{id}', [App\Http\Controllers\app\RegraController::class, 'edit'])->name('regra.edit');
    Route::put('/regra', [App\Http\Controllers\app\RegraController::class, 'update'])->name('regra.update');
    Route::get('/regra/destroy/{id}', [App\Http\Controllers\app\RegraController::class, 'destroy'])->name('regra.destroy');

    
});