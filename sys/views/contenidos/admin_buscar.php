<div id="wp-link">
    <ul>
        <?php foreach($result as $i=>$row):?>
        <li class="<?php echo $i%2?'alternate':''; ?>" data-h="<?php echo get_bloginfo('url').'/'.$this->titulos[$row->cont_tipo_id][1].'/'.$row->cont_id; ?>-contenido">
            <span class="item-title"><?php echo $row->cont_titulo ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
</div>