<?php
/*
if(!function_exists('menu_page_url')){
	function menu_page_url($base,$u= false){
		return get_bloginfo('wpurl').'/wp-admin/admin.php?page=menu_'.$base;
	}
}*/

class contenidos {
	var $db;
	var $titulos;
	var $url_parent;
	var $tipo;
	function __construct() {
		$this->titulos = array(
			null,
            array('Jurisprudencias','jurisprudencia'),
            array('Doctrinas','doctrina'),
			array('Escritos','escritos'),
			array('Legislación','legislacion')
        );
	}

	function prueba(){
		$nonce = wp_create_nonce("prueba_nonce");
		$link = admin_url('admin-ajax.php?action=prueba&post_id=1&nonce='.$nonce);
		echo '<a class="user_like" data-nonce="' . $nonce . '" data-post_id="1" href="' . $link . '">Like this Post</a>';
		?>
		<form action="admin-ajax.php" method="post">
			<input type="text" name="action" value="prueba">
			<input type="text" name="_wpnonce" value="8b5390fcee">
			<input type="submit">
		</form>
		<?php
		echo admin_url('admin-ajax.php');
		echo "hola";
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

	function getclass(){
		return $this->titulos[$this->tipo][1];
	}
	function getPadre($id){
		$padre = $this->db->get_row("SELECT cate_nombre FROM sys_categoria WHERE cate_id='{$id}'");
		return $padre->cate_nombre;
	}


	function getPermalink_base($class=""){
		if(!empty($class)) return get_bloginfo('url').'/'.$class.'/';
		return get_bloginfo('url').'/'.$this->getclass().'/';
	}

	function getPermalink($slug,$class=""){
		return $this->getPermalink_base($class).$slug.'/';	
	}

	function listado($p=0) {
		$turl = "";
		$base = $this->getPermalink_base();

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo,cate_nombre,cate_nivel FROM sys_contenido LEFT JOIN sys_categoria ON cont_cate_id=cate_id WHERE cont_tipo_id={$this->tipo} ORDER BY cont_fecha DESC LIMIT {$current},{$nreg}");
		
		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/buscar_en.php");
		get_footer();
	}

	function buscar_full($q) {
		$modulos = array();
		$limits = array(null,6,3,3,6,6);
		foreach($this->titulos as $k=>$item){
			$sql_qs = " AND ".GetQS($q,array('cont_titulo','cont_sumilla','cont_subtitulo'));
			$modulos[$item[1]] = array(
				'registros'=> $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo,cont_idimagen,cate_nombre,cate_nivel FROM sys_contenido LEFT JOIN sys_categoria ON cont_cate_id=cate_id WHERE cont_tipo_id=1 {$sql_qs} ORDER BY cont_fecha DESC LIMIT {$limits[$k]}"),
				'total'=> $this->db->get_row("SELECT FOUND_ROWS() as total")->total,
			);
		}

		$elpe = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS deta_id,deta_elpe_id,deta_titulo,deta_sumilla,elpe_fecha,deta_subtitulo,deta_img FROM sys_elperuano JOIN sys_elperuano_detalle WHERE 1 {$sql_elpe} ORDER BY elpe_fecha DESC LIMIT 3");
		$elpe_total = $this->db->get_row("SELECT FOUND_ROWS() as total")->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/buscar_full.php");
		get_footer();
	}


	function buscar_en($q="",$p=0) {
		$sql_qs = " AND ".GetQS($q,array('cont_titulo','cont_sumilla','cont_subtitulo'));

		$turl = "q={$q}";
		$base = get_bloginfo('url')."/jurisprudencia/buscar/";
		$t = 1;

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo,cate_nombre,cate_nivel FROM sys_contenido LEFT JOIN sys_categoria ON cate_id=cont_cate_id WHERE cont_tipo_id={$this->tipo} {$sql_qs} ORDER BY cont_fecha DESC LIMIT {$current},{$nreg}");

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/buscar_en.php");
		get_footer();
	}




/*

	function full($t,$q="",$p=0) {
		$sql_norma = " AND ".GetQS($q,array('norm_titulo','norm_sumilla'));
		$sql_juris = " AND ".GetQS($q,array('cont_titulo','cont_sumilla','cont_subtitulo'));

		$turl = "";
		$base = get_bloginfo('url');
		if($t==1) $base .= '/jurisprudencia/';
		if($t==2) $base .= '/docrina/';
		if($t==3) $base .= '/escritos/';

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		if($t==0){
			$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS norm_id,norm_titulo,norm_sumilla,norm_fecha FROM sys_norma WHERE 1 {$sql_norma} LIMIT {$current},{$nreg}");
		}else{
			$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo FROM sys_contenido WHERE cont_tipo_id={$t} {$sql_juris} LIMIT {$current},{$nreg}");
		}

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenido/norma_full.php");
		get_footer();
	}

	function buscar($t,$q="",$p=0) {
		$sql_norma = " AND ".GetQS($q,array('norm_titulo','norm_sumilla'));
		$sql_juris = " AND ".GetQS($q,array('cont_titulo','cont_sumilla','cont_subtitulo'));

		$turl = "q={$q}";
		$base = get_bloginfo('url')."/{$t}/";
		$tid = 0;
		if($t=='jurisprudenci') $base .= '/jurisprudencia/';
		if($t==2) $base .= '/docrina/';
		if($t==3) $base .= '/escritos/';

		$page = intval($p);
		$nreg = 24;
		if($page!=0)$current = $nreg*($page-1);
		else $current = $nreg*($page);

		if($t==0){
			$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS norm_id,norm_titulo,norm_sumilla,norm_fecha FROM sys_norma WHERE 1 {$sql_norma} LIMIT {$current},{$nreg}");
		}else{
			$result = $this->db->get_results("SELECT SQL_CALC_FOUND_ROWS cont_id,cont_titulo,cont_sumilla,cont_slug,cont_fecha,cont_subtitulo,cont_idarchivo FROM sys_contenido WHERE cont_tipo_id={$t} {$sql_juris} LIMIT {$current},{$nreg}");
		}

		$row = $this->db->get_row("SELECT FOUND_ROWS() as total");
		$total = $row->total;

		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenido/norma_full.php");
		get_footer();
	}*/
/*
	function admin_jurisprudencias(){
		$this->admin_listado(1);
	}

	function admin_doctrinas(){
		$this->admin_listado(2);
	}

	function admin_escritos(){
		$this->admin_listado(3);
	}*/


	function admin_listado($q="",$page="0"){
		wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin/addbutton.js');

		require_once( get_template_directory().'/sys/lib/class-wp-datatable.php' );

		$cate = isset($_GET['categoria'])?$_GET['categoria']:'';
		$cates = $this->db->get_results("select a.cate_id, CONCAT(if(a.cate_nivel!=0,'-- --',''),a.cate_nombre) as cate_nombre from sys_categoria a LEFT JOIN sys_categoria b ON a.cate_nivel=b.cate_id WHERE a.cate_tipo_id='{$this->tipo}' order by if(a.cate_nivel=0,a.cate_id,a.cate_nivel) asc,a.cate_nivel asc", ARRAY_A);
		$wptable = new dataTable($this->titulos[$this->tipo][0],$this->titulos[$this->tipo][1]);

		$wptable->db = &$this->db;
		$wptable->actions = array(
			'delete'=>array('url'=>get_admin_base($this->getclass(),'admin_borrar').'&id=%s','param'=>'onclick="return confirm(\'Desea eliminar\')"','text'=>'Borrar','idname'=>'cont_id'),
			'edit'=>array('url'=>get_admin_base($this->getclass(),'admin_editar').'&id=%s','param'=>'','text'=>'Editar','idname'=>'cont_id'),
		);
		$wptable->columns = array('cont_id'=>'WD_ID','cont_titulo'=>'Título','cont_subtitulo'=>'SubTítulo / Autor','cont_sumilla'=>'Sumilla', 'cate_nombre'=>'Categoría');
		$wptable->from = "FROM sys_contenido LEFT JOIN sys_categoria ON cont_cate_id=cate_id";
		$wptable->header = getSelect($cates,'cate_id','cate_nombre',$cate,'- Categorias - ','name="categoria"');
		
		$condiciones = array();
		$condiciones[] = "cont_tipo_id='".$this->tipo."'";
		if (!empty($cate))  $condiciones[] = "cont_cate_id='".$cate."'";

        $wptable->where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
			 
		include(get_template_directory()."/sys/views/contenidos/admin_listado.php");
	}


	function admin_logs($id=""){
		wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
		
		if(empty($id)){
			$row = $this->db->get_row("SELECT id FROM log ORDER BY id DESC");
			$id = $row->id;
		}

		$row = $this->db->get_row("SELECT * FROM log WHERE id='{$id}'");
		echo $id."--";	
		include(get_template_directory()."/sys/views/contenidos/admin_logs.php");
	}

	function admin_crear($id=0){
		wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin/addbutton.js');
		$titulo = "Añadir ".$this->titulos[$this->tipo][1];
		$cates = $this->db->get_results("select a.cate_id, CONCAT(if(a.cate_nivel!=0,'-- --',''),a.cate_nombre) as cate_nombre from sys_categoria a LEFT JOIN sys_categoria b ON a.cate_nivel=b.cate_id WHERE a.cate_tipo_id='{$this->tipo}' order by if(a.cate_nivel=0,a.cate_id,a.cate_nivel) asc,a.cate_nivel asc", ARRAY_A);
		$selec = getSelect($cates,'cate_id','cate_nombre','','- Categorias - ','name="categoria"');
		$row = array('cont_id' => '',
                        'cont_titulo' => '',
                        'cont_subtitulo' => '',
                         'cont_sumilla' => '',
						 'cont_contenido' => '',
						 'cont_slug' => '',
                         'cont_archivo' => '',
                         'cont_idarchivo' => '',
                         'cont_imagen' => '',
                         'cont_idimagen' => '',
                         'cont_fecha' => date('d/m/Y'),
                         'cont_principal' => '',
                         'cont_sect_id' => '',
                         'cont_url_firma' => '',
                         'cont_disp_id' => '',
                         'cont_fuente' => ''
                         );

        $row = (object)$row;
        $baselink = get_bloginfo('url').'/'.$this->titulos[$this->tipo][1].'/';

		include(get_template_directory()."/sys/views/contenidos/admin_form.php");
	}

	function admin_editar($id){
		wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin/addbutton.js');
		$titulo = "Editar ".$this->titulos[$this->tipo][1];
		$row = $this->db->get_row("SELECT * FROM sys_contenido WHERE cont_id='{$id}'");
		$cates = $this->db->get_results("select a.cate_id, CONCAT(if(a.cate_nivel!=0,'-- --',''),a.cate_nombre) as cate_nombre from sys_categoria a LEFT JOIN sys_categoria b ON a.cate_nivel=b.cate_id WHERE a.cate_tipo_id='{$this->tipo}' order by if(a.cate_nivel=0,a.cate_id,a.cate_nivel) asc,a.cate_nivel asc", ARRAY_A);
		$selec = getSelect($cates,'cate_id','cate_nombre',$row->cont_cate_id,'- Categorias - ','name="categoria"');
		$baselink = get_bloginfo('url').'/'.$this->titulos[$this->tipo][1].'/';
				
		include(get_template_directory()."/sys/views/contenidos/admin_form.php");
	}

	
	function admin_guardar($id=0){
		
		$redirect = $_POST['redirect'];
		$titulo = stripslashes($_POST['titulo']);
		$subtitulo = stripslashes($_POST['subtitulo']);
		$principal = isset($_POST['principal'])?'1':'0';
		$sumilla = stripslashes($_POST['sumilla']);
		$archivo = $_POST['archivo'];
		$idarchivo = $_POST['idarchivo'];
		$imagen = $_POST['imagen'];
		$idimagen = $_POST['idimagen'];
		$categoria = $_POST['categoria'];
		$contenido = stripslashes($_POST['contenido']);
		$fecha = "";
		$fuente = $_POST['fuente'];
		$slug = $_POST['slug'];

		$slug = str_replace(array(" ","_","ñ","á","é","í","ó","ú","/","Ñ","Á","É","Í","Ó","Ú"),array("-","-","n","a","é","í","ó","ú","-","n","a","é","í","ó","ú"),strtolower($slug));

		$categoria = empty($categoria)?null:$categoria;

		preg_match_all('#data-f="([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})"#is',$contenido,$arr);
		//print_r($arr);
		//echo $contenido;
		$fechas = array();
		//echo "sdf";
		if(isset($arr[0])){
			foreach($arr[0] as $i=>$items){
				$dia = str_pad($arr[1][$i], 2, "0", STR_PAD_LEFT);
				$mes = str_pad($arr[2][$i], 2, "0", STR_PAD_LEFT);
				$anio = str_pad($arr[3][$i], 4, "20", STR_PAD_LEFT);
				$fechas[$anio.$mes.$dia] = "{$anio}-{$mes}-{$dia}";
				//echo "sdf";
			}
			krsort($fechas);
			foreach($fechas as $ifecha){
				$fecha = $ifecha;
				break;
			}
		}
		//die($fecha);

		$data = array(
				'cont_titulo'=>$titulo,
				'cont_subtitulo'=>$subtitulo,
				'cont_principal'=>$principal,
				'cont_tipo_id'=>$this->tipo,
				'cont_cate_id'=>$categoria,
				'cont_fecha'=>$fecha,
				'cont_archivo'=>$archivo,
				'cont_idarchivo'=>$idarchivo,
				'cont_imagen'=>$imagen,
				'cont_idimagen'=>$idimagen,
				'cont_sumilla'=>$sumilla,
				'cont_contenido'=>$contenido,
				'cont_slug'=>$slug,
				'cont_fuente'=>$fuente);

		/*if(!empty($archivo)){
			$uploads = wp_upload_dir();
			$path_archivo = str_replace($uploads['baseurl'],$uploads['basedir'],$archivo);
			$path_imagen = str_replace(".pdf",".jpg",$path_archivo);
			$url_imagen = str_replace(".pdf",".jpg",$archivo);
			
			$im = new imagick($path_archivo);
			$im->setImageFormat('jpg');
			$im->resizeImage(150, 0, Imagick::FILTER_LANCZOS, 1);
			file_put_contents ($path_imagen, $im);
			$data = array_merge($data,array('cont_imagen'=>$url_imagen));
		}*/

		if(!empty($id)){
			$data['cont_imagen'] = $this->guardar_imagen($id,$contenido);
			$this->db->update('sys_contenido',$data,array('cont_id'=>$id));
		}else{
			$this->db->insert('sys_contenido',$data);
			$id= $this->db->insert_id;
			$imagen = $this->guardar_imagen($id,$contenido);
			$this->db->update('sys_contenido',array('cont_imagen'=>$imagen),array('cont_id'=>$id));
		}

		if($this->db->last_error !== '') {
			dieMsg(array('exito'=>false,'mensaje'=>'Error en los campos.'));
		}
		if(empty($redirect)){
			$_GET['page'] = $this->getclass();
			$redirect = get_admin_base($this->getclass(),'admin_editar').'&id='.$id;
		}
		
		dieMsg(array('exito'=>true,'mensaje'=>'','redirect'=>$redirect));
	}



	function guardar_imagen($id,$contenido){
			return '';
			$uploads = wp_upload_dir();
			$path_archivo = $uploads['basedir'].'/'.$this->getclass().'/'.$id.'.pdf';
			$path_imagen = $uploads['basedir'].'/'.$this->getclass().'/'.$id.'.jpg';
			$imagen = $uploads['baseurl'].'/'.$this->getclass().'/'.$id.'.jpg';
			

			require_once( get_template_directory().'/sys/lib/html2_pdf_lib/html2pdf.class.php' );
			try
			    {
			    $html2pdf = new Html2Pdf('P', 'A4', 'es');
			    $html2pdf->setDefaultFont('courier');
			    $html2pdf->writeHTML($contenido);
			    $html2pdf->output($path_archivo,'F');
			

				$im = new imagick($path_archivo);
				$im->setImageFormat( "jpg" );
				$img_name = $path_imagen;
				$im->setSize(200,319);
				$im->setIteratorIndex(0);

				//$im->setImageAlphaChannel(0);
			    //$im = $im->flattenImages(); // remove any transparency
			   // $im->scaleImage(300,0);  //resize...to less than 300px wide
			   // $d = $im->getImageGeometry();
			   //     $h = $d['height'];
			    //    if($h > 300) 
			    //    $im->scaleImage(0,300); 
			    //$im->setImageCompression(\Imagick::COMPRESSION_UNDEFINED);
			    //$im->setImageCompressionQuality(0);


				//$im->resizeImage(200, 0, Imagick::FILTER_LANCZOS, 1);
				$im->writeImage($img_name);
				$im->clear();
				$im->destroy();
				//unlink($path_archivo);
			 }
				catch(HTML2PDF_exception $e) {
			        echo $e;
			        exit;
			    }
		return $imagen;
	}

	function admin_borrar($id){
		$this->db->delete('sys_contenido',array("cont_tipo_id"=>$this->tipo,'cont_id'=>$id));
		$menu = get_bloginfo('wpurl')."/wp-admin/admin.php?page=menu_".$this->titulos[$this->tipo][1];
		header("location:".$menu);
	}

	function admin_buscar($qs){
		$result = $this->db->get_results("SELECT * FROM sys_contenido WHERE 1 AND ".GetQS($qs,array('cont_titulo','cont_subtitulo','cont_sumilla'))." LIMIT 20");
		include(get_template_directory()."/sys/views/contenidos/admin_buscar.php");
	}

	function ver($slug){
		//die($slug);
		wp_enqueue_script('navigation', get_template_directory_uri() . '/plg/navigation.js',null,false,true);
		wp_enqueue_script('tooltip', get_template_directory_uri() . '/plg/tooltip.js',null,false,true);
		$row = $this->db->get_row("SELECT * FROM sys_contenido WHERE cont_tipo_id='{$this->tipo}' AND cont_slug='{$slug}'");
		$id = $row->cont_id;
		//die($id);
		show_edit($id);
		$this->setMetas($row->cont_titulo,$row->cont_sumilla);
		$tipo = $row->cont_tipo_id;
		get_header();
		include(get_template_directory()."/sys/views/menu.php");
		include(get_template_directory()."/sys/views/contenidos/ver.php");
		get_footer();
	}


}

