<?php

// edicion de contenidos de las siguientes clases
$cont_id = '';
function show_edit($id){
    global $cont_id;
    $cont_id = $id;
    add_action('admin_bar_menu', 'add_toolbar_items', 100);

    
}

function add_toolbar_items($admin_bar){
    global $cont_id;
    $class =  get_query_var('controller');
    $method =  get_query_var('method');
    $cid =  get_query_var('cid');
    if(!empty($cont_id)) $cid = $cont_id;
    if(in_array($class,array('legislacion','jurisprudencia','doctrina','escritos','historica'))&&$method=='ver'){
    //$extra = '';
    //$titulo = $class;
    //$extra = $class='contenidos';
    /*if($class=='jurisprudencia'){ $extra = '&tipo=1'; $class='contenidos'; }
    if($class=='doctrina'){ $extra = '&tipo=2'; $class='contenidos'; }
    if($class=='escritos'){ $extra = '&tipo=3'; $class='contenidos'; }*/
    $admin_bar->add_menu( array(
        'id'    => 'edit',
        'title' => 'Editar '.$class,
        'href'  => get_bloginfo('wpurl')."/wp-admin/admin.php?page=menu_{$class}&controller={$class}&method=admin_editar&id={$cid}",
        'meta'  => array(
            'title' => __('Editar Norma'),            
        ),
    ));
    }
}


// agregar boton de editor tinymce
function enqueue_plugin_scripts($plugin_array)
{
    $plugin_array["green_button_plugin"] =  get_template_directory_uri(). "/plg/admin/addbutton.js";
    return $plugin_array;
}
add_filter("mce_external_plugins", "enqueue_plugin_scripts");
function register_buttons_editor($buttons)
{
    array_push($buttons, "green");
    return $buttons;
}
add_filter("mce_buttons", "register_buttons_editor");


/*
function urlstring($str){
    $str = str_replace(array('á','é','í','ó','ú','ñ',' '),array('a','e','i','o','u','n','_'),strtolower(trim($str)));
    return $str;
}
*/
function urlstring($text)
{
  $text = str_replace('.','',$text);
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function dateToMysql($date){
    $date = trim($date);
    $retorno = null;
    if(preg_match('/ /',$date)) $retorno = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $date);
    $retorno = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $date);
    if(empty($retorno)) return null;
    return $retorno;
}
function dateToFront($date){
    $date = trim($date);
    $retorno = null;
    if(preg_match('/ /',$date)) $retorno = preg_replace('#(\d{4})-(\d{2})-(\d{2})\s(.*)#', '$3/$2/$1 $4', $date);
    $retorno = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $date);
    if(empty($retorno)) return null;
    return $retorno;
} 

function Framework($class="",$method=""){
    global $wpdb;
	$class =  get_query_var('controller');
	$method =  get_query_var('method');
	//echo 'class', $class, 'method', $method;
	//die();
    $path = realpath(dirname(__FILE__)).'/controllers/' . $class . '.php';
    if(!file_exists($path)){
        die('El archivo "'.$path.'" no existe');
    }
    require_once($path);
    if(!class_exists($class)){
        die('La clase "'.$clase.'" no existe');
    }

    $controller = new $class();
    $controller->db = &$wpdb;
    if(!method_exists($controller, $method)){
        die('El método "'.$method.'" no existe');
    }

    global $wp_query;
	//print_r(array_merge(array_slice($wp_query->query,3),$_GET));
	//exit(0);
	//die($method);
	
    call_user_func_array(array(&$controller, $method), array_values(array_merge(array_slice($wp_query->query,3),$_GET)));
}

function FrameworkAdmin($class,$method){
    global $wpdb;
	$class =  isset($_GET['controller'])?$_GET['controller']:$class;
	$method =  isset($_GET['method'])?$_GET['method']:$method;
    $path = realpath(dirname(__FILE__)).'/controllers/' . $class . '.php';
    if(!file_exists($path)){
        die('El archivo "'.$path.'" no existe');
    }
    require_once($path);
    if(!class_exists($class)){
        die('La clase "'.$clase.'" no existe');
    }

    $controller = new $class();
    $controller->db = &$wpdb;
    if(!method_exists($controller, $method)){
        die('El método "'.$method.'" no existe');
    }
    call_user_func_array(array(&$controller, $method), array_slice($_GET,3));
}


