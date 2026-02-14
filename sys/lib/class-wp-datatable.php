<?php

class dataTable extends WP_List_Table {
	var $db;
	var $columns;
	var $from;
	var $singular;
	var $header;
	var $first;
	var $actions;
	var $page;
	var $where;
	var $orderby;
   public function __construct($singular,$plural,$page=20) {
		$this->page = $page;
		parent::__construct( [
			'singular' => __( $singular, 'sp' ), //singular name of the listed records
			'plural'   => __( $plural, 'sp' ), //plural name of the listed records
			'ajax'     => false, //should this table support ajax?
			
		] );

	}

	function extra_tablenav( $which ) {
		$s = isset($_GET['s'])?$_GET['s']:'';
       if ( $which == "top" ){
          ?>
        <div class="alignleft actions bulkactions">
        <?php
		   echo (!empty($this->header))?'<input type="search" name="s" placeholder="Buscar" class="alignleft" value="'.$s.'" >':'';
	       echo $this->header;
	       echo (!empty($this->header))?'<button id="js-filter" class="button" type="submit">Filtrar</button>':'';
        ?>  
        </div>
        <?php
       }
       if ( $which == "bottom" ){
          //The code that goes after the table is there
       }
    }

	public function get_customers( $per_page = 20, $page_number = 1 ) {
	  foreach($this->columns as $k=>$v) $cols[] = $k;
	  $sql = "SELECT SQL_CALC_FOUND_ROWS ".implode(',',$cols)." ".$this->from;
	  if(!empty($this->where)) $sql = $sql . ' WHERE '.$this->where;   

	 /* if ( ! empty( $_REQUEST['orderby'] ) ) {
	    $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
	    $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
	  }*/
	  if(!empty($this->orderby)) $sql = $sql . ' ORDER BY '.$this->orderby;

	  $sql .= " LIMIT ".($page_number-1)*$per_page.",$per_page";
	  //$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
	  //echo $sql;
	  $result = $this->db->get_results( $sql, 'ARRAY_A' );

	  return $result;
	}

	public function record_count() {
	  $sql = "SELECT FOUND_ROWS() as total";
	  return $this->db->get_var($sql);
	}

	public function no_items() {
	  _e( 'No hay contenidos visibles.', 'sp' );
	}

	public function column_default( $item, $column_name ) {
		if($column_name==$this->first){
			foreach($this->actions as $k=>$v)
				$actions[$k] = sprintf( '<a href="'.$v['url'].'" '.$v['param'].' >'.$v['text'].'</a>',$item[$v['idname']],isset($v['idname2'])?$item[$v['idname2']]:null);
			return '<strong>' .$item[$column_name]. '</strong>'.$this->row_actions( $actions );
		}
		return $item[ $column_name ];
	}

	function get_columns() {

	  $columns = array();
	  foreach($this->columns as $k=>$v){
	  	if(!preg_match('/WD_/',$v)){
	  		$columns[$k] = __( $v, 'sp' );
	  		if(empty($this->first)) $this->first = $k;
	  	}
	  }
	  
	  return $columns;
	}

	public function prepare_items() {

  			$columns = $this->get_columns();
		  $hidden = array();
		  $sortable = $sortable_columns = array();
		   $this->_column_headers = array($columns, $hidden, $sortable);


	  $this->process_bulk_action();
	  $per_page     = $this->get_items_per_page( 'customers_per_page', $this->page );
	  $current_page = $this->get_pagenum();
	  $this->items = self::get_customers( $per_page, $current_page );

	  $total_items  = $this->record_count();

	  $this->set_pagination_args( [
	    'total_items' => $total_items, //WE have to calculate the total number of items
	    'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ] );


	  
	}




}