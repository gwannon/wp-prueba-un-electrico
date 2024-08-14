<?php 

ini_set("display_errors", 0);
//flush_rewrite_rules(true);

// Cars ----------------------------------------
// ------------------------------------------------
add_action( 'init', 'wp_prueba_un_electrico_car_create_post_type' );
function wp_prueba_un_electrico_car_create_post_type() {
	$labels = array(
		'name'               => __( 'Cars', 'wp-prueba-un-electrico' ),
		'singular_name'      => __( 'car', 'wp-prueba-un-electrico' ),
		'add_new'            => __( 'Add new', 'wp-prueba-un-electrico' ),
		'add_new_item'       => __( 'Add new car', 'wp-prueba-un-electrico' ),
		'edit_item'          => __( 'Edit car', 'wp-prueba-un-electrico' ),
		'new_item'           => __( 'New car', 'wp-prueba-un-electrico' ),
		'all_items'          => __( 'All cars', 'wp-prueba-un-electrico' ),
		'view_item'          => __( 'View car', 'wp-prueba-un-electrico' ),
		'search_items'       => __( 'Search car', 'wp-prueba-un-electrico' ),
		'not_found'          => __( 'Car not found', 'wp-prueba-un-electrico' ),
		'not_found_in_trash' => __( 'Car not found in trash bin', 'wp-prueba-un-electrico' ),
		'menu_name'          => __( 'Cars', 'wp-prueba-un-electrico' ),
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __( 'Add new car', 'wp-prueba-un-electrico' ),
		'public'        => true,
		'menu_position' => 7,
		'taxonomies' 		=> array('brand,cartype'),
		'supports'      => array( 'title', 'editor', 'thumbnail'/*, 'page-attributes'*/ ),
		'rewrite'	      => array( 'slug' => 'coche-electrico', 'with_front' => false),
		'query_var'	    => true,
		'has_archive' 	=> false,
		'hierarchical'	=> true,
  	'exclude_from_search' => true,
	);
	register_post_type( 'car', $args );
}

//Brand -------------------------
add_action( 'init', 'wp_prueba_un_electrico_car_brand_create_type' );
function wp_prueba_un_electrico_car_brand_create_type() {
	$labels = array(
		'name'              => __( 'Brands', 'wp-prueba-un-electrico' ),
		'singular_name'     => __( 'Brand', 'wp-prueba-un-electrico' ),
		'search_items'      => __( 'Search brand', 'wp-prueba-un-electrico' ),
		'all_items'         => __( 'All brands', 'wp-prueba-un-electrico' ),
		'parent_item'       => __( 'Parent brand', 'wp-prueba-un-electrico' ),
		'parent_item_colon' => __( 'Parent brand', 'wp-prueba-un-electrico' ).":",
		'edit_item'         => __( 'Edit brand', 'wp-prueba-un-electrico' ),
		'update_item'       => __( 'Update brand', 'wp-prueba-un-electrico' ),
		'add_new_item'      => __( 'Add brand', 'wp-prueba-un-electrico' ),
		'new_item_name'     => __( 'New brand', 'wp-prueba-un-electrico' ),
		'menu_name'         => __( 'Brands', 'wp-prueba-un-electrico' ),
	);
	$args = array(
		'labels' 		        => $labels,
		'hierarchical' 	    => true,
		'public'		        => true,
		'query_var'		      => true,
		'show_in_nav_menus' => false,
		'has_archive'       => false,
    'rewrite'           => array( 'slug' => 'marca', 'with_front' => false),
    'publicly_queryable' => false,
	);
  register_taxonomy( 'brand', array('car'), $args );
}

/*function wp_prueba_un_electrico_car_brand_fields($tag) {
	$term_meta = get_option( "taxonomy_".$tag->term_id);
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><?php _e('Image', 'wp-prueba-un-electrico'); ?></th>
		<td>
			<input type="text" name="term_meta[image]" id="term_meta[image]" size="3" style="width: 100%;" value="<?php echo $term_meta['image'] ? $term_meta['image'] : ''; ?>">
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><?php _e('Contact emails (separated by commas)', 'wp-prueba-un-electrico'); ?></th>
		<td>
			<input type="text" name="term_meta[contact_emails]" id="term_meta[contact_emails]" size="3" style="width: 100%;" value="<?php echo $term_meta['contact_emails'] ? $term_meta['contact_emails'] : ''; ?>">
		</td>
	</tr>
	<?php
}

add_action( 'brand_edit_form_fields', 'wp_prueba_un_electrico_car_brand_fields', 10, 2);
 
function wp_prueba_un_electrico_car_brand_save_fields( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id");
		$cat_keys = array_keys($_POST['term_meta']);
		foreach ($cat_keys as $key){
			if (isset($_POST['term_meta'][$key]) && $_POST['term_meta'][$key] != ''){
				$term_meta2[$key] = $_POST['term_meta'][$key];
			}
		}
		update_option( "taxonomy_$t_id", $term_meta2 );
	}
}
add_action( 'edited_brand', 'wp_prueba_un_electrico_car_brand_save_fields', 10, 2);*/

