<?php
/**
 * Template Name: Landing Page 2 ( Page Translation Form )
 *
 * This is the template that displays the home with contact form
 *
 * @package sparkling
 */

get_header(); ?>

  <div id="primary" class="content-area">

    <main id="main" class="site-main one-step landing-page" role="main">

      <?php while ( have_posts() ) : the_post(); ?>

        <?php get_template_part( 'content', 'landing-page-translate' ); ?>

      <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->

  </div><!-- #primary -->

<?php get_footer(); ?>
