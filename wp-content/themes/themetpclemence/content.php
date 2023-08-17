<!-- Nous avons accès ici aux valeurs renvoyées par "the_post()"
On a donc accès à tous les champs de la table wp_posts
On peut donc afficher le titre, le contenu, la date, l'auteur, etc...
-->

<a href="<?php the_permalink() ?>">
    <h4 class="text-warning blog-post-title">
        <?php the_title(); ?>
    </h4>
</a>
<p>
    <?php the_date(); ?> par <a href="#"><?php the_author(); ?></a>
</p>
<?php the_content(); ?>