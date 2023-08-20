<?php

// Création d'un menu de navigation
// 1. On enregistre le menu
// 2. On initialise le menu
// 3. On l'active et on le configure dans le BO
// 4. On design le menu dans le thème

// 1. On enregistre le menu
function register_menu()
{
    // fonction native de wordpress qui permet d'enregistrer un menu
    register_nav_menus(
        array(
            'menu-sup' => __('Main menu'), // __() permet de traduire le mot dans les différents langages
        )
    );
}
// 2. On initialise le menu
add_action('init', 'register_menu');
// add_action permet d'exécuter une fonction à un moment précis
// 1er paraètre: le hook 'init' qui permet d'exécuter la fonction au moment de l'initialisation du thème
// 2eme paramètre: le nom de la fonction à exécuter

// 3. configuration du menu dans le BO

// 4. on design le menu dans le theme
class Simple_menu extends Walker_Nav_Menu
{
    // on va appeler et surcharger la méthode start_el()
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        // $output: ce qui va être affiché (template)
        // $data_object: servira à récupérer les infos du menu(titres, liens, etc...)

        // 1) on récupère les datas du menu dans les variables
        $title = $data_object->title; //récupère les titres du menu
        $permalink = $data_object->url; //récupère les liens du menu

        // 2) on construit le template
        $output .= "<div class='nav-item'>"; // on ouvre une div
        $output .= "<a class='nav-link border bg-warning m-1 custom_a' href='$permalink'>";
        $output .= $title; // on affiche le titre
        $output .= "</a>"; // on ferme le a
    }

    public function end_el(&$output, $data_object, $depth = 0, $argc = null)
    {
        $output .= "</div>"; // on ferme la div
    }
}

// on design un menu qui gère les sous-menus
class Depth_menu extends Walker_Nav_Menu
{
    // fonction pour démarrer le niveau de menu
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= "<ul class='sub-menu'>"; //on ouvre une url
    }

    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        // on récupère les titres
        $title = $data_object->title;
        // on récupère les liens
        $permalink = $data_object->url;
        // on gère l'indentation des liens
        // lien doc php signification \t https://www.php.net/manual/fr/regexp.reference.escape.php
        $indentation = str_repeat("\t", $depth);
        // les classes css à ajouter
        $classes = empty($data_object->classes) ? array() : (array) $data_object->classes;
        // on ajoute la classe css menu-item
        $class_name = join(' ', apply_filters('nav_menu_css_array', array_filter($classes), $data_object));

        if ($depth > 0) {
            // esc_attr() permet d'échapper les caractères spéciaux
            $output .= $indentation . '<li class="' . esc_attr($class_name) . '">';
        } else {
            $output .= '<li class="' . esc_attr($class_name) . '">';
        }

        $output .= '<a href="' . $permalink . '">' . $title . '</a>';
    }

    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
        $output .= "</li>"; // on ferme la li
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= "</ul>"; // on ferme la ul
    }
}

// SHORTCODE

// 1er exemple : shortcode sans paramètre
function monShortCode()
{
    // on retourne le shortcode que l'on souhaite afficher
    return "<div class='alert alert-success'>Mon super shortcode</div>";
}
// on ajoute le shortcode à notre thème
add_shortcode('monShort', 'monShortCode');

// 2ième exemple :  shortcode avec paramètres
function monShortPromo($atts) //on ajoute un paramètre
{
    // on va déclarer une variable ici $a
    // on utilise la fonction WP 'shortcode_atts()'
    // pour attribuer une valeur par défaut à notre paramètre
    $a = shortcode_atts(['percent' => 10], $atts);
    // on retourne le shortcode que l'on souhaite afficher
    // la variabl se met entre { }
    return "<div class='alert alert-success'>Promo de {$a['percent']}%</div>";
}
// on ajoute le shortcode à notre theme
add_shortcode('promo', 'monShortPromo');