function dieMsg($json){
    echo json_encode($json);
    exit;
}

function getSelect($cates,$fid,$fval,$def,$empty,$param){
    $html = '<select '.$param.'>';
    if(!empty($empty)) $html .= '<option value="">'.$empty.'</option>';
    foreach( $cates as $cat ){
        $selected = '';
        if( $def == $cat[$fid] ){
            $selected = ' selected = "selected"';   
        }
        $html .= '<option value="'.$cat[$fid].'" '.$selected.'>'.$cat[$fval].'</option>';
        
    }
    $html .= '</select>';
    return $html;
}

function get_admin_base($controller,$method){
	return admin_url("admin.php").'?page=menu_'.$controller."&controller={$controller}&method={$method}";
}
/*
function get_blog_base111($controller,$method){
	return get_bloginfo('url')."/{$controller}/{$method}/";
}*/

function get_blog_base($controller,$method){
	return admin_url('admin-ajax.php')."?action=prueba&controller={$controller}&method={$method}";
}

//add_action( 'wp_ajax_nopriv_prueba', 'prueba' );
add_action( 'wp_ajax_prueba', 'prueba' );
function prueba(){
    FrameworkAdmin($_GET['controller'],$_GET['method']);
}


function register_custom_menu_page() {
    add_menu_page('Systema', 'Sistema', 'manage_options', 'menu_legislacion','menu_legislacion',null,4);
    //add_submenu_page( 'menu_legislacion', 'Legislación', 'Legislación','manage_options', 'menu_legislacion', 'menu_legislacion');
    //add_submenu_page( 'menu_legislacion', 'Jurisprudencia', 'Jurisprudencia','manage_options', 'menu_jurisprudencia', 'menu_jurisprudencia');
    //add_submenu_page( 'menu_legislacion', 'Doctrina', 'Doctrina','manage_options', 'menu_doctrina', 'menu_doctrina');
    //add_submenu_page( 'menu_legislacion', 'Escritos', 'Escritos','manage_options', 'menu_escritos', 'menu_escritos');
    add_submenu_page( 'menu_legislacion', 'El Peruano', 'El Peruano','manage_options', 'menu_elperuano', 'menu_elperuano');
    //add_submenu_page( 'menu_legislacion', 'Diccionario', 'Diccionario','manage_options', 'menu_diccionario', 'menu_diccionario');
    //add_submenu_page( 'menu_legislacion', 'Academia', 'Academia','manage_options', 'menu_academia', 'menu_academia');
    //add_submenu_page( 'menu_legislacion', 'Trabajo', 'Trabajo','manage_options', 'menu_trabajo', 'menu_trabajo');
    //add_submenu_page( 'menu_legislacion', 'Empresas', 'Empresas','manage_options', 'menu_empresa', 'menu_empresa');
    //add_submenu_page( 'menu_legislacion', 'Log', 'Log','manage_options', 'menu_log', 'menu_log');

    //add_menu_page('Legislación', 'Legislación', 'manage_options', 'menu_normas','menu_normas','dashicons-clipboard',4);
    //add_submenu_page('menu_normas', 'Normas', 'Todos los contenidos','manage_options', 'menu_normas', 'menu_normas');
    //add_submenu_page('menu_normas', 'Calendario', 'Agregar contenido','manage_options', 'menu_normas_calendario', 'menu_normas_calendario');
    //add_submenu_page('menu_spij', 'Bot Decadas', 'Bot Decadas','manage_options', 'menu_spij_bot_decadas', 'menu_spij_bot_decadas');
    //add_submenu_page('menu_spij', 'Bot Contenidos', 'Bot Contenidos','manage_options', 'menu_spij_bot_contenidos', 'menu_spij_bot_contenidos');
}
add_action('admin_menu', 'register_custom_menu_page');

