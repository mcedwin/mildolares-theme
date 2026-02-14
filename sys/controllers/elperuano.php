<?php
class elperuano
{
	var $db;
	var $titulos;
	var $title;
	var $description;
	function __construct()
	{
		wp_register_script('my-script', 'myscript_url');
		wp_enqueue_script('my-script');
		$translation_array = array('templateUrl' => get_template_directory_uri());
		wp_localize_script('my-script', 'object_name', $translation_array);
		wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
	}
	function admin_listado($anio = '')
	{
		$anio = empty($anio) ? date('Y') : $anio;
		$tanio = $anio;
		if (date('Y') == $anio) $tanio = "";


		$minanio = 1997;
		$sql = "SELECT  month(elpe_fecha) mes,day(elpe_fecha) dia,elpe_id FROM sys_elperuano WHERE year(elpe_fecha)='{$anio}'";
		$results = $this->db->get_results($sql);
		$rs = array();
		foreach ($results as $row) {
			$rs[$row->mes * 1][$row->dia * 1] = $row;
		}



		$min_date = $minanio;
		$button_left = '<ul>';
		for ($i = (date('Y')); $i >= $min_date; $i--) {
			$button_left .= '<li><a href="' . get_admin_base("elperuano", "admin_listado") . "&anio={$i}" . '">' . $i . '</a></li>';
		}
		$button_left .= '</ul>';

		$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


		include(get_template_directory() . "/sys/views/elperuano/admin_listado.php");
	}

	function admin_documentos($fecha, $id = "")
	{

		wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin/addbutton.js');

		require_once(get_template_directory() . '/sys/lib/class-wp-datatable.php');

		$wptable = new dataTable('El peruano', 'elperuano',300);

		$wptable->db = &$this->db;
		$wptable->actions = array(
			//'delete' => array('url' => get_admin_base('elperuano', 'admin_borrar') . '&id=%s&sid=%s', 'param' => 'onclick="return confirm(\'Desea eliminar\')"', 'text' => 'Borrar', 'idname' => 'deta_elpe_id',"idname2"=>"deta_id"),
			'edit' => array('url' => get_admin_base('elperuano', 'admin_editar') . '&id=%s&sid=%s', 'param' => '', 'text' => 'Editar', 'idname' => "deta_elpe_id","idname2"=>"deta_id"),
		);
		$wptable->columns = array('deta_id' => 'WD_ID','deta_elpe_id' => 'WD_SID', 'deta_titulo' => 'Título', 'deta_subtitulo' => 'SubTítulo', 'deta_sumilla' => 'Sumilla');
		$wptable->from = "FROM sys_elperuano_detalle WHERE deta_elpe_id='{$id}'";

		$condiciones = array();

		$thisDate = dateToFront($fecha);

		$wptable->where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";

		include(get_template_directory() . "/sys/views/elperuano/admin_documentos.php");
	}

