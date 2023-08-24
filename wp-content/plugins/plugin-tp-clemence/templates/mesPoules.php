<?php
class ClemPoules
{
    // fonction d'affichage pour sous-menu
    public function mesPoules()
    {
        // on va instancier la classe Ern_Database_Service
        $db = new Tp_Wp_Clemence_Database_Service();
        // on récupère le titre de la page
        echo "<h2>" . get_admin_page_title() . "</h2>";
        // si la page dans laquelle on est == ern-client (slug de la page), on affiche la liste ds groupes
        if ($_GET["page"] == "gaming-poules" || $_POST["send"] == "ok" || $_POST["action"] == "delete-poule") {
            // on va mettre une seconde condition
            // si les données sont présentes, on exécute la requête.
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                // on exécute la méthode save_client
                $db->save_poule();
            }

            // de la même manière que pour l'insertion, on utilise le flag action pour savoir si on doit supprimer ou pas
            if (isset($_POST['action']) && $_POST['action'] == 'delete-poule') {
                // on exécute la méthode save_client
                $db->delete_poule($_POST['delete-poule']);
            }

            // on instancie la classe Ern_List
            $table = new Tp_List_Poules();
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
            // input compétition
            echo "<div>" .
                "<label for='nomcompet'>Compétition choisie</label><br>" .
                // requête pour obtenir la liste des compétitions
                $compets = $db->findAllCompetitions();
            echo "<select name='nomcompet' id='nomcompet'>";
            foreach ($compets as $compet) {
                echo "<option value='" . $compet->id . "'>" . $compet->label . "</option>";
            }
            echo "</select>";
            echo "</div><hr>";
            // input nom poule choisie
            echo "<div>" .
                "<label for='label'>Poule choisie</label><br>" .
                // requête pour obtenir la liste des groupes
                $groups = $db->findAllGroupes();
            echo "<select name='label' id='label'>";
            foreach ($groups as $group) {
                echo "<option value='" . $group->id . "'>" . $group->label . "</option>";
            }
            echo "</select>";
            echo "</div><hr>";
            // input ton surnom
            echo "<div>" .
                "<label for='surnom'>Ton pseudo</label><br>" .
                // requête pour obtenir la liste des joueurs
                $pseudos = $db->findAll();
            echo "<select name='surnom' id='surnom'>";
            foreach ($pseudos as $pseudo) {
                echo "<option value='" . $pseudo->id . "'>" . $pseudo->surnom . "</option>";
            }
            echo "</select>";
            echo "</div><hr>";
            // input submit
            echo "<div>" .
                "<input type='submit' value='Ajouter' class='button button-primary'>" .
                "</div>";
        }
    }
}
