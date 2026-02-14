<?php
include("contenidos.php");
class legislacion extends contenidos{

	function __construct() {
		parent::__construct();
		$this->tipo = 4;
	}
	
	function get_normas(){
		$result = $this->db->get_results("SELECT cont_id as id_norma,cont_titulo as norma FROM sys_contenido WHERE cont_tipo_id=4 ORDER BY cont_fecha DESC");
		foreach($result as $cod=>$row){
			$row->norma = html_entity_decode($row->norma);
		}
		echo json_encode($result);
	}
	
		
	function get_refresh($tid){
		
		$this->db->get_row("DELETE FROM sys_temp_cate WHERE cate_tipo_id={$tid}");
		$this->db->get_row("DELETE FROM sys_temp_cont WHERE cont_tipo_id={$tid}");
		
		
		$row = $this->db->get_row("SELECT * FROM sys_contenido WHERE cont_id={$tid} ORDER BY cont_fecha DESC");
		
		preg_match_all("#<h([0-9])>(.+?)<#",$row->cont_contenido,$arr);
		//print_r($arr);
		//die();
		$index = 0;
		$return = $this->tersedia_child($id,$arr,$index,1);
		
		//$this->refresh_a1('-3',$arr,$row->cont_contenido,'','');
			
		foreach($return as $item){
			$inombre = $item['titulo_1'];
			$this->db->query("INSERT INTO sys_temp_cate(cate_nombre,cate_tipo_id) VALUES('{$inombre}','{$tid}')");
			$id = $this->db->insert_id;
			if(isset($item['children_1'])){
				foreach($item['children_1'] as $item2){
					$inombre = $item2['titulo_2'];
					$this->db->query("INSERT INTO sys_temp_cate(cate_nombre,cate_tipo_id,cate_padre) VALUES('{$inombre}','{$tid}','{$id}')");
					$id2 = $this->db->insert_id;
					if(isset($item2['children_2'])){
						foreach($item2['children_2'] as $item3){
							$inombre = $item3['titulo_3'];
							$this->db->query("INSERT INTO sys_temp_cate(cate_nombre,cate_tipo_id,cate_padre) VALUES('{$inombre}','{$tid}','{$id2}')");
							$id3 = $this->db->insert_id;
							if(isset($item3['children_3'])){
									foreach($item3['children_3'] as $item4){
										$inombre = $item4['titulo_4'];
										$this->db->query("INSERT INTO sys_temp_cate(cate_nombre,cate_tipo_id,cate_padre) VALUES('{$inombre}','{$tid}','{$id3}')");
										$id4 = $this->db->insert_id;
										if(isset($item4['children_4'])){
											
										}else{
											$this->refresh_a($item4['id_titulo'],$arr,$row->cont_contenido,$id4,$tid);
										}
									}
							}else{
								$this->refresh_a($item3['id_titulo'],$arr,$row->cont_contenido,$id3,$tid);
							}
						}
					}else{
						$this->refresh_a($item2['id_titulo'],$arr,$row->cont_contenido,$id2,$tid);
					}
				}
			}else{
				$this->refresh_a($item['id_titulo'],$arr,$row->cont_contenido,$id,$tid);
			}
		}
	}
	
	/*
	function refresh_a1($pid,$arr,$cont,$id,$tid){
		list($lls,$pid) = explode("-",$pid);
		$texto = preg_replace('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/',".",$arr[2][$pid]);
		
		preg_match("#$texto(.+?)<h#s",$cont,$arr);
		$cont = $arr[1];
		
		
		preg_match_all("#<strong>(Artículo.+?)</strong>#s",$cont,$artis);
		$arr = preg_split("#<strong>Artículo.+?</strong>#s",$cont);

		
		
		foreach($artis[1] as $in=>$val){
			$contenido = trim(strip_tags($arr[$in+1]));
			$val = trim($val,".-");
			$val  = str_replace(".-",":",$val);
			$data = array(
					'cont_cate_id' => $id,
					'cont_titulo' => $val,
					'cont_contenido' => $contenido,
					'cont_tipo_id' => $tid,
				);
				print_r($data);

		}
	}*/
	
