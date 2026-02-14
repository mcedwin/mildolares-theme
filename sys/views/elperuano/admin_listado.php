<style>
    .elperuano a {
        color: blue;
        text-decoration: none;
    }

    .elperuano a.rojo{
        color:red;
    }

    .elperuano .btn-group * {
        float: left;
    }

    .elperuano button {
        display: none;
    }

    .elperuano h2 {
        text-align: center;
    }

    .elperuano ul {
        margin: 0;
        padding: 0;
    }

    .elperuano ul li {
        float: left;
        margin-left: 10px;
    }

    .col-sm-4 {
        float: left;
        width: 30%;
        margin-left: 15px;
    }

    .row::after {
        content: "";
        clear: both;
        display: table;
        margin-bottom: 20px;
    }

    .caja table {

        width: 100%;
    }

    .elperuano table td,
    .elperuano table th {
        border-bottom: 2px solid gray;
        padding: 4px;
        font-size: 16px;
    }

    .elperuano table td {
        color: #888;
    }
</style>
<div class="container elperuano">
    <div class="row">
        <div class="col-md-4">
            <br>
            <?= $button_left ?>
        </div>
        <div class="col-md-4 text-center">

        </div>
        <div class="col-md-4 text-right">
            <br>

        </div>
    </div>

    <h2>Listado por calendario <?= $anio ?></h2>


    <div class="row">
        <?php
        $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        for ($c = 1; $c <= 12; $c++) {
            $week = 1;
            $mes = $c;
            $calendar = array();
            $time = strtotime($anio . '-' . $mes . '-' . '1');
            for ($i = 1; $i <= date('t', $time); $i++) {
                $time = strtotime($anio . '-' . $mes . '-' . $i);
                $day_week = date('N', $time);
                $smes = $meses[$c];

                $calendar[$week][$day_week] = $i;
                if ($day_week == 7) {
                    $week++;
                };
            }
        ?>
            <div class="caja <?php echo ($c % 3 == 0 ? 'fin' : '') ?> col-sm-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="7" class="tht"><?php echo $smes ?></th>
                        </tr>
                        <tr>
                            <th>Lun</th>
                            <th>Mar</th>
                            <th>Mié</th>
                            <th>Jue</th>
                            <th>Vie</th>
                            <th>Sáb</th>
                            <th>Dom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calendar as $days) : ?>
                            <tr>
                                <?php for ($i = 1; $i <= 7; $i++) : ?>
                                    <td>
                                        <?php if (isset($days[$i])) {
                                            $time = strtotime($anio . '-' . $mes . '-' . $days[$i]);
                                            $thisDate = date( 'Y-m-d', $time); // 2010-05-01, 2010-05-02, etc
                                            $class="";
                                            $id = "";
                                            if ($time <= time() && isset($rs[$mes * 1][$days[$i] * 1])) {
                                                $class="rojo";
                                                $id = $rs[$mes * 1][$days[$i] * 1]->elpe_id;
                                            }
                                        ?>
                                                <a href="<?php echo get_admin_base("elperuano","admin_documentos")."&fecha={$thisDate}&id={$id}"; ?>" class="<?php echo $class;?>"><?php echo  $days[$i]; ?></a>
                               <?php
                                        } ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php
            if ($c % 3 == 0) echo '</div><div class="row">';
        }
        ?>
    </div>
</div>