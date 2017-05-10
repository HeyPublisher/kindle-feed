<?php
/**
 * Kindle Feed Template for displaying Future Posts in a way Kindle can consume.
 *
 * @package WordPress
 */

// The class with set options should already be loaded.
global $kf, $post, $cons_shareFollow;
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// dump the Share and Follow junk, if present.
if ($cons_shareFollow) {
	remove_filter('the_content', array(&$cons_shareFollow, 'addContent'));
}
query_posts( $kf->query_string_for_posts(array('p'=> $post->ID )));
while( have_posts()) : the_post();
	$post = get_post(get_the_ID(), OBJECT);
  $cats = get_the_category();
  $cat = $cats[0]->slug; // we only care about first one.
	$charset = 'UTF-8'; // Force this
  $title = get_the_title();
  $abstract = ''; //$kf->strip_excerpt(get_the_excerpt());
  $author = get_the_author();
  $bio = get_the_author_meta('description');
  $authorbio = '';
  if ($bio !== '') {
    $authorbio = <<<EOF
    <hr/>
    <h3>About {$author}:</h3>
    <p align="left" width="0">{$bio}</p>
EOF;
  }

  $pubdate = get_the_date('Ymd');
  $content = get_the_content_feed();
  $content = $kf->strip_content($content);
  $pre = '';
  // TODO: hack alert - this should be pulled from configuration
  if ($cat == 'poetry') {
    $pre = '<p/>'; // this ensures first stanza is not left align by default.
  }
  $html = <<<EOF
<?xml version="1.0" encoding="{$charset}"?>
<html>
  <head>
    <title>{$title}</title>
    <meta name="abstract" content="{$abstract}"/>
    <meta name="author" content="by {$author}"/>
    <meta name="dc.date.issued" content="{$pubdate}"/>
  </head>
  <body>
    {$pre}
    {$content}
    {$authorbio}
	</body>
</html>
EOF;
  header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . $charset, true);
  echo $html;
endwhile;
?>
