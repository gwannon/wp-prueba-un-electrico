<?php

/**
 * Plugin Name: WP Coches Eléctricos
 * Plugin URI:  https://github.com/gwannon/wp-prueba-un-electrico
 * Description: Plugin de de WordPress para gestionar fichas de coches eléctricos.
 * Version:     1.0
 * Author:      Gwannon
 * Author URI:  https://github.com/gwannon/
 * License:     GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-prueba-un-electrico
 *
 * PHP 8.1
 * WordPress 6.4.3
 */


//Cargamos el multi-idioma
function wp_prueba_un_electrico_plugins_loaded() {
  load_plugin_textdomain('wp-prueba-un-electrico', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}
add_action('plugins_loaded', 'wp_prueba_un_electrico_plugins_loaded', 0 );

/* ----------- Includes ------------ */
include_once(plugin_dir_path(__FILE__).'lib/custom_posts.php');
include_once(plugin_dir_path(__FILE__).'lib/car.php');

/* ----------- Globales ----------- */
define("SEARCH_RESULTS_PAGE_ID", 281);

//Shortcode
function wp_prueba_un_electrico_buscador_shortcode($params = array(), $content = null) {
  global $post;
  ob_start(); ?>
  <form id="filtercar" action="<?php echo get_the_permalink(SEARCH_RESULTS_PAGE_ID); ?>">
    <select name="brand">
      <option value="0">Marca</option>  
    </select>
    <select name="type">
      <option value="0">Tipo</option>  
    </select>
    <select name="model">
      <option value="0">Modelo</option>  
    </select>
    <input type="submit" value="Buscar">
  </form>
  <style>
    #filtercar {
      max-width: 1060px;
      margin: auto;
      font-family: 'Roboto Condensed';
      width: calc(100% - 10px);
      padding: 5px;
      border-radius: 5px;
      background: linear-gradient(90deg, rgba(70,240,105,1) 0%, rgba(38,208,155,1) 50%, rgba(5,178,200,1) 100%);
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
      justify-content: space-between;
      box-shadow: 4px 8px 7px 0px #00000030;
    }

    #filtercar > * {
      width: 100%;
      border-radius: 5px;
      border: none;
      padding: 13px 45px 13px 25px;
      font-size: 18px;
      color: #666666;
      background: #ffffff url('/wp-content/plugins/wp-prueba-un-electrico/images/after.png') center right no-repeat;
      font-family: 'Roboto Condensed';
      font-weight: 500;
      margin: 0px;
      position: relative;
    }

    #filtercar > select {
      appearance: none;
      font-size: 27px;
    }

    #filtercar > select > option {
      font-size: 18px;
      line-height: 120%;
      color: #666666;
      padding: 15px 45px 15px 25px;
      font-family: 'Roboto Condensed';
      font-weight: 500;
    }

    #filtercar > input {
      background: #46f069 none; 
      font-size: 27px;
      color: #333333;
      font-weight: 500;
      padding: 15px;
      border: 2px solid #ffffff;
    }

    @media screen and (min-width: 680px) {
      #filtercar > select {
        width: calc(28.5% - 5px);
        font-size: 18px;
      }
      #filtercar > input {
        width: calc(14.5% - 5px);
        border: 2px solid #46f069;
        font-size: 17px;
        padding: 13px 15px;
      }
    }
  </style>
  <script>

    <?php $filter1 = get_query_var('filter1'); $filter2 = get_query_var('filter2'); 
    if($filter1 != "" && $filter2 != "") { ?>
      var current_brand = <?=get_term_by('slug', $filter1, 'brand')->term_id; ?>; //1
      var current_type = <?=get_term_by('slug', $filter2, 'cartype')->term_id; ?>;
    <?php } else if($filter1 != "" && term_exists($filter1, 'brand')) {  ?>
      var current_brand = <?=get_term_by('slug', $filter1, 'brand')->term_id; ?>; //2
      var current_type = 0;
    <?php } else if($filter1 != "" && term_exists($filter1, 'cartype')) { ?>
      var current_brand = 0; //3
      var current_type = <?=get_term_by('slug', $filter1, 'cartype')->term_id; ?>;
    <?php } else { ?>
      var current_brand = 0; //4
      var current_type = 0;
    <?php } ?>
    filterCar();
    jQuery("#filtercar > select:nth-of-type(1), #filtercar > select:nth-of-type(2)").on("change", function() {
      filterCar();
    });
    function filterCar() {
      jQuery.getJSON('<?php echo admin_url('admin-ajax.php')."?action=filter-car"; ?>', {
          brand: (current_brand > 0 ? current_brand : jQuery("#filtercar > select:nth-of-type(1)").val()),
          type: (current_type > 0 ? current_type : jQuery("#filtercar > select:nth-of-type(2)").val())
        }, function() {
        console.log( "success" );
      }).done(function( data ) {

        if(current_brand > 0) { var currentval = current_brand; current_brand = 0; }
        else var currentval = jQuery("#filtercar > select:nth-of-type(1)").val();
        jQuery("#filtercar > select:nth-of-type(1)").find("option").remove();
        jQuery("#filtercar > select:nth-of-type(1)").append('<option value = "0">Marca</option>');
        jQuery.each(data.brands, function( i, brand ) {
          jQuery("#filtercar > select:nth-of-type(1)").append('<option value = "'+brand.term_id+'"'+(currentval == brand.term_id ? ' selected="selected"' : '')+'>'+brand.name+'</option>');
        });

        if(current_type > 0) { var currentval = current_type; current_type = 0; }
        else currentval = jQuery("#filtercar > select:nth-of-type(2)").val();
        jQuery("#filtercar > select:nth-of-type(2)").find("option").remove();
        jQuery("#filtercar > select:nth-of-type(2)").append('<option value = "0">Tipo</option>');
        jQuery.each(data.types, function( i, type ) {
          jQuery("#filtercar > select:nth-of-type(2)").append('<option value = "'+type.term_id+'"'+(currentval == type.term_id ? ' selected="selected"' : '')+'>'+type.name+'</option>');
        });

        currentval = jQuery("#filtercar > select:nth-of-type(3)").val();
        jQuery("#filtercar > select:nth-of-type(3)").find("option").remove();
        jQuery("#filtercar > select:nth-of-type(3)").append('<option value = "0">Modelo</option>');
        jQuery.each(data.models, function( i, model ) {
          jQuery("#filtercar > select:nth-of-type(3)").append('<option value = "'+model.ID+'"'+(currentval == model.ID ? ' selected="selected"' : '')+'>'+model.post_title+'</option>');
        });

      });
    }

  </script>
  <?php return ob_get_clean();
}
add_shortcode('buscador', 'wp_prueba_un_electrico_buscador_shortcode');

