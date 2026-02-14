<?php
include("contenidos.php");
class doctrina extends contenidos{
	var $db;
	function __construct() {
		parent::__construct();
		$this->tipo = 2;
	}
/*
	function listado($p=0) {
		$turl = "";
		$base = get_bloginfo('url').'/doctrina/';

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo FROM sys_contenido WHERE cont_tipo_id={$this->tipo} ORDER BY cont_fecha DESC  LIMIT {$current},{$nreg}");

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
		$base = get_bloginfo('url')."/doctrina/buscar/";
		$t = 2;

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo FROM sys_contenido WHERE cont_tipo_id=2 {$sql_juris} ORDER BY cont_fecha DESC LIMIT {$current},{$nreg}");

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/norma_full.php");
		get_footer();
	}

	function ver($slug){
		$row = $this->db->get_row("SELECT * FROM sys_contenido WHERE cont_tipo_id='{$this->tipo}' AND cont_slug='{$slug}'");
		$id = $row->cont_id;
		show_edit($id);

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/ver.php");
		get_footer();
	}

*/
}

