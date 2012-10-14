<?php /* Template Name: page-topics*/ ?>
<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
			<div id="content">
<?php
$guide_cat = get_category_id('guides');
$idea_cat = get_category_id('ideas');

// limit list to user city + any city
$city_terms = get_terms('nh_cities');
foreach ($city_terms as $city_term) {
	$city_term = $city_term->name;
	if ($city_term == $user_city OR $city_term == 'Any City') {
		$cities[] = $city_term;
	}
}
foreach ($cities as $city) {
	if ($city != 'Any City') {
		$city_name = substr($city,0,-3); //remove state
	}
	else {
		$city_name = $city;
	}	
}
$city_slug = strtolower($city);
$city_slug = str_replace(' ','-',$city_slug);
$city_url = get_term_link($city,'nh_cities');
?>
				<h3 class="page-title">Topics for <?php echo $city_name;?></h3>
	
				<div id="list-ideas">
					<ul class="list-ideas">			
<?php 
// get tags for guides + ideas in user city and any city
$query_b = array(
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'category',
			'field' => 'id',
			'terms' => array($guide_cat,$idea_cat)
		),
		array(
			'taxonomy' => 'nh_cities',
			'field' => 'slug',
			'terms' => array($city_slug,'Any City')
		)		
	)
);
$base_tags = query_posts($query_b);
foreach ($base_tags as $base_tag) {
	$base_post_tags = wp_get_post_tags($base_tag->ID);
	foreach ($base_post_tags as $base_post_tag) {
		$b_post_tags[] = $base_post_tag->name;
	}
}
// get tags for guides in user city and any city
$query_g = array(
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'category',
			'field' => 'id',
			'terms' => array($guide_cat)
		),
		array(
			'taxonomy' => 'nh_cities',
			'field' => 'slug',
			'terms' => array($city_slug,'Any City')
		)		
	)
);
$guide_tags = query_posts($query_g);
foreach ($guide_tags as $guide_tag) {
	$guide_post_tags = wp_get_post_tags($guide_tag->ID);
	foreach ($guide_post_tags as $guide_post_tag) {
		$g_post_tags[] = $guide_post_tag->name;
	}
}
// get tags for ideas in user city and any city
$query_i = array(
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'category',
			'field' => 'id',
			'terms' => array($idea_cat)
		),
		array(
			'taxonomy' => 'nh_cities',
			'field' => 'slug',
			'terms' => array($city_slug,'Any City')
		)		
	)
);
$idea_tags = query_posts($query_i);
foreach ($idea_tags as $idea_tag) {
	$idea_post_tags = wp_get_post_tags($idea_tag->ID);
	foreach ($idea_post_tags as $idea_post_tag) {
		$i_post_tags[] = $idea_post_tag->name;
	}
}

$b_tags_unique = array_unique($b_post_tags);
$count_g_post_tags = array_count_values($g_post_tags);
$g_tags_unique = array_unique($g_post_tags);
$count_i_post_tags = array_count_values($i_post_tags);
$i_tags_unique = array_unique($i_post_tags);

if ($b_tags_unique) {	
	foreach ($b_tags_unique as $b_tag) {
		echo '<li class="nhline" style="margin:.75em 0 .75em 0;border-top:1px solid #ccc;padding-top:1em;">';	
		$tag_slug = strtolower($b_tag);
		$tag_slug = str_replace(' ','-',$tag_slug);
		echo '<a class="nhline" href="'.$app_url.'/topics/'.$tag_slug.'" title="See content tagged as '.$b_tag.'">'.$b_tag.'</a>';

		foreach ($g_tags_unique as $g_tag) {
			if ($b_tag == $g_tag) {
				$count_per_g_tag = $count_g_post_tags[$g_tag];		
				if ($count_per_g_tag == '1') {
					echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$count_per_g_tag.'&nbsp;Guide</span></span>';
				}
				elseif ($count_per_g_tag > 1) {
					echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$count_per_g_tag.'&nbsp;Guides</span></span>';
				}
			}
		}
		foreach ($i_tags_unique as $i_tag) {
			if ($b_tag == $i_tag) {
				$count_per_i_tag = $count_i_post_tags[$i_tag];		
				if ($count_per_i_tag == '1') {
					echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$count_per_i_tag.'&nbsp;Idea</span></span>';
				}
				elseif ($count_per_i_tag > 1) {
					echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$count_per_i_tag.'&nbsp;Ideas</span></span>';
				}
			}
		}		
	echo '</li>';				
	}
}
?>
					</ul>			
				</div>
								
			</div><!--/ content-->
<?php
if (is_user_logged_in()) {
	get_sidebar('misc');
}
else {
	get_sidebar('misc_loggedout');
}
?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>