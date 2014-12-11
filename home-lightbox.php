<?php if(!count($_GET)) { ?>


<div id="lightbox-wrapper">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <div id="map-lightbox-bg"></div>
                <div id="map-lightbox" class="hneue-light">
                    <div id="map-lightbox-text">
                        <?php 

                        $args = array(
                        'posts_per_page'  => 1,
                        'numberposts'     => 1,
                        'order'           => 'ASC',
                        'post_type'       => 'homepage-item',
                        'category_name'   => 'lightbox'
                        );

                        $row1_posts_array = get_posts( $args );

                        foreach( $row1_posts_array as $post ) : setup_postdata($post); ?>
                        <p class="map-lightbox-title hneue-bold"><?php the_title(); ?></p>
                        <p><?php the_content(); ?></p>
                        <?php endforeach; ?>
                    </div>
                    <button id="map-lightbox-close"></button>
                    <a href="/projects/">All projects</a>
                </div>
           </div>
        </div>
    </div>
</div>

<?php } ?>