function menu_legislacion(){
    //die("fin");
    FrameworkAdmin('legislacion','admin_listado');
}
function menu_jurisprudencia(){
   FrameworkAdmin('jurisprudencia','admin_listado');
}
function menu_doctrina(){
   FrameworkAdmin('doctrina','admin_listado');
}
function menu_escritos(){
   FrameworkAdmin('escritos','admin_listado');
}
function menu_elperuano(){
   FrameworkAdmin('elperuano','admin_listado');
}
function menu_diccionario(){
   FrameworkAdmin('diccionario','admin_listado');
}
function menu_academia(){
   FrameworkAdmin('academia','admin_listado');
}
function menu_trabajo(){
   FrameworkAdmin('trabajo','admin_listado');
}
function menu_empresa(){
   FrameworkAdmin('empresas','admin_listado');
}
function menu_log(){
    FrameworkAdmin('contenidos','admin_logs');
 }
/*
function menu_normas(){
  FrameworkAdmin('normas','admin_listado');
}*/
/*
function menu_normas_calendario(){
  FrameworkAdmin('spij','admin_calendario');
}
function menu_normas_bot_decadas(){
  FrameworkAdmin('spij','admin_decadas');
}
function menu_normas_bot_contenidos(){
  FrameworkAdmin('spij','admin_bot');
}*/


function custom_rewrite_basic(){
  /*add_rewrite_rule('^sys/([a-z_]+)/([a-z_]+)/?', 'index.php?pagename=sys&controller=$matches[1]&method=$matches[2]', 'top');
  add_rewrite_rule('^sys/([a-z_]+)/([a-z_]+)/([0-9a-z]+)/?', 'index.php?pagename=sys&controller=$matches[1]&method=$matches[2]&cid=$matches[3]', 'top');
  add_rewrite_rule('^contenidos/buscar/?$', 'index.php?pagename=sys&controller=contenidos&method=buscartodo', 'top');
*/
  $clases = array('legislacion','jurisprudencia','doctrina','escritos','elperuano','trabajo','diccionario','academia','empresas');
  $imclases = implode('|',$clases);
  
  add_rewrite_rule('^('.$imclases.')/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=listado', 'top');
  add_rewrite_rule('^(elperuano)/([a-z_]+)/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=$matches[2]', 'top');
  add_rewrite_rule('^(elperuano)/([a-z_]+)/([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ\s:_-]+)/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=$matches[2]&cid=$matches[3]', 'top');
  add_rewrite_rule('^(elperuano)/([0-9]+)\-([0-9]+)/([^_]+?)/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=ver&cid=$matches[2]&cid2=$matches[3]&cid3=$matches[4]', 'top');
  add_rewrite_rule('^(diccionario)/([a-z])/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=verp&cid=$matches[2]', 'top');
  add_rewrite_rule('^('.$imclases.')/([^_]+?)/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=ver&cid=$matches[2]', 'top');
  add_rewrite_rule('^('.$imclases.')/([a-z_]+)/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=$matches[2]', 'top');
  add_rewrite_rule('^('.$imclases.')/([a-z_]+)/([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ\s:_-]+)/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=$matches[2]&cid=$matches[3]', 'top');
  add_rewrite_rule('^('.$imclases.')/([a-z_]+)/(.+?)/([0-9,-a-zA-Z]+)?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=$matches[2]&cid=$matches[3]&cid2=$matches[4]', 'top');


  //add_rewrite_rule('^('.$imclases.')/?([0-9]+)-(.+)/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=ver&cid=$matches[2]&cid2=$matches[3]', 'top');
  //add_rewrite_rule('^(diccionario)/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=listado', 'top');
  //add_rewrite_rule('^(diccionario)/([a-z])/?$', 'index.php?pagename=$matches[1]&controller=$matches[1]&method=ver&cid=$matches[2]', 'top');
}
add_action('init', 'custom_rewrite_basic');
function register_query_var( $vars ){
    $vars[] = 'controller';
    $vars[] = 'method';
    $vars[] = 'cid';
    $vars[] = 'cid2';
    $vars[] = 'cid3';
    $vars[] = 'q';
    $vars[] = 'pa';
    return $vars;
}
add_filter( 'query_vars', 'register_query_var' );

