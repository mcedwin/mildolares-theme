<?php 
    get_header();
    $post_portada = array();
?>
<div class="content">
    <?php $tipo = get_theme_mod( 'portada' ) ?>
    <div class="container">
        <?php if($tipo=="full"): ?>
        <div class="row index-full">
            <div class="col-md-12">
                <?php 
                    $args = array( 'posts_per_page' => 1, 'meta_key' => 'portada','meta_value' => 'true'/*'category' =>  get_cat_ID ( 'Portada' )*/ );
                    $myposts = get_posts( $args );
                    $post = $myposts[0];
                    $post_portada[] = $post->ID;
                    setup_postdata( $post );
                ?>
                <div class="box">
                    <a href="<?php echo get_permalink(); ?>">
                        <?php the_image('portada', 1200, 600, 'img-fluid'); ?>
                    </a>
                    <div class="desc">       
                        <h3 class="post-title">
                            <a href="<?php echo get_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <ul class="list-inline list-unstyled">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                        <!--<p class="hidden-md hidden-sm hidden-xs"><?php echo get_the_excerpt(); ?></p>-->
                    </div>    
                </div>
            </div>
        </div>
        <?php elseif($tipo=='box'): ?>
        <div class="row index-box">
            <?php 
                $args = array( 'posts_per_page' => 5,'meta_key' => 'portada','meta_value' => 'true'/*, 'category' =>  get_cat_ID ( 'Portada' )*/ );
                $myposts = get_posts( $args );
                $posts = array();
            ?>
            <div class="col-sm-6">
                <div class="box">
                    <?php 
                        $post = $myposts[0];
                        $post_portada[] = $post->ID;
                        setup_postdata( $post ); 
                    ?>
                    <a href="<?php echo get_permalink(); ?>">
                        <?php the_image('portada-cuadrado', 600, 600, 'img-fluid'); ?>
                    </a>
                    <div class="desc">       
                        <h3 class="post-title">
                            <a href="<?php echo get_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <ul class="list-inline list-unstyled">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                        <!--<p class="hidden-sm"><?php echo get_the_excerpt(); ?></p>-->
                    </div>
                </div>
                <div class="mb20 visible-xs"></div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <?php 
                        foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); 
                            if($i<=0)continue;
                            $post_portada[] = $post->ID;
                    ?>
                    <div class="col-xs-6">
                        <a href="<?php echo get_permalink(); ?>">
                            <?php the_image('portada-cate', 800, 600, 'img-fluid');  ?>
                        </a>
                        <div class="base">
                            <h3>
                                <a href="<?php echo get_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <ul class="list-inline list-unstyled meta">
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                            </ul>
                        </div>
                    </div>
                    <?php if(($i+0)%2==0&&($i+0)!=4) echo '<div class="clearfix barra"></div>';?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php elseif($tipo=='box-neg'): ?>
        <div class="row index-box-neg">
            <?php 
                $args = array( 'posts_per_page' => 5,'meta_key' => 'portada','meta_value' => 'true'/*, 'category' =>  get_cat_ID ( 'Portada' )*/ );
                $myposts = get_posts( $args );
                $posts = array();
            ?>
            <div class="col-sm-6">
                <div class="box">
                    <?php 
                        $post = $myposts[0];
                        $post_portada[] = $post->ID;
                        setup_postdata( $post ); 
                    ?>
                    <a href="<?php echo get_permalink(); ?>">
                        <?php the_image('portada-cuadrado', 600, 600, 'img-fluid'); ?>
                    </a>
                    <div class="desc">       
                        <h3 class="post-title">
                            <a href="<?php echo get_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <ul class="list-inline list-unstyled">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                        <!--<p class="hidden-sm"><?php echo get_the_excerpt(); ?></p>-->
                    </div>
                </div>
                <div class="mb20 visible-xs"></div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <?php 
                        foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); 
                            if($i<=0)continue;
                            $post_portada[] = $post->ID;
                    ?>
                    <div class="col-xs-6">
                        <a href="<?php echo get_permalink(); ?>">
                            <?php the_image('portada-cate', 800, 600, 'img-fluid');  ?>
                        </a>
                        <div class="base">
                            <h3>
                                <a href="<?php echo get_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <ul class="list-inline list-unstyled meta">
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                            </ul>
                        </div>
                    </div>
                    <?php if(($i+0)%2==0&&($i+0)!=4) echo '<div class="clearfix barra"></div>';?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php elseif($tipo=='semi-neg'): ?>
        <div class="row index-semi-neg">
            <?php 
                $args = array( 'posts_per_page' => 3,'meta_key' => 'portada','meta_value' => 'true' /*'category' =>  get_cat_ID ( 'Portada' ) */);
                $myposts = get_posts( $args );
                $posts = array();
                foreach ( $myposts as $post ) : 
                    $post_portada[] = $post->ID;
                    $posts[] = $post;
                endforeach; ?>
            <div class="col-sm-8">
                <div class="box">
                    <?php 
                        $post = $posts[0];
                        setup_postdata( $post ); 
                    ?>
                    <a href="<?php echo get_permalink(); ?>">
                        <?php the_image('portada-semi', 750, 520, 'img-fluid'); ?>
                    </a>
                    <div class="desc">       
                        <h3 class="post-title">
                            <a href="<?php echo get_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <ul class="list-inline list-unstyled">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                        <!--<p class="hidden-sm"><?php echo get_the_excerpt(); ?></p>-->
                    </div>
                </div>
                <div class="mb20 visible-xs"></div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                <?php 
                    foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); 
                        if($i<=0)continue;
                        $post_portada[] = $post->ID;
                ?>
                    <div class="col-sm-12 col-xs-6">
                        <a href="<?php echo get_permalink(); ?>">
                            <?php the_image('portada-cate', 800, 600, 'img-fluid');  ?>
                        </a>
                        <div class="base">
                            <h3>
                                <a href="<?php echo get_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <ul class="list-inline list-unstyled meta">
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                            </ul>
                        </div>
                    </div>
                <?php if(($i+0)%1==0&&($i+0)!=3) echo '<div class="clearfix barra hidden-xs"></div>';?>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="row index-semi">
            <?php 
                $args = array( 'posts_per_page' => 3,'meta_key' => 'portada','meta_value' => 'true' /*'category' =>  get_cat_ID ( 'Portada' ) */);
                $myposts = get_posts( $args );
                $posts = array();
                foreach ( $myposts as $post ) : 
                    $post_portada[] = $post->ID;
                    $posts[] = $post;
                endforeach; ?>
            <div class="col-sm-8">
                <div class="box">
                    <?php 
                        $post = $posts[0];
                        setup_postdata( $post ); 
                    ?>
                    <a href="<?php echo get_permalink(); ?>">
                        <?php the_image('portada-semi', 750, 520, 'img-fluid'); ?>
                    </a>
                    <div class="desc">       
                        <h3 class="post-title">
                            <a href="<?php echo get_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <ul class="list-inline list-unstyled">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                        <!--<p class="hidden-sm"><?php echo get_the_excerpt(); ?></p>-->
                    </div>
                </div>
                <div class="mb20 visible-xs"></div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                <?php 
                    foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); 
                        if($i<=0)continue;
                        $post_portada[] = $post->ID;
                ?>
                    <div class="col-sm-12 col-xs-6">
                        <a href="<?php echo get_permalink(); ?>">
                            <?php the_image('portada-cate', 800, 600, 'img-fluid');  ?>
                        </a>
                        <div class="base">
                            <h3>
                                <a href="<?php echo get_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <ul class="list-inline list-unstyled meta">
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                            </ul>
                        </div>
                    </div>
                <?php if(($i+0)%1==0&&($i+0)!=3) echo '<div class="clearfix barra hidden-xs"></div>';?>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>



        <div class="title-section">
            <h2>
                <span>Más </span> Compartidas
            </h2>
            <div class="line"></div>
        </div>
        <div class="row">
            <?php 
                $args = array( 'posts_per_page' => 4,'exclude'=> $post_portada,'orderby'=> 'meta_value_num','meta_key' => 'shares','order'=> 'DESC','date_query' => array(array('after' => '1 week ago')));
                $myposts = get_posts( $args );
                foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); ?>
                    <div class="col-sm-3 col-xs-6">
                        <a href="<?php echo get_permalink(); ?>">
                            <?php the_image('portada-cate', 310, 174, 'img-fluid'); ?>
                        </a>
                        <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <ul class="list-inline list-unstyled meta">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                    </div>
                    <?php if(($i+1)%2==0&&($i+1)!=4) echo '<div class="clearfix visible-xs"></div>';?>
            <?php endforeach; ?>
        </div>


        
       

        <div class="title-section">
            <h2>
                <span>Video </span> Noticias
            </h2>
            <div class="line"></div>
        </div>
        <div class="videos">
            <div class="row">
            <?php 
                $args = array( 'posts_per_page' => 6, 'category' => array(get_cat_ID ( 'Videos' )) );
                $myposts = get_posts( $args );
                foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); ?>
                   <div class="col-sm-2 col-xs-6 item">
                        <a href="<?php echo get_permalink(); ?>" class="img-video">
                            <?php  the_image('thumbnail', 400, 300, 'img-fluid',true); ?>
                            <i class="glyphicon glyphicon-play-circle"></i>
                        </a>
                        <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <ul class="list-inline list-unstyled meta">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                    </div>
                    <?php if(($i+1)%2==0&&($i+1)!=8) echo '<div class="clearfix visible-xs"></div>';?>
                    <?php if(($i+1)%6==0&&($i+1)!=8) echo '</div><div class="row">';?>
            <?php endforeach; ?>
            </div>
        </div>

        <div class="title-section">
            <h2>
                <span> </span> Actualidad
            </h2>
            <div class="line"></div>
        </div>


        <div class="row">
            <div class="col-sm-6">
                    <div class="subindex">
                        <?php 
                        $args = array( 'posts_per_page' => 6,'exclude'=> $post_portada, 'category' => array(get_cat_ID ( 'Actualidad' )) );
                        $myposts = get_posts( $args );
                        $post = $myposts[0];
                        setup_postdata( $post );
                        ?>
                        <article>
                            <a href="<?php echo get_permalink(); ?>">
                                <?php the_image('large', 800, 600, 'img-fluid'); ?>
                            </a>
                            <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <ul class="list-inline list-unstyled meta">
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                                <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                            </ul>
                            <p class="hidden-xs"><?php echo get_the_excerpt(); ?></p>
                        </article>
                    </div>
                    <ul class="list">
                        <?php 
                        foreach($myposts as $i=>$post):
                            if($i<=5)continue;
                            setup_postdata( $post ); 
                        ?>
                        <li><a href="<?php echo get_permalink(); ?>"><strong><?php the_title(); ?></a></strong></li>
                        <?php endforeach; ?>
                    </ul>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="row">
                        <?php 
                        foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); if($i<=0||$i>=5)continue; ?>
                            <div class="col-sm-12 col-xs-6 <?php if($i==4)echo "visible-xs" ?>">
                                <article>
                                <a href="<?php echo get_permalink(); ?>">
                                    <?php  the_image('portada-cate', 800, 600, 'img-fluid'); ?>
                                </a>
                                <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <ul class="list-inline list-unstyled meta">
                                    <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                                    <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                                    <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                                </ul>
                                </article>
                            </div>
                            <?php if(($i)%2==0&&($i)!=4) echo '<div class="clearfix visible-xs"></div>';?>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="adsense">
                            <div class="ads">
                                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                <!-- lap300x600 -->
                                <ins class="adsbygoogle"
                                     style="display:inline-block;width:300px;height:600px"
                                     data-ad-client="ca-pub-4648682563959505"
                                     data-ad-slot="2586805202"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="title-section">
            <h2>
                <span>Noticias </span> Destacadas
            </h2>
            <div class="line"></div>
        </div>
        <div class="row destacadas">
            <?php 
                $destacados = array('Perú','Mundo','Vida y Estilo','Deportes');
                foreach($destacados as $ides=>$des):
                    $catid = get_cat_ID ($des);
                    $link = get_category_link( $catid );
            ?>
            <div class="col-sm-3 col-xs-6">
                <h3 class="title-cate"><a href="<?php echo $link; ?>"><?php echo $des; ?></a></h3>
                <?php 
						//$args = array( 'posts_per_page' => 4,'exclude'=> $post_portada, 'category' => array($catid) );
                        $args = array( 'posts_per_page' => 4,'exclude'=> $post_portada, 'category' => array($catid),'orderby'=> 'meta_value_num','meta_key' => 'shares','order'=> 'DESC','date_query' => array(array('after' => '3 day ago')));
                        $myposts = get_posts( $args );
                        $post = $myposts[0]; setup_postdata( $post ); 
                ?>
                <article>
                <a href="<?php echo get_permalink(); ?>">
                    <?php  the_image('portada-cate', 800, 600, 'img-fluid'); ?>
                </a>
                <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                <ul class="list-inline list-unstyled meta">
                    <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                    <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                    <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                </ul>
                </article>
                <ul class="list">
                <?php foreach($myposts as $i=>$post): setup_postdata( $post ); if($i==0)continue; ?>
                <li><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php if(($ides+1)%2==0&&($ides+1)!=4) echo '<div class="clearfix visible-xs"></div>';?>
            <?php endforeach; ?>
        </div>
        

