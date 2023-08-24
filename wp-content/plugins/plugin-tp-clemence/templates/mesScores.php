<?php
class ClemScores
{
    // fonction d'affichage pour sous-menu
    public function mesScores()
    {
        // on va instancier la classe Ern_Database_Service
        $db = new Tp_Wp_Clemence_Database_Service();
        // on récupère le titre de la page
        echo "<h2>" . get_admin_page_title() . "</h2>";
        // si la page dans laquelle on est == ern-client (slug de la page), on affiche la liste ds groupes
        if ($_GET["page"] == "gaming-scores" || $_POST["send"] == "ok" || $_POST["action"] == "delete-scores") {
            // on va mettre une seconde condition
            // si les données sont présentes, on exécute la requête.
            if (isset($_POST['send']) && $_POST['send'] == 'ok') {
                // on exécute la méthode save_client
                $db->save_scores();
            }

            // de la même manière que pour l'insertion, on utilise le flag action pour savoir si on doit supprimer ou pas
            if (isset($_POST['action']) && $_POST['action'] == 'delete-scores') {
                // on exécute la méthode save_client
                $db->delete_scores($_POST['delete-scores']);
            }

            // on instancie la classe Ern_List
            $table = new Tp_List_Scores();
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
            // input points (résultat)
            echo "<div>" .
                "<label for='points'>Résultat</label><br>" .
                // requête pour obtenir la liste des compétitions
                $points = $db->findAllPoints();
            echo "<select name='points' id='points'>";
            foreach ($points as $point) {
                echo "<option value='" . $point->id . "'>" . $point->label . "</option>";
            }
            echo "</select>";
            echo "</div><hr>";
            // input joueur
            echo "<div>" .
                "<label for='player'>Joueur</label><br>" .
                // requête pour obtenir la liste des compétitions
                $joueurs = $db->findAll();
            echo "<select name='player' id='player'>";
            foreach ($joueurs as $joueur) {
                echo "<option value='" . $joueur->id . "'>" . $joueur->surnom . "</option>";
            }
            echo "</select>";
            echo "</div><hr>";
            // input match
            echo "<div>" .
                "<label for='match'>Match</label><br>" .
                // requête pour obtenir la liste des dates de matchs
                $matchs = $db->findAllMatchs();
            echo "<select name='match' id='match'>";
            foreach ($matchs as $match) {
                echo "<option value='" . $match->id . "'>" . $match->date . "</option>";
            }
            echo "</select>";
            echo "</div><hr>";
            // input poule
            echo "<div>" .
                "<label for='group'>Groupe de poule</label><br>" .
                // requête pour obtenir la liste des dates de matchs
                $groups = $db->findAllGroupes();
            echo "<select name='pool' id='pool'>";
            foreach ($groups as $group) {
                echo "<option value='" . $group->id . "'>" . $group->label . "</option>";
            }
            echo "</select>";
            echo "</div><hr>";
            // input compétition
            echo "<div>" .
                "<label for='competition'>Compétition</label><br>" .
                // requête pour obtenir la liste des compet
                $competitions = $db->findAllCompetitions();
            echo "<select name='competition' id='competition'>";
            foreach ($competitions as $competition) {
                echo "<option value='" . $competition->id . "'>" . $competition->label . "</option>";
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
