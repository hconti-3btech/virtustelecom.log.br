<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\ItenContainer;
use App\Models\Movimento;
use App\Models\Ordem;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\User;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdemController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except(['index']);
    }

    public function viewOrdem(Request $request){
        $this->authorize('controle_supervisor_apoio');

        $dataForm = $request->all();

        $oldDtIni = $request->data_ini;
        $oldDtFim = $request->data_fim;

        isset($dataForm['data_ini']) ? $dataForm['data_ini'] = $request->data_ini.' 00:00:00' : '';
        isset($dataForm['data_fim']) ? $dataForm['data_fim'] = $request->data_fim.' 23:59:59' : '';


        strlen($request->data_ini) ? $data_ini = $dataForm['data_ini'] : $data_ini =  date('Y-m-d 00:00:00');
        strlen($request->data_fim) ? $data_fim = $dataForm['data_fim'] : $data_fim = date("Y-m-d 23:59:59");

        $ordens = Ordem::whereBetween('dt_abertura', [$data_ini, $data_fim])->get();

        //Trata para apresentar na tabela
        $tabelaItens = [];

        //Cria tabelaItens
        $i = 0;
        foreach ($ordens as $ordem) {

            $timeZone = new DateTimeZone('UTC');
            
            $tecnico = User::where('id',$ordem['id_user_tec'])->first();

            empty($tecnico) ? $tecnico = new User() : '';

            $servico = Servico::where('id',$ordem['id_servico_tipo'])->first();

            $dt_abertura = DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_abertura'], $timeZone)->format('d/m/Y');

            $dt_fechamento = DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_fechamento'], $timeZone) > '2022-01-01 00:01:00' ? DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_fechamento'], $timeZone)->format('d/m/Y') : '';

            switch ($ordem['status']) {
                case 'atribuida':
                    $corStatus  = 'success';
                    $texStatus  = 'Atribuida';
                    break;
                
                case 'informada':
                    $corStatus  = 'secondary';
                    $texStatus  = 'Informada';
                    break;
                
                case 'cancelada':
                    $corStatus  = 'danger';
                    $texStatus  = 'Cancelada';
                    break;
                
                case 'finalizada':
                    $corStatus  = 'primary';
                    $texStatus  = 'Finalizada';
                    break;
                default:
                    $corStatus  = 'info';
                    $texStatus  = 'Aberta';
                    break;
            }

            $tabelaItens[$i] = [
                'id'        =>  $ordem['id'],
                'ordem'     =>  $ordem['ordem'],
                'cliente'   =>  $ordem['nome_cli'],
                'tecnico'   =>  $tecnico->name,
                'servico'   =>  $servico['nome'],
                'data_abe'  =>  $dt_abertura,
                'data_fec'  =>  $dt_fechamento,
                'corStatus' =>  $corStatus,
                'texStatus' =>  $texStatus,
            ];

            $i++;
        }

        $ordens = $tabelaItens;

        $url = $_SERVER["REQUEST_URI"];
                
        return view('app.ordens.viewOrdens')
        ->with([
            'ordens'    =>  $ordens,
            'msg'       =>  $request->msg,
            'data_ini'  =>  $oldDtIni,
            'data_fim'  =>  $oldDtFim,
            'url'   => $url,
        ]);
    }

    public function cadOrdem(Request $request){
        $this->authorize('controle');

        $usuarios = User::where('nv_acesso','tecnico')->where('status','ativo')->get();

        $servicos = Servico::where('status','ativo')->get();

        $data_ini = DATE('Y-m-d');

        return view('app.ordens.cadOrdem')
        ->with([
            'usuarios'  =>  $usuarios,
            'servicos'  =>  $servicos,
            'data_ini'  =>  $data_ini,
        ]);

    }

    public function cadOrdemSave(Request $request){
        $this->authorize('controle');

        $dt_abertura = str_replace("/", "-", $request->dt_abertura);
        $dt_abertura = date('Y-m-d', strtotime($dt_abertura));

        $request->validate([
            'ordem' => 'required|integer',
            'id_servico_tipo' => 'required|integer',
            'dt_abertura' => 'required|date',
        ]);

        $ordem = new Ordem();

        $ordem->ordem = $request->ordem;
        $ordem->cdc = $request->cdc;
        $ordem->nome_cli = $request->nome_cli;
        $ordem->id_servico_tipo = $request->id_servico_tipo;
        $ordem->id_user_tec = $request->id_user_tec;
        $ordem->dt_abertura = $dt_abertura;
        $ordem->status = $request->id_user_tec == '' ? 'aberta' : 'atribuida';
        $ordem->save();

        return redirect()
            ->route('viewOrdem')
            ->with([
                'msg'   =>  'Ordem Adicionada com Sucess',
                'type'  =>  'success'
            ]);

    }

    public function atribOrdem(Request $request){
        $this->authorize('controle_supervisor_apoio');

        $url = $_GET['url'];

        $timeZone = new DateTimeZone('UTC');

        $ordem = Ordem::where('id',$request->id)->first();

        $servico = Servico::where('id',$ordem['id_servico_tipo'])->first();

        $dt_abertura = DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_abertura'], $timeZone)->format('d/m/Y');

        $usuarios = User::where('nv_acesso','tecnico')->where('status','ativo')->get();
        
        $tabelaItens = [
            'id'        =>  $ordem['id'],
            'cdc'       =>  $ordem['cdc'],
            'ordem'     =>  $ordem['ordem'],
            'cliente'   =>  $ordem['nome_cli'],
            'tecnico'   =>  $ordem['id_user_tec'],
            'servico'   =>  $servico['nome'],
            'data_abe'  =>  $dt_abertura,
            ];

        $ordem = $tabelaItens;        
        

        return view('app.ordens.atribOrdem')
        ->with([
            'ordem'     =>  $ordem,
            'usuarios'  =>  $usuarios,
            'url'       =>  $url,
        ]);

    }

    public function atribOrdemUp(Request $request){
        $this->authorize('controle_supervisor_apoio');

        $dataForm = $request->all();

        $url = $dataForm['url'];

        $ordem = Ordem::where('id',$request->id)->first();
        
        $movimento = Movimento::where('ordem',$ordem->id)->first();

        if (empty($movimento)){
            Ordem::where('id',$request->id)->update([
                'id_user_tec' => $request->id_user_tec,
                'status' => $request->id_user_tec == '' ? 'aberta' : 'atribuida',
            ]);
        }else{
            return redirect()
            ->route('viewOrdem')
            ->with([
                'msg'   =>  'A ordem só pode ser ATRIBUIDA caso nao existam movimentos relacionados a ela',
                'type'  =>  'warning'
            ]);

        }

        return redirect($url)
            // ->route('viewOrdem')
            ->with([
                'msg'   =>  'Ordem Atualizada com Sucesso',
                'type'  =>  'success'
            ]);


    }

    public function edtOrdem(Request $request){
        $this->authorize('controle');

        $url = $_GET['url'];

        $usuarios = User::where('nv_acesso','tecnico')->where('status','ativo')->get();

        $servicos = Servico::where('status','ativo')->get();

        $data_ini = DATE('Y-m-d');

        $ordem = Ordem::where('id',$request->id)->first();

        return view('app.ordens.edtOrdem')
        ->with([
            'usuarios'  =>  $usuarios,
            'servicos'  =>  $servicos,
            'data_ini'  =>  $data_ini,
            'ordem'     =>  $ordem,
            'url'       =>  $url,
        ]);

    }

    public function edtOrdemUp(Request $request){
        $this->authorize('controle');

        $dataForm = $request->all();

        $url = $dataForm['url'];

        $request->validate([
            'ordem' => 'required|integer',
            'id_servico_tipo' => 'required|integer',
            'dt_abertura' => 'required|date',
        ]);

        $ordem = Ordem::where('id',$request->id)->first();
        if ($ordem->status == 'aberta' || $ordem->status == 'atribuida'){
            Ordem::where('id',$request->id)->update([
                'id_user_tec' => $request->id_user_tec,
                'ordem' => $request->ordem,
                'cdc' => $request->cdc,
                'nome_cli' => $request->nome_cli,
                'id_servico_tipo' => $request->id_servico_tipo,
                'id_user_tec' => $request->id_user_tec,
                'status' => $request->id_user_tec == '' ? 'aberta' : 'atribuida',
            ]); 
        }else{
            
            return redirect($url)
            //->route('viewOrdem')
            ->with([
                'msg'   =>  'A ordem so pode ser alterada se ABERTA ou ATRIBUIDA',
                'type'  =>  'warning'
            ]);

        }
        
        
        return redirect($url)
            //->route('viewOrdem')
            ->with([
                'msg'   =>  'Ordem Atualizada com Sucesso',
                'type'  =>  'success'
            ]);


    }

    public function delOrdem(Request $request){
        $this->authorize('controle');

        $dataForm = $request->all();

        $url = $dataForm['url'];
        
        $ordem = Ordem::where('id',$dataForm['id'])->first();

        $movimento = Movimento::where('ordem',$ordem->ordem)->first();     

        if (empty($movimento)){

            Ordem::where('id',$request->id)->delete();  

            return redirect($url)
                //->route('viewOrdem')
                ->with([
                    'msg'   =>  'Ordem Deletada com Sucesso',
                    'type'  =>  'danger'
                ]);

        }

        return redirect($url)
                //->route('viewOrdem')
                ->with([
                    'msg'   =>  'Ordem não pode ser deleta por existir movimentos atrelados a ela',
                    'type'  =>  'danger'
                ]);

    }

    public function viewExecOrdem(Request $request, $dt_ini = null, $dt_fim = null, $tec = null){

        $this->authorize('ativo');

        $user = Auth::user();
        
        $showFiltro = true;

        $dataForm = $request->all();

        $oldDtIni = $request->data_ini;
        $oldDtFim = $request->data_fim;

        //Data INI
        if (isset($dt_ini)){
            
            $data_ini = $dt_ini.' 00:01:00';

            $showFiltro = false;

        }elseif (isset($dataForm['data_ini'])){
            
            $data_ini = $dataForm['data_ini'].' 00:00:00';

        }else{
            
            $data_ini = DATE('Y-m-d'). ' 00:00:00';

        }

        //Data FIM
        if (isset($dt_fim)){
            
            $data_fim = $dt_fim.' 23:59:00';

            $showFiltro = false;

        }elseif (isset($dataForm['data_fim'])){

            $data_fim = $dataForm['data_fim'].' 23:59:59';

        }else{
            
            $data_fim = DATE('Y-m-d'). ' 23:59:59';

        }

        //Condicoes SQL
        $condicoes = [
            isset($tec) ? 'id_user_tec = "'.$tec.'" ':NULL,
            isset($data_ini) ? 'dt_abertura >= "' .$data_ini. '" ' : NULL,
            isset($data_fim) ? 'dt_abertura <= "' .$data_fim. '" ' : NULL,
            $user->nv_acesso != 'admin' ? 'id_user_tec = '.$user->id.' AND status <> "aberta"'  : NULL, 
        ];

        $condicoes = array_filter($condicoes);

        //Clausulas
        $where = implode (' AND ',$condicoes);

        $sql = "select * from ordems where $where ";

        //retorna Obj
        $ordens = DB::select($sql);

        //Converte para array
        $ordens = json_decode(json_encode($ordens), true);

        //Trata para apresentar na tabela
        $tabelaItens = [];

        //Data
        $dataMin = DateTime::createFromFormat('d/m/Y' , '01/01/2022');

        //Cria tabelaItens
        $i = 0;
        foreach ($ordens as $ordem) {

            $timeZone = new DateTimeZone('UTC');
            
            $tecnico = User::where('id',$ordem['id_user_tec'])->first();

            empty($tecnico) ? $tecnico = new User() : '';

            $servico = Servico::where('id',$ordem['id_servico_tipo'])->first();

            $dt_abertura = DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_abertura'], $timeZone)->format('d/m/Y');

            $dt_fechamento = DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_fechamento'], $timeZone) > '2022-01-01 00:01:00' ? DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_fechamento'], $timeZone)->format('d/m/Y') : '';


            switch ($ordem['status']) {
                case 'atribuida':
                    $corStatus  = 'success';
                    $texStatus  = 'Atribuida';
                    break;
                
                case 'informada':
                    $corStatus  = 'secondary';
                    $texStatus  = 'Informada';
                    break;
                
                case 'cancelada':
                    $corStatus  = 'danger';
                    $texStatus  = 'Cancelada';
                    break;
                
                case 'finalizada':
                    $corStatus  = 'primary';
                    $texStatus  = 'Finalizada';
                    break;
                default:
                    $corStatus  = 'info';
                    $texStatus  = 'Aberta';
                    break;
            }

            $tabelaItens[$i] = [
                'id'        =>  $ordem['id'],
                'ordem'     =>  $ordem['ordem'],
                'cliente'   =>  $ordem['nome_cli'],
                'tecnico'   =>  $tecnico->name,
                'servico'   =>  $servico['nome'],
                'data_abe'  =>  $dt_abertura,
                'data_fec'  =>  $dt_fechamento,
                'status'    =>  $ordem['status'],
                'corStatus' =>  $corStatus,
                'texStatus' =>  $texStatus,
            ];

            $i++;
        }

        $ordens = $tabelaItens;

        $url = $_SERVER["REQUEST_URI"];
        
        return view('app.ordens.viewExecOrdem')
        ->with([
            'ordens'    =>  $ordens,
            'msg'       =>  $request->msg,
            'data_ini'  =>  $oldDtIni,
            'data_fim'  =>  $oldDtFim,
            'url'       =>  $url,
            'showFiltro' =>  $showFiltro,
        ]);
    }

    public function execOrdem(Request $request){

        $this->authorize('ativo');
        
        $user = Auth::user();

        $url = $_GET['url'];

        $timeZone = new DateTimeZone('UTC');

        $ordem = Ordem::where('id',$request->id)->first();

        $servico = Servico::where('id',$ordem['id_servico_tipo'])->first();

        $dt_abertura = DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_abertura'], $timeZone)->format('d/m/Y');

        $tecnico = User::where('id',$ordem['id_user_tec'])->first();

        if (empty($tecnico)){
            return redirect()
            ->route('viewExecOrdem')
            ->with(['msg'   =>  'Técnico nao atribuido',
                    'type'  =>  'warning']); 
        }
        
        $tabelaItens = [
            'id'        =>  $ordem['id'],
            'cdc'       =>  $ordem['cdc'],
            'ordem'     =>  $ordem['ordem'],
            'cliente'   =>  $ordem['nome_cli'],
            'tecnico'   =>  $tecnico['name'],
            'id_user_tec'   =>  $ordem['id_user_tec'],
            'servico'   =>  $servico['nome'],
            'data_abe'  =>  $dt_abertura,
            'status'  =>  $ordem['status'],
        ];

        $ordem = $tabelaItens;

        $sql = "select * from produtos where id IN (SELECT id_produto FROM iten_containers where qtd > 0 and id_container in (SELECT `id` from containers where id_user = '$user->id')) and status = 'ativo';";
        
        $produtos = DB::select($sql);

        $produtosRetirada = Produto::where('status','ativo')->get();
        
        return view('app.ordens.execOrdem')
        ->with([
            'ordem'     =>  $ordem,
            'produtos'  =>  $produtos,
            'produtosRetirada' => $produtosRetirada,
            'user'      =>  $user,
            'url'       =>  $url,
        ]);
    }

    public function loadControle(Request $request){

        $this->authorize('ativo');

        $dataForm = $request->all();
        $id_produto = $dataForm['id_produto'];
        $id_usuario = $dataForm['id_usuario'];

        $sql = "select * from iten_containers where id_produto = '$id_produto' and id_container in (select id from containers where id_user = '$id_usuario')";

        $controles = DB::select($sql);

        return view('app.ordens.controle_ajax', ['controles' => $controles]);
    }

    public function loadMac(Request $request){

        $this->authorize('ativo');

        $dataForm = $request->all();
        $controle = $dataForm['controle'];
        $id_produto = $dataForm['id_produto'];

        $sql = "SELECT * FROM iten_containers WHERE id_produto = '$id_produto' and controle = '$controle'";

        $itenContainer = DB::select($sql);

        return view('app.ordens.mac_ajax', [
            'itenContainer' => $itenContainer,
        ]);
    }

    public function execOrdemSave(Request $request){

        $this->authorize('ativo');

        $request->validate([
            'id' => 'required|integer',
            'status' => 'required',
        ]);
        
        $user = Auth::user();

        $dataForm = $request->all();

        $url = $dataForm['url'];

        $dt_fechamento = DATE("Y-m-d H:i:s");
        
        $ordem = Ordem::where('id',$request->id)->first();

        $container = Container::where('id_user', $request->id_user_tec)->first();

    
        if ($ordem->status == 'atribuida'){

            if ($request->status == 'cancelada' || $request->status == 'informada'){
        
                //atualizar ordem 
                $ordem->update([
                    'status' => $request->status,
                    'motivo' => $request->motivo,
                    'dt_fechamento' => $dt_fechamento,
                ]);    
    
                
            }elseif($request->status == 'finalizada'){

                
                
                $dataForm['produto'] = array_filter($dataForm['produto']);
                if (!empty($dataForm['produto'])){

                    //verifica se tem produtos duplicados
                    for ($i=0; $i < count($dataForm['produto'])-1; $i++) { 

                        for ($x = $i+1; $x < count($dataForm['produto']); $x++){
                            
                            if ($dataForm['produto'][$i] == $dataForm['produto'][$x]){

                                $produto = Produto::where('id',$request->produto[$i])->first();

                                return redirect($url)
                                //->route('viewExecOrdem')
                                ->with(['msg'   =>  'Produto '.$produto->nome.' duplicado',
                                        'type'  =>  'warning']);
                            }
                        } 
                    }
                    

                    //sempre add um produto para ativacao (id_servico_tipo 1)
                    if (empty($request->produto[0]) && $ordem->id_servico_tipo == 1 && $request->status == 'finalizada'){
                        return redirect($url)
                        //->route('viewExecOrdem')
                        ->with(['msg'   =>  'É necessario adicionar um produto ao menos para finalizar uma ordem desse tipo',
                                'type'  =>  'warning']);
                    }
                        


                    //verifica se tem quatidade suficiente
                    for ($i=0; $i < count($dataForm['produto']); $i++) {

                        $itenContainer = ItenContainer::where('id_container', $container->id)->where('id_produto', $request->produto[$i])->where('controle',$request->controle[$i])->first();

                        $produto = Produto::where('id',$request->produto[$i])->first();
                        
                        if(empty($produto)){
                            return redirect($url)
                            //->route('viewExecOrdem')
                            ->with(['msg'   =>  'O Produto'.$i.' nao existe',
                                    'type'  =>  'warning']);
                        }
                        
                        if(!empty($itenContainer)){
                            if($itenContainer->qtd - $request->qtd[$i] < 0){
                                return redirect($url)
                                //->route('viewExecOrdem')
                                ->with(['msg'   =>  'Produto '.$produto->nome.' quantidade insuficiente',
                                        'type'  =>  'warning']);
                            }
                        }else{
                            return redirect($url)
                                //->route('viewExecOrdem')
                                ->with(['msg'   =>  'Produto nao existe para esse container ',
                                        'type'  =>  'warning']);
                        }

                    }

                    for ($i=0; $i < count($dataForm['produto']); $i++) {

                        $produto = Produto::where('id',$request->produto[$i])->first();

                        if(empty($produto)){
                            return redirect($url)
                            //->route('viewExecOrdem')
                            ->with(['msg'   =>  'O Produto'.$i.' nao existe',
                                    'type'  =>  'warning']);
                        }

                        //remove do estoque
                        $itenContainer = ItenContainer::where('id_container', $container->id)->where('id_produto', $request->produto[$i])->where('controle',$request->controle[$i])->first();
                        
                        if($request->qtd[$i] < 0){
                            return redirect($url)
                            //->route('viewExecOrdem')
                            ->with(['msg'   =>  'Produto '.$produto->nome.' com quantidade 0 ou negativa',
                                    'type'  =>  'warning']);
                        }


                        if (!empty($itenContainer)){

                            $qtd = $itenContainer->qtd - $request->qtd[$i];

                            if ($qtd == 0){
                                $itenContainerDel[$i] = $itenContainer->id;
                            }else{
                                $itenContainerUp[$i] = [
                                    'id' => $itenContainer->id,
                                    'qtd' => $qtd,
                                ];
                            }
                            

                        }else{
                                
                            return redirect($url)
                                //->route('viewExecOrdem')
                                ->with(['msg'   =>  'Produto '.$produto->nome.' com controle '.$request->controle[$i].' nao existe na origem',
                                        'type'  =>  'warning']);
                            
                        }

                        $movimento[$i] = new Movimento;  

                        //cadastra Movimento
                        $movimento[$i]->id_origem   = $container->id;
                        $movimento[$i]->id_destino  = 3;
                        $movimento[$i]->id_produto  = $request->produto[$i];
                        $movimento[$i]->qtd         = $request->qtd[$i];
                        $movimento[$i]->controle    = strtoupper($request->controle[$i]);
                        $movimento[$i]->mac    = strtoupper($request->mac[$i]);
                        $movimento[$i]->solicitante = $user->id;
                        $movimento[$i]->aprovante   = $user->id;
                        $movimento[$i]->ordem   = $request->ordem;
                        $movimento[$i]->status = 'Confirmado';

                                                                    
                    }


                    //executa no bd
                    //deletas os itens caso qtd 0
                    if(!empty($itenContainerDel)){
                        
                        sort($itenContainerDel);
                        for ($i=0; $i < count($itenContainerDel); $i++) { 
                            ItenContainer::where('id',$itenContainerDel[$i])->delete();
                        
                        }
                    }
                    

                    //atualiza os itens com qtd > 0
                    if(!empty($itenContainerUp)){
                        sort($itenContainerUp);
                        for ($i=0; $i < count($itenContainerUp); $i++) { 
                            ItenContainer::where('id',$itenContainerUp[$i]['id'])->update([
                                'qtd' => $itenContainerUp[$i]['qtd'],
                            ]);
                        }
                    }


                    if(!empty($movimento)){
                        //cria os movimentos
                        sort($movimento);
                        for ($i=0; $i < count($movimento); $i++) { 
                            $movimento[$i]->save();
                        }
                    }
                }
                

                if (isset($dataForm['produto-retirada'])){
                    
                    $dataForm['produto-retirada'] = array_filter($dataForm['produto-retirada']);
                    if (!empty($dataForm['produto-retirada'])){

                        for ($i=0; $i < count($dataForm['produto-retirada']); $i++) {

                            //verifica se o prduto existe
                            $produto = Produto::where('id',$dataForm['produto-retirada'][$i])->first();

                            if(empty($produto)){
                                return redirect($url)
                                //->route('viewExecOrdem')
                                ->with(['msg'   =>  'O Produto'.$i.' nao existe',
                                        'type'  =>  'warning']);
                            }

                            //verifica se tem o produto no itens container
                            $itenContainer = ItenContainer::where('id_container', $container->id)->where('id_produto', $dataForm['produto-retirada'][$i])->where('controle',$dataForm['controle-retirada'][$i])->first();

                            if (empty($itenContainer)){//salva

                                $itenContainerSalva[$i] = new ItenContainer();  

                                $itenContainerSalva[$i]->id_container = $container->id;
                                $itenContainerSalva[$i]->id_produto = $dataForm['produto-retirada'][$i];
                                $itenContainerSalva[$i]->qtd = $dataForm['qtd-retirada'][$i];
                                $itenContainerSalva[$i]->controle = strtoupper($dataForm['controle-retirada'][$i]);
                                $itenContainerSalva[$i]->mac = strtoupper($dataForm['mac-retirada'][$i]);

                            }else{//atualiza

                                $qtd = $itenContainer->qtd + $dataForm['qtd-retirada'][$i];

                                $itenContainerUp[$i] = [
                                    'id' => $itenContainer->id,
                                    'qtd' => $qtd,
                                ];
                            }

                            $movimento[$i] = new Movimento;  

                            //cadastra Movimento
                            $movimento[$i]->id_origem   = 3;
                            $movimento[$i]->id_destino  = $container->id;
                            $movimento[$i]->id_produto  = $dataForm['produto-retirada'][$i];
                            $movimento[$i]->qtd         = $dataForm['qtd-retirada'][$i];
                            $movimento[$i]->controle    = strtoupper($dataForm['controle-retirada'][$i]);
                            $movimento[$i]->mac    = strtoupper($dataForm['mac-retirada'][$i]);
                            $movimento[$i]->solicitante = $user->id;
                            $movimento[$i]->aprovante   = $user->id;
                            $movimento[$i]->ordem   = $request->ordem;
                            $movimento[$i]->status = 'Confirmado';

                        }

                    }
                }
                

                //executa no bd
                //atualiza os itens com qtd > 0
                if(!empty($itenContainerUp)){
                    sort($itenContainerUp);
                    for ($i=0; $i < count($itenContainerUp); $i++) { 
                        ItenContainer::where('id',$itenContainerUp[$i]['id'])->update([
                            'qtd' => $itenContainerUp[$i]['qtd'],
                        ]);
                    }
                }


                if(!empty($itenContainerSalva)){
                    //cria os movimentos
                    sort($itenContainerSalva);
                    for ($i=0; $i < count($itenContainerSalva); $i++) { 
                        $itenContainerSalva[$i]->save();
                    }
                }

                if(!empty($movimento)){
                    //cria os movimentos
                    sort($movimento);
                    for ($i=0; $i < count($movimento); $i++) { 
                        $movimento[$i]->save();
                    }
                }


            
                //atualizar ordem 
                $ordem->update([
                    'status' => $request->status,
                    'motivo' => $request->motivo,
                    'dt_fechamento' => $dt_fechamento,
                ]);
            
                
            }else{
                return redirect($url)
                    //->route('viewExecOrdem')
                    ->with(['msg'   =>  'Selecione um Status Valido',
                            'type'  =>  'warning']);
            }

            return redirect($url)
                //->route('viewExecOrdem')
                ->with([
                    'msg'   =>  'Ordem Atualizada com Sucesso',
                    'type'  =>  'success'
                ]);

        }elseif ($ordem->status == 'aberta'){

            return redirect($url)
            //->route('viewExecOrdem')
            ->with(['msg'   =>  'Tecnico não atribuido',
                    'type'  =>  'warning']);

        }else{

            return redirect($url)
            //->route('viewExecOrdem')
            ->with(['msg'   =>  'Ordem Ja Finalizada/Cancelada/Informada',
                    'type'  =>  'warning']);
        }

    }

    public function viewItensOrdem(Request $request){
        
        $this->authorize('ativo');
        
        $user = Auth::user();

        $timeZone = new DateTimeZone('UTC');

        $ordem = Ordem::where('id',$request->id)->first();

        $servico = Servico::where('id',$ordem['id_servico_tipo'])->first();

        $dt_abertura = DateTime::createFromFormat('Y-m-d H:i:s' , $ordem['dt_abertura'], $timeZone)->format('d/m/Y');

        $tecnico = User::where('id',$ordem['id_user_tec'])->first();

        if (empty($tecnico)){
            return redirect()
            ->route('viewExecOrdem')
            ->with(['msg'   =>  'Técnico nao atribuido',
                    'type'  =>  'warning']); 
        }
        
        $tabelaItens = [
            'id'        =>  $ordem['id'],
            'cdc'       =>  $ordem['cdc'],
            'ordem'     =>  $ordem['ordem'],
            'cliente'   =>  $ordem['nome_cli'],
            'tecnico'   =>  $tecnico['name'],
            'id_user_tec'   =>  $ordem['id_user_tec'],
            'servico'   =>  $servico['nome'],
            'data_abe'  =>  $dt_abertura,
            'status'  =>  $ordem['status'],
            'motivo'  =>  $ordem['motivo'],
        ];

        $ordem = $tabelaItens;

        $movimentos = Movimento::where('ordem', $ordem['ordem'])->get();

        //Cria tabelaItens
        $i = 0;
        $tabelaItensMovimentos = [];
        foreach ($movimentos as $movimento) {

            $produto = Produto::where('id', $movimento['id_produto'])->first();

            $tabelaItensMovimentos[$i] = [
                'id'        =>  $movimento['id'],
                'produto'   =>  $produto['nome'],
                'qtd'       =>  $movimento['qtd'],
                'controle'  =>  $movimento['controle'],
                'mac'       =>  $movimento['mac'],
                'ordem'     =>  $movimento['ordem'],
            ];

            $i++;
        }
                
        return view('app.ordens.viewItensOrdem')
        ->with([
            'ordem'     =>  $ordem,
            'tabelaItensMovimentos'  =>  $tabelaItensMovimentos,
            'user'      =>  $user,
        ]);
    }

    public function testeHora()
    {
        echo Date('Y-m-d H:i:s');
    }
}