<?php
/*
Plugin Name: Widget Météo
Description: Affiche les informations météorologiques d'une ville.
Version: 1.6
Author: Charly L
*/

class Weather_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'weather_widget',
            'Widget Météo',
            array('description' => ' La météo en direct des villes de France !')
        );
    }

    public function form($instance) {
        // Récupérer les valeurs actuelles ou définir des valeurs par défaut
        $city = isset($instance['city']) ? esc_attr($instance['city']) : 'Belley';
        $country = isset($instance['country']) ? esc_attr($instance['country']) : 'France';
        $language = isset($instance['language']) ? esc_attr($instance['language']) : 'french';

        // Afficher le formulaire
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('city'); ?>">Ville:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('city'); ?>" name="<?php echo $this->get_field_name('city'); ?>" type="text" value="<?php echo $city; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('country'); ?>">Pays:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('country'); ?>" name="<?php echo $this->get_field_name('country'); ?>" type="text" value="<?php echo $country; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('language'); ?>">Langue:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('language'); ?>" name="<?php echo $this->get_field_name('language'); ?>" type="text" value="<?php echo $language; ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['city'] = sanitize_text_field($new_instance['city']);
        $instance['country'] = sanitize_text_field($new_instance['country']);
        $instance['language'] = sanitize_text_field($new_instance['language']);
        return $instance;
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        $city = isset($instance['city']) ? $instance['city'] : 'Belley'; // Ville
        $country = isset($instance['country']) ? $instance['country'] : 'France'; // Pays
        $language = isset($instance['language']) ? $instance['language'] : 'french'; // Langue

        $api_url = "https://www.weatherwp.com/api/common/publicWeatherForLocation.php?city=$city&country=$country&language=$language";

        $response = wp_remote_get($api_url);

        if (!is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response));

            if ($data && $data->status === 200) {
                $temperature = $data->temp;
                $icon_url = $data->icon;
                $description = $data->description;

                echo "<h3>Météo de $city - $temperature °C - $description</h3>";
                echo "<img src='$icon_url' alt='Weather Icon'>";
            } else {
                echo "Impossible de récupérer les données météo pour $city.";
            }
        }

        echo $args['after_widget'];
    }

}

function register_weather_widget() {
    register_widget('Weather_Widget');
}
add_action('widgets_init', 'register_weather_widget');
?>