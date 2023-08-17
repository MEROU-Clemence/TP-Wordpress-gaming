<!-- Template pour afficher la liste des articles d'une catégorie -->
<div>
    <h3>
        <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
    </h3>
    <!-- on check si c'est un post -->
    <?php if ('post' == get_post_type()) : ?>
        <div class="blog-postmeta">
            <p class="post-date">
                <?php echo get_the_date() ?>
            </p>
        </div>
    <?php endif; ?>

</div>
<div class="entry-summary">
    <!-- the_excerpt() est une fonction native qui permet d'afficher un extrait de l'article alors que the_content() renvoie la totalité -->
    <?php the_excerpt() ?>
    <!-- on ajoute un bouton Voir Plus pour l'intégralité du post -->
    <a href="<?php the_permalink() ?>">
        <!-- esc_html_e permet d'interprêter le code hexadécimal (ex ici = &rarr qui donne une fleche) -->
        <?php esc_html_e("Lire plus &rarr; ") ?>
    </a>
</div>