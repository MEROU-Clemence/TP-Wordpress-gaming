<?php

/*
* Plugin Name: Plugin TP WP Clemence
* Description: Plugin tp clemence
* Autho: my
* Version: 1.0.0
*/

// on importe notre fichier du Widget
require_once plugin_dir_path(__FILE__) . "/widget/Tp_Wp_Clemence_Widget.php";
// on importe notre fichier de Database.
require_once plugin_dir_path(__FILE__) . "/service/Tp_Wp_Clemence_Database_service.php";

// création de la classe du plugin
class Tpclem
{
    // appelle du constructor 
    public function __construct()
    { // fonction qui se charge de l'instance de la classe (=tout ce qu'on met dans le construct sera appelé apres avec des new Ern quelque part d'autre)

        // on va enregistrer le widget
        add_action("widgets_init", function () {
            register_widget("Tp_Wp_Clemence_Widget");
        });
    }
}

new Tpclem(); // on instancie la class