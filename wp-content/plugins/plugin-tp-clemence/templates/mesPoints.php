<?php
class ClemPoints
{
    // fonction d'affichage pour sous-menu
    public function mesPoints()
    {
        // on va instancier la classe Ern_Database_Service
        $db = new Tp_Wp_Clemence_Database_Service();
        // on récupère le titre de la page
        echo "<h2>" . get_admin_page_title() . "</h2>";
        // si la page dans laquelle on est == ern-client (slug de la page), on affiche la liste ds groupes
        if ($_GET["page"] == "gaming-points" || $_POST["send"] == "ok" || $_POST["action"] == "delete-points") {
            // on va mettre une seconde condition
            // si les données sont présentes, on exécute la requête.
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                // on exécute la méthode save_client
                $db->save_points();
            }

            // de la même manière que pour l'insertion, on utilise le flag action pour savoir si on doit supprimer ou pas
            if (isset($_POST['action']) && $_POST['action'] == 'delete-points') {
                // on exécute la méthode save_client
                $db->delete_points($_POST['delete-points']);
            }

            // on instancie la classe Ern_List
            $table = new Tp_List_Points();
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
                "<label for='labelpoints'>Résultat</label>" .
                "<input type='text' name='labelpoints' id='labelpoints' class='widefat' required>" .
                "</div>";
            // input points
            echo "<div>" .
                "<label for='points'>Points</label>" .
                "<input type='number' name='points' id='points' class='widefat' required>" .
                "</div>";
            // input submit
            echo "<div>" .
                "<input type='submit' value='Ajouter' class='button button-primary'>" .
                "</div>";
        }
    }
}
