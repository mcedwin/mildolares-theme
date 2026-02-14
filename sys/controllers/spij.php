<?php 

class spij
{
    var $db;
    var $year;

    function __construct() {
        $this->url_parent = "";
        wp_register_script( 'my-script', 'myscript_url' );
        wp_enqueue_script( 'my-script' );
        $translation_array = array( 'templateUrl' => get_template_directory_uri() );
        wp_localize_script( 'my-script', 'object_name', $translation_array );
        wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
        wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
    }

    public function reporte(){
        $res = $this->db->get_results("select count(*) a,count(recu_contenido) b from spij_recurso");
        echo "<table border=1>";
        echo "<tr><th>Todo</th><th>Leidos</th></tr>";
        foreach($res as $row){
            echo "<tr><td>{$row->a}</td><td>{$row->b}</td></tr>";
        }
        echo "</table>";


        $res = $this->db->get_results("select DATE(recu_fechareg) a,HOUR(recu_fechareg) b,count(*) c,count(recu_contenido) d from spij_recurso WHERE 1 GROUP BY DATE(recu_fechareg),HOUR(recu_fechareg) ORDER BY recu_fechareg DESC");
        echo "<table border=1>";
        echo "<tr><th>Fecha</th><th>Hora</th><th>Todo</th><th>Leidos</th></tr>";
        foreach($res as $row){
            echo "<tr><td>{$row->a}</td><td>{$row->b}</td><td>{$row->c}</td><td>{$row->d}</td></tr>";
        }
        echo "</table>";
        session_start();
        $_SESSION['HOla']=0;
    }

    public function admin_iniciar(){
        $this->get_web_page('http://spij.minjus.gob.pe/libre/access.asp?infobase=legcargen');
        $this->get_web_page('http://spij.minjus.gob.pe/libre/img/loading.gif');
        $this->get_web_page('http://spij.minjus.gob.pe/js/logout.js');
        $this->get_web_page('http://spij.minjus.gob.pe/CLP/contenidos.dll?f=templates&fn=default.htm&vid=Ciclope:CLPlegcargen');
        $this->get_web_page('http://spij.minjus.gob.pe/CLP/contenidos.dll?f=templates$fn=FormBusqueda.htm$3.0');
        $this->get_web_page('http://spij.minjus.gob.pe/CLP/contenidos.dll?f=templates$fn=contents-frame-h.htm$3.0&sel=0&tf=main&tt=document-frameset.htm&t=contents-frame-h.htm&och=onClick');
        $head = $this->get_web_page('http://spij.minjus.gob.pe/libre/main.asp');
        echo $head['content'];
    }

    public function salir(){
        unlink("cookie{$this->year}.txt");
    }

    public function esvalido($html){
        if(!empty($web['errmsg']))return -1;
        if(preg_match('/(ingrese nombre de usuario)/i',$html))return 0;
        if(preg_match('/CICLOPE no encontro documentos/i',$html))return 2;
        if(preg_match('/NO puede habilitar el documento/i',$html))return 0;
        if(preg_match('/(Server Error)/i',$html))return -1;
        return 1;
    }

