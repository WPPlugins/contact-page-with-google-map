<!-- single-contact_page_cpt.php -->
<?php

// first we get all the meta data
get_header(); 
$pid = get_the_ID();

$api_key = get_post_meta($post->ID, 'google_api_key', true);

$biz_name = get_post_meta($post->ID, 'contact_page_name', true);
$email    = get_post_meta($post->ID, 'contact_page_email', true);
$phone    = get_post_meta($post->ID, 'contact_page_phone', true);
$address  = get_post_meta($post->ID, 'contact_page_street', true);
$city     = get_post_meta($post->ID, 'contact_page_city', true);
$state    = get_post_meta($post->ID, 'contact_page_state', true);
$zip      = get_post_meta($post->ID, 'contact_page_zip', true);
$address  = $address . ' ' . $city . ' ' . $zip;

// is open
$mday_is_open     = get_post_meta($post->ID, 'mday_is_open', true);
$tuesday_is_open  = get_post_meta($post->ID, 'tuesday_is_open', true);
$wday_is_open     = get_post_meta($post->ID, 'wday_is_open', true);
$thursday_is_open = get_post_meta($post->ID, 'thursday_is_open', true);
$fday_is_open     = get_post_meta($post->ID, 'fday_is_open', true);
$saturday_is_open = get_post_meta($post->ID, 'saturday_is_open', true);
$sunday_is_open   = get_post_meta($post->ID, 'sunday_is_open', true);

// all day
$mday_allday     = get_post_meta($post->ID, 'mday_allday', true);
$tuesday_allday  = get_post_meta($post->ID, 'tuesday_allday', true);
$wday_allday     = get_post_meta($post->ID, 'wday_allday', true);
$thursday_allday = get_post_meta($post->ID, 'thursday_allday', true);
$fday_allday     = get_post_meta($post->ID, 'fday_allday', true);
$saturday_allday = get_post_meta($post->ID, 'saturday_allday', true);
$sunday_allday   = get_post_meta($post->ID, 'sunday_allday', true);

// hours
$mday_start	    = get_post_meta($post->ID, 'mday_start', true);
$mday_end       = get_post_meta($post->ID, 'mday_end', true);
$tuesday_start	= get_post_meta($post->ID, 'tuesday_start', true);
$tuesday_end    = get_post_meta($post->ID, 'tuesday_end', true);
$wday_start	    = get_post_meta($post->ID, 'wday_start', true);
$wday_end       = get_post_meta($post->ID, 'wday_end', true);
$thursday_start	= get_post_meta($post->ID, 'thursday_start', true);
$thursday_end   = get_post_meta($post->ID, 'thursday_end', true);
$fday_start	    = get_post_meta($post->ID, 'fday_start', true);
$fday_end       = get_post_meta($post->ID, 'fday_end', true);
$saturday_start	= get_post_meta($post->ID, 'saturday_start', true);
$saturday_end   = get_post_meta($post->ID, 'saturday_end', true);
$sunday_start	= get_post_meta($post->ID, 'sunday_start', true);
$sunday_end     = get_post_meta($post->ID, 'sunday_end', true);

if ($mday_is_open != 'on') {
	$monday = 'Closed';
} elseif ($mday_allday == 'on') {
	$monday = 'All Day';
} else {
	$monday = $mday_start . ' to ' . $mday_end;
}

if ($tuesday_is_open != 'on') {
	$tuesday = 'Closed';
} elseif ($tuesday_allday == 'on') {
	$tuesday = 'All Day';
} else {
	$tuesday = $tuesday_start . ' to ' . $tuesday_end;
}

if ($wday_is_open != 'on') {
	$wednesday = 'Closed';
} elseif ($wday_allday == 'on') {
	$wednesday = 'All Day';
} else {
	$wednesday = $wday_start . ' to ' . $wday_end;
}

if ($thursday_is_open != 'on') {
	$thursday = 'Closed';
} elseif ($thursday_allday == 'on') {
	$thursday = 'All Day';
} else {
	$thursday = $thursday_start . ' to ' . $thursday_end;
}

