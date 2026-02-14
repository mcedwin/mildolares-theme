<?php
class academia {
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

		$cate = isset($_GET['categoria'])?$_GET['categoria']:'';
		$cates = $this->db->get_results("select * from sys_academia_categoria order by cate_id asc", ARRAY_A);
		$regi = isset($_GET['region'])?$_GET['region']:'';
		$regis = $this->db->get_results("select * from sys_region order by regi_id asc", ARRAY_A);

		$wptable = new dataTable('Academias','Academia');

		$wptable->db = &$this->db;
		$wptable->actions = array(
			'delete'=>array('url'=>get_admin_base('academia','admin_borrar').'&id=%s','param'=>'onclick="return confirm(\'Desea eliminar\')"','text'=>'Borrar','idname'=>'acad_id'),
			'edit'=>array('url'=>get_admin_base('academia','admin_editar').'&id=%s','param'=>'','text'=>'Editar','idname'=>'acad_id'),
		);
		$wptable->columns = array('acad_id'=>'WD_ID','acad_titulo'=>'Título','empr_nombre'=>'Empresa','regi_nombre'=>'Región');
		$wptable->from = "FROM sys_academia LEFT JOIN sys_region ON acad_regi_id=regi_id LEFT JOIN sys_empresa ON acad_empr_id=empr_id";
		$wptable->header = getSelect($regis,'regi_id','regi_nombre',$regi,'- Regiones - ','name="region"');
		$wptable->header .= getSelect($cates,'cate_id','cate_nombre',$cate,'- Categorias - ','name="categoria"');
		
		$condiciones = array();
		if (!empty($cate))  $condiciones[] = "acad_regi_id='".$cate."'";

        $wptable->where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
       