    public function getFecha($str){
        if(empty($str)) return NULL;
        $meses = array("enero"=>1,"febrero"=>2,"marzo"=>3,"abril"=>4,"mayo"=>5,"junio"=>6,"julio"=>7,"agosto"=>8,"septiembre"=>9,"setiembre"=>9,"octubre"=>10,"noviembre"=>11,"diciembre"=>12);
        preg_match("/([0-9]{1,2}).+? ([a-z]+) .*?([0-9]{4})/i",$str,$arr);
        //print_r($arr);
       // exit(0);
        return $arr[3]."-".$meses[strtolower($arr[2])]."-".$arr[1];

    }
/*
    public function boot_titulo(){
        $recu = $this->db->query("SELECT recu_id,recu_contenido FROM spij_recurso WHERE recu_tipo='URL' AND recu_revisado_meta=0 AND recu_url_final IS NOT NULL LIMIT 1")->row();
        $id = $recu->recu_id;
        $contenido = $recu->recu_contenido;
        preg_match('/<span field="dia" style="display:none;">(.+?)<\/span>/is', $contenido, $afecha);
        preg_match('/<span field="sector" style="display:none;">(.+?)<\/span>/is', $contenido, $asector);
        preg_match('/<span field="sumilla" style="display:none;">(.+?)<\/span>/is', $contenido, $asumilla);
        preg_match('/<H2 CLASS=\'Norma\'>(.+?)<\/H2>/is', $contenido, $anorma);


        $fecha = @$this->db->escape($afecha[1]);
        $ifecha = @$this->db->escape($this->getFecha($afecha[1]));

        $sector = @$this->db->escape($asector[1]);
        $sumilla = @$this->db->escape($asumilla[1]);
        $norma = @$this->db->escape(trim(strip_tags($anorma[1])));
        $snorma = @trim(strip_tags($anorma[1]));

        $cates = $this->db->query("SELECT disp_id,disp_nombre,disp_name FROM spij_dispositivo")->result();

        $cate_id = NULL;
        if(!empty($snorma))
        foreach($cates as $cate):
            if(preg_match("/^{$cate->disp_nombre}/i",$snorma)||preg_match("/^{$cate->disp_name}/i",$snorma)){
                $cate_id = $cate->spij_id;
                break;
            }
        endforeach;
        $cate_id = @$this->db->escape($cate_id);
          
        $this->db->query("UPDATE spij_recurso SET recu_fecha_texto=$fecha,recu_fecha=$ifecha,recu_sector=$sector,recu_sumilla=$sumilla,recu_titulo=$norma,recu_cate_id=$cate_id,recu_revisado_meta=1 WHERE recu_id='{$id}'");

        //echo $contenido;
        echo '<meta http-equiv="refresh" content="0">';

    }

    public function boot_portal($ini=0) {
        echo "<pre>";
        echo date("Y-m-d H:i:s");
        $this->db->db_debug = FALSE;
        //AND DATE(NOW())=DATE(recu_fechareg)
        $recu = $this->db->get_row("SELECT recu_id,recu_url FROM spij_recurso WHERE (recu_tipo='URL')  AND recu_revisado=0 LIMIT {$ini},1");
        echo '<h1>'.$recu->recu_url . "</h1>\r\n";
        $web = $this->get_web_page($recu->recu_url);
        $html = $web['body'];
        $url = $web['url'];
        if($this->esvalido($web)==0){
            die("<h1>Error de session a spij</h1>".'<meta http-equiv="refresh" content="1">');
        }else if($this->esvalido($web)==0){
            $this->db->query("UPDATE spij_recurso SET recu_revisado=-1,recu_fecharev=NOW() WHERE recu_id='{$recu->recu_id}'");
            die("<h1>Error de </h1>".'<meta http-equiv="refresh" content="1">');
        }
        $firma = md5($html);
        echo $firma;
        $links = $this->get_links($url,$html);
        $contenido = $this->db->escape($html);

        $url = $this->db->escape($url);

        $this->db->query("UPDATE spij_recurso SET recu_contenido=$contenido,recu_url_final=$url,recu_revisado=recu_revisado+1,recu_fecharev=NOW(),recu_fechaupd=IF('{$firma}'=recu_firma,recu_fechaupd,NOW()),recu_firma='{$firma}' WHERE recu_id='{$recu->recu_id}'");
        
        foreach ($links as $link) {
            $nombre = $this->db->escape($link[0]);
            $link_firma = md5($link[1]);
            $link_fin = $this->db->escape($link[1]);
            $link_original = $this->db->escape($link[2]);
            $link_script = $this->db->escape($link[3]);
            $link_tipo = $link[4];
            echo $link_fin . "\r\n";
            $rq=$this->db->query("SELECT recu_id FROM spij_recurso WHERE recu_url_firma='{$link_firma}'");

            if($rq->num_rows()){
                $row = $rq->row();
                $this->db->query("UPDATE spij_recurso SET recu_padres=CONCAT(recu_padres,'-','{$recu->recu_id}') WHERE recu_id='{$row->recu_id}'");
            }else{
                $this->db->query("INSERT INTO spij_recurso(recu_nombre,recu_url,recu_url_firma,recu_url_original,recu_url_script,recu_fechareg,recu_fechaupd,recu_revisado,recu_tipo,recu_padres) VALUES({$nombre},{$link_fin},'{$link_firma}',{$link_original},{$link_script},NOW(),NOW(),0,'{$link_tipo}','{$recu->recu_id}')");
            }
        }
        echo "</pre>";
        echo '<meta http-equiv="refresh" content="1">';
    }*/

