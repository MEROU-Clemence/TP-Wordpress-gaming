<?php

/*
* Plugin Name: Plugin TP WP Clemence
* Description: Plugin tp clemence
* Autho: my
* Version: 1.0.0
*/

// on importe notre fichier du Widget
require_once plugin_dir_path(__FILE__) . "widget/Tp_Wp_Clemence_Widget.php";
// on importe notre fichier de Database.
require_once plugin_dir_path(__FILE__) . "service/Tp_Wp_Clemence_Database_service.php";
// on importe notre fichier List
require_once plugin_dir_path(__FILE__) . "Tp_List.php";
// Inclure les fichier à partir du dossier template
include plugin_dir_path(__FILE__) . "templates/mesPlayers.php";
include plugin_dir_path(__FILE__) . "templates/mesCompetitions.php";
include plugin_dir_path(__FILE__) . "templates/mesGroupes.php";
include plugin_dir_path(__FILE__) . "templates/mesPoules.php";
include plugin_dir_path(__FILE__) . "templates/mesMatchs.php";
include plugin_dir_path(__FILE__) . "templates/mesPoints.php";
include plugin_dir_path(__FILE__) . "templates/mesScores.php";


// création de la classe du plugin
class Tpclem
{

    // appelle du constructor 
    public function __construct()
    { // fonction qui se charge de l'instance de la classe (=tout ce qu'on met dans le construct sera appelé apres avec des new Ern quelque part d'autre)

        // activation du plugin: création des tables à l'activation du plugin
        // __FILE__: constante magique qui contient le chemin du fichier dans lequel on se trouve
        register_activation_hook(__FILE__, array("Tp_Wp_Clemence_Database_Service", "create_db"));

        // désactivation du plugin: vidange des tables à la désactivation du plugin
        // register_deactivation_hook(__FILE__, array("Tp_Wp_Clemence_Database_Service", "empty_db"));

        // désinstallation du plugin: suppression des tables à la désinstallation du plugin
        // ATTENTION LE PLUGIN SERA SUPPRIMÉ DU CODE SOURCE
        register_uninstall_hook(__FILE__, array("Tp_Wp_Clemence_Database_Service", "delete_db"));

        // on va enregistrer le widget
        add_action("widgets_init", function () {
            register_widget("Tp_Wp_Clemence_Widget");
        });

        // on va enregistrer le menu client
        add_action("admin_menu", array($this, "add_menu_players"));
    }

    // création du menu dans le backoffice pour gérer les clients
    public function add_menu_players()
    {
        $player = new ClemPlayers();
        $competition = new ClemCompetitions();
        $groupe = new ClemGroupes();
        $poule = new ClemPoules();
        $match = new ClemMatchs();
        $point = new ClemPoints();
        $score = new ClemScores();
        // On a une méthode de WP qui permet de le faire (elle attend 7 arguments)
        // 1er argument: titre de la page
        // 2ième argument: titre du menu
        // 3ième argument: capacité de l'utilisateur à voir le menu (ici droit admin)
        // 4ième argument: slug de la page (pour construire l'url)
        // 5ième argument: callback qui va afficher la page
        // ($this car on est dans la class Tpclem,
        // mesClients() est la fonction de cette class)
        // 6ième argument: icon du menu
        // 7ième argument: position du menu
        // liste des positions du menu:
        // https://developer.wordpress.org/reference/functions/add_menu_page/



        //********MENU PRINCIPAL********
        // MES COMPETITIONS
        add_menu_page(
            "NOS COMPETITIONS (REPERTOIRE)",
            "COMPETITIONS",
            "manage_options",
            "gaming-competitions",
            array($competition, "mesCompetitions"),
            "dashicons-groups",
            40
        );

        // MES JOUEURS
        add_menu_page(
            "NOS JOUEURS INSCRITS",
            "JOUEURS",
            "manage_options",
            "gaming-players",
            array($player, "mesPlayers"),
            "dashicons-groups",
            41
        );

        // MES GROUPES
        add_menu_page(
            "NOS GROUPES (REFERENCES)",
            "GROUPES",
            "manage_options",
            "gaming-groupes",
            array($groupe, "mesGroupes"),
            "dashicons-groups",
            42
        );

        // MES POULES
        add_menu_page(
            "REPARTITIONS DANS LES POULES (Groupes d'appartenance)",
            "POULES",
            "manage_options",
            "gaming-poules",
            array($poule, "mesPoules"),
            "dashicons-groups",
            43
        );

        // MES MATCHS
        add_menu_page(
            "MATCHS GLOSSAIRE",
            "MATCHS",
            "manage_options",
            "gaming-matchs",
            array($match, "mesMatchs"),
            "dashicons-groups",
            44
        );

        // MES POINTS
        add_menu_page(
            "POINTS INDICATEUR",
            "POINTS",
            "manage_options",
            "gaming-points",
            array($point, "mesPoints"),
            "dashicons-groups",
            44
        );

        // MES SCORES
        add_menu_page(
            "SCORES / RESULTATS",
            "SCORES",
            "manage_options",
            "gaming-scores",
            array($score, "mesScores"),
            "dashicons-groups",
            45
        );

        //*********SOUS MENUS********

        // 1er argument: son menu parent (le slug du parent)
        // 2ième argument: titre de la page
        // 3ième argument: titre du menu
        // 4ième argument: capacité de l'utilisateur à voir le menu (ici droit admin)
        // 5ième argument: slug de la page (pour construire l'url)
        // 6ième argument: callback qui va afficher la page

        // on va ajouter un sous-menu pour AJOUTER une COMPETITION
        add_submenu_page(
            "gaming-competitions",
            "AJOUTER UNE COMPETITION",
            "AJOUTER Compétition",
            "manage_options",
            "gaming-competition-add",
            array($competition, "mesCompetitions")
        );


        // on va ajouter un sous-menu pour AJOUTER un JOUEUR
        add_submenu_page(
            "gaming-players",
            "AJOUTER UN NOUVEAU JOUEUR",
            "AJOUTER joueur",
            "manage_options",
            "gaming-players-add",
            array($player, "mesPlayers")
        );

        // on va ajouter un sous-menu pour AJOUTER un GROUPE
        add_submenu_page(
            "gaming-groupes",
            "AJOUTER UN NOUVEAU GROUPE",
            "AJOUTER groupe",
            "manage_options",
            "gaming-groupes-add",
            array($groupe, "mesGroupes")
        );

        // on va ajouter un sous-menu pour AJOUTER une POULE
        add_submenu_page(
            "gaming-poules",
            "S'AFFILIER A UNE POULE",
            "AJOUTER poule",
            "manage_options",
            "gaming-poules-add",
            array($poule, "mesPoules")
        );

        // on va ajouter un sous-menu pour AJOUTER un MATCH
        add_submenu_page(
            "gaming-matchs",
            "AJOUTER UN MATCH",
            "AJOUTER match",
            "manage_options",
            "gaming-matchs-add",
            array($match, "mesMatchs")
        );


        // on va ajouter un sous-menu pour AJOUTER des Scores
        add_submenu_page(
            "gaming-scores",
            "AJOUTER UN SCORE",
            "AJOUTER scores",
            "manage_options",
            "gaming-scores-add",
            array($score, "mesScores")
        );
    }
}


new Tpclem(); // on instancie la class