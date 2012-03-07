<?php get_header(); ?>
		
			<div class="style2">    
			    <h3 class="entry-title"><?php the_title() ?></h3>
			    <?php while ( have_posts() ) : the_post() ?>
					
					<div class="entry-content"><?php the_content() ?></div>
					
			    <?php endwhile; ?>
			    
			</div>
						

<?php get_footer(); ?>