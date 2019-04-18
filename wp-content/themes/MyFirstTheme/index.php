<?php get_headers(); ?>

<h1>First Theme</h1>


<?php

if (have_post()):
	while(have_post()):the_post(); ?>

	<h2><?php the_title(): ?></h2>

	<?php the_content(); ?>

	<?php endwhile;

else:
	echo '<p>No content found</p>';
	endif; ?>

<?php get_footer(); ?>