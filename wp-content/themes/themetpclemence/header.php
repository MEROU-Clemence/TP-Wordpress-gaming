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
            <div class="en-tete-line p-1 justify-content-around row">
                <div class="en-tete-links col-md-5">
                    <i class="bi bi-geo-alt pr-1 custom-icon"><a href="#">Trouver magasin</a></i>
                    <i class="bi bi-envelope pr-1 custom-icon"><a href="#">mon-compte@gmail.com</a></i>
                </div>
                <div class="en-tete-links col-md-5 d-flex justify-content-end align-items-center">
                    <a class="p-1" href="#">Promotions du moment</a>
                    <div class="vertical-bar"></div>
                    <a class="p-1" href="#">Services customisation</a>
                    <div class="vertical-bar"></div>
                    <a class="p-1" href="#">Cadeaux</a>
                </div>   
            </div>
            <div class="logo-search-user justify-content-around">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <img class="logo-gaming-palace" src="<?php echo get_template_directory_uri(); ?>/uploads/2023/08/cropped-Logo-et-nom-du-site.png" alt="Logo de mon site">
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex search-bar custom-center align-items-center justify-content-center">
                            <div class="d-flex search-content">
                                <form action="/rechercher" method="get">
                                    <div class=d-flex>
                                        <select id="categorie" name="categorie" onchange="redirectOnChange(this)">
                                            <option value="toutes">Toutes catégories: &nbsp; &nbsp; &nbsp; &#9660;</option>
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
                                        <div>
                                            <button type="submit">RECHERCHE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 compte d-flex align-items-center justify-content-end">
                        <div class="d-flex align-items-center">
                            <div class="connexion">
                                <span>Mon compte</span>
                                <div class="d-flex">
                                    <a href="#">Connection</a>
                                    <span class="barre">/</span>
                                    <a href="#">Déconnection</a>
                                </div>
                            </div>
                            <div class="d-flex picto">
                                <i class="bi bi-arrow-left-right"></i>
                                <i class="bi bi-heart"></i>
                                <i class="bi bi-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="menu-pages d-flex justify-content-around">
                <div class="d-flex justify-content-around">
                    <div class="col-md-2 search-content-onglet">
                        <form action="/rechercher" method="get">
                            <div class="d-flex">
                                <i class="bi bi-list hamburger"></i>
                                <select id="categorie-deux" name="categorie" onchange="redirectOnChange(this)">
                                    <option value="toutes">Toutes catégories &nbsp; &nbsp; &nbsp; &#9660;</option>
                                    <option value="accueil" data-link="http://tp-wp-clemence.lndo.site">Accueil</option>
                                    <option value="compétition" data-link="http://tp-wp-clemence.lndo.site/competition/">Compétitions</option>
                                    <option value="catalogue" data-link="http://tp-wp-clemence.lndo.site/catalogue/">Catalogue</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-5">
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
                </div>
                <div class="col-md-2 mt-3 d-flex justify-content-end">
                    <span class="fr">Fr</span>
                </div>
            </div>
        </div>
    </header>