	function refresh_a($pid,$arr,$cont,$id,$tid){
		list($lls,$pid) = explode("-",$pid);
		$texto = preg_replace('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/',".",$arr[2][$pid]);
		
		preg_match("#$texto(.+?)<h#s",$cont,$arr);
		$cont = $arr[1];
		preg_match_all("#<strong>(Artículo.+?)</strong>#s",$cont,$artis);
		$arr = preg_split("#<strong>Artículo.+?</strong>#s",$cont);
		//print_r($arr);
		foreach($artis[1] as $in=>$val){
			$contenido = trim(strip_tags($arr[$in+1],'<span>'));
			echo $contenido;
			$val = trim($val,".-");
			$val  = str_replace(".-",":",$val);
			$data = array(
					'cont_cate_id' => $id,
					'cont_titulo' => $val,
					'cont_contenido' => $contenido,
					'cont_tipo_id' => $tid,
				);
				$status = $this->db->insert('sys_temp_cont', $data);
				
				echo($this->db->last_error);
				//die($texto);
		}
	}
	
	/*
	function refresh_a($pid,$arr,$cont,$id,$tid){
		list($lls,$pid) = explode("-",$pid);
		$texto = preg_replace('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/',".",$arr[2][$pid]);
		
		preg_match("#$texto(.+?)<h#s",$cont,$arr);
		$cont = $arr[1];
		preg_match_all("#>(Artículo.+?)</strong>(.+?)<strong#s",$cont,$arr);
		
		foreach($arr[1] as $in=>$val){
			$contenido = trim(strip_tags($arr[2][$in]));
			$val = trim($val,".-");
			$val  = str_replace(".-",":",$val);
			$data = array(
					'cont_cate_id' => $id,
					'cont_titulo' => $val,
					'cont_contenido' => $contenido,
					'cont_tipo_id' => $tid,
				);
				$status = $this->db->insert('sys_temp_cont', $data);
				
				echo($this->db->last_error);
				//die($texto);

		}
	}
	*/
	function get_titulocontenido($pid){
		$rows = $this->db->get_results("SELECT cont_id as id_articulo,cont_titulo as Titulo,cont_contenido as Contenido FROM sys_temp_cont WHERE cont_cate_id={$pid} ORDER BY cont_id DESC");
		foreach($rows as $cod=>$row){
			$row->Titulo = html_entity_decode($row->Titulo);
			$contenido  = $row->Contenido;
			$row->Contenido = html_entity_decode(strip_tags($row->Contenido));
			preg_match_all('#<span class="relam" data-f="(.*?)" data-v="(.*?)">(.*?)</span>#s',$contenido,$arr);
			$subs = array();
			foreach($arr[0] as $i=>$r){
				$subs[] = array('titulo'=>$arr[3][$i],'contenido'=>strip_tags(html_entity_decode($arr[2][$i])).(empty($arr[1][$i])?'':'\r\n\r\nFecha de publicación:'.$arr[1][$i]));
			}
			$row->ITEMS = $subs;
		}
		echo json_encode($rows,JSON_PRETTY_PRINT);
	}
	
	function get_normacontenido($id){
		$rows = $this->db->get_results("SELECT * FROM sys_temp_cate WHERE cate_tipo_id='{$id}' AND cate_padre IS NULL ORDER BY cate_id ASC");
		echo($this->db->last_error);
		$out = array(); 
		foreach($rows as $item){
			$out1 = array('titulo_1'=>html_entity_decode(strip_tags($item->cate_nombre)));
			$rows2 = $this->db->get_results("SELECT * FROM sys_temp_cate WHERE cate_padre='{$item->cate_id}' ORDER BY cate_id ASC");
			if(count($rows2)>0){
				$out1['children_1'] = array();
				foreach($rows2 as $item2){
					$out2 = array('titulo_2'=>html_entity_decode(strip_tags($item2->cate_nombre)));
					$rows3 = $this->db->get_results("SELECT * FROM sys_temp_cate WHERE cate_padre='{$item2->cate_id}' ORDER BY cate_id ASC");
					if(count($rows3)>0){
						$out2['children_2'] = array();
						foreach($rows3 as $item3){	
							$out3 = array('titulo_3'=>html_entity_decode(strip_tags($item3->cate_nombre)));
							$rows4 = $this->db->get_results("SELECT * FROM sys_temp_cate WHERE cate_padre='{$item3->cate_id}' ORDER BY cate_id ASC");
							if(count($rows4)>0){
								$out3['children_3'] = array();
								foreach($rows4 as $item4){
									$out4= array('titulo_4'=>html_entity_decode(strip_tags($item4->cate_nombre)));
									$out4['id_titulo'] = $item4->cate_id;
									$out3['children_3'][] = $out4;
								}
								
							}else{
								$out3['id_titulo'] = $item3->cate_id;
							}
							$out2['children_2'][] = $out3;
						}
						
					}else{
						$out2['id_titulo'] = $item2->cate_id;
					}
					$out1['children_1'][] = $out2;
				}
			}else{
				$out1['id_titulo'] = $item->cate_id;
			}
			$out[] = $out1;
		}
		//print_r($out);
		echo json_encode($out);
	}
	
