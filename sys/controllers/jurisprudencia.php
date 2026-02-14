<?php
include("contenidos.php");
class jurisprudencia extends contenidos{
	var $db;
	var $title;
	var $description;
	function __construct() {
		parent::__construct();
		$this->tipo = 1;
	}
	

	
		function get_contenido_jurisprudencia(){
		$rows = $this->db->get_results("SELECT cont_id as id_norma,cont_titulo as norma, cont_subtitulo as cont_subtitulo, cont_sumilla as cont_sumilla, cont_contenido as Contenido FROM sys_contenido WHERE cont_tipo_id=1 ORDER BY cont_fecha DESC");
		foreach($rows as $cod=>$row){
			$contenido  = $row->Contenido;
			$row->Contenido = html_entity_decode(strip_tags($row->Contenido));
			preg_match_all('#<span class="relam" data-f="(.*?)" data-v="(.*?)">(.*?)</span>#s',$contenido,$arr);
			
			$subs = array();
			foreach($arr[0] as $i=>$r){
				$subs[] = array('titulo'=>$arr[3][$i],'contenido'=>strip_tags(html_entity_decode($arr[2][$i])).(empty($arr[1][$i])?'':'\r\n\r\nFecha de publicaciÃ³n:'.$arr[1][$i]));
			}
			$row->ITEMS = $subs;
		}
		echo json_encode($rows,JSON_PRETTY_PRINT);
	}
	

	function get_titulocontenido($pid){
		$rows = $this->db->get_results("SELECT cont_id as id_articulo,cont_titulo as Titulo,cont_contenido as Contenido, cont_sumilla as Sumilla FROM sys_contenido WHERE cont_cate_id={$pid} and cont_tipo_id=1 ORDER BY cont_id ASC");
		foreach($rows as $cod=>$row){
			$row->Titulo = html_entity_decode($row->Titulo);
			$contenido  = $row->Contenido;
			$row->Contenido = html_entity_decode(strip_tags($row->Contenido));
			preg_match_all('#<span class="relam" data-f="(.*?)" data-v="(.*?)">(.*?)</span>#s',$contenido,$arr);
			
			$subs = array();
			foreach($arr[0] as $i=>$r){
				$subs[] = array('titulo'=>$arr[3][$i],'contenido'=>strip_tags(html_entity_decode($arr[2][$i])).(empty($arr[1][$i])?'':'\r\n\r\nFecha de publicaci¨®n:'.$arr[1][$i]));
			}
			$row->ITEMS = $subs;
		}
		echo json_encode($rows,JSON_PRETTY_PRINT);
	}
	

/*
	function listado($p=0) {
		$turl = "";
		$base = get_bloginfo('url').'/jurisprudencia/';
		$t = 1;

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo,cate_nombre,cate_nivel FROM sys_contenido LEFT JOIN sys_categoria ON cont_cate_id=cate_id WHERE cont_tipo_id={$t} ORDER BY cont_fecha DESC LIMIT {$current},{$nreg}");

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/norma_full.php");
		get_footer();
	}

	function buscar($q="",$p=0) {
		$sql_juris = " AND ".GetQS($q,array('cont_titulo','cont_sumilla','cont_subtitulo'));

		$turl = "q={$q}";
		$base = get_bloginfo('url')."/jurisprudencia/buscar/";
		$t = 1;

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo,cate_nombre,cate_nivel FROM sys_contenido LEFT JOIN sys_categoria ON cate_id=cont_cate_id WHERE cont_tipo_id=1 {$sql_juris} ORDER BY cont_fecha DESC LIMIT {$current},{$nreg}");

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/norma_full.php");
		get_footer();
	}

	function getPadre($id){
		$padre = $this->db->get_row("SELECT cate_nombre FROM sys_categoria WHERE cate_id='{$id}'");
		return $padre->cate_nombre;
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

	function ver($slug){
		$row = $this->db->get_row("SELECT * FROM sys_contenido WHERE cont_tipo_id='{$this->tipo}' AND cont_slug='{$slug}'");
		$id = $row->cont_id;
		show_edit($id);
		$this->setMetas($row->cont_titulo,$row->cont_sumilla);
		$tipo = $row->cont_tipo_id;
		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/ver.php");
		get_footer();
	}


*/

}

