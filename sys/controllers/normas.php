<?php
class normas {
	var $db;
	var $titulos;
	var $url_parent;
	var $title;
	var $description;
	function __construct() {
		
	}

	function libs(){
		$this->url_parent = "";
		wp_register_script( 'my-script', 'myscript_url' );
		wp_enqueue_script( 'my-script' );
		$translation_array = array( 'templateUrl' => get_template_directory_uri() );
		wp_localize_script( 'my-script', 'object_name', $translation_array );
        wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
	}
	

	function admin_listado($q="",$page="0"){
		$this->libs();
		require_once( get_template_directory().'/sys/lib/class-wp-datatable.php' );

		$s = isset($_GET['s'])?$_GET['s']:'';
		$disp = isset($_GET['dispositivo'])?$_GET['dispositivo']:'';
		$disps = $this->db->get_results('select * from spij_dispositivo order by disp_id asc', ARRAY_A);
		$cate = isset($_GET['categoria'])?$_GET['categoria']:'';
		$cates = $this->db->get_results('select * from spij_categoria order by cate_id asc', ARRAY_A);

		$wptable = new dataTable('SPIJ','SPIJS');

		$wptable->db = &$this->db;
		$wptable->actions = array(
			'edit'=>array('url'=>get_admin_base('normas','admin_editar').'&id=%s','param'=>'','text'=>'Editar','idname'=>'recu_id'),
			'view'=>array('url'=>get_bloginfo('url').'/normas/ver/%s/','param'=>'','text'=>'Ver','idname'=>'recu_id'),
		);
		$wptable->columns = array('recu_id'=>'WD_ID','recu_nombre'=>'Nombre','recu_titulo'=>'Título','recu_sumilla'=>'Sumilla','disp_nombre'=>'Dispositivo','recu_fecha'=>'Fecha');
		$wptable->from = "FROM spij_recurso LEFT JOIN spij_dispositivo ON recu_disp_id=disp_id";
		$wptable->header = getSelect($disps,'disp_id','disp_nombre',$disp,'- Dispositivos - ','name="dispositivo"');
		$wptable->header .= getSelect($cates,'cate_id','cate_nombre',$cate,'- Categorias - ','name="categoria"');
		
		$condiciones = array();
		if (!empty($disp))  $condiciones[] = "recu_disp_id='".$disp."'";
		if (!empty($cate))  $condiciones[] = "recu_cate_id='".$cate."'";
		if (!empty($s))  $condiciones[] = " (recu_titulo LIKE '%{$s}%' OR recu_sumilla LIKE '%{$s}%')";

        $wptable->where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
        $wptable->orderby = 'recu_fecha DESC';
       
			 
		include(get_template_directory()."/sys/views/normas/admin_listado.php");

	}

	function listado($p=0){
		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$t = 0;
		$q = "";
		$turl = "";
		$base = get_bloginfo('url').'/normas/';

		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS recu_id,recu_titulo,recu_nombre,recu_sumilla,recu_fecha FROM spij_recurso WHERE 1 ORDER BY recu_fecha DESC LIMIT {$current},{$nreg}");

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/norma_full.php");
		get_footer();
	}

	function buscar($q="",$p=0){
		$sql_norma = " AND ".GetQS($q,array('recu_titulo','recu_sumilla'));

		$turl = "q={$q}";
		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$t = 0;
		$base = get_bloginfo('url').'/normas/buscar/';

		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS recu_id,recu_titulo,recu_nombre,recu_sumilla,recu_fecha FROM spij_recurso WHERE 1 {$sql_norma} ORDER BY recu_fecha DESC LIMIT {$current},{$nreg}");

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/norma_full.php");
		get_footer();
	}

	function admin_editar($id){
		$this->libs();
		$row = $this->db->get_row("SELECT * FROM spij_recurso WHERE recu_id='{$id}'");
		$cates = $this->db->get_results("select * from spij_categoria order by cate_id asc", ARRAY_A);
		$selec = getSelect($cates,'cate_id','cate_nombre',$row->recu_cate_id,'- Categorias - ','name="categoria"');

		$row->recu_contenido = stripslashes($row->recu_contenido);
		//$permalink = get_bloginfo('url').'/normas/ver/'.$id.'/';
		$permalink = get_bloginfo('url').'/normas/'.$id.'-'.urlstring($row->recu_titulo).'/';
		include(get_template_directory()."/sys/views/normas/admin_form.php");
	}
	
	function admin_guardar($id){
		$this->libs();
		$redirect = $_POST['redirect'];
		$titulo = stripslashes($_POST['titulo']);
		$nombre = stripslashes($_POST['nombre']);
		//$principal = isset($_POST['principal'])?'1':'0';
		$sumilla = stripslashes($_POST['sumilla']);
		$contenido = stripslashes($_POST['contenido']);
		$categoria = $_POST['categoria'];

		$categoria = empty($categoria)?null:$categoria;

		$this->db->update('spij_recurso',array(
			'recu_titulo'=>$titulo,
			'recu_nombre'=>$nombre,
			//'recu_principal'=>$principal,
			'recu_sumilla'=>$sumilla,
			'recu_cate_id'=>$categoria,
			'recu_contenido'=>$contenido
			),array('recu_id'=>$id)
		);
		dieMsg(array('exito'=>true,'mensaje'=>'','redirect'=>$redirect));
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

	function ver($id){
		$row = $this->db->get_row("SELECT * FROM spij_recurso WHERE recu_id='{$id}'");
		$this->setMetas($row->recu_titulo,$row->recu_nombre." - ".$row->recu_sumilla);
		

		wp_enqueue_script('admin_script2', get_template_directory_uri() . '/plg/FileSaver.js',null,false,true);
		wp_enqueue_script('admin_script1', get_template_directory_uri() . '/plg/norma.js',null,false,true);
		wp_enqueue_script('admin_script3', get_template_directory_uri() . '/plg/jquery.wordexport.js',null,false,true);
		get_header();

		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/normas/ver.php");
		get_footer();
	}
}