    /*public function get_links($url,$html) {
        
        $links = array();
        $parse = parse_url($url);
        $host = $parse['host'];
        //$html = str_replace(array("\r","\n"),array('',''),$html);
        preg_match_all('/<a.{1,100}?href=\'(.+?)\'.{0,100}?>(.{0,500}?)<\/a/is', $html, $match);
        print_r($match);
        foreach ($match[1] as $cod=>$link) {
            $tipo = "";
            if (preg_match("/$host/i", $link))
                $link = $link;
            else if(preg_match("/^\//i",$link))
                $link = 'http://'.$host.$link;
            if (!preg_match('/^http/i', $link))
                $link = str_replace('//', 'http://', $link);
            if (preg_match('/^\?/i', $link)){
                $urlbase = preg_replace('/\?.*$/i', '', $url);
                $link = str_replace('?', $urlbase.'?', $link);
                $tipo = 'POP';
            }
                
            $original_link = $link;
            $link = preg_replace('/#.*$/i', '', $link);
            $link = preg_replace('/\$an\=.+?\$/i','$',$link);
            $link_script = preg_replace('/\?.*$/i', '', $link);
            $titulo = strip_tags($match[2][$cod]);
            if(empty($tipo)){
                $tipo = explode(".",$link);
                $ext = strtolower($tipo[count($tipo)-1]);
                $otrs = array('gif','jpg','txt','mpg','mpeg','mp4','png','mp3');
                $tipo = (in_array($ext,$otrs)?'OTR':($ext=='pdf'?'PDF':'URL'));
            }
            
            if(preg_match('/htm\/pop/',$link)){
                $tipo = 'POP';
                $tlink = explode("/",$url);
                $tlink[count($tlink)-1] = $link;
                $link = implode("/",$tlink);
            }
            $onclick = $match[0][$cod];
            if(preg_match("/onClick=\"window\.open\('(.+?)'/i",$onclick,$mao))
            {
                $tipo = 'POP';
                $tlink = explode("/",$link);
                $tlink[count($tlink)-1] = $mao[1];
                $link = implode("/",$tlink);
            }
            $link = preg_replace("/ +/","",$link);

            if(!preg_match("/spij\.minjus\.gob\.pe/i",$link))continue;

            $links[$link] = array($titulo,$link,$original_link,$link_script,$tipo);
        }

        return ($links);
    }*/



