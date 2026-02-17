    </main>

    <footer style="margin-top:40px; padding:20px; border-top:1px solid #ddd; text-align:center; font-size:14px;">
        
        <div class="footer-menu">
            <a href="<?php echo home_url(); ?>">Inicio</a> |
            <a href="<?php echo home_url('/acerca-de/'); ?>">Acerca de</a> |
            <a href="<?php echo home_url('/contacto/'); ?>">Contacto</a> |
            <a href="<?php echo home_url('/mi-camino-hacia-1000-usd-al-mes/'); ?>">Proyecto</a>
        </div>

        <div class="footer-copy" style="margin-top:10px;">
            © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos los derechos reservados.
        </div>

    </footer>

    <?php wp_footer(); ?>
</body>
</html>