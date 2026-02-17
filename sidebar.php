<aside class="md:col-span-1 space-y-5">


<!-- 🚀 Mi Meta $1000 -->
<div class="bg-white border rounded-xl p-6 shadow-sm">

    <h3 class="text-lg font-semibold mb-3 border-b pb-2">
        🚀 Camino a $1000 USD/mes
    </h3>

    <?php
        $meta = 1000;
        $actual = 10; // Cambia este valor manualmente
        $porcentaje = ($actual / $meta) * 100;
    ?>

    <div class="mb-2 text-sm">
        <span class="font-semibold">$<?php echo $actual; ?></span>
        de $<?php echo $meta; ?>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-4">
        <div class="bg-green-800 h-4 rounded-full transition-all duration-500"
             style="width: <?php echo $porcentaje; ?>%">
        </div>
    </div>

    <p class="text-xs text-gray-500 mt-3">
        Progreso actual: <?php echo round($porcentaje); ?>%
    </p>

</div>

    <!-- 🔥 Más Leídos -->
    <div class="bg-white border rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">
            🔥 Más Leídos
        </h3>
        <ul class="space-y-3 text-sm">
            <?php
            $popular = new WP_Query(array(
                'posts_per_page' => 5,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC'
            ));
            while ($popular->have_posts()) : $popular->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>" class="hover:text-green-700 transition">
                        <?php the_title(); ?>
                    </a>
                </li>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>
    </div>

    <!-- 💵 Tipo de Cambio -->
    <div class="bg-white border rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">
            💵 Tipo de Cambio Hoy
        </h3>
        <div class="text-sm space-y-2">
            <div class="flex justify-between">
                <span>Compra</span>
                <span class="font-semibold">S/ 3.344</span>
            </div>
            <div class="flex justify-between">
                <span>Venta</span>
                <span class="font-semibold">S/ 3.356</span>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                Actualizado diariamente
            </p>
        </div>
    </div>

    <!-- 🧮 Calculadora Simple -->
    <div class="bg-white border rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">
            🧮 Calculadora de Interés
        </h3>
        <input type="number" id="capital" placeholder="Capital"
            class="w-full border rounded-md p-2 mb-3 text-sm">
        <input type="number" id="tasa" placeholder="Tasa %"
            class="w-full border rounded-md p-2 mb-3 text-sm">
        <button onclick="calcular()"
            class="w-full bg-green-800 text-white py-2 rounded-md text-sm hover:bg-green-900 transition">
            Calcular
        </button>
        <p id="resultado" class="text-sm mt-3 font-semibold"></p>
    </div>

    <!-- 📂 Categorías -->
    <div class="bg-white border rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">
            📂 Categorías
        </h3>
        <ul class="space-y-2 text-sm">
            <?php
            wp_list_categories(array(
                'title_li' => '',
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 5
            ));
            ?>
        </ul>
    </div>

</aside>

<script>
function calcular() {
    let capital = parseFloat(document.getElementById('capital').value);
    let tasa = parseFloat(document.getElementById('tasa').value);

    if (!capital || !tasa) return;

    let interes = capital * (tasa / 100);
    document.getElementById('resultado').innerText =
        "Interés estimado: S/ " + interes.toFixed(2);
}
</script>