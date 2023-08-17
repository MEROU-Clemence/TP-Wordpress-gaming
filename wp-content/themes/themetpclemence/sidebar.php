<div class="col-sm-3 bg-warning offset-1 blog-sidebar">
    <div class="sidebar-module sidebar-module-insert">
        <?php if (is_single()) : ?> <!-- is_single permet de savoir si on est dans le détail d'un post -->
            <h5>A propos</h5>
            <!-- ici on affiche la biographie de l'auteur -->
            <p>
                <?php the_author_meta('description'); ?>
            </p>
            <!-- Montrer tous les articles de l'auteur -->
            <h5>Articles de l'auteur</h5>
            <ol class="list-unstyled">
                <!-- on doit interroger la BDD pour récupérer les posts de l'auteur -->
                <?php
                $author_post = new WP_Query(array( //WP_Query permet de constuire une requête personnalisée
                    'author' => get_the_author('ID') // on récupère l'id de l'auteur du post va chercher tous les articles du même auteur
                    // pourrait se traduire par:
                    // SELECT * FROM wp_posts WHERE post_author = id
                ));

                // on boucle sur les posts de l'auteur
                while ($author_post->have_posts()) : $author_post->the_post();
                ?>
                    <li>
                        <!-- the_permalink() permet de récupérer le lien vers le post -->
                        <!-- the_title permet de récupérer le titre du post -->
                        <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                    </li>
                <?php endwhile; ?>
            </ol>
        <?php endif; ?>
        <h5>Archives</h5>
        <!-- ici on affiche les archives des posts -->
        <p>
            <?php wp_get_archives('type=monthly'); ?>
        </p>
        <?php
        // affichage du widget dans la sidebar
        if (is_active_sidebar('new-widget-area')) : ?>
            <div id="secondary-sidebar" class="new-widget-area">
                <?php dynamic_sidebar("new-widget-area") ?>
            </div>

        <?php endif; ?>
    </div>
</div>