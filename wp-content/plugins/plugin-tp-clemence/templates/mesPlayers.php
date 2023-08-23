<?php
class ClemPlayers
{
    // fonction d'affichage pour sous-menu
    public function mesPlayers()
    {
        // on va instancier la classe Ern_Database_Service
        $db = new Tp_Wp_Clemence_Database_Service();
        // on récupère le titre de la page
        echo "<h2>" . get_admin_page_title() . "</h2>";
        // si la page dans laquelle on est == ern-client (slug de la page), on affiche la liste
        if ($_GET["page"] == "gaming-players" || $_POST["send"] == "ok" || $_POST["action"] == "delete-player") {
            // on va mettre une seconde condition
            // si les données sont présentes, on exécute la requête.
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                // on exécute la méthode save_client
                $db->save_player();
            }

            // de la même manière que pour l'insertion, on utilise le flag action pour savoir si on doit supprimer ou pas
            if (isset($_POST['action']) && $_POST['action'] == 'delete-player') {
                // on exécute la méthode save_client
                $db->delete_player($_POST['delete-player']);
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
                "<label for='competition'>Compétition référence (numéro associé à la compétition)</label>" .
                "<input type='number' name='competition' id='competition' class='widefat' required>" .
                "</div>";
            // input submit
            echo "<div>" .
                "<input type='submit' value='Ajouter' class='button button-primary'>" .
                "</div>";
        }
    }
}
