<?php
class Tp_Wp_Clemence_Widget extends WP_Widget // on étend la classe WP_Widget
{
    // on surcharge son constructor
    public function __construct()
    {
        // on déclare une variable avec les options du widget
        $widget_ops = array( // on ajoute des options au widget
            // ajout d'une classe css
            "className" => "tp-wp-clemence",
            // ajout d'une description
            "description" => __("Mes résultats des compétitions"), // on traduit le texte
            // pour éviter de rafraîchir la fenêtre du navigateur
            'customize_selective_refresh' => true
        );
        // on surcharge le constructeur de la classe parent
        parent::__construct(
            // on donne un nom au widget
            'resultats',
            // on donne un titre au widget
            __("Resultats"),
            // on lui donne des options
            $widget_ops
        );
    }

    // Méthode pour afficher le formulaire de configuration du widget
    public function form($instance)
    {
        // Récupérer les valeurs actuelles des options du widget (si disponibles)
        $title = !empty($instance['title']) ? $instance['title'] : __('Résultats');
 
        // Afficher le champ pour le titre du widget
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titre du Widget:'); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <?php
    }

    // Méthode pour afficher le contenu du widget
    public function widget($args, $instance)
    {
        // Titre du widget
        echo $args['before_widget'];
        echo $args['before_title'] . __('Résultats') . $args['after_title'];

        // Afficher les scores
        global $wpdb;
        $scores = $wpdb->get_results("SELECT 
        s.id, 
        i.label AS nomresultat,
        i.points, 
        p.surnom AS surnom, 
        m.date_match AS date, 
        g.label AS label, 
        c.label AS competition
            FROM {$wpdb->prefix}clem_scores as s
            INNER JOIN {$wpdb->prefix}clem_pool AS po
            ON s.pool_id = po.id
            INNER JOIN {$wpdb->prefix}clem_points AS i
            ON s.points_id = i.id
            INNER JOIN {$wpdb->prefix}clem_player AS p
            ON s.player_id = p.id
            INNER JOIN {$wpdb->prefix}clem_match AS m
            ON s.match_id = m.id
            INNER JOIN {$wpdb->prefix}clem_group AS g
            ON po.group_id = g.id
            INNER JOIN {$wpdb->prefix}clem_competition AS c
            ON po.competition_id = c.id");
        
        if (!empty($scores)) {
            echo '<table class="widget-results-table">';
            echo '<tr><th>Joueur</th><th>Compétition</th><th>Poule</th><th>Match</th><th>Points</th></tr>';
            
            foreach ($scores as $score) {
                echo '<tr>';
                echo '<td>' . $score->surnom . '</td>';
                echo '<td>' . $score->competition . '</td>';
                echo '<td>' . $score->label . '</td>';
                echo '<td>' . $score->date . '</td>';
                echo '<td>' . $score->points . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo 'Aucun score trouvé.';
        }

        echo $args['after_widget'];
    }
}