<?php /*?>
        <div class="title-section">
            <h2>
                <span>Videos </span> Virales
            </h2>
            <div class="line"></div>
        </div>
        <div class="videos">
            <div class="row">
            <?php 
                $args = array( 'posts_per_page' => 8, 'category' => array(get_cat_ID ( 'Virales' )) );
                $myposts = get_posts( $args );
                foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); ?>
                   <div class="col-sm-3 col-xs-6 item">
                        <a href="<?php echo get_permalink(); ?>" class="img-video">
                            <?php  the_image('portada-cate', 800, 600, 'img-fluid',true); ?>
                            <i class="glyphicon glyphicon-play-circle"></i>
                        </a>
                        <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <ul class="list-inline list-unstyled meta">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                    </div>
                    <?php if(($i+1)%2==0&&($i+1)!=8) echo '<div class="clearfix visible-xs"></div>';?>
                    <?php if(($i+1)%4==0&&($i+1)!=8) echo '</div><div class="row">';?>
            <?php endforeach; ?>
            </div>
        </div>


        <div class="title-section">
            <h2>
                <span>KIOSKO </span> Diarios
            </h2>
            <div class="line"></div>
        </div>
        <div class="videos">
            <div class="row">
            <?php 
                $args = array( 'posts_per_page' => 6, 'category' => array(get_cat_ID ( 'kiosko' )) );
                $myposts = get_posts( $args );
                foreach ( $myposts as $i=>$post ) : setup_postdata( $post ); ?>
                   <div class="col-sm-2 col-xs-6 item">
                        <a href="<?php echo get_permalink(); ?>" class="img-video">
                            <?php  the_image('portada-cate', 800, 600, 'img-fluid',true); ?>
                            <i class="glyphicon glyphicon-play-circle"></i>
                        </a>
                        <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <ul class="list-inline list-unstyled meta">
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-calendar"></i> <?php echo get_elapsed_time(); ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-share" aria-hidden="true"></i> <?php echo get_post_custom_values("shares")[0] ?> </a></li>
                            <li><a href="<?php echo get_permalink(); ?>"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo get_post_custom_values("views")[0] ?> </a></li>
                        </ul>
                    </div>
                    <?php if(($i+1)%2==0&&($i+1)!=8) echo '<div class="clearfix visible-xs"></div>';?>
                    <?php if(($i+1)%6==0&&($i+1)!=8) echo '</div><div class="row">';?>
            <?php endforeach; ?>
            </div>
        </div>
		<?php */?>
    </div>
</div>
<?php get_footer(); ?>