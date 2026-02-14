<?php
class trabajo {
	var $db;
	function __construct() {
		wp_register_script( 'my-script', 'myscript_url' );
		wp_enqueue_script( 'my-script' );
		$translation_array = array( 'templateUrl' => get_template_directory_uri() );
		wp_localize_script( 'my-script', 'object_name', $translation_array );
        wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
	}

	function admin_listado($q="",$page="0"){

		require_once( get_template_directory().'/sys/lib/class-wp-datatable.php' );

		//$cate = isset($_GET['categoria'])?$_GET['categoria']:'';
		//$cates = $this->db->get_results("select * from sys_categoria where cate_tipo_id='{$tipo}' order by cate_id asc", ARRAY_A);
		$regi = isset($_GET['region'])?$_GET['region']:'';
		$regis = $this->db->get_results("select * from sys_region order by regi_id asc", ARRAY_A);

		$wptable = new dataTable('Trabajos','Trabajo');

		$wptable->db = &$this->db;
		$wptable->actions = array(
			'delete'=>array('url'=>get_admin_base('trabajo','admin_borrar').'&id=%s','param'=>'onclick="return confirm(\'Desea eliminar\')"','text'=>'Borrar','idname'=>'trab_id'),
			'edit'=>array('url'=>get_admin_base('trabajo','admin_editar').'&id=%s','param'=>'','text'=>'Editar','idname'=>'trab_id'),
		);
		$wptable->columns = array('trab_id'=>'WD_ID','trab_titulo'=>'Título','empr_nombre'=>'Empresa','regi_nombre'=>'Región');
		$wptable->from = "FROM sys_trabajo LEFT JOIN sys_region ON trab_regi_id=regi_id LEFT JOIN sys_empresa ON trab_empr_id=empr_id";
		$wptable->header = getSelect($regis,'regi_id','regi_nombre',$regi,'- Regiones - ','name="region"');
		
		$condiciones = array();
		if (!empty($cate))  $condiciones[] = "trab_regi_id='".$cate."'";

        $wptable->where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
       
		include(get_template_directory()."/sys/views/trabajo/admin_listado.php");

	}
	function admin_crear($id=0){
		$titulo = "Añadir trabajo";
		$regis = $this->db->get_results("select * from sys_region order by regi_id asc", ARRAY_A);
		$select_region = getSelect($regis,'regi_id','regi_nombre','','- Regiones - ','name="region"');
		$emprs = $this->db->get_results("select * from sys_empresa order by empr_nombre asc", ARRAY_A);
		$select_empresa = getSelect($emprs,'empr_id','empr_nombre','','- Empresas - ','name="empresa"');
		$row = array('trab_id' => '',
                        'trab_titulo' => '',
                        'trab_empr_id' => '',
                         'trab_regi_id' => '',
                         'trab_ubicacion' => '',
                         'trab_archivo' => '',
                         'trab_idarchivo' => '',
                         'trab_contenido' => '',
                         'trab_desde' => date('Y-m-d'),
                         'trab_hasta' => date('Y-m-d'),
                         'trab_vacantes' => '',
                         'trab_presupuesto' => '',
                         'trab_experiencia' => ''
                         );

        $row = (object)$row;
        $permalink = "";

		include(get_template_directory()."/sys/views/trabajo/admin_form.php");
	}

	function admin_editar($id){
		$titulo = "Editar trabajo";
		$row = $this->db->get_row("SELECT * FROM sys_trabajo WHERE trab_id='{$id}'");
		$regis = $this->db->get_results("select * from sys_region order by regi_id asc", ARRAY_A);
		$select_region = getSelect($regis,'regi_id','regi_nombre',$row->trab_regi_id,'- Regiones - ','name="region"');
		$emprs = $this->db->get_results("select * from sys_empresa order by empr_nombre asc", ARRAY_A);
		$select_empresa = getSelect($emprs,'empr_id','empr_nombre',$row->trab_empr_id,'- Empresas - ','name="empresa"');
		include(get_template_directory()."/sys/views/trabajo/admin_form.php");
	}

