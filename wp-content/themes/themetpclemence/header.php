<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thème TP</title>
    <!-- import de bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- import de style.css -->
    <!-- <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>"> -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/style.css"; ?>">
</head>

<body>
    <!-- Partie réservée au header -->
    <header class="header-general">
        <div class="en-tete-line">
            <div>
                <span><i class="bi bi-geo-alt custom-icon">Trouver Magasin</i></span>
                <span><i class="bi bi-envelope custom-icon">mon-compte@gmail.com</i></span>
            </div>
            <div class="en-tete-links">
                <span>Promotions du moment</span>
                <div class="vertical-bar"></div>
                <span>Services customisation</span>
                <div class="vertical-bar"></div>
                <span>Cadeaux</span>
            </div>
        </div>
        <a href="<?php echo home_url(); ?>">
            <img class="logo-gaming-palace" src="<?php echo get_template_directory_uri(); ?>/uploads/2023/08/Logo-de-mon-site.png" alt="Logo de mon site">
        </a>

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


    </header>