	/*function admin_ejecutar($fecha){
		date_default_timezone_set('America/Lima');
		//$fecha = date("d/m/Y");
		list($dia,$mes,$anio) = explode("/",$fecha);

		$postdata = http_build_query(
		    array(
		    	'cddesde'=>$fecha,
				'cdhasta'=>$fecha,
				'btnBuscar'=>'Filtrar',
				//'X-Requested-With'=>'XMLHttpRequest'
		    )
		);


		$opts = array('http' =>
		    array(
		        'method'  => 'POST',
		        'header'  => "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\n".
					//"Origin: https://diariooficial.elperuano.pe\r\n".
					//"Host: diariooficial.elperuano.pe\r\n".
		        	//"Referer: https://diariooficial.elperuano.pe/Normas\r\n".
					//"X-Requested-With: XMLHttpRequest\r\n".
					"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36\r\n"

		        ,
		        'content' => $postdata
		    )
		);

		$context  = stream_context_create($opts);

		//$result = file_get_contents('https://diariooficial.elperuano.pe');
		//print_r($result);
		//exit(0);

		$result = file_get_contents('https://diariooficial.elperuano.pe/Normas/Filtro?dateparam='.$mes.'%2F'.$dia.'%2F'.$anio.'%2000%3A00%3A00', false, $context);

		preg_match_all('/<img src="(.+?)" width="100" alt="Patente" .*?<h4>(.+?)<.+?<h5><a href="(.+?)">(.+?)<.+?<p><b>Fecha: (.+?)<.+?<p>(.+?)<.+?<li><a href="(.+?)".+?<li><a href="(.+?)"/s',$result,$arra);
		//var_dump($arra);

		$fecha = dateToMysql($fecha);
		$updf = $arra[8][0];

		$rows = $this->db->get_results("SELECT elpe_id,elpe_pdf FROM sys_elperuano WHERE elpe_fecha='{$fecha}'");
		$id = "";
		$file = "";
		$uploads = wp_upload_dir();

		if(count($rows)>0){
			$id = $rows[0]->elpe_id;
			$file = "cuad_{$id}.pdf";
			$this->db->query("UPDATE sys_elperuano SET elpe_pdf='{$file}' WHERE elpe_id='{$id}'");
		}else{
			$this->db->query("INSERT INTO sys_elperuano(elpe_fecha) VALUES('{$fecha}')");
			$id = $this->db->insert_id;
			$file = "cuad_{$id}.pdf";
			$this->db->update('sys_elperuano',array('elpe_pdf'=>$file),array('elpe_id'=>$id));
		}
		
		$this->get($updf,$uploads['basedir']."/elperuano/".$file);

		echo "<ol>";
		foreach($arra[1] as $i=>$v){
			$uiimg = str_replace("..","http://diariooficial.elperuano.pe",$arra[1][$i]);
			$file_img = "indi_{$id}_{$i}.jpg";
			$titulo = $arra[2][$i];
			$ucont = $arra[3][$i];
			$subtitulo = $arra[4][$i];
			$sumilla = $arra[6][$i];
			$uipdf = $arra[7][$i];
			$file_pdf = "indi_{$id}_{$i}.pdf";
			$did = ($i+1);

			$this->get($uiimg,$uploads['basedir']."/elperuano/".$file_img);
			$this->get($uipdf,$uploads['basedir']."/elperuano/".$file_pdf);

			$contenido = file_get_contents($ucont);
			preg_match('/<div class="inner-detail clearfix">(.+?)<div class="social-section">/s',$contenido,$arr);
			$contenido = "<div>".$arr[1];

			$rows = $this->db->get_results("SELECT deta_elpe_id FROM sys_elperuano_detalle WHERE deta_elpe_id='{$id}' AND deta_id='$did'");
			if(count($rows)>0){
				$this->db->update('sys_elperuano_detalle',array(
					'deta_id'=>$did,
					'deta_elpe_id'=>$id,
					'deta_titulo'=>$titulo,
					'deta_subtitulo'=>$subtitulo,
					'deta_contenido'=>$contenido,
					'deta_sumilla'=>$sumilla,
					'deta_pdf'=>$file_pdf,
					'deta_img'=>$file_img,
					),array('deta_id'=>$did,'deta_elpe_id'=>$id)
				);	
			}else{
				$this->db->insert('sys_elperuano_detalle',array(
					'deta_id'=>$did,
					'deta_elpe_id'=>$id,
					'deta_titulo'=>$titulo,
					'deta_subtitulo'=>$subtitulo,
					'deta_contenido'=>$contenido,
					'deta_sumilla'=>$sumilla,
					'deta_pdf'=>$file_pdf,
					'deta_img'=>$file_img
				));	
			}
			
			
			echo "<li>Relizado : {$titulo} - {$subtitulo}</li>";
		}
		echo "</ol>";
	}*/

