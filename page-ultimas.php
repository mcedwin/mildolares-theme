<?php /*
	template name: Últimas
*/ 
get_header(); 

$args = array('paged' => ( get_query_var('paged') ? get_query_var('paged') : 1));
                        query_posts( $args );
                        //$post = $myposts[0]; setup_postdata( $post ); 

?>
    <div class="container content">
        <div class="row">
            <div class="col-md-8">
                <div class="title-section">
                    <h2>
                        <span>Últimas </span> noticias
                    </h2>
                    <div class="line"></div>
                </div>
                <div class="list list-news">
                    <?php
                        while (have_posts()) {
                            the_post();
                    ?>
                    <article>
                        <div class="row">
                            <div class="col-md-4">
                               <?php 
                                    the_image('portada-cate', 800, 600, 'img-fluid');
                                ?>
                            </div>
                            <div class="col-md-8">
                                <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo get_the_excerpt(); ?></p>
                                <ul class="list-inline list-unstyled">
                                    <li><i class="fa fa-user"></i> <?php echo get_the_author(); ?></li>
                                    <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                                </ul>
                            </div>
                        </div>
                    </article>
                    <hr>
                    <?php
                        }
                    ?>
                </div>
                 <?php
                    if (function_exists('pagenavi')) {
                        pagenavi();
                    }
                    ?>
            </div>
            <div class="col-md-4">
                <div class="adsense">
                            <div class="ads">
							<a href="https://pe.jooble.org/"><img src="<?php echo get_template_directory_uri() ?>/img/bannerjooble.png" class="img-fluid" alt="JOOBLE"></a>
							<?php /*
                                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                <!-- lap300x600 -->
                                <ins class="adsbygoogle"
                                     style="display:inline-block;width:300px;height:600px"
                                     data-ad-client="ca-pub-4648682563959505"
                                     data-ad-slot="2586805202"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
								*/ ?>
                            </div>
                        </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>