<?php
class ClemMatchs
{
    // fonction d'affichage pour sous-menu
    public function mesMatchs()
    {
        // on va instancier la classe Ern_Database_Service
        $db = new Tp_Wp_Clemence_Database_Service();
        // on récupère le titre de la page
        echo "<h2>" . get_admin_page_title() . "</h2>";
        // si la page dans laquelle on est == ern-client (slug de la page), on affiche la liste ds groupes
        if ($_GET["page"] == "gaming-matchs" || $_POST["send"] == "ok" || $_POST["action"] == "delete-match") {
            // on va mettre une seconde condition
            // si les données sont présentes, on exécute la requête.
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                // on exécute la méthode save_match
                $db->save_match();
            }

            // de la même manière que pour l'insertion, on utilise le flag action pour savoir si on doit supprimer ou pas
            if (isset($_POST['action']) && $_POST['action'] == 'delete-match') {
                // on exécute la méthode save_match
                $db->delete_match($_POST['delete-match']);
            }

            // on instancie la classe Ern_List
            $table = new Tp_List_Matchs();
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
            // input joueur 1
            echo "<div>" .
                "<label for='joueur1'>Joueur 1</label>" .
                "<input type='text' name='joueur1' id='joueur1' class='widefat' required>" .
                "</div>";
            // input joueur 2
            echo "<div>" .
                "<label for='joueur2'>Joueur 2</label>" .
                "<input type='text' name='joueur2' id='joueur2' class='widefat' required>" .
                "</div>";
            // input date match
            echo "<div>" .
                "<label for='datematch'>Date matchs</label>" .
                "<input type='text' name='datematch' id='datematch' class='widefat' required>" .
                "</div>";
            // input poule
            echo "<div>" .
                "<label for='ispool'>Poule</label>" .
                "<input type='text' name='ispool' id='ispool' class='widefat' required>" .
                "</div>";
            // input submit
            echo "<div>" .
                "<input type='submit' value='Ajouter' class='button button-primary'>" .
                "</div>";
        }
    }
}