	function admin_ejecutar($fecha)
	{
		date_default_timezone_set('America/Lima');
		//$fecha = date("d/m/Y");
		list($dia, $mes, $anio) = explode("/", $fecha);

		$postdata = http_build_query(
			array(
				'cddesde' => $fecha,
				'cdhasta' => $fecha,
				'btnBuscar' => 'Filtrar',
				//'X-Requested-With'=>'XMLHttpRequest'
			)
		);


		$opts = array(
			'http' =>
			array(
				'method'  => 'POST',
				'header'  => "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\n" .
					//"Origin: https://diariooficial.elperuano.pe\r\n".
					//"Host: diariooficial.elperuano.pe\r\n".
					//"Referer: https://diariooficial.elperuano.pe/Normas\r\n".
					//"X-Requested-With: XMLHttpRequest\r\n".
					"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36\r\n",
				'content' => $postdata
			)
		);

		$context  = stream_context_create($opts);

		//$result = file_get_contents('https://diariooficial.elperuano.pe');
		//print_r($result);
		//exit(0);
		$ourl = 'https://diariooficial.elperuano.pe/Normas/Filtro?dateparam=' . $mes . '%2F' . $dia . '%2F' . $anio . '%2000%3A00%3A00';
		
		$result = file_get_contents($ourl, false, $context);
		//die($result);
		//echo $result;
		preg_match_all('/<img src="(.+?)" width="100" alt="Patente" .*?<h4>(.+?)<.+?<h5><a href="(.+?)">(.+?)<.+?<p>.+?<b>Fecha: (.+?) <.+?<p>(.+?)<.+?<li>.+?<input.+?data.url="(.+?)".+?<li>.+?<input.+?data.url="(.+?)"/s', $result, $arra);

		/*echo $ourl;
		print_r($arra);
		exit(0);*/

		$fecha = dateToMysql($fecha);
		$updf = $arra[8][0];

		// echo '<pre>';
		// print_r($updf);
		// die();

		$rows = $this->db->get_results("SELECT elpe_id,elpe_pdf FROM sys_elperuano WHERE elpe_fecha='{$fecha}'");
		$id = "";
		$file = "";
		$uploads = wp_upload_dir();

		if (count($rows) > 0) {
			$id = $rows[0]->elpe_id;
			$file = "cuad_{$id}.pdf";
			$this->db->query("UPDATE sys_elperuano SET elpe_pdf='{$file}' WHERE elpe_id='{$id}'");
		} else {
			$this->db->query("INSERT INTO sys_elperuano(elpe_fecha) VALUES('{$fecha}')");
			$id = $this->db->insert_id;
			$file = "cuad_{$id}.pdf";
			$this->db->update('sys_elperuano', array('elpe_pdf' => $file), array('elpe_id' => $id));
		}
		
		

		$this->get($updf, $uploads['basedir'] . "/elperuano/" . $file);

		$contenido_vacio = NULL;
		echo "<ol>";
		foreach ($arra[1] as $i => $v) {
			$did = ($i + 1);
			//print_r($arra);
			$uiimg = str_replace("..", "https://diariooficial.elperuano.pe", $arra[1][$i]);
			$file_img = "indi_{$id}_{$did}.jpg";
			$titulo = $arra[2][$i];
			$ucont = $arra[3][$i];
			$subtitulo = str_replace('&#176;','°',$arra[4][$i]);
			$sumilla = $arra[6][$i];
			$uipdf = $arra[7][$i];
			$file_pdf = "indi_{$id}_{$did}.pdf";

			$this->get($uiimg, $uploads['basedir'] . "/elperuano/" . $file_img);
			$this->get($uipdf, $uploads['basedir'] . "/elperuano/" . $file_pdf);

			$ucont = str_replace("dispositivo/NL","api/visor_html",$ucont);

			$contenido = file_get_contents($ucont);
			
			preg_match('/<div class="inner-detail clearfix">(.+?)<div class="social-section">/s', $contenido, $arr);


			if (isset($arr[1])) {
				$contenido =  "<div>" . $arr[1];
				$contenido_vacio = false;
			} else {
				preg_match('#<body>(.+?)</body>#s', $contenido, $arr);
				$contenido = $arr[1];
				$contenido_vacio = true;
			}

			$rows = $this->db->get_results("SELECT deta_elpe_id FROM sys_elperuano_detalle WHERE deta_elpe_id='{$id}' AND deta_id='$did'");

			$this->db->show_errors = true;
			$this->db->suppress_errors = false;
			defined('DIEONDBERROR') and define('DIEONDBERROR', true);
			if (count($rows) > 0) {
				$status = $this->db->update(
					'sys_elperuano_detalle',
					array(
						'deta_id' => $did,
						'deta_elpe_id' => $id,
						'deta_titulo' => ($titulo),
						'deta_subtitulo' => ($subtitulo),
						'deta_contenido' => ($contenido),
						'deta_sumilla' => ($sumilla),
						'deta_ref' => ($ucont),
						'deta_pdf' => $file_pdf,
						'deta_img' => $file_img,
					),
					array('deta_id' => $did, 'deta_elpe_id' => $id)
				);
			} else {
				$status = $this->db->insert('sys_elperuano_detalle', array(
					'deta_id' => $did,
					'deta_elpe_id' => $id,
					'deta_titulo' => ($titulo),
					'deta_subtitulo' => ($subtitulo),
					'deta_contenido' => ($contenido),
					'deta_sumilla' => ($sumilla),
					'deta_ref' => ($ucont),
					'deta_pdf' => $file_pdf,
					'deta_img' => $file_img
				));
			}

			$warning = '';
			if ($status === false) {
				$warning = '<strong style="color:red">El registro no fue ingresado a la base de datos : {{' . $this->db->last_error . '}}</strong>';
				$sql = "INSERT INTO sys_elperuano_detalle (deta_id,deta_elpe_id,deta_titulo) VALUES ('{$did}','$id','--------') ON DUPLICATE KEY UPDATE deta_id='{$did}',deta_elpe_id='{$id}'";
				$this->db->query($sql);
 
			} else if ($contenido_vacio) {
				$warning = '<strong style="color:red">Contenido extenso guardardo : </strong>';

			}
			echo "<li><a href='".get_admin_base('elperuano', 'admin_editar') . '&id='.$id.'&sid='.$did."' target='blank'>Editar</a> Realizado : {$titulo} - {$subtitulo} <span class='text-red'>{$warning}</span>  <a href='{$ucont}' target='blank'>{$ucont}</a> </li>";


			//if($i>=1)break;
			
			
		}
		echo "</ol>";
		$thisDate = dateToFront($fecha);
		echo "<a href='" . get_admin_base("elperuano", "admin_documentos") . "&fecha={$thisDate}&id={$id}" . "'>Regresar</a>";
	}

