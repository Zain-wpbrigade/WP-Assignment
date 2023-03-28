<?php
// Get header file
get_header();

// Query to retrieve featured posts
$args_featured = array(
    'post_type' => 'post',
    'meta_query' => array(
        array(
            'key' => '_featured_post',
            'value' => '1',
            'compare' => '='
        )
    )
);

// Query to retrieve non-featured posts
$args_non_featured = array(
    'post_type' => 'post',
    'meta_query' => array(
        array(
            'key' => '_featured_post',
            'value' => '1',
            'compare' => '!='
        )
    )
);

// Execute featured post query
$featured_query = new WP_Query($args_featured);

// Execute non-featured post query
$non_featured_query = new WP_Query($args_non_featured);

// If there are any featured posts
if ($featured_query->have_posts()) :
    // Loop through featured posts
    while ($featured_query->have_posts()) :
        $featured_query->the_post();
        ?>

<!-- Display featured post -->
<div class="post">
  <?php if (has_post_thumbnail()) : ?>
    <div class="featured">
      <?php the_post_thumbnail('large'); ?>
    </div>
      <?php endif; ?>
      <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div class="post-meta">
      <span class="post-date"><?php the_date(); ?></span>
      <span class="post-category"><?php the_category(', '); ?></span>
    </div>
    <div class="post-content"><?php the_excerpt(); ?></div>
</div>
    
<!-- Search form -->
<form action="" method="get" id="searchform">
  <input type="text" name="s" id="s" placeholder="Search...">
  <button type="submit">Search</button>
</form>

<?php endwhile;
endif;

// If there are any non-featured posts
if ($non_featured_query->have_posts()) :

  // Loop through non-featured posts
  while ($non_featured_query->have_posts()) :
  $non_featured_query->the_post();
?>
    
<!-- Display non-featured post -->
<div class="post">
  <?php if (has_post_thumbnail()) : ?>
  <div class="non-featured">
  <?php the_post_thumbnail('large'); ?>
</div>
<?php endif; ?>
  <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  <div class="post-meta">
    <span class="post-date"><?php the_date(); ?></span>
    <span class="post-category"><?php the_category(', '); ?></span>
  </div>
  <div class="post-content"><?php the_excerpt(); ?></div>
</div>
<?php endwhile;
endif;

// Reset post data to prevent conflicts
wp_reset_postdata();

// Get footer file
get_footer();
?>
<style>
.post {
  display: inline-block;
  width: 30%;
  margin: 1%;
  vertical-align: top;
}

.featured {
  margin-bottom: 10px;
}

.post-title {
  font-size: 20px;
  margin-bottom: 5px;
}

.post-meta {
  font-size: 14px;
  color: #888;
  margin-bottom: 10px;
}

.post-date {
  margin-right: 10px;
}

.post-content {
  font-size: 16px;
  line-height: 1.5;
}

#searchform {
  display: inline-block;
  width: 100%;
  margin-bottom: 10px;
}

#s {
  padding: 5px;
  width: 70%;
  border: 1px solid #ccc;
  border-radius: 3px;
}

button[type="submit"] {
  padding: 5px 10px;
  background-color: #0073aa;
  color: #fff;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}

/* Media queries for responsive design */
@media only screen and (max-width: 768px) {
  .post {
    width: 45%;
  }
}

@media only screen and (max-width: 480px) {
  .post {
    width: 100%;
  }
}
</style>