function wp_prueba_un_electrico_buscador_resultados_shortcode($params = array(), $content = null) {
  global $post, $wp_query;
  ob_start();

  $filter1 = get_query_var('filter1');
  $filter2 = get_query_var('filter2');
  if(isset($params['ultimos']) && $params['ultimos'] == 1) {
    $args = array(
      'post_type' => 'car',
      'posts_per_page' => 4,
      'order' => 'DESC',
      'orderby' => 'post_date',
    );

  } else {

    $args = array(
      'post_type' => 'car',
      'posts_per_page' => -1,
    );

    if($filter1 != "" && $filter2 != "") {

      $args['tax_query']['relation'] = 'AND';
      $args['tax_query'][] = [
        'taxonomy' => 'brand',
        'field' => 'slug',
        'terms' => $filter1
      ];

      $args['tax_query'][] = [
        'taxonomy' => 'cartype',
        'field' => 'slug',
        'terms' => $filter2
      ];


    } else if($filter1 != "") {
      if(term_exists($filter1, 'brand'))
        $args['tax_query'][] = [
          'taxonomy' => 'brand',
          'field' => 'slug',
          'terms' => $filter1
        ];
      else if(term_exists($filter1, 'cartype')) {
        $args['tax_query'][] = [
          'taxonomy' => 'cartype',
          'field' => 'slug',
          'terms' => $filter1
        ];

      }
    }

  }
  $cars = get_posts( $args ); ?>
  
  <?php if(count($cars) > 0) { ?>
    <div class="cargrid">
      <?php foreach($cars as $car) { ?>
        <div class="caritem">
          <img src="<?php echo get_the_post_thumbnail_url($car->ID, 'medium' ); ?>">
          <h3><?php echo $car->post_title; ?></h3>
          <a href='<?php echo get_the_permalink($car->ID); ?>'><?php _e("Try it", "wp-prueba-un-electrico"); ?></a>
        </div>
      <?php } ?> 
    </div>
  <?php } else { ?>
    <h3><?php _e("Sorry, there are no results for this search.", "wp-prueba-un-electrico"); ?></h3>
  <?php } ?>
  <style>
    .cargrid {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .cargrid .caritem {
      box-sizing: border-box;
      padding: 10px;
      width: calc(100% - 20px);
      box-shadow: 4px 8px 7px 0px #00000030;
      border-radius: 8px;
      background-color: #f2f2f2;
      display: flex;
      flex-wrap: wrap;
      align-content: space-between;
    }

    .cargrid .caritem h3 {
      margin-top: 10px;
      margin-bottom: 20px;
      font-family: "Roboto Condensed";
      line-height: 100%;
      font-size: 32px;
    }

    .cargrid .caritem img {
      box-sizing: border-box;
      width: 100%;
      border-radius: 8px;
      background-color: #fff;
      padding: 10px;
    }

    .cargrid .caritem a {
      display: block;
      box-sizing: border-box;
      width: 100%;
      background-color: #46f069;
      padding: 10px 10px 10px 10px;
      text-align: center;
      color: #000;
      text-decoration: none;
      border-radius: 8px;
      font-family: "Roboto Condensed";
      line-height: 100%;
      font-size: 24px;
      font-weight: 500;
    }

    @media (min-width: 600px) {
      .cargrid .caritem { width: calc(50% - 20px); }
    }

    @media (min-width: 900px) {
      .cargrid .caritem { width: calc(33.33% - 20px); }
    }

    @media (min-width: 1100px) {
      .cargrid .caritem { width: calc(25% - 20px); }
    }
  </style>
  <?php return ob_get_clean();
}
add_shortcode('resultados', 'wp_prueba_un_electrico_buscador_resultados_shortcode');


//ADMIN-AJAX
add_action( 'wp_ajax_filter-car', 'wp_prueba_un_electrico_action_filter_car' );
add_action( 'wp_ajax_nopriv_filter-car', 'wp_prueba_un_electrico_action_filter_car' );
function wp_prueba_un_electrico_action_filter_car() {
  
  //Brands
  $json['brands'] = [];
  $brands = get_terms(array(
    'taxonomy' => 'brand',
    'hide_empty' => true,
  ));
  $json['brands'] = $brands;
  
  //Types
  $json['types'] = [];
  $types = get_terms(array(
    'taxonomy' => 'cartype',
    'hide_empty' => true,
  ));
  $json['types'] = $types;

  //MOdels
  $json['models'] = [];
  if((isset($_GET['type']) && $_GET['type'] > 0) || (isset($_GET['brand']) && $_GET['brand'] > 0)) {
    $args = array(
      'post_type' => 'car'
    );

    if(isset($_GET['type']) && $_GET['type'] > 0 && isset($_GET['brand']) && $_GET['brand'] > 0) {
      $args['tax_query']['relation'] = 'AND';
    }

    if(isset($_GET['type']) && $_GET['type'] > 0) {
      $args['tax_query'][] = [
        'taxonomy' => 'cartype',
        'field' => 'id',
        'terms' => $_GET['type']
      ];
    }


    if( isset($_GET['brand']) && $_GET['brand'] > 0) {
      $args['tax_query'][] = [
        'taxonomy' => 'brand',
        'field' => 'id',
        'terms' => $_GET['brand']
      ];
    }
    $json['models'] = get_posts( $args );
  }
  echo json_encode($json);
  wp_die();
}

//REDIRECTS 
add_action( 'init', 'wp_prueba_un_electrico_redirects' );
function wp_prueba_un_electrico_redirects() {
  $url_parts = parse_url("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
  $actual_link = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
  if($actual_link == get_the_permalink(SEARCH_RESULTS_PAGE_ID)) {
    if(isset($_GET['model']) && $_GET['model'] > 0) {
      wp_redirect(get_the_permalink($_GET['model']));
    } else if((isset($_GET['brand']) && $_GET['brand'] > 0) || (isset($_GET['type']) && $_GET['type'] > 0)) {
      $labels = [];
      if(isset($_GET['brand']) && $_GET['brand'] > 0) {
        $term = get_term($_GET['brand'], 'brand');
        $labels[] = $term->slug;
      }
      if(isset($_GET['type']) && $_GET['type'] > 0) {
        $term = get_term($_GET['type'], 'cartype');
        $labels[] = $term->slug;
      }
      $url = get_the_permalink(SEARCH_RESULTS_PAGE_ID).implode("/", $labels)."/";
      wp_redirect($url);
    }
  }
}

/* ----------- Rewrite Rules ------- */
add_action( 'init', 'wp_prueba_un_electrico_rewrite_rules' );
function wp_prueba_un_electrico_rewrite_rules(){
  $slug = get_post_field( 'post_name', SEARCH_RESULTS_PAGE_ID);
  add_rewrite_tag('%filter1%','([^&/]+)');
  add_rewrite_tag('%filter2%','([^&/]+)');
  add_rewrite_rule('^'.$slug.'/([^/]*)/([^/]*)/?','index.php?page_id='.SEARCH_RESULTS_PAGE_ID.'&filter1=$matches[1]&filter2=$matches[2]','top');
  add_rewrite_rule('^'.$slug.'/([^/]*)/?','index.php?page_id='.SEARCH_RESULTS_PAGE_ID.'&filter1=$matches[1]','top');
  //add_rewrite_rule('^'.$slug.'/marca-([^/]*)/?','index.php?page_id='.SEARCH_RESULTS_PAGE_ID.'&brand=$matches[1]','top');
  flush_rewrite_rules();
}

//LIstado de coches en CF7
add_action( 'wpcf7_init', 'custom_car_select' );

function custom_car_select() {
    wpcf7_add_form_tag( 'car_select', 'custom_car_handler', array( 'name-attr' => true ) );
}

function custom_car_handler( $tag ) {
    $atts = array();
    $atts['name'] = $tag->name;
    $atts['class'] = $tag->get_class_option();
    $atts['id'] = $tag->get_id_option();
    $atts = wpcf7_format_atts( $atts );
    $html = '<select ' . $atts . '>';
    $args = array(
      'post_type' => 'car',
      'posts_per_page' => -1,
    );
    $cars = get_posts( $args );
    foreach ( $cars as $car ):
      $car_id = $car->ID;
      $slug = $car->post_name;
      $title = get_the_title($car_id);

      $brand_names = wp_get_object_terms($car_id, 'brand'); 
      $value = strtolower("via-web,marca-".$brand_names[0]->name.",".$brand_names[0]->name."-web,".$title);

      $html .= '<option value="' . $value . '">' . $title . '</option>';
    endforeach;
    $html .= '</select>';
    return $html;
}


//Modificamos las metas
add_filter('wpseo_metadesc', 'wp_prueba_un_electrico_filter_wpseo_title');
add_filter('wpseo_title', 'wp_prueba_un_electrico_filter_wpseo_title');
add_filter('wpseo_opengraph_desc', 'wp_prueba_un_electrico_filter_wpseo_title');
add_filter('wpseo_opengraph_title', 'wp_prueba_un_electrico_filter_wpseo_title');

function  wp_prueba_un_electrico_filter_wpseo_title($title) {
  if(is_page(SEARCH_RESULTS_PAGE_ID) ) {
    $filter1 = get_query_var('filter1');
    $filter2 = get_query_var('filter2');
    if($filter1 != "" && term_exists($filter1, 'brand')) {
      $title = str_replace("[marca]", sprintf(__("de %s", "wp-prueba-un-electrico"), get_term_by('slug', $filter1, 'brand')->name), $title);
    }
    else if($filter1 != "" && term_exists($filter1, 'cartype')) {
      $title = str_replace("[tipo]", sprintf(__("de tipo %s", "wp-prueba-un-electrico"), get_term_by('slug', $filter1, 'cartype')->name), $title);
      $title = str_replace("[marca]", "", $title);
    } else {
      $title = str_replace("[marca]", "", $title);
    } 
    if($filter2 != "" && term_exists($filter2, 'cartype')) {
      $title = str_replace("[tipo]", sprintf(__("de tipo %s", "wp-prueba-un-electrico"), get_term_by('slug', $filter2, 'cartype')->name), $title);
    } else {
      $title = str_replace("[tipo]", "", $title);
    }
  }
  return $title;
}

add_filter('wpseo_canonical', 'wp_prueba_un_electrico_filter_wpseo_canonical');
add_filter('wpseo_opengraph_url', 'wp_prueba_un_electrico_filter_wpseo_canonical');
function wp_prueba_un_electrico_filter_wpseo_canonical ($canonical) { 
  if(is_page(SEARCH_RESULTS_PAGE_ID) ) {
    $labels = [];
    $filter1 = get_query_var('filter1');
    $filter2 = get_query_var('filter2');
    if($filter1 != "" && (term_exists($filter1, 'brand') || term_exists($filter1, 'cartype') )) {
      $labels[] = $filter1;
    }
    if($filter2 != "" && term_exists($filter2, 'cartype')) {
      $labels[] = $filter2;
    }
    if(count($labels) > 0) {
      $canonical = get_the_permalink(SEARCH_RESULTS_PAGE_ID).implode("/", $labels)."/";
      return $canonical;
    }
  }
  return $canonical;
}