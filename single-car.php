<?php
define("PLANTILLA_CAR", 76);

$car_id = get_the_id();
$template = get_post(PLANTILLA_CAR); 
$content = $template->post_content;

//Imágenes
$content = str_replace("*|image|*", get_post_thumbnail_id($car_id), $content);
$photos = [];
foreach(get_post_meta( $car_id, '_car_photo_gallery', true ) as $photo) {
	$photos[] = $photo['photo'];
}
$content = str_replace("*|image_interior_1|*", $photos[sizeof($photos) - 2], $content);
$content = str_replace("*|image_interior_2|*", $photos[sizeof($photos) - 1], $content);
$content = str_replace("*|gallery|*", implode(",", array_slice($photos, 0, -2)), $content);

$content = apply_filters('the_content', $content); 

//Modelo
$content = str_replace("*|model|*", get_the_title($car_id), $content);

//Marca
$brand_names = wp_get_object_terms($car_id, 'brand'); 
$content = str_replace("*|brand|*", $brand_names[0]->name, $content);
$term_meta = get_option( "taxonomy_".$brand_names[0]->term_id);
$content = str_replace("*|brand_image|*", $term_meta['image'], $content);

//Tipo
$type_names = wp_get_object_terms($car_id, 'cartype'); 
$content = str_replace("*|type|*", $type_names[0]->name, $content);
$term_meta = get_option( "taxonomy_".$type_names[0]->term_id);
$content = str_replace("*|type_image|*", $term_meta['image'], $content);

//Textos varios
$content = str_replace("*|maintext|*", str_replace("\n", "<br/>", get_the_content()), $content);
$content = str_replace("*|title_1|*", get_post_meta( $car_id, '_car_title_1', true ), $content);
$content = str_replace("*|text_1|*", get_post_meta( $car_id, '_car_text_1', true ), $content);
$content = str_replace("*|title_2|*", get_post_meta( $car_id, '_car_title_2', true ), $content);
$content = str_replace("*|text_2|*", get_post_meta( $car_id, '_car_text_2', true ), $content);

//Caracterśiticas
$content = str_replace("*|range|*", get_post_meta( $car_id, '_car_range', true ), $content);
$content = str_replace("*|recharge|*", get_post_meta( $car_id, '_car_recharge', true ), $content);
$content = str_replace("*|consumption|*", get_post_meta( $car_id, '_car_consumption', true ), $content);
$content = str_replace("*|acceleration|*", get_post_meta( $car_id, '_car_acceleration', true ), $content);
$content = str_replace("*|power|*", get_post_meta( $car_id, '_car_power', true ), $content);
$content = str_replace("*|video|*", get_post_meta( $car_id, '_car_url_youtube', true ), $content);
$content = str_replace("*|image|*", get_post_thumbnail_id($car_id), $content);

echo $content; 