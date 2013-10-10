<div id="lightbox-wrapper">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <div id="map-lightbox-bg"></div>
                <div id="map-lightbox" class="hneue-light">
                    <div id="map-lightbox-text">
                        <?php 

                        $cat_obj = get_category_by_slug('lightbox'); 
                        $row_cat_id = $cat_obj->term_id;

                        $args = array(
                        'posts_per_page'  => 15,
                        'numberposts'     => 15,
                        'category'        => $row_cat_id,
                        'order'           => 'ASC',
                        'post_type'       => 'homepage-item',
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