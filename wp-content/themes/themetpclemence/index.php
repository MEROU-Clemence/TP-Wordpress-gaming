<!-- on appelle notre header avec la fonction native get_header-->
<?php get_header(); ?>

<!-- partie réservée au main -->
<main>
    <div class="d-flex">
        <div class="bloc-main">
            <?php
            // SI j'ai au moins un post, je boucle dessus pour récupérer chaque post
            if (have_posts()) : while (have_posts()) : the_post();

                    get_template_part('content', get_post_format());

                // on ferme la boucle while
                endwhile;
            // on ferme la condition if
            endif;
            ?>

            <!-- <em>Partie pour le contenu</em> -->
        </div>
        <!-- importer la sidebar -->
        <?php get_sidebar(); ?>
    </div>
</main>

<!-- on appelle notre footer avec la fonction native get_footer-->
<?php get_footer(); ?>