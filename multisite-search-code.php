<?php
	$results = array();  //array for store search site result
	$siteResults = array();
	$sites = wp_get_sites();  //get all site list
	$searchTerm = $_GET['s'];

	for ($i=0;$i<count($sites);$i++){
	    $search_blog = $sites[$i]["blog_id"];
	    $blog_details = get_blog_details($search_blog);
	    //prepare for check sitename: path
	    $name = str_replace('/', '', $sites[$i]["path"]);
	    $name = str_replace('-', ' ', $name);
	    //prepare for check sitename sitename
	    $text = '(' . $name . '|' . $blog_details->blogname .')';
	    $reg =  '/^' . $text . '$/i';

    	    switch_to_blog($search_blog);  //switch blog to search
    	    //search arg goes here
	    $arg = array(
	    	'post_type' => array('post', 'page'),
	    	's' => $searchTerm,
	    	'sentence' => 'true',
	    );

	    $query = new WP_Query($arg);
	    if($query->have_posts()){
	    	$results = array_merge($results, $query->posts);
	    }
	    restore_current_blog();  //switch back

		//check sitename
	    if(preg_match($reg,$searchTerm)){
	    	$siteResults[] = array('post_title' => 'Home - ' . $blog_details->blogname, 'guid' => $blog_details->path);
	    }
	    //end check site name

	}//end for

	$results = array_merge($siteResults, $results);
	//print results
	var_dump($results);

?>