	function admin_guardar($id=0){
		$redirect = $_POST['redirect'];
		$titulo = stripslashes($_POST['titulo']);
		$empresa = $_POST['empresa'];
		$region = $_POST['region'];
		$ubicacion = stripslashes($_POST['ubicacion']);
		$archivo = $_POST['archivo'];
		$idarchivo = $_POST['idarchivo'];
		$contenido = stripslashes($_POST['contenido']);
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$vacantes = $_POST['vacantes'];
		$presupuesto = $_POST['presupuesto'];
		$experiencia = $_POST['experiencia'];

		$empresa = empty($empresa)?null:$empresa;
		$region = empty($region)?null:$region;

	
		$data = array(
				'trab_titulo'=>$titulo,
				'trab_empr_id'=>$empresa,
				'trab_regi_id'=>$region,
				'trab_ubicacion'=>$ubicacion,
				'trab_archivo'=>$archivo,
				'trab_idarchivo'=>$idarchivo,
				'trab_contenido'=>$contenido,
				'trab_desde'=>$desde,
				'trab_hasta'=>$hasta,
				'trab_vacantes'=>$vacantes,
				'trab_presupuesto'=>$presupuesto,
				'trab_experiencia'=>$experiencia);

		if(!empty($id)){
			$this->db->update('sys_trabajo',$data,array('trab_id'=>$id));
		}else{
			$this->db->insert('sys_trabajo',$data);	
		}
		
		dieMsg(array('exito'=>true,'mensaje'=>'','redirect'=>$redirect));
	}


	function listado(){
		
		$base = get_bloginfo('url').'/trabajo/';

		$p = isset($_GET['pa'])?$_GET['pa']:0;
		$region = isset($_GET['regi'])?$_GET['regi']:'';
		$empresa = isset($_GET['empr'])?$_GET['empr']:'';

		$aturl = array();
		if(!empty($region)) $aturl[] = 'regi='.$region;
		if(!empty($empresa)) $aturl[] = 'empr='.$empresa;
		$turl = implode('&',$aturl);

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$sql_aux = "";
		if(!empty($region)) $sql_aux .= " AND trab_regi_id='{$region}' ";
		if(!empty($empresa)) $sql_aux .= " AND trab_empr_id='{$empresa}' ";


		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS trab_id,trab_titulo, empr_nombre,empr_logo, trab_desde, trab_hasta, trab_vacantes,trab_experiencia, trab_presupuesto,trab_ubicacion FROM sys_trabajo LEFT JOIN sys_region ON trab_regi_id=regi_id LEFT JOIN sys_empresa ON trab_empr_id=empr_id WHERE 1 {$sql_aux} ORDER BY trab_desde DESC LIMIT {$current},{$nreg}");

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;


		$empresas = $this->db->get_results("SELECT * FROM sys_empresa");
		$regiones = $this->db->get_results("SELECT * FROM sys_region");

		if(empty($region)) $txtregion = "Todo el Perú";
		else $txtregion = $this->db->get_row("SELECT * FROM sys_region WHERE regi_id='{$region}'")->regi_nombre;

		if(empty($empresa)) $txtempresa = "Todos las Instituciones";
		else $txtempresa = $this->db->get_row("SELECT * FROM sys_empresa WHERE empr_id='{$empresa}'")->empr_nombre;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/trabajo/listado.php");
		get_footer();
	}
	

	function ver($id = ''){
		$row = $this->db->get_row("SELECT * FROM sys_elperuano_detalle WHERE deta_elpe_id='{$elpe_id}' AND deta_id='{$deta_id}'");
		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/trabajo/ver.php");
		get_footer();
	}


	function admin_borrar($id){
		$this->db->delete('sys_trabajo',array('trab_id'=>$id));
		$menu = get_bloginfo('wpurl')."/wp-admin/admin.php?page=menu_trabajo";
		header("location:".$menu);
	}



}