	function miupdate($table,$array,$conds)
	{
		$updates = array();
		$wheres = array();
		foreach ($array as $key => $value) {
			$updates[] = "$key = '$value'";
		}

		foreach ($conds as $key => $value) {
			$wheres[] = "$key = '$value'";
		}
		$implodeArray = implode(', ', $updates);
		$implodeWheres = implode(' AND ', $wheres);
		$sql = sprintf("UPDATE %s SET %s WHERE %s", $table, $implodeArray, $implodeWheres);
		return $this->db->query($sql);
	}

	function miinsert($table,$array)
	{
		$fields = array();
		$vals = array();
		foreach ($array as $key => $value) {
			$fields[] = $key;
			$vals[] = $value;
		}
		$implodeArray = implode(',', $fields);
		$implodeWheres = "'".implode("','", $vals)."'";
		$sql = sprintf("INSERT INTO %s(%s) VALUES(%s)", $table, $implodeArray, $implodeWheres);
		return $this->db->query($sql);
	}

	function get($url, $path)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close($ch);
		file_put_contents($path, $data);
	}

	function listado($fecha = '')
	{
		if (empty($fecha)) $row = $this->db->get_row("SELECT * FROM sys_elperuano ORDER BY elpe_fecha DESC LIMIT 1");
		else $row = $this->db->get_row("SELECT * FROM sys_elperuano WHERE elpe_fecha='{$fecha}'");
		if (empty($row->elpe_id)) die("no hay");
		$fecha  = $row->elpe_fecha;
		setlocale(LC_TIME, "es_ES");
		$strfecha = utf8_encode(ucfirst(strftime("%A, %d de %B %Y", strtotime($row->elpe_fecha))));
		$uploads = wp_upload_dir();
		$base = $uploads['baseurl'] . "/elperuano/";

		$result = $this->db->get_results("SELECT * FROM sys_elperuano_detalle WHERE deta_elpe_id={$row->elpe_id} ORDER BY deta_titulo ASC");

		$titulos = array();

		foreach ($result as $r) {
			$titulos[$r->deta_titulo][] = $r;
		}

		get_header();
		include(get_template_directory() . "/sys/views/menu.php");
		include(get_template_directory() . "/sys/views/elperuano/listado.php");
		get_footer();
	}

	function getTitle()
	{
		return $this->title;
	}
	function getDescription()
	{
		return $this->descripcion;
	}
	function setMetas($title, $descripcion)
	{
		$this->title = $title;
		$this->descripcion = $descripcion;
		add_filter('wp_title', array(&$this, 'getTitle'), 10, 2);
		add_filter('the_excerpt', array(&$this, 'getDescription'), 10, 2);
	}

	function ver($id, $ids = "",$slug)
	{

		//print_r($_GET);
		//die($id."#".$ids);
		//$ids = explode('/',$ids);


		$elpe_id = $id;
		$deta_id = $ids;
		$row = $this->db->get_row("SELECT * FROM sys_elperuano_detalle WHERE deta_elpe_id='{$elpe_id}' AND deta_id='{$deta_id}'");
		//die($slug."---".urlstring($row->deta_subtitulo));
		if($slug != urlstring($row->deta_subtitulo)){
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			get_template_part( 404 ); exit();
		}

		$this->setMetas($row->deta_sumilla, $row->deta_titulo);
		$link = get_bloginfo('url') . '/elperuano/' . $row->deta_elpe_id . '-' . $row->deta_id . '/' . urlstring($row->deta_subtitulo);
		$GLOBALS['metas'] = array(
			'<meta property="og:url" content="' . $link . '" />',
			'<meta property="og:type" content="website" />',
			'<meta property="og:title" content="' . $row->deta_sumilla . '" />',
			'<meta property="og:description" content="' . $row->deta_titulo . '" />',
			'<meta property="og:image" content="' . get_template_directory_uri() . '/img/el_peruano.png" />'
		);

		get_header();
		include(get_template_directory() . "/sys/views/menu.php");
		include(get_template_directory() . "/sys/views/elperuano/ver.php");
		get_footer();
	}

	function calendario($anio = '')
	{
		$anio = empty($anio) ? date('Y') : $anio;
		$tanio = $anio;
		if (date('Y') == $anio) $tanio = "";


		$minanio = $this->db->get_row("SELECT MIN(year(elpe_fecha)) as minanio FROM sys_elperuano")->minanio;
		$sql = "SELECT  month(elpe_fecha) mes,day(elpe_fecha) dia FROM sys_elperuano WHERE year(elpe_fecha)='{$anio}'";
		$results = $this->db->get_results($sql);
		$rs = array();
		foreach ($results as $row) {
			$rs[$row->mes * 1][$row->dia * 1] = $row;
		}

		$min_date = $minanio;
		$max_date = date('Y');
		if ($anio <= $min_date) {
			$button_left = '';
		} else {
			$button_left = '<div class="btn-group">';
			$button_left .= '<a type="button"  href="' . get_bloginfo('url') . '/elperuano/calendario/' . ($anio - 1) . '" class="btn btn-success">' . ($anio - 1) . '</a>';
			if (($anio - 1) > $min_date) {
				$button_left .= '<div class="btn-group">';
				$button_left .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">';

				for ($i = ($anio - 2); $i >= $min_date; $i--) {
					$button_left .= '<li><a href="' . get_bloginfo('url') . '/elperuano/calendario/' . $i . '">' . $i . '</a></li>';
				}

				$button_left .= '</ul>';
				$button_left .= '</div>';
			}
			$button_left .= '</div>';
		}

		if ($anio >= $max_date) {
			$button_right = '';
		} else {
			$button_right = '<div class="btn-group">';
			$button_right .= '<a type="button"  href="' . get_bloginfo('url') . '/elperuano/calendario/' . ($anio + 1) . '" class="btn btn-success">' . ($anio + 1) . '</a>';
			if (($anio + 1) < $max_date) {
				$button_right .= '<div class="btn-group">';
				$button_right .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">';
				for ($i = ($anio + 2); $i <= $max_date; $i++) {
					$button_right .= '<li><a href="' . get_bloginfo('url') . '/elperuano/calendario/' . $i . '">' . $i . '</a></li>';
				}

				$button_right .= '</ul>';
				$button_right .= '</div>';
			}
			$button_right .= '</div>';
		}

		$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");



		get_header();
		include(get_template_directory() . "/sys/views/menu.php");
		include(get_template_directory() . "/sys/views/elperuano/calendario.php");
		get_footer();
	}

	function admin_editar($id,$sid){
		wp_enqueue_style('admin_style', get_template_directory_uri() . '/css/admin.css');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin.js');
		wp_enqueue_script('admin_script', get_template_directory_uri() . '/plg/admin/addbutton.js');
		$titulo = "Editar registro";
		$row = $this->db->get_row("SELECT * FROM sys_elperuano_detalle WHERE deta_elpe_id='{$id}' AND deta_id='{$sid}'");
		$baselink = get_bloginfo('url').'/elperuano/';
		$uploads = wp_upload_dir();
		$base = $uploads['baseurl'] . "/elperuano/";
		include(get_template_directory()."/sys/views/elperuano/admin_form.php");
	}

	
	function admin_guardar($id,$sid){
		
		$redirect = $_POST['redirect'];
		$titulo = stripslashes($_POST['titulo']);
		$subtitulo = stripslashes($_POST['subtitulo']);
		$sumilla = stripslashes($_POST['sumilla']);
		$contenido = stripslashes($_POST['contenido']);


		$data = array(
				'deta_titulo'=>$titulo,
				'deta_subtitulo'=>$subtitulo,
				'deta_sumilla'=>$sumilla,
				'deta_contenido'=>$contenido);



		if(!empty($id)){
			$this->db->update('sys_elperuano_detalle',$data,array('deta_elpe_id'=>$id,'deta_id'=>$sid));
		}else{
			$this->db->insert('sys_elperuano_detalle',$data);
		}

		if($this->db->last_error !== '') {
			dieMsg(array('exito'=>false,'mensaje'=>'Error en los campos.'));
		}
		if(empty($redirect)){
			$_GET['page'] = 'elperuano';
			$redirect = get_admin_base('elperuano','admin_editar').'&id='.$id.'&sid='.$sid;
		}
		
		dieMsg(array('exito'=>true,'mensaje'=>'','redirect'=>$redirect));
	}
}