//Apara actualizar el cache de rewrite
global $wp_rewrite;
$wp_rewrite->flush_rules( true );


function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/css/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');
function wpdocs_theme_add_editor_styles() {
    add_editor_style( get_template_directory_uri().'/css/admin.css' );
}
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );

/*
function rlink($coincidencias){
    global $url_parent;
    $tipo = "";
    $link = $coincidencias[1];
    $parse = parse_url($url_parent);
    $host = $parse['host'];

    if (preg_match("/$host/i", $link))
        $link = $link;
    else if(preg_match("/^\//i",$link))
        $link = 'http://'.$host.$link;
    if (!preg_match('/^http/i', $link))
        $link = str_replace('//', 'http://', $link);
    if (preg_match('/^\?/i', $link)){
        $urlbase = preg_replace('/\?.*$/i', '', $url_parent);
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
    return "href='".get_site_url()."/norma/".md5($link).$ancla."'";
}

*/
function GetQS($_m, $campos){
    $_m = preg_replace("/[ \t]+/i", " ", trim($_m));
    $mms = explode(" ", $_m);
    $arro = array();
    foreach ($mms as $ms) {
        $arra = array();
        foreach ($campos as $cam) {
            $arra[] = "$cam LIKE '%$ms%'";
        }
        $arro[] = "(" . implode(" OR ", $arra) . ")";
    }
    $mm_sql = implode(" AND ", $arro);
    return $mm_sql;
}




 function check_integer($which) {
        if(isset($_GET[$which])){
            if (intval($_GET[$which])>0) {
                return intval($_GET[$which]);
            } else {
                return false;
            }
        }
        return false;
    }

    function get_current_page() {
        if(($var=check_integer('pa'))) {
            return $var;
        } else {
            return 1;
        }
    }

function doPages($page_size, $thepage, $query, $total=0) {
        $thepage = $thepage.(preg_match('/\?/',$thepage)?'&':'?');
        $index_limit = 7;
        
        if(strlen($query)>0){
            $query = $query.'&';
        }
        $current = get_current_page();
        
        $total_pages=ceil($total/$page_size);
        $start=max($current-intval($index_limit/2), 1);
        $end=$start+$index_limit-1;
        echo '<div class="text-center">';
        echo '<ul class="pagination text-center">';


        $i = $current-1;
        echo '<li class="page-item"><a href="'.$thepage.$query.'pa='.$i.'" class="page-link" rel="nofollow" title="Ir a la p&aacute;gina '.$i.'">&lt;</a></li>';


        if($start > 1) {
            $i = 1;
            echo '<li class="page-item"><a href="'.$thepage.$query.'pa='.$i.'" title="Ir a la p&aacute;gina '.$i.'" class="page-link">'.$i.'</a></li>';
        }

        for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
            echo '<li class="page-item"><a href="'.$thepage.$query.'pa='.$i.'" title="Ir a la p&aacute;gina '.$i.'" class="page-link '.($i==$current?' active':'').'">'.$i.'</a></li>';
        }

        if($total_pages > $end){
            $i = $total_pages;
            echo '<li class="page-item"><a href="'.$thepage.$query.'pa='.$i.'" title="Ir a la p&aacute;gina '.$i.'" class="page-link">'.$i.'</a></li>';
        }


        $i = $current+1;
        echo '<li class="page-item"><a href="'.$thepage.$query.'pa='.$i.'" class="page-link" rel="nofollow" title="Ir a la p&aacute;gina '.$i.'">&gt;</a></li>';

        echo '</ul>';
        if ($total != 0){
            echo '<p>('.$total.' registros)</p>';
        }
        echo '</div>';
        
    }



?>