<!-- Ici on design le détail d'un post -->

<a href="<?php the_permalink() ?>">
    <h1 class="blog-post-title title-single">
        <?php the_title() ?>
    </h1>
</a>
<div class="mt-3">
    <?php the_date() ?> par <a href="#"><?php the_author() ?></a>
</div>
<div class="mt-3">
    <p class="mini-title-single">Catégories: <?php the_category() ?></p>
</div>
<?php if (has_tag()) : ?>
    <p class="mini-title-single"> <!-- ici le titre Etiquettes qui s'affiche: --><?php the_tags() ?></p>
<?php endif; ?>
<div class="mt-3">
    <?php the_content() ?>
</div>