<?php
$mil_goal = mil_goal_settings();
$mil_pct  = mil_goal_percent($mil_goal['goal'], $mil_goal['current']);
$mil_rate = mil_exchange_rate();
?>
<aside class="space-y-5">

  

  <!-- 🔥 Más Leídos -->
  <?php
  $mil_popular = mil_popular_posts(5);
  if ($mil_popular->have_posts()) :
      ?>
  <section class="border rounded-xl p-6 shadow-sm">
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">
      <?php esc_html_e('Más leídos', 'mil'); ?>
    </h2>
    <ol class="space-y-3 text-sm">
      <?php
      while ($mil_popular->have_posts()) :
          $mil_popular->the_post();
          ?>
          <li class="flex items-start gap-2">
            <span class="text-xs text-gray-400 mt-1 w-5 shrink-0"><?php echo esc_html(mil_number(mil_views_of(get_the_ID()), 0)); ?></span>
            <a href="<?php the_permalink(); ?>" class="hover:text-green-900 transition">
              <?php the_title(); ?>
            </a>
          </li>
          <?php
      endwhile;
      wp_reset_postdata();
      ?>
    </ol>
  </section>
  <?php endif; ?>

  <!-- 💵 Tipo de cambio -->
  <?php if ($mil_rate) : ?>
    <section class="border rounded-xl p-6 shadow-sm">
      <h2 class="text-lg font-semibold mb-4 border-b pb-2">
        <?php esc_html_e('Tipo de cambio', 'mil'); ?>
      </h2>
      <div class="text-sm space-y-2">
        <div class="flex justify-between">
          <span><?php esc_html_e('Compra', 'mil'); ?></span>
          <span class="font-semibold">S/ <?php echo esc_html(mil_number($mil_rate['buy'], 3)); ?></span>
        </div>
        <div class="flex justify-between">
          <span><?php esc_html_e('Venta', 'mil'); ?></span>
          <span class="font-semibold">S/ <?php echo esc_html(mil_number($mil_rate['sell'], 3)); ?></span>
        </div>
        <p class="text-xs text-gray-500 mt-2">
          <?php
          printf(
              /* translators: %s: fecha de actualización */
              esc_html__('Actualizado el %s.', 'mil'),
              esc_html(isset($mil_rate['date']) ? $mil_rate['date'] : '')
          );
          ?>
        </p>
      </div>
    </section>
  <?php endif; ?>

  <!-- 🧮 Calculadora -->
  <section class="border rounded-xl p-6 shadow-sm">
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">
      <?php esc_html_e('Calculadora de interés', 'mil'); ?>
    </h2>
    <form id="mil-calc" novalidate>
      <div class="mb-3">
        <label class="sr-only" for="mil-calc-capital"><?php esc_html_e('Capital', 'mil'); ?></label>
        <input type="number" id="mil-calc-capital" name="capital" inputmode="decimal" min="0" step="any"
               placeholder="<?php esc_attr_e('Capital', 'mil'); ?>"
               class="w-full border rounded-md p-2 text-sm">
      </div>
      <div class="mb-3">
        <label class="sr-only" for="mil-calc-rate"><?php esc_html_e('Tasa anual en porcentaje', 'mil'); ?></label>
        <input type="number" id="mil-calc-rate" name="rate" inputmode="decimal" min="0" step="any"
               placeholder="<?php esc_attr_e('Tasa anual %', 'mil'); ?>"
               class="w-full border rounded-md p-2 text-sm">
      </div>
      <button type="submit"
              class="w-full bg-green-800 text-white py-2 rounded-md text-sm font-semibold hover:bg-green-900 transition">
        <?php esc_html_e('Calcular', 'mil'); ?>
      </button>
      <p id="mil-calc-result" class="text-sm mt-3 font-semibold" role="status" aria-live="polite"></p>
    </form>
  </section>

  <!-- 📂 Categorías -->
  <section class="border rounded-xl p-6 shadow-sm">
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">
      <?php esc_html_e('Categorías', 'mil'); ?>
    </h2>
    <ul class="space-y-2 text-sm">
      <?php
      wp_list_categories(array(
          'title_li' => '',
          'orderby'  => 'count',
          'order'    => 'DESC',
          'number'   => 6,
      ));
      ?>
    </ul>
  </section>

</aside>