if ($fday_is_open != 'on') {
	$friday = 'Closed';
} elseif ($fday_allday == 'on') {
	$friday = 'All Day';
} else {
	$friday = $fday_start . ' to ' . $fday_end;
}

if ($saturday_is_open != 'on') {
	$saturday = 'Closed';
} elseif ($saturday_allday == 'on') {
	$saturday = 'All Day';
} else {
	$saturday = $saturday_start . ' to ' . $saturday_end;
}

if ($sunday_is_open != 'on') {
	$sunday = 'Closed';
} elseif ($sunday_allday == 'on') {
	$sunday = 'All Day';
} else {
	$sunday = $sunday_start . ' to ' . $sunday_end;
}

if ( ! isset( $content_width ) ) {
	$content_width = 1170;
}

?>

<style>
.clear {
	clear: both;
}
.container-fluid {
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}
.row {
  margin-right: -15px;
  margin-left: -15px;
}
.col-lg-6, .col-md-6, .col-sm-6 {
	float: left;
	width: 50%;
}
.wrap {
	max-width: <?php echo $content_width; ?>px;
	margin: 0 auto;
}


#primary {
	width: 100%;
}
.dayname {
	display: inline-block;
	min-width: 145px;
}
</style>

<div class="wrap zen-contact-page-header">
	<header class="page-header">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</header>
</div>

<div id="primary" class="content-area zen-contact-page">
	<main id="main" class="site-main" role="main">
		<div class="container-fluid">
			<div class="row">
		<?php if (!empty($pid)) { 
			$content_post = get_post($pid);
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content); ?>
			<div class="wrap"><?php echo $content; ?></div>
			
				<?php if (!empty($api_key)) { ?>
				<div id="map" class="zen-contact_page_map">
					<iframe
					  width="100%"
					  height="300"
					  frameborder="0" style="border:0; margin-bottom: 20px;"
					  src="https://www.google.com/maps/embed/v1/place?key=<?php echo esc_html($api_key); ?>&q=<?php echo str_replace(' ', '+', esc_html($address) ); ?>,<?php echo str_replace(' ', '+', esc_html($city) );?>+<?php echo esc_html($state); ?>" allowfullscreen>
					</iframe>
				</div>
				<?php } ?>
				
				<div class="wrap">
					<div class="col-lg-6 col-md-6 col-sm-6">
						<h1>ADDRESS</h1>
						<p><?php echo esc_html($biz_name); ?></p>
						<p><?php echo esc_html($address); ?></p>
						<p><?php echo esc_html($city) . ' ' . esc_html($state) . ' ' . esc_html($zip); ?></p>
						
						<h1>PHONE</h1>
						<p><a href="tel:<?php echo esc_html($phone); ?>"><?php echo esc_html($phone); ?></a></p>
						
						<h1>EMAIL</h1>
						<p><a href="mailto:<?php echo esc_html($email); ?>"><?php echo esc_html($email); ?></a></p>
					</div>
					
					<div class="col-lg-6 col-md-6 col-sm-6">
						<h1>HOURS</h1>
						<div class="day"><p><span class="dayname">Monday: </span><?php echo esc_html($monday); ?></p></div>
						<div class="day"><p><span class="dayname">Tuesday: </span><?php echo esc_html($tuesday); ?></p></div>
						<div class="day"><p><span class="dayname">Wednesday: </span><?php echo esc_html($wednesday); ?></p></div>
						<div class="day"><p><span class="dayname">Thursday: </span><?php echo esc_html($thursday); ?></p></div>
						<div class="day"><p><span class="dayname">Friday: </span><?php echo esc_html($friday); ?></p></div>
						<div class="day"><p><span class="dayname">Saturday: </span><?php echo esc_html($saturday); ?></p></div>
						<div class="day"><p><span class="dayname">Sunday: </span><?php echo esc_html($sunday); ?></p></div>
					</div>
					
					<div class="clear"></div>
				</div>
				<?php
			} else {
				echo '<p>Sorry, there was an error and this post or page could not be found</p>';
			}
		?>
			</div><!-- .row -->
		</div><!-- .container-fluid -->
	</main><!-- #main -->
</div><!-- #primary -->
	<?php get_sidebar(); ?>

<?php get_footer();