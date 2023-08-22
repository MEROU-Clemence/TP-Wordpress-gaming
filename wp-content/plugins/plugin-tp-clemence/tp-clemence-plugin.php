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
        register_deactivation_hook(__FILE__, array("Tp_Wp_Clemence_Database_Service", "empty_db"));

        // désinstallation du plugin: suppression des tables à la désinstallation du plugin
        // ATTENTION LE PLUGIN SERA SUPPRIMÉ DU CODE SOURCE
        register_uninstall_hook(__FILE__, array("Tp_Wp_Clemence_Database_Service", "delete_db"));

        // on va enregistrer le widget
        add_action("widgets_init", function () {
            register_widget("Tp_Wp_Clemence_Widget");
        });

        // on va enregistrer le menu client
        add_action("admin_menu", array($this, "add_menu_client"));
    }

    // création du menu dans le backoffice pour gérer les clients
    public function add_menu_client()
    {
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



        // CLIENTS SITE GAMING PALACE
        add_menu_page(
            "Les clients de Gaming Palace",
            "Clients GAMING PALACE",
            "manage_options",
            "gaming-client",
            array($this, "mesClients"),
            "dashicons-groups",
            40
        );

        // on va ajouter un sous-menu pour ajouter un client
        // 1er argument: son menu parent (le slug du parent)
        // 2ième argument: titre de la page
        // 3ième argument: titre du menu
        // 4ième argument: capacité de l'utilisateur à voir le menu (ici droit admin)
        // 5ième argument: slug de la page (pour construire l'url)
        // 6ième argument: callback qui va afficher la page
        add_submenu_page(
            "gaming-client",
            "Ajouter un client",
            "Ajouter",
            "manage_options",
            "gaming-client-add",
            array($this, "mesClients")
        );

        //     // JOUEURS
        //     add_submenu_page(
        //         "gaming-client",
        //         "JOUEURS",
        //         "JOUEURS",
        //         "manage_options",
        //         "gaming-competition-add",
        //         array($this, "mesClients")
        //     );

        //     // COMPETITIONS
        //     add_submenu_page(
        //         "gaming-client",
        //         "COMPETITIONS",
        //         "COMPETITIONS",
        //         "manage_options",
        //         "gaming-competition-add",
        //         array($this, "mesClients")
        //     );

        //     // POULES
        //     add_submenu_page(
        //         "gaming-client",
        //         "POULES",
        //         "POULES",
        //         "manage_options",
        //         "gaming-competition-add",
        //         array($this, "mesClients")
        //     );

        //     // RESULTATS
        //     add_submenu_page(
        //         "gaming-client",
        //         "RESULTATS",
        //         "RESULTATS",
        //         "manage_options",
        //         "gaming-competition-add",
        //         array($this, "mesClients")
        //     );

    }
    // fonction d'affichage pour le menu
    public function mesClients()
    {
        // on va instancier la classe Ern_Database_Service
        $db = new Tp_Wp_Clemence_Database_Service();
        // on récupère le titre de la page
        echo "<h2>" . get_admin_page_title() . "</h2>";
        // si la page dans laquelle on est == ern-client (slug de la page), on affiche la liste
        if ($_GET["page"] == "gaming-client" || $_POST["send"] == "ok" || $_POST["action"] == "delete-client") {
            // on va mettre une seconde condition
            // si les données sont présentes, on exécute la requête.
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                // on exécute la méthode save_client
                $db->save_client();
            }

            // de la même manière que pour l'insertion, on utilise le flag action pour savoir si on doit supprimer ou pas
            if (isset($_POST['action']) && $_POST['action'] == 'delete-client') {
                // on exécute la méthode save_client
                $db->delete_client($_POST['delete-client']);
            }

            // on instancie la classe Ern_List
            $table = new Tp_List();
            // on appelle la méthode prepate_items
            $table->prepare_items();
            // on génère le rendu HTML de la table grâce à la méthode display
            //  que l'on imbrique dans un formulaire
            echo "<form method='post'>";
            $table->display();
            echo "</form>";

            // on va afficher le formulaire d'ajout de clients
            // echo "<table class='table-border'>";
            // echo "<tr>";
            // echo "<th>Nom</th>";
            // echo "<th>Prénom</th>";
            // echo "<th>Email</th>";
            // echo "<th>Téléphone</th>";
            // echo "<th>Fidélité</th>";
            // echo "</th>";

            // // on boucle dans le tableau de clients pour afficher les clients
            // foreach ($db->findAll() as $client) {
            //     echo "<tr>";
            //     echo "<td>" . $client->nom . "</td>";
            //     echo "<td>" . $client->prenom . "</td>";
            //     echo "<td>" . $client->email . "</td>";
            //     echo "<td>" . $client->telephone . "</td>";
            //     echo "<td>" . (($client->fidelite == 0) ? "Client pas fidèle" : "Client fidèle") . "</td>";
            //     // ON AJOUTE un bouton pour supprimer le client
            //     echo "<td>";
            //     // on utilise un formulaire pour envoyer les données
            //     echo "<form method='post'>";
            //     // input hidden qui sert de flag pour le traitement
            //     echo "<input type='hidden' name='action' value='del'>";
            //     // input hidden qui contient l'id du client
            //     echo "<input type='hidden' name='id' value='" . $client->id . "'>";
            //     // input submit pour envoyer le formulaire
            //     echo "<input type='submit' value='del' class='button button-danger'>";
            //     echo "</form>";
            //     echo "</td>";
            //     echo "</tr>";
            // }
            // // on pense à fermer la table
            // echo "</table>";
        } else {
            // on crée le formulaire d'ajout de client
            echo "<form method='post'>";
            // on va ajouter un input de type hidden pour envoyer "ok" lorsqu'on poste le formulaire
            // cette valeur "ok" nous sert de flag pour faire du traitement dessus
            echo "<input type='hidden' name='send' value='ok'>";
            // input nom
            echo "<div>" .
                "<label for='nom'>Nom</label>" .
                "<input type='text' name='nom' id='nom' class='widefat' required>" .
                "</div>";
            // input prenom
            echo "<div>" .
                "<label for='prenom'>Prénom</label>" .
                "<input type='text' name='prenom' id='prenom' class='widefat' required>" .
                "</div>";
            // input surnom
            echo "<div>" .
                "<label for='surnom'>Surnom</label>" .
                "<input type='text' name='surnom' id='surnom' class='widefat' required>" .
                "</div>";
            // input email
            echo "<div>" .
                "<label for='email'>E-mail</label>" .
                "<input type='email' name='email' id='email' class='widefat' required>" .
                "</div>";
            // input compétition
            echo "<div>" .
                "<label for='competition'>Compétition nom</label>" .
                "<input type='text' name='competition' id='competition' class='widefat' required>" .
                "</div>";
            // input submit
            echo "<div>" .
                "<input type='submit' value='Ajouter' class='button button-primary'>" .
                "</div>";
        }
    }
}

new Tpclem(); // on instancie la class