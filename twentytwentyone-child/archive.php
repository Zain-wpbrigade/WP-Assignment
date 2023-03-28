<?php
get_header();

// Set up variables for featured and non-featured posts
$featured_posts = array();
$non_featured_posts = array();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        
        // Check if post is featured or not
        if ( get_post_meta( get_the_ID(), 'featured_case_study', true ) == '1' ) {
            $featured_posts[] = get_the_ID();
        } else {
            $non_featured_posts[] = get_the_ID();
        }

    endwhile;

    // Display featured posts section
    if ( ! empty( $featured_posts ) ) : ?>

        <section id="featured-posts" class="posts-case-study">
            <h2>Featured Case Studies</h2>

            <?php foreach ( $featured_posts as $post_id ) : ?>
                <?php $post = get_post( $post_id ); setup_postdata( $post ); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                        <div class="entry-thumbnail">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>
                    <header class="entry-header">
                        <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    </header>

                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endforeach; ?>

        </section>

    <?php endif;

    // Display search bar
    ?>
    <section id="search-bar">
        <form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <input type="text" name="s" id="s" placeholder="Search Case Studies">
            <button type="submit" id="searchsubmit">Serach</button>
        </form>
    </section>
    

    <?php

    // Display non-featured posts section
    if ( ! empty( $non_featured_posts ) ) : ?>
    
        <section id="non-featured-posts" class="posts-case-study">
            <h2>Other Case Studies</h2>
            <div id="ajax-content">
                <?php foreach ( $non_featured_posts as $post_id ) : ?>
                    <?php $post = get_post( $post_id ); setup_postdata( $post ); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="entry-thumbnail">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>
                        <header class="entry-header">
                            <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                        </header>

                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

    <?php endif;
endif;

get_footer();
?>


<style>
    /* CSS for featured posts */
#featured-posts {
    margin-bottom: 30px;
}

#featured-posts h2 {
    font-size: 24px;
    margin-bottom: 20px;
}

#featured-posts article {
    margin-bottom: 50px;
}

#featured-posts .entry-thumbnail {
    margin-bottom: 20px;
}

#featured-posts .entry-thumbnail img {
    width: 100%;
}

#featured-posts .entry-title {
    font-size: 20px;
    margin-bottom: 10px;
}

#featured-posts .entry-content {
    font-size: 16px;
    line-height: 1.5;
}

/* CSS for non-featured posts */
#non-featured-posts {
    margin-bottom: 30px;
}

#non-featured-posts h2 {
    font-size: 24px;
    margin-bottom: 20px;
}

#non-featured-posts article {
    width: calc(33.33% - 2%);
    display: inline-block;
    margin-right: 2%;
    margin-bottom: 30px;
    vertical-align: top;
}

#non-featured-posts article:nth-child(3n) {
    margin-right: 0;
}

#non-featured-posts .entry-thumbnail {
    margin-bottom: 10px;
}

#non-featured-posts .entry-thumbnail img {
    width: 100%;
}

#non-featured-posts .entry-title {
    font-size: 18px;
    margin-bottom: 5px;
}

#non-featured-posts .entry-meta {
    font-size: 14px;
    color: #888;
    margin-bottom: 10px;
}

#non-featured-posts .entry-content {
    font-size: 16px;
    line-height: 1.5;
}

</style>

<script>
    // AJAX search functionality
    jQuery(document).ready(function($) {
        $('#searchform').submit(function() {
            var s = $('#s').val();
            $.ajax({
                type: "GET",
                data: {
                    's': s
                },
                dataType: "html",
                url: "<?php echo esc_js( home_url() ); ?>/wp-admin/admin-ajax.php?action=ajax_search",
                success: function(data) {
                    $('#ajax-content').html(data);
                }
            });
            return false;
        });
    });
</script>