//Car type -------------------------
add_action( 'init', 'wp_prueba_un_electrico_car_cartype_create_type' );
function wp_prueba_un_electrico_car_cartype_create_type() {
	$labels = array(
		'name'              => __( 'Car types', 'wp-prueba-un-electrico' ),
		'singular_name'     => __( 'Car type', 'wp-prueba-un-electrico' ),
		'search_items'      => __( 'Search car type', 'wp-prueba-un-electrico' ),
		'all_items'         => __( 'All car types', 'wp-prueba-un-electrico' ),
		'parent_item'       => __( 'Parent car type', 'wp-prueba-un-electrico' ),
		'parent_item_colon' => __( 'Parent car type', 'wp-prueba-un-electrico' ).":",
		'edit_item'         => __( 'Edit car type', 'wp-prueba-un-electrico' ),
		'update_item'       => __( 'Update car type', 'wp-prueba-un-electrico' ),
		'add_new_item'      => __( 'Add car type', 'wp-prueba-un-electrico' ),
		'new_item_name'     => __( 'New car type', 'wp-prueba-un-electrico' ),
		'menu_name'         => __( 'Car types', 'wp-prueba-un-electrico' ),
	);
	$args = array(
		'labels' 		        => $labels,
		'hierarchical' 	    => true,
		'public'		        => true,
		'query_var'		      => true,
		'show_in_nav_menus' => false,
		'has_archive'       => false,
    'rewrite'           => array( 'slug' => 'tipo', 'with_front' => false),
    'publicly_queryable' => false,
	);
  register_taxonomy( 'cartype', array('car'), $args );
}

//CAMPOS personalizados ---------------------------
// ------------------------------------------------
function wp_prueba_un_electrico_get_car_custom_fields() {
	global $post;

	/*
		OK Autonomía eléctrica en km (WLTP):
		OK Tiempo de carga con potencia de carga máxima de 10 a 80%:
		Consumo de energía (por 100 km):
		Aceleración 0–100 km/h en s:
		OK Potencia del motor (CV):
	*/
	$fields = array(
		'range' => array ('titulo' => __( 'Electrical range (km)', 'wp-prueba-un-electrico' ), 'tipo' => 'number'), 
		'recharge' => array ('titulo' => __( 'Charging time with maximum charging power from 10 to 80% (minutes):', 'wp-prueba-un-electrico' ), 'tipo' => 'number'),
		'acceleration' => array ('titulo' => __( 'Acceleration 0–100 km/h (seconds)', 'wp-prueba-un-electrico' ), 'tipo' => 'number'),
		'power' => array ('titulo' => __( 'Engine power (hp)', 'wp-prueba-un-electrico' ), 'tipo' => 'number'),
		'consumption' => array ('titulo' => __( 'Power consumption per 100 km (kWh)', 'wp-prueba-un-electrico' ), 'tipo' => 'number'),
		'video_gallery' => array ('titulo' => __( 'Video gallery', 'wp-prueba-un-electrico' ), 'tipo' => 'repeater', 'min' => 2, 'fields' => array (
			'descripption' => array('titulo' => __( 'Description', 'wp-prueba-un-electrico' ), 'tipo' => 'text'),
			'url_youtube' => array('titulo' => __( 'URL Youtube', 'wp-prueba-un-electrico' ), 'tipo' => 'text')
		)),
		'photo_gallery' => array ('titulo' => __( 'Photo gallery', 'wp-prueba-un-electrico' ), 'tipo' => 'repeater', 'min' => 10, 'fields' => array (
			'descripption' => array('titulo' => __( 'Description', 'wp-prueba-un-electrico' ), 'tipo' => 'text'),
			'photo' => array('titulo' => __( 'Image', 'wp-prueba-un-electrico' ), 'tipo' => 'image')	
		)),
	);
	return $fields;
}