	function get_fulltitulos(){
		
		$result = $this->db->get_results("SELECT cont_id as id_norma,cont_titulo as norma FROM sys_contenido WHERE cont_tipo_id=4 ORDER BY cont_fecha DESC");
		//$result[] = array('id'=>-1,'norma'=>'El Peruano');
		echo json_encode($result);
	}
	
	
	#  legislacion/puno/36,35
	function get_busquedacontenido($termino,$ids=''){
		$sql0 = empty($ids)?'': "cont_tipo_id IN ($ids) AND ";
		$result = $this->db->get_results("SELECT 
		cont_titulo as Titulo,cont_contenido as Contenido,
		cate_nombre as 'Decreto_legislativo',
		cont_cate_id as id_titulo, cont_tipo_id as id_norma, 
		(SELECT cont_titulo FROM sys_contenido WHERE cont_id=cate_tipo_id) as 'norma'
		FROM sys_temp_cate JOIN sys_temp_cont ON cate_id=cont_cate_id WHERE {$sql0} cont_titulo LIKE '%$termino%' ORDER BY cont_id DESC");
		
		foreach($rows as $cod=>$row){
			$row->Titulo = html_entity_decode($row->Titulo);
			$row->Contenido = html_entity_decode($row->Contenido);
		}

		//echo($this->db->last_error);
		echo json_encode($result);
	}
	
	function get_busquedaarticulo($id,$idp){
		$row = $this->db->get_row("SELECT * FROM sys_temp_cont WHERE cont_tipo_id='{$idp}' AND (cont_titulo LIKE 'Articulo {$id}.%' OR cont_titulo LIKE 'Articulo {$id}:%')");
		//print_r($row);
		if(!isset($row->cont_id))die("No encontrado");
		
		$result = $this->db->get_results("SELECT cont_id as id_articulo,cont_titulo as Titulo, cont_contenido as Contenido FROM sys_temp_cont WHERE cont_tipo_id='{$idp}' AND cont_cate_id='{$row->cont_cate_id}' ORDER BY cont_id");
		$pos = 0;
		
		foreach($result as $cod=>$row){
			$row->Titulo = html_entity_decode($row->Titulo);
			$row->Contenido = html_entity_decode($row->Contenido);
		}
		
		foreach($result as $index=>$r){
			if($r->id_articulo==$row->cont_id){
				$pos = $index;
				break;
			}
		}
		$data = array("posicion"=>$pos,"data"=>$result);
		echo json_encode($data);
	}
	
	
	function get_elperuanocontenido($fecha){
		header('Content-Type: text/html; charset=UTF-8');
		$rowp = $this->db->get_row("SELECT CONCAT('https://lapatria.pe/web/wp-content/uploads/elperuano/',elpe_pdf) as pdf,elpe_fecha as fecha,elpe_id FROM sys_elperuano WHERE elpe_fecha<='$fecha' ORDER BY elpe_fecha DESC LIMIT 1");
		$result = $this->db->get_results("SELECT deta_titulo,deta_subtitulo as Titulo2, deta_sumilla as Contenido,CONCAT('https://lapatria.pe/web/wp-content/uploads/elperuano/',deta_pdf) as pdf, CONCAT(deta_elpe_id,'-',deta_id) as id_ley FROM sys_elperuano_detalle WHERE deta_elpe_id='{$rowp->elpe_id}' ORDER BY deta_titulo ASC");
		
		$titulos = array();

		foreach ($result as $r) {
			$titulos[$r->deta_titulo][] = $r;
		}
		$data = array();
		foreach($titulos as $k=>$rows){
			$temp = array();
			foreach($rows as $row){
				unset($row->deta_titulo);
				$row->Titulo2 = html_entity_decode($row->Titulo2);
				$row->Titulo2 = html_entity_decode($row->Titulo2);
				$row->Contenido = html_entity_decode($row->Contenido);
				$temp[] = $row;
			}
			$data[] = array('Titulo'=>$k,'Leyes'=>$temp);
		}
		
		$superdata = array('pdf'=>$rowp->pdf,'fecha'=>$rowp->fecha,'data'=>$data);
		
		echo json_encode($superdata);
	}
	
	function get_elperuanoleycontenido($pid){
		list($cid,$id)=explode("-",$pid);
		
		$row = $this->db->get_row("SELECT deta_sumilla as titulo,deta_subtitulo as subtitulo, deta_contenido as contenido 
		FROM sys_elperuano_detalle WHERE deta_elpe_id='{$cid}' AND deta_id='{$id}'");
		
		$row->contenido = trim(strip_tags(preg_replace('#<h1 class="sumilla">(.+?)</h1>#','',html_entity_decode($row->contenido))),"\n\r \t");
		
		echo json_encode($row);
	}
	
	
	/*
	function get_titulocontenido($pid){
		list($id,$cid) = explode("-",$pid);
		$row = $this->db->get_row("SELECT * FROM sys_contenido WHERE cont_id={$id} ORDER BY cont_fecha DESC");
		preg_match_all("#<h([0-9])>(.+?)<#",$row->cont_contenido,$arr);
		$nivel =  $arr[1];
		$texto = $arr[0][$cid];
		$texto = preg_replace('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/',".",$texto);
		//echo $texto;
		preg_match("#$texto(.+?)<h#s",$row->cont_contenido,$arr);
		$cont = $arr[1];
		preg_match_all("#>(Artículo.+?)</strong>(.+?)<strong#s",$cont,$arr);
		//print_r($arr);
		$out = array();
		foreach($arr[1] as $in=>$val){
			$out[] = array(
				'id_articulo'=>$pid."-".$in,
				'Titulo'=>$val,
				'Contenido'=>trim(strip_tags($arr[2][$in])));
		}
		die(json_encode($out));
	}
	/*
	function recu($nivel,$arr,$index){
		$niveln = $arr[1][$index+1];
		$am = array();
		if($niveln>$nivel)
			while($index<coynt($arr)){
			$nivel = $arr[1][$index];
			$texto = $arr[2][$index];
			$chils = array();
			if($oldnivel<$nivel){
				$chils = $thias->recu($nivel,$arr,$index);
			}
			
		}
		$a = array("titulo_".$nivel=>$texto,"children_".$nivel=>recu($nivel+1,$arr,$index+1);
	}*/
	/*
	function get_normacontenido($id){
		$row = $this->db->get_row("SELECT * FROM sys_contenido WHERE cont_id={$id} ORDER BY cont_fecha DESC");
		
		preg_match_all("#<h([0-9])>(.+?)</h[0-9]>#",$row->cont_contenido,$arr);
		print_r($arr);
		$out = array();
		$chils = array(1=>array(),2=>array(),3=>array(),4=>array());
		$titus = array(1=>'',2=>'',3=>'',4=>'');
		foreach($arr[0] as $index=>$item){
			$nivel = $arr[1][$index];
			$texto = $arr[2][$index];
			
			if($nivel!=$oldnivel){
				if($nivel==2) $out[] = array("titulo_".$nivel=>$texto,"children_".$nivel=>$chil2;
				if($oldnivel==2) $chils[] = array("titulo_".$nivel=>$texto,"children_".$nivel=>$chil2;
			}
			$chils[$nivel] = array("titulo_".$nivel=>$texto,"children_".$nivel=>$chils[$nivel+1];
			if($nivel==1){
				$texto1 = $texto;
			}
			
			$oldnivel = $nivel;
		}
		//echo json_encode($result);
	}*/
	
	
	
	
	/*
	function get_normacontenido($id){
		$row = $this->db->get_row("SELECT * FROM sys_contenido WHERE cont_id={$id} ORDER BY cont_fecha DESC");
		
		preg_match_all("#<h([0-9])>(.+?)<#",$row->cont_contenido,$arr);
		//print_r($arr);
		$out = array();
	$index = 0;
$return = $this->tersedia_child($id,$arr,$index,1);


	die(json_encode($return));
	}


*/

//-------------------------------------- function ------------------------------------------
function tersedia_child($id,$arr, &$index,$ini){
	$all = array();

	
	while($index<count($arr[2])) {
		$nivel=$arr[1][$index];
		if($nivel == $ini){
			$val = $arr[2][$index];
			$index++;
			$child = $this->tersedia_child($id,$arr,$index,$nivel+1);
			$s = array('titulo_'.$nivel => $val);
			if($child==null){
				$s['id_titulo'] = $id."-".($index-1);
			}else{
				$s['children_'.$nivel] = $child;
			}
			//echo "$index#\n";
			$all[] = $s;
		}else{
			//$index--;
			break;
		}
	}
	
	if(!empty($all))
		return $all;
	else
		return null;
}


}

