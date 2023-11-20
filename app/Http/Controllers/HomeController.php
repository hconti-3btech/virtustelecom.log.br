<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Movimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index($msg = null)
    {   
        return view('home')
        ->with([
            'msg' => $msg,
        ]);

        // $array = ["216930","307255","449834","521105","526970","527207","536819","537389","539129","600386","604383","622188","623976","677149","677917","684866","685831","690945","697687","699783","699962","702341","775908","778756","779135","783122","825515","825516","826313","873980","876626","877367","880479","881950","882865","883109","892503","912835","912838","913694","913695","913696","913697","913698","913701","913702","913703","929286","929289","929290","953424","953534","953535","953536","953537","953538","953714","953715","953716","953717","953718","953719","953720","953721","953722","953774","953775","953776","953777","953778","953779","953780","953784","953785","953786","953787","953788","953789","953790","953792","953824","953825","953826","953827","953828","953829","953830","953831","953832","953833","953844","953845","953846","953847","953848","953849","953850","953851","953852","953853","953884","953885","953886","953887","953888","953889","953890","953901","953902","953903","954084","954085","954086","954087","954088","954089","954090","954091","954092","954093","954104","954105","954106","954107","954108","954109","954110","954111","954112","954113","962944","962945","962946","962947","962948","962949","962950","962951","962952","962953","962954","962955","962956","962957","962958","962959","962960","962961","962962","962963","962964","962965","962966","962967","962968","962969","962970","962971","962972","962973","962974","962975","962976","962977","962978","962979","962980","962981","962982","962983","962984","962985","962986","962987","962988","962989","962990","962991","962992","962993","962994","962995","962996","962997","962998","962999","963000","963001","963002","963003","963004","963005","963006","963007","963008","963009","963010","963011","963012","963013","963014","963015","963016","963017","963018","963019","963020","963021","963022","963023","963024","963025","963026","963027","963028","963029","963030","963031","963032","963033","963034","963035","963036","963037","963038","963039","963040","963041","963042","963043","963044","963045","963046","963047","963048","963049","963050","963051","963052","963053","963054","963055","963056","963057","963058","963059","963060","963061","963062","963063","963064","963065","963066","963067","963068","963069","963070","963071","963072","963073","963074","963075","963076","963077","963078","963079","963080","963081","963082","963083","963084","963085","963086","963087","963088","963089","963090","963091","963092","963093","963094","963095","963096","963097","963098","963099","963100","963101","963102","963103","963104","963105","963106","963107","963108","963109","963110","963111","963112","963113","963114","963115","963116","963117","963118","963119","963120","963121","963122","963123","963124","963125","963126","963127","963128","963129","963130","963131","963132","963133","963134","963135","963136","963137","963138","963139","963140","963141","963142","963143","963144","963145","963146","963147","963148","963149","963150","963151","963152","963153","963154","963155","963156","963157","963158","963159","963160","963161","963162","963163","963164","963165","963166","963167","963168","963169","963170","963171","963172","963173","963174","963175","963176","963177","963178","963179","963180","963181","963182","963183","963184","963185","963186","963187","963188","963189","963190","963191","963192","963193","963194","963195","963196","963197","963198","963199","963200","963201","963202","963203","963204","963205","963206","963207","963208","963209","963210","963211","963212","963213","963214","963215","963216","963217","963218","963219","963220","963221","963222","963223","963224","963225","963226","963227","963228","963229","963230","963231","963232","963233","963234","963235","963236","963237","963238","963239","963240","963241","963242","963243","963244","963245","963246","963247","963248","963249","963250","963251","963252","963253","963254","963255","963256","963257","963258","963259","963260","963261","963262","963263","963264","963265","963266","963267","963268","963269","963270","963271","963272","963273","963274","963275","963276","963277","963278","963279","963280","963281","963282","963283","963284","963285","963286","963287","963288","963289","963290","963291","963292","963293","963294","963295","963296","963297","963298","963299","963300","963301","963302","963303","993332","1007017","1022034","1081490","1132335","1206016","1210649","1229527","1229528","1229529","1229532","1229533","1229534","1229535","1229536","1229537","1229538","1229539","1230148","1230150","1230151","1230152","1230153","1230156","1230157","1230158","1230159","1230160","1230161","1230162","1230163","1230168","1230169","1230171","1230172","1230173","1230174","1230176","1230180","1230181","1230182","1230183","1230184","1230187","1230188","1230189","1230190","1230191","1230192","1230193","1230194","1230195","1230200","1230201","1230204","1230205","1230209","1230210","1230220","1230221","1230222","1230223","1230225","1230228","1230229","1230230","1230231","1230232","1230236","1230237","1230238","1230239","1230244","1230245","1230247","1230248","1230249","1230250","1230251","1230252","1230256","1230257","1230258","1230259","1230260","1230261","1230262","1230263","1230264","1230265","1230266","1230267","1230268","1230269","1230270","1230271","1230272","1230273","1230274","1230275","1230276","1230277","1230278","1230279","1230280","1230281","1230282","1230283","1230284","1230285","1230286","1230287","1230288","1230289","1230290","1230291","1230292","1230294","1230295","1230296","1230297","1230298","1230299","1230300","1230301","1230302","1230303","1230304","1230305","1230306","1230307","1230314","1230319","1230320","1230322","1230323","1230324","1230325","1230326","1230327","1230328","1230329","1230330","1230331","1230332","1230333","1230334","1230335","1230336","1230337","1230338","1230339","1230349","1230350","1230351","1230352","1230353","1230354","1230356","1230357","1230358","1230359","1230360","1230361","1230363","1230368","1230371","1230372","1230373","1230374","1230375","1230376","1230377","1230378","1230379","1230387","1230404","1230405","1230409","1230412","1230418","1230419","1230420","1230424","1230425","1230426","1230427","1230428","1230429","1230430","1230442","1230446","1230447","1230448","1230450","1230454","1230455","1230456","1230459","1230461","1230462","1230463","1230464","1230465","1230470","1230471","1230472","1230473","1230474","1230475","1230476","1230481","1230483","1230499","1230500","1230501","1230502","1230503","1230504","1230505","1230506","1230508","1230510","1230511","1230512","1230513","1230514","1230515","1230516","1230517","1230518","1230519","1230521","1230522","1230523","1230524","1230525","1230526","1230527","1230528","1230529","1230530","1230531","1230532","1230533","1230534","1230535","1230536","1230537","1230538","1230539","1230540","1230541","1230542","1230543","1230544","1230545","1230546","1230547","1230548","1230549","1230550","1230551","1230552","1230553","1230558","1230559","1230562","1230563","1230564","1230565","1230566","1230568","1230571","1230572","1230574","1230575","1230576","1230577","1230578","1230579","1230585","1230586","1230588","1230589","1230596","1230597","1230598","1230599","1230601","1230608","1230609","1230644","1230645","1230646","1230647","1230648","1230649","1230650","1230651","1230652","1230653","1230654","1230655","1230656","1230657","1230658","1230659","1230660","1230661","1230662","1230663","1230664","1230665","1230666","1230667","1230668","1230669","1230670","1230671","1230672","1230673","1230674","1230675","1230676","1230677","1230678","1230679","1230680","1230681","1230682","1230683","1230684","1230685","1230686","1230687","1230688","1230689","1230690","1230691","1230692","1230693","1230694","1230695","1230700","1230701","1230702","1230703","1230704","1230705","1230706","1230707","1230710","1230711","1230712","1230713","1230714","1230715","1230716","1230717","1230718","1230719","1230720","1230721","1230722","1230723","1230724","1230725","1230726","1230727","1230728","1230729","1230730","1230731","1230732","1230733","1230734","1230735","1230736","1230737","1230738","1230739","1230740","1230741","1230742","1230743","1230744","1230745","1230746","1230747","1230748","1230749","1230750","1230752","1230753","1230755","1230756","1230757","1230758","1230759","1230760","1230761","1230762","1230763","1230764","1230765","1230766","1230767","1230768","1230769","1230770","1230771","1230772","1230773","1230774","1230775","1230776","1230777","1230778","1230779","1230780","1230781","1230782","1230783","1230784","1230785","1230786","1230787","1230790","1230791","1230792","1230793","1230794","1230795","1230798","1230799","1230800","1230801","1230802","1230803","1230804","1230805","1230806","1230807","1230808","1230809","1230810","1230811","1230812","1230813","1230814","1230816","1230817","1230818","1230819","1230820","1230821","1230822","1230823","1230824","1230826","1230827","1230829","1230830","1230831","1230832","1230833","1230834","1230835","1230836","1230837","1230838","1230839","1230840","1230841","1230842","1230843","1230844","1230845","1230846","1230847","1230848","1230849","1230850","1230851","1230852","1230853","1230854","1230855","1230856","1230857","1230858","1230859","1230860","1230861","1230862","1230863","1230864","1230865","1230866","1230867","1232793","1232794","1232795"]; 

        // //$array = ["216930","307255","449834","521105","526970","527207","536819","537389","539129","600386","604383","622188","623976","677149","677917","684866","685831","690945","697687","699783","699962","702341","775908","778756","779135","783122","825515","825516","826313","873980","876626","877367","880479","881950","882865","883109","892503","912835","912838","913694","913695","913696","913697","913698","913701","913702","913703","929286","929289","929290","953424","953534","953535","953536","953537","953538","953714","953715","953716","953717","953718","953719","953720","953721","953722","953774","953775","953776","953777","953778","953779","953780","953784","953785","953786","953787","953788","953789","953790","953792","953824","953825","953826","953827","953828","953829","953830","953831","953832","953833"]; 

        // foreach ($array as $key => $value) {
        
        //     $sql = "SELECT * FROM `movimentos` WHERE controle = $value";

        //     //retorna Obj
        //     $movimentos = DB::select($sql);
            
        //     if (count($movimentos) > 0 ){
                
        //         $ultimo = $movimentos[0]->created_at;
        //         foreach ($movimentos as $movimento) {

        //             if ($ultimo <> null && $ultimo < $movimento->created_at) {
        //                 $ultimo = $movimento->created_at;
        //             }

        //         }  

        //         $resultados[] = Movimento::where('created_at',$ultimo)->where('controle',$movimento->controle)->first();
        //     }

        // }

        // foreach ($resultados as $resultado){
        //     $container = Container::find($resultado->id_destino);

        //     $resultado->local = $container->nome;
        // }

        // return view('app.testeß')->with([    
        //     'resultados' => $resultados,
        // ]);
    }



}
