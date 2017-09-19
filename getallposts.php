<?php
/*
Plugin Name: Get All Posts
Description: Получить список всех постов. Ссылки и заголовки. http://sitename/?getallposts=1
Version: 1.0.0
Author: Evgeny Stefanenko
Author URI: http://www.clarionlife.net
*/

$getallposts_class = new getallposts();
add_action( 'init', array( $getallposts_class, 'run') );

class getallposts
{
    function run()
    {
    	if ( isset( $_GET['getallposts'] ) && $_GET['getallposts'] == 1 )
    	{
    		$data = array();
            $wpquery = new WP_Query;
            $posts = $wpquery->query(array(
            	'post_type' => 'post',
				'posts_per_page' => -1
            ));
            foreach( $posts as $post ){
            	$data[] = array( get_permalink( $post ), esc_html( $post->post_title ) );
            }
            $this->array_to_csv_download( $data );
    		exit;
    	}
    }

    function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'";');

        // open the "output" stream
        // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
        $f = fopen('php://output', 'w');

        foreach ($array as $line) {
            fputcsv($f, $line, $delimiter);
        }
    }
}