     public function bot($ini=0,$bfecha='') {
        //echo date('d/m/Y H:i:s');
        echo "<pre>";
        //$this->db->show_errors();

        if(!empty($bfecha)){
            list($anio,$mes,$dia) = explode("-",$bfecha);
            $this->year = $anio;
        }


        if(empty($bfecha)) $recu = $this->db->get_row("SELECT * FROM spij_recurso WHERE recu_revisado=0 ORDER BY recu_fechareg DESC LIMIT {$ini},1");
        else $recu = $this->db->get_row("SELECT * FROM spij_recurso WHERE recu_revisado=0 and recu_fecha='{$bfecha}' ORDER BY recu_fechareg DESC LIMIT {$ini},1");
        if(!isset($recu->recu_id))die('fin');
        echo "<h4>$recu->recu_id\r\n$recu->recu_fecha\r\n$recu->recu_fechareg\r\n$recu->recu_nreg\r\n$recu->recu_url</h4>";
        $web = $this->get_web_page($recu->recu_url);
        $contenido = $web['body'];

        //echo '<meta http-equiv="refresh" content="0">';
        //exit(0);

        $esvalido = $this->esvalido($web['content']);
        //echo $esvalido;
        //print_r($web);
        //die("fi");
        if($esvalido!=1){
            echo "<h2>Error en el servidor</h2>";
            $this->db->query("INSERT INTO boddia(fecha,estado,fechareg) VALUES('{$recu->recu_fecha}','0',NOW())");
            $bod = $this->db->get_row("SELECT estado FROM boddia WHERE fecha='{$recu->recu_fecha}'");

            if($esvalido==0&&$ini==1&&$bod->estado==1){
                echo $web['content'];
                $this->salir();
                $this->admin_iniciar();
                $web = $this->get_web_page($recu->recu_url);
                $contenido = $web['body'];
            }
            //$this->setlog($recu->recu_id, -1);
        }
        
        $esvalido = $this->esvalido($web['content']);
        if($esvalido!=1){
            echo "<h3>Sin solucion</h3>";
            echo '<meta http-equiv="refresh" content="10">';
            exit(0);
        }

        preg_match('/<span field="dia" style="display:none;">(.+?)<\/span>/is', $contenido, $afecha);
        preg_match('/<span field="sector" style="display:none;">(.+?)<\/span>/is', $contenido, $asector);
        preg_match('/<span field="sumilla" style="display:none;">(.+?)<\/span>/is', $contenido, $asumilla);
        preg_match('/<H2 CLASS=\'Norma\'>(.+?)<\/H2>/is', $contenido, $anorma);

        $fecha = @($afecha[1]);
        $ifecha = @($this->getFecha($afecha[1]));

        $sector = @($asector[1]);
        $sumilla = @($asumilla[1]);
        $norma = @(trim(strip_tags($anorma[1])));
        $snorma = @trim(strip_tags($anorma[1]));

        $cates = $this->db->get_results("SELECT disp_id,disp_nombre,disp_name FROM spij_dispositivo");

        $cate_id = NULL;
        if(!empty($snorma))
        foreach($cates as $cate):
            if(preg_match("/^{$cate->disp_nombre}/i",$snorma)||preg_match("/^{$cate->disp_name}/i",$snorma)){
                $cate_id = $cate->disp_id;
                break;
            }
        endforeach;

        $datos = array(
            'recu_fechao'=>$ifecha,
            'recu_sumilla'=>$sumilla,
            'recu_disp_id'=>$cate_id,
            'recu_contenido'=>$contenido,
            'recu_revisado'=>1,
            'recu_nombre'=>$norma,
            );
        //session_start();
        //echo $_SESSION['HOla']++;
        $this->db->update('spij_recurso',$datos,array('recu_id'=>$recu->recu_id));
		//die($this->db->last_query);
        if(empty($bfecha)){
            echo '<meta http-equiv="refresh" content="0">';
        }    
        else{
            $url = get_bloginfo('url')."/spij/bot/?id=0&fecha=".$bfecha;
            echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$url}'\" />";    
        }
    }

    function get_web_page($url) {
        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36';

        $options = array(
            CURLOPT_CUSTOMREQUEST => "GET", //set request type post or get
            CURLOPT_POST => false, //set to GET
            CURLOPT_USERAGENT => $user_agent, //set user agent
            CURLOPT_COOKIEFILE => "cookie{$this->year}.txt", //set cookie file
            CURLOPT_COOKIEJAR => "cookie{$this->year}.txt", //set cookie jar
            CURLOPT_COOKIE => 'CLP/contenidos.dll/NPUSERNAME=Anonymous; CLP/contenidos.dll/NPPASSWORD=; CLP/contenidos.dll/NPAC_CREDENTIALSPRESENT=TRUE; CLP/contenidos.dll/CLP=TRUE; path=/',
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        
        $header['errmsg'] = $errmsg;
        $header['content'] = ($content);


        preg_match('/<body[^>]*>(.*?)<\/BODY/is',$content,$partes);
        if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
            print '¡El límite de marcha atrás se agotó!';
        }

        $body = $partes[1];
        $body = str_replace("&nbsp;","[nbsp]",$body);
        $body = html_entity_decode(($body));
        $body = str_replace("[nbsp]","&nbsp;",$body);
        if(empty($body))$body = ($content);

        require_once( get_template_directory().'/sys/lib/Encoding.php' );

        $header['body'] = $utf8_string = Encoding::toUTF8($body);
        $header['content'] = $utf8_string = Encoding::toUTF8($header['content']);

        //if(!empty($err)) die("<h1>Error al cargar la pagina</h1>");
        return $header;
    }

    function set_post($url) {
        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36';

        $options = array(
            CURLOPT_CUSTOMREQUEST => "GET", //set request type post or get
            CURLOPT_POST => 1, //set to GET
            CURLOPT_USERAGENT => $user_agent, //set user agent
            CURLOPT_COOKIEFILE => "cookie{$this->year}.txt", //set cookie file
            CURLOPT_COOKIEJAR => "cookie{$this->year}.txt", //set cookie jar
            CURLOPT_COOKIE => 'CLP/contenidos.dll/NPUSERNAME=Anonymous; CLP/contenidos.dll/NPPASSWORD=; CLP/contenidos.dll/NPAC_CREDENTIALSPRESENT=TRUE; CLP/contenidos.dll/CLP=TRUE; path=/',
            CURLOPT_POSTFIELDS=>"xhitlist_vpc=next",
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        
        $header['errmsg'] = $errmsg;
        $header['content'] = ($content);


        preg_match('/<body[^>]*>(.*?)<\/BODY/is',$content,$partes);
        if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
            print '¡El límite de marcha atrás se agotó!';
        }

        $body = $partes[1];
        $body = str_replace("&nbsp;","[nbsp]",$body);
        $body = html_entity_decode(($body));
        $body = str_replace("[nbsp]","&nbsp;",$body);
        if(empty($body))$body = ($content);

        require_once( get_template_directory().'/sys/lib/Encoding.php' );

        $header['body'] = $utf8_string = Encoding::toUTF8($body);
        $header['content'] = $utf8_string = Encoding::toUTF8($header['content']);

        if(!empty($err)) die("<h1>Error al cargar la pagina:{$err}</h1>");
        return $header;
    }

    function admin_decadas(){
        $anio = 1900;
        while($anio<2018){
            echo '<a href="'.get_bloginfo('url').'/spij/getdia/?fecha='.$anio.'-01-01&inicio=true">'.$anio.'</a><br>';
            $anio += 10;
        }
    }

    function admin_calendario($anio=''){
        $anio = empty($anio)?date('Y'):$anio;
        $tanio = $anio;
        if(date('Y')==$anio)$tanio = "";

        $meses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        include(get_template_directory()."/sys/views/spij/calendario.php");

    }

    function admin_bot(){
        
    }

    function actdia(){
        //$this->getdia('2006-06-28',false,false);
        //exit(0);
        $recu = $this->db->get_row("SELECT * FROM boddia WHERE estado=0");
        if(!empty($recu->fecha)){
            $this->getdia($recu->fecha,false,false);
            $recu = $this->db->get_row("UPDATE boddia SET estado=1 WHERE fecha='{$recu->fecha}'");
            
        }

        $todo = $this->db->get_row("SELECT NOW()-fechareg as total FROM boddia order by fechareg asc LIMIT 1");
        $total = @$todo->total;
        if($total>500){
            $this->db->query("DELETE FROM boddia WHERE 1");
        }
        
        $url = get_bloginfo('url')."/spij/actdia/";
        echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$url}'\" />";
    }

    function getdia($fecha,$inicio=false,$bred=true){
        //die($inicio."-".$bred);
        $meses = array("","enero","febrero","marzo","abril","mayo","junio","julio","agosto","setiembre","octubre","noviembre","diciembre");
        $fecha = empty($fecha)?date("Y-m-d"):$fecha;
        list($anio,$mes,$dia) = explode("-",$fecha);
        $strfecha = $dia."+de+".$meses[$mes*1]."+de+".$anio;

        $this->year = $anio;

        if($anio%10==0&&$mes=='01'&&$dia=='01'&&$inicio==false) die("Fin de la serie del año: ".($anio-10));

        $url = "http://spij.minjus.gob.pe/CLP/contenidos.dll?f=xhitlist&xhitlist_x=Advanced&xhitlist_s=&xhitlist_d=&xhitlist_q=%5BField+dia%3A%22{$strfecha}%22%5D%26%5BField+sumilla%3A*%5D&xhitlist_hc=&xhitlist_mh=100000&global=G_&G_QUERY=&xhitlist_xsl=xhitlist_normas_new.xsl&xhitlist_vpc=first&xhitlist_sel=title%3Bpath%3Brelevance-weight%3Bcontent-type%3Bhome-title%3Bfield%3Adia%3Bfield%3Asector&txtFecha={$fecha}&txtSumilla=";
        $web = $this->get_web_page($url);
        $body = $web['body'];
        $esvalido = $esval = $this->esvalido($web['content']);
        if($esvalido==1){
            echo $body;
            preg_match("/ ([0-9]+) resultados/",$web['body'],$partes);
            preg_match("/ ([0-9]+) resultados/",$web['body'],$partes);

            $registros = $partes[1];
            $paginas = ceil($registros/20);

            echo "<h1>$fecha<br>$registros en $paginas paginas</h1>";
            $this->setlinks($body,1,$fecha,$registros);
            //sleep(10);
            for($i=2;$i<=$paginas;$i++){
                $url = "http://spij.minjus.gob.pe/CLP/contenidos.dll?f=xhitlist&xhitlist_vpc=next";
                $web = $this->set_post($url);
                $body = $web['body'];
                echo $body;
                if($this->esvalido($web['content'])){
                    $this->setlinks($body,$i,$fecha,$registros);
                }else{
                    $this->setlog($fecha,$i);
                    break;
                }
            }
            if($bred)$this->redirect(date('Y-m-d', strtotime($fecha. ' + 1 days')));
            else{
                $url = get_bloginfo('url')."/spij/bot/?id=0&fecha=".$fecha;
                echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$url}'\" />";
            }
        }else{

            if($esvalido==2){
                echo "<h2>No hay nada</h2>";
                if($bred)$this->redirect(date('Y-m-d', strtotime($fecha. ' + 1 days')));
            }else{
                echo "<h2>Error en el servidor</h2>";
                $this->setlog($fecha, -1);
                $this->salir();
                $this->admin_iniciar();
                if($bred)$this->redirect($fecha);
            }
            
        }
    }

    function redirect($fecha){
        $url = get_bloginfo('url')."/spij/getdia/?fecha=".$fecha;
        echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$url}'\" />";
    }

    function setlinks($html,$pagina,$fecha,$registros){
        preg_match_all('/"hit-title"><a href="(.+?)".+?img.+?>(.+?)<\/a.+?<td valign="top" class="hit-home-title">(.+?)<.+?<td valign="top" class="hit-home-title">(.*?)<.+?<td valign="top" class="hit-home-title">(.*?)<.+?<td valign="top" nowrap class="hit-type">(.+?)</s', $html, $partes);
        //print_r($partes);
        foreach($partes[1] as $i=>$reg){
            $nreg  =(($pagina-1)*20+($i+1));
            $row = $this->db->get_row("SELECT * FROM spij_recurso WHERE recu_fecha='{$fecha}' AND recu_idfecha='{$nreg}'");
            $datos = array(
                'recu_fecha'=>$fecha,
                'recu_idfecha'=>$nreg,
                'recu_url'=>'http://spij.minjus.gob.pe'.$partes[1][$i],
                'recu_titulo'=>$partes[2][$i],
                'recu_nreg'=>$registros,
                'recu_strfecha'=>$partes[3][$i],
                'recu_sector'=>$partes[4][$i],
                'recu_ubicacion'=>$partes[5][$i],
                'recu_tipo'=>$partes[6][$i],
                'recu_fechareg'=>date('Y-m-d H:i:s'),
                'recu_sect_id'=>$this->getIdSector($partes[4][$i]),
                'recu_ubic_id'=>$this->getIDUbicacion($partes[5][$i])
                );
            if(empty($row->recu_id)){
                $this->db->show_errors();
                $this->db->insert('spij_recurso',$datos);
                echo "$nreg +++<br>";
            }else{
                $this->db->update('spij_recurso',$datos,array('recu_fecha'=>$fecha,'recu_idfecha'=>$nreg));
                echo "$nreg ----<br>";
            }
            
        }
    }

    function getIdSector($str){
        $str = trim($str);
        $row = $this->db->get_row("SELECT sect_id FROM spij_sector WHERE sect_nombre='{$str}'");
        if(!empty($row->sect_id)){
            return $row->sect_id;
        }else{
            $this->db->insert('spij_sector',array('sect_nombre'=>$str));
            return $this->db->insert_id;
        }
    }
    function getIdUbicacion($str){
        $str = trim($str);
        $row = $this->db->get_row("SELECT ubic_id FROM spij_ubicacion WHERE ubic_nombre='{$str}'");
        if(!empty($row->ubic_id)){
            return $row->ubic_id;
        }else{
            $this->db->insert('spij_ubicacion',array('ubic_nombre'=>$str));
            return $this->db->insert_id;
        }
    }

    function setlog($fecha,$pagina){
        $log  = "{$fecha}\t{$pagina}".PHP_EOL;
        file_put_contents('./log_bot.txt', $log, FILE_APPEND);
    }
    
    
    /* es para migrar no sirve */
    function rlink($coincidencias)
    {
        $tipo = "";
        $link = $coincidencias[1];
        $parse = parse_url($this->url_parent);
        $host = $parse['host'];

        if (preg_match("/$host/i", $link))
            $link = $link;
        else if(preg_match("/^\//i",$link))
            $link = 'http://'.$host.$link;
        if (!preg_match('/^http/i', $link))
            $link = str_replace('//', 'http://', $link);
        if (preg_match('/^\?/i', $link)){
            $urlbase = preg_replace('/\?.*$/i', '', $this->url_parent);
            $link = str_replace('?', $urlbase.'?', $link);
            $tipo = 'POP';
        }
            
        $original_link = $link;
        $anclas = explode("#",$link);
        $ancla = "";
        if(count($anclas)>1){
            $ancla = "#".$anclas[1];
        }
        $link = preg_replace('/#.*$/i', '', $link);
        $link = preg_replace('/\$an\=.+?\$/i','$',$link);
        return "href='".get_site_url()."/sys/normas/ver/".md5($link).$ancla."'";
    }

    function migrar(){
        $rows  = $this->db->get_results("SELECT * FROM spij_recurso WHERE recu_tipo='URL' AND recu_revisado=1 AND recu_migrado=0 LIMIT 100");
        foreach($rows as $row){
            $this->url_parent = $row->recu_url_final;
            $contenido = preg_replace_callback("/href=\'(.+?)\'/is",array($this,"rlink"),$row->recu_contenido);
            $contenido = str_replace('<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">','',$contenido);
            $id = $row->recu_id;
            $nombre = $row->recu_nombre;
            $titulo = $row->recu_titulo;
            $sumilla = $row->recu_sumilla;
            $fecha = $row->recu_fecha;
            $sector = $row->recu_sect_id;
            $dispositivo = $row->recu_disp_id;
            if(!empty($titulo)||!empty($contenido))
            $this->db->insert('sys_norma',array(
                'norm_recu_id'=>$id,
                'norm_titulo'=>$titulo,
                'norm_nombre'=>$nombre,
                'norm_sumilla'=>$sumilla,
                'norm_contenido'=>$contenido,
                'norm_fecha'=>$fecha,
                'norm_sect_id'=>$sector,
                'norm_disp_id'=>$dispositivo
            ));
            $this->db->query("UPDATE spij_recurso SET recu_migrado=1 WHERE recu_id='{$id}'");
        }
    }

}