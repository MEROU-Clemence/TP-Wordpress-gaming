<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thème TP</title>
    <!-- import de bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- import de style.css -->
    <!-- <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>"> -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/style.css"; ?>">
</head>

<body>
    <!-- Partie réservée au header -->
    <header>
        <div class="header-general">
            <div class="en-tete-line">
                <div class="en-tete-links">
                    <i class="bi bi-geo-alt custom-icon p-1"><a href="#">Trouver magasin</a></i>
                    <i class="bi bi-envelope custom-icon p-1"><a href="#">mon-compte@gmail.com</a></i>
                </div>
                <div class="en-tete-links p-1">
                    <a href="#">Promotions du moment</a>
                    <div class="vertical-bar"></div>
                    <a href="#">Services customisation</a>
                    <div class="vertical-bar"></div>
                    <a href="#">Cadeaux</a>
                </div>
                <hr class="line-separate">
            </div>
            <div class="logo-search-user">
                <a href="<?php echo home_url(); ?>">
                    <img class="logo-gaming-palace" src="<?php echo get_template_directory_uri(); ?>/uploads/2023/08/Logo-de-mon-site.png" alt="Logo de mon site">
                </a>
                <div class="search-bar">
                    <div class="search-content">
                        <form action="/rechercher" method="get">
                            <select id="categorie" name="categorie" onchange="redirectOnChange(this)">
                                <option value="toutes">Toutes catégories: &#9660;</option>
                                <option value="pc" data-link="http://tp-wp-clemence.lndo.site/product-category/pc-et-kit-pc/">PC et Kit-PC</option>
                                <option value="tel" data-link="http://tp-wp-clemence.lndo.site/product-category/portables-et-tablettes/">Portables et tablettes</option>
                                <option value="pieces" data-link="http://tp-wp-clemence.lndo.site/product-category/pieces-detaches/">Pièces détachées</option>
                                <option value="accessoires" data-link="http://tp-wp-clemence.lndo.site/product-category/accessoires/">Accessoires</option>
                                <option value="figurines" data-link="http://tp-wp-clemence.lndo.site/product-category/figurines/">Figurines</option>
                                <option value="matériel" data-link="http://tp-wp-clemence.lndo.site/product-category/materiel-jeux/">Matériel jeux</option>
                                <option value="audio" data-link="http://tp-wp-clemence.lndo.site/product-category/casques-audio/">Casques audio</option>
                                <option value="vr" data-link="http://tp-wp-clemence.lndo.site/product-category/casques-vr/">Casques VR</option>
                            </select>
                            <input type="text" name="q" placeholder="Recherches produits...">
                            <button type="submit">RECHERCHE</button>
                        </form>
                    </div>
                </div>
                <div class="compte">
                    <div class="connexion">
                        <span>Mon compte</span>
                        <div>
                            <a href="#">Connection</a>
                            <span>/</span>
                            <a href="#">Déconnection</a>
                        </div>
                    </div>
                    <div class="picto">
                        <i class="bi bi-arrow-left-right"></i>
                        <i class="bi bi-heart"></i>
                        <i class="bi bi-cart"></i>
                    </div>
                </div>
            </div>
            <div>
                
            </div>
            <em><?php echo get_bloginfo('description'); ?></em>

            <!-- on affiche le menu -->
            <?php
            // appel du menu simple
            // wp_nav_menu(array(
            //     "theme_location" => "menu-sup", // on indique le menu à afficher
            //     "container" => "nav", // on indique que le menu sera dans une balise nav
            //     "container_class" => "navbar navbar-expand-sm navbar-light", // on ajoute des class bootstrap
            //     "menu_class" => "navbar-nav me-auto p-1", // on ajoute des class bootstrap
            //     "menu_id" => "menu-principal", //on ajoute un id
            //     "walker" => new Simple_menu() //récupération de notre template du menu
            // )) 

            // appel du menu avec sous-menu
            wp_nav_menu(array(
                "theme_location" => "menu-sup", // on indique le menu à afficher
                "menu_class" => "custom-menu", // ajout de la classe pour le css
                "container" => false,
                'walker' => new Depth_menu() // récupération de notre template du menu
            ))

            ?>

        </div>
    </header>