function wp_prueba_un_electrico_car_add_custom_fields() {
  add_meta_box(
    'box_activities', // $id
    __('Car Data', 'wp-prueba-un-electrico'), // $title 
    'wp_prueba_un_electrico_show_custom_fields', // $callback
    'car', // $page
    'normal', // $context
    'high'); // $priority
}
add_action('add_meta_boxes', 'wp_prueba_un_electrico_car_add_custom_fields');
add_action('save_post', 'wp_prueba_un_electrico_save_custom_fields' );

//Columnas , filtros y ordenaciones ------------------------------------------------
function wp_prueba_un_electrico_car_set_custom_edit_columns($columns) {
  $columns['brand-car'] = __( 'Brand', 'wp-prueba-un-electrico');
	$columns['type-car'] = __( 'Type', 'wp-prueba-un-electrico');
	return $columns;
}

function wp_prueba_un_electrico_car_custom_column( $column ) {
  global $post;
  if ($column == 'brand-car') {
    $terms = get_the_terms( $post->ID, 'brand'); 
    $string = array();
    if(is_array($terms) && count($terms) > 0) {
			$sorted_terms = sort_terms_hierarchically( $terms );
		  foreach($sorted_terms as $term) {
		    $string[] = $term->name;
		  }
    }
    if(count($string) > 0) echo implode (", ", $string);
  } else if ($column == 'type-car') {
    $terms = get_the_terms( $post->ID, 'cartype'); 
    $string = array();
    if(is_array($terms) && count($terms) > 0) {
			$sorted_terms = sort_terms_hierarchically( $terms );
		  foreach($sorted_terms as $term) {
		    $string[] = $term->name;
		  }
    }
    if(count($string) > 0) echo implode (", ", $string);
  }
}

function wp_prueba_un_electrico_car_brand_post_by_taxonomy() {
	global $typenow;
	$post_type = 'car'; // change to your post type
	$taxonomy  = 'brand'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'hierarchical' 		=> 1,
			'show_option_all' => __('Show all', 'wp-prueba-un-electrico' ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}

function wp_prueba_un_electrico_car_brand_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'car'; // change to your post type
	$taxonomy  = 'brand'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}

function wp_prueba_un_electrico_car_cartype_post_by_taxonomy() {
	global $typenow;
	$post_type = 'car'; // change to your post type
	$taxonomy  = 'cartype'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'hierarchical' 		=> 1,
			'show_option_all' => __('Show all', 'wp-prueba-un-electrico' ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}

function wp_prueba_un_electrico_car_cartype_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'car'; // change to your post type
	$taxonomy  = 'cartype'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}

//Los hooks si estamos en el admin 
if ( is_admin() && 'edit.php' == $pagenow && isset($_GET['post_type']) && 'car' == $_GET['post_type'] ) {
  add_filter( 'manage_edit-car_columns', 'wp_prueba_un_electrico_car_set_custom_edit_columns' ); //Metemos columnas
  add_action( 'manage_car_posts_custom_column' , 'wp_prueba_un_electrico_car_custom_column', 'category' ); //Metemos columnas

  add_action( 'restrict_manage_posts', 'wp_prueba_un_electrico_car_brand_post_by_taxonomy' ); //Añadimos filtro tipo
  add_filter( 'parse_query', 'wp_prueba_un_electrico_car_brand_id_to_term_in_query' ); //Añadimos filtro tipo

	add_action( 'restrict_manage_posts', 'wp_prueba_un_electrico_car_cartype_post_by_taxonomy' ); //Añadimos filtro tipo
  add_filter( 'parse_query', 'wp_prueba_un_electrico_car_cartype_id_to_term_in_query' ); //Añadimos filtro tipo
  
	add_filter( 'months_dropdown_results', '__return_empty_array' ); //Quitamos el filtro de fechas en el admin
}
add_action('pre_get_posts', 'wp_prueba_un_electrico_default_order', 99);

//Orden por defecto de las solicitudes
function wp_prueba_un_electrico_default_order($query) {
	global $pagenow;
  if ( is_admin() && 'edit.php' == $pagenow && isset($_GET['post_type']) && 'car' == $_GET['post_type']) {
    if (!isset($_GET['orderby'])) {
        $query->set('orderby', 'date');
    }
    if (!isset($_GET['order'])) {
        $query->set('order', 'DESC');
    }
  }
}
