<?php
class diccionario {
	var $db;
	var $titulos;
	var $url_parent;
	var $title;
	var $descripcion;
	
	function __construct() {
		wp_register_script( 'my-script', 'myscript_url' );
		wp_enqueue_script( 'my-script' );
		$translation_array = array( 'templateUrl' => get_template_directory_uri() );
		wp_localize_script( 'my-script', 'object_name', $translation_array );
        wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
	}

	function buscar($q) {
		$sql = " AND ".GetQS($q,array('dicc_nombre','dicc_descripcion'));

		$rows = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS dicc_id,dicc_nombre,dicc_descripcion FROM sys_diccionario WHERE 1 {$sql_norma} LIMIT 6");
		$rows_total = $this->db->get_row("SELECT FOUND_ROWS() as total")->total;

		get_header();
		include(get_template_directory()."/sys/views/diccionario/dicc_buscar.php");
		get_footer();
	}

	function admin_listado($q="",$page="0"){
		require_once( get_template_directory().'/sys/lib/class-wp-datatable.php' );

		$wptable = new dataTable("Diccionario","Diccionario");

		$wptable->db = &$this->db;
		$wptable->actions = array(
			'delete'=>array('url'=>get_admin_base('diccionario','admin_borrar').'&id=%s','param'=>'onclick="return confirm(\'Desea eliminar\')"','text'=>'Borrar','idname'=>'dicc_id'),
			'edit'=>array('url'=>get_admin_base('diccionario','admin_editar').'&id=%s','param'=>'','text'=>'Editar','idname'=>'dicc_id'),
		);
		$wptable->columns = array('dicc_id'=>'WD_ID','dicc_nombre'=>'Nombre','dicc_descripcion'=>'Descripción');
		$wptable->from = "FROM sys_diccionario ORDER BY dicc_fechaupd DESC";
		$wptable->header = " ";
		
		include(get_template_directory()."/sys/views/diccionario/admin_listado.php");

	}



	function admin_crear($id=0){
		$titulo = "Añadir término";
		$row = array('dicc_id' => '',
                        'dicc_nombre' => '',
                         'dicc_descripcion' => '',
                         'dicc_contenido' => ''
                         );

        $row = (object)$row;
        $permalink = "";

		include(get_template_directory()."/sys/views/diccionario/admin_form.php");
	}

	function admin_editar($id){
		$titulo = "Editar término";
		$row = $this->db->get_row("SELECT * FROM sys_diccionario WHERE dicc_id='{$id}'");
		include(get_template_directory()."/sys/views/diccionario/admin_form.php");
	}

	function admin_guardar($id=0){
		$redirect = $_POST['redirect'];
		$nombre = $_POST['nombre'];
		$descripcion = stripslashes($_POST['descripcion']);
		$contenido = stripslashes($_POST['contenido']);
		$inicial = substr($nombre,0, 1);
		
		if(!empty($id)){
			$this->db->update('sys_diccionario',array(
				'dicc_nombre'=>$nombre,
				'dicc_inicial'=>$inicial,
				'dicc_descripcion'=>$descripcion,
				'dicc_contenido'=>$contenido,
				'dicc_fechaupd' => date('Y-m-d h:i:s')
				),array('dicc_id'=>$id)
			);	
		}else{
			$this->db->insert('sys_diccionario',array(
				'dicc_nombre'=>$nombre,
				'dicc_inicial'=>$inicial,
				'dicc_descripcion'=>$descripcion,
				'dicc_contenido'=>$contenido,
				'dicc_fechaupd' => date('Y-m-d h:i:s')
				));	
		}

		dieMsg(array('exito'=>true,'mensaje'=>'','redirect'=>$redirect));
	}

	function listado($p=0){
		
	    $this->verp('A');
	    exit(0);
		$lists = array();
		for($i = ord('a');$i<=ord('z');$i++){
			$lists[chr($i)] = $this->db->get_results("SELECT * FROM sys_diccionario WHERE dicc_inicial='".chr($i)."' LIMIT 4");
		}
		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/diccionario/listado.php");
		get_footer();
	}

	function verp($l,$p=0){
		$turl = "";
		$base = get_bloginfo('url').'/diccionario/';
        /*
		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);
        */
        $current = $l;
        $lists = array();
		for($i = ord('a');$i<=ord('z');$i++){
			$lists[] = chr($i);
		}
        
		if(isset($_GET["se"])){
			$search = $_GET["se"];
			$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS * FROM sys_diccionario WHERE dicc_nombre LIKE '%{$search}%' ORDER BY dicc_nombre");
		}else{
			
			$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS * FROM sys_diccionario WHERE dicc_inicial='{$l}' ORDER BY dicc_nombre");
		}

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;
        
		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/diccionario/ver.php");
		get_footer();
	}
	
	function getTitle(){
		return $this->title;
	}
	function getDescription(){
		return $this->descripcion;
	}
	function setMetas($title,$descripcion){
		$this->title = $title;
		$this->descripcion = $descripcion;
		add_filter( 'wp_title', array(&$this, 'getTitle' ), 10, 2 );
		add_filter( 'the_excerpt', array(&$this, 'getDescription' ), 10, 2 );
		
	}
	function ver($palabra){
	    
		$base = get_bloginfo('url').'/diccionario/';
		$turl = $base."detalle/".$palabra;
	    //$palabra = str_replace("-", " ", $palabra);
	    $palabra = urldecode($palabra);

		$result = $this->db->get_row("SELECT * FROM sys_diccionario WHERE dicc_nombre = '{$palabra}'");
		
	    $this->setMetas($result->dicc_nombre,$result->dicc_descripcion);

		if(!$result)
			header('Location:'.$base);

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/diccionario/detalle.php");
		get_footer();
	}

	function admin_borrar($id){
		$this->db->delete('sys_diccionario',array('dicc_id'=>$id));
		$menu = get_bloginfo('wpurl')."/wp-admin/admin.php?page=menu_diccionario";
		header("location:".$menu);
	}

}

