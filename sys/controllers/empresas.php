<?php
class empresas {
	var $db;
	var $titulos;
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

		$wptable = new dataTable("Empresa","Empresas");

		$wptable->db = &$this->db;
		$wptable->actions = array(
			'delete'=>array('url'=>get_admin_base('empresas','admin_borrar').'&id=%s','param'=>'onclick="return confirm(\'Desea eliminar\')"','text'=>'Borrar','idname'=>'empr_id'),
			'edit'=>array('url'=>get_admin_base('empresas','admin_editar').'&id=%s','param'=>'','text'=>'Editar','idname'=>'empr_id'),
		);
		$wptable->columns = array('empr_id'=>'WD_ID','empr_nombre'=>'Nombre');
		$wptable->from = "FROM sys_empresa";
		$wptable->header = ' ';
		
		include(get_template_directory()."/sys/views/empresas/admin_listado.php");

	}

	function admin_crear($id=0){
		$titulo = "Añadir empresa";
		$row = array('empr_id' => '',
                        'empr_nombre' => '',
                        'empr_logo' => '',
                        'empr_contenido' => '',
                        'empr_idlogo' => ''
                    );

        $row = (object)$row;
        $permalink = "";

		include(get_template_directory()."/sys/views/empresas/admin_form.php");
	}

	function admin_editar($id){
		$titulo = "Editar empresa";
		$row = $this->db->get_row("SELECT * FROM sys_empresa WHERE empr_id='{$id}'");
		include(get_template_directory()."/sys/views/empresas/admin_form.php");
	}

	function admin_guardar($id=0){
		$redirect = $_POST['redirect'];
		$nombre = $_POST['nombre'];
		$logo = $_POST['logo'];
		$idlogo = $_POST['idlogo'];
		$contenido = stripslashes($_POST['contenido']);

	
		$data = array(
				'empr_nombre'=>$nombre,
				'empr_logo'=>$logo,
				'empr_contenido'=>$contenido,
				'empr_idlogo'=>$idlogo);


		if(!empty($id)){
			$this->db->update('sys_empresa',$data,array('empr_id'=>$id));
		}else{
			$this->db->insert('sys_empresa',$data);	
		}
		
		dieMsg(array('exito'=>true,'mensaje'=>'','redirect'=>$redirect));
	}

	function admin_borrar($id){
		$this->db->delete('sys_empresa',array('empr_id'=>$id));
		$menu = get_bloginfo('wpurl')."/wp-admin/admin.php?page=menu_empresa";
		header("location:".$menu);
	}

}