		include(get_template_directory()."/sys/views/academia/admin_listado.php");

	}
	function admin_crear($id=0){
		$titulo = "Añadir curso";
		$regis = $this->db->get_results("select * from sys_region order by regi_id asc", ARRAY_A);
		$select_region = getSelect($regis,'regi_id','regi_nombre','','- Regiones - ','name="region"');
		$emprs = $this->db->get_results("select * from sys_empresa order by empr_nombre asc", ARRAY_A);
		$select_empresa = getSelect($emprs,'empr_id','empr_nombre','','- Empresas - ','name="empresa"');
		$cates = $this->db->get_results("select * from sys_academia_categoria order by cate_id asc", ARRAY_A);
		$select_categoria = getSelect($regis,'regi_id','regi_nombre','','- Categoria - ','name="categoria"');
		$row = array('acad_id' => '',
                        'acad_titulo' => '',
                        'acad_empr_id' => '',
                         'acad_regi_id' => '',
                         'acad_cate_id' => '',
                         'acad_ubicacion' => '',
                         'acad_archivo' => '',
                         'acad_idarchivo' => '',
                         'acad_contenido' => '',
                         'acad_desde' => date('Y-m-d'),
                         'acad_hasta' => date('Y-m-d'),
                         'acad_vacantes' => '',
                         'acad_presupuesto' => '',
                         'acad_nivel' => '',
                         'acad_modalidad' => '',
                         );

        $row = (object)$row;
        $permalink = "";

		include(get_template_directory()."/sys/views/academia/admin_form.php");
	}

	function admin_editar($id){
		$titulo = "Editar curso";
		$row = $this->db->get_row("SELECT * FROM sys_academia WHERE acad_id='{$id}'");
		$regis = $this->db->get_results("select * from sys_region order by regi_id asc", ARRAY_A);
		$select_region = getSelect($regis,'regi_id','regi_nombre',$row->acad_regi_id,'- Regiones - ','name="region"');
		$emprs = $this->db->get_results("select * from sys_empresa order by empr_nombre asc", ARRAY_A);
		$select_empresa = getSelect($emprs,'empr_id','empr_nombre',$row->acad_empr_id,'- Empresas - ','name="empresa"');
		$cates = $this->db->get_results("select * from sys_academia_categoria order by cate_id asc", ARRAY_A);
		$select_categoria = getSelect($regis,'regi_id','regi_nombre',$row->acad_cate_id,'- Categoria - ','name="categoria"');
		include(get_template_directory()."/sys/views/academia/admin_form.php");
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
		$nivel = $_POST['nivel'];
		$modalidad = $_POST['modalidad'];

		$empresa = empty($empresa)?null:$empresa;
		$region = empty($region)?null:$region;
	
		$data = array(
				'acad_titulo'=>$titulo,
				'acad_empr_id'=>$empresa,
				'acad_regi_id'=>$region,
				'acad_ubicacion'=>$ubicacion,
				'acad_archivo'=>$archivo,
				'acad_idarchivo'=>$idarchivo,
				'acad_contenido'=>$contenido,
				'acad_desde'=>$desde,
				'acad_hasta'=>$hasta,
				'acad_vacantes'=>$vacantes,
				'acad_presupuesto'=>$presupuesto,
				'acad_nivel'=>$nivel,
				'acad_modalidad'=>$modalidad);

		if(!empty($id)){
			$this->db->update('sys_academia',$data,array('acad_id'=>$id));
		}else{
			$this->db->insert('sys_academia',$data);	
		}
		
		dieMsg(array('exito'=>true,'mensaje'=>'','redirect'=>$redirect));
	}

	function listado(){
		
		$base = get_bloginfo('url').'/academia/';

		$p = isset($_GET['pa'])?$_GET['pa']:0;
		$region = isset($_GET['regi'])?$_GET['regi']:'';
		$empresa = isset($_GET['empr'])?$_GET['empr']:'';
		$categoria = isset($_GET['cate'])?$_GET['cate']:'';

		$aturl = array();
		if(!empty($region)) $aturl[] = 'regi='.$region;
		//if(empty($empresa)) $aturl[] = 'empr='.$empresa;
		if(!empty($categoria)) $aturl[] = 'cate='.$categoria;
		$turl = implode('&',$aturl);

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$sql_aux = "";
		if(!empty($region)) $sql_aux .= " AND acad_regi_id='{$region}' ";
		//if(!empty($empresa)) $sql_aux .= " AND acad_empr_id='{$empresa}' ";
		if(!empty($categoria)) $sql_aux .= " AND acad_cate_id='{$categoria}' ";


		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS acad_id,acad_titulo, empr_nombre,empr_logo,acad_desde, acad_hasta, acad_vacantes,acad_nivel, acad_presupuesto,acad_ubicacion,acad_modalidad FROM sys_academia LEFT JOIN sys_region ON acad_regi_id=regi_id LEFT JOIN sys_empresa ON acad_empr_id=empr_id LEFT JOIN sys_academia_categoria ON acad_cate_id=cate_id WHERE 1 {$sql_aux} ORDER BY acad_desde DESC LIMIT {$current},{$nreg}");

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;


		$categorias = $this->db->get_results("SELECT * FROM sys_academia_categoria");
		$regiones = $this->db->get_results("SELECT * FROM sys_region");

		if(empty($region)) $txtregion = "Todo el Perú";
		else $txtregion = $this->db->get_row("SELECT * FROM sys_region WHERE regi_id='{$region}'")->regi_nombre;

		if(empty($empresa)) $txtcategoria = "Todos las categorías";
		else $txtcategoria = $this->db->get_row("SELECT * FROM sys_academia_categoria WHERE cate_id='{$categoria}'")->cate_nombre;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/academia/listado.php");
		get_footer();
	}

	function admin_borrar($id){
		$this->db->delete('sys_academia',array('acad_id'=>$id));
		$menu = get_bloginfo('wpurl')."/wp-admin/admin.php?page=menu_academia";
		header("location:".$menu);
	}

}

