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
            array(
                'customize_selective_refresh' => true,
            )
        );
    }

    public function form($instance) {
        //get the values of the current city, or set by default with some values
        //esc_attr WordPress method to Escaping for HTML attributes. avoiding XSS, more security
        $city = 'Belley';
        if (isset($instance['city'])) {
            $city = esc_attr($instance['city']);
        }

        $country = 'France';
        if (isset($instance['country'])) {
            $country = esc_attr($instance['country']);
        }

        $language = 'french';
        if (isset($instance['language'])) {
            $language = esc_attr($instance['language']);
        }

        // Display the form to fill up on the wodget settings
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
//sanitize_text_field,Word Press method to Sanitizes a string from user input or from the database.
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['city'] = sanitize_text_field($new_instance['city']);
        $instance['country'] = sanitize_text_field($new_instance['country']);
        $instance['language'] = sanitize_text_field($new_instance['language']);
        return $instance;
    }

    public function widget($args, $instance) {

        $city = 'Belley';
        if (isset($instance['city'])) {
            $city = esc_attr($instance['city']);
        }

        $country = 'France';
        if (isset($instance['country'])) {
            $country = esc_attr($instance['country']);
        }

        $language = 'french';
        if (isset($instance['language'])) {
            $language = esc_attr($instance['language']);
        }

        echo $args['before_widget'];
        ?>
        <div id="weather-widget"></div>
        <script>
            var city = '<?php echo esc_js($city); ?>';
            var country = '<?php echo esc_js($country); ?>';
            var language = '<?php echo esc_js($language); ?>';
            var apiUrl = "https://www.weatherwp.com/api/common/publicWeatherForLocation.php?city=" + city + "&country=" + country + "&language=" + language;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data.status === 200) {
                        var temperature = data.temp;
                        var iconUrl = data.icon;
                        var description = data.description;

                        var weatherWidget = document.getElementById('weather-widget');
                        weatherWidget.innerHTML = "<h3>Météo de " + city + " - " + temperature + " °C - " + description + "</h3><img src='" + iconUrl + "' alt='Weather Icon'>";
                    } else {
                        var weatherWidget = document.getElementById('weather-widget');
                        weatherWidget.innerHTML = "Impossible de récupérer les données météo pour " + city + ".";
                    }
                })
                .catch(error => {
                    var weatherWidget = document.getElementById('weather-widget');
                    weatherWidget.innerHTML = "Erreur lors de la récupération des données météo pour " + city + ".";
                });
        </script>
        <?php
        echo $args['after_widget'];
    }

}

function register_weather_widget() {
    register_widget('Weather_Widget');
}
add_action('widgets_init', 'register_weather_widget');
?>
