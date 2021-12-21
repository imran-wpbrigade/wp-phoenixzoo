<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section, opens the <body> tag and adds the site's header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php $viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' ); ?>
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>

  <script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33031918-1']);
  _gaq.push(['_trackPageview']);

  (function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  </script>

  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-NMJPT23');</script>
  <!-- End Google Tag Manager -->

	<!-- Global site tag (gtag.js) - Google Ads: 939428836 -->
	<?php	
	// global $post;
	// if ( 'digital-safari' == $post->post_name ) : 
	?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-939428836"></script>
	<script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-939428836');</script>
	<?php 
	// endif; 
	?>
	<!-- End Global site tag (gtag.js) - Google Ads: 939428836 -->

  <!-- Facebook Pixel Code -->
  <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '657880041221687');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=657880041221687&ev=PageView&noscript=1"
  /></noscript>
  <!-- End Facebook Pixel Code -->
  <!-- Segment Pixel - Phoenix Zoo Website Retargeting - DO NOT MODIFY -->
  <script src="https://secure.adnxs.com/seg?add=8035032&t=1" type="text/javascript"></script>
  <!-- End of Segment Pixel -->

</head>
<body <?php body_class(); ?>>

<!-- The Lukens Company Google Code for Remarketing Tag --><!--------------------------------------------------Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup---------------------------------------------------><script type="text/javascript">/* <![CDATA[ */var google_conversion_id = 940239676;var google_custom_params = window.google_tag_params;var google_remarketing_only = true;/* ]]> */</script><script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script><noscript><div style="display:inline;"><img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/940239676/?value=0&amp;guid=ON&amp;script=0"/></div></noscript>

<!-- The Lukens Company Centro Retargeting Pixel --><script type="text/javascript">var ssaUrl = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'centro.pixel.ad/iap/af2456031571e636';new Image().src = ssaUrl;</script><!-- The Lukens Company Facebook Pixel Code for Phoenix Zoo (for Retargeting. Can also be used for Conversion Tracking)--><script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','//connect.facebook.net/en_US/fbevents.js');fbq('init', '1482115145333251');fbq('track', "PageView");</script><noscript><img height="1" width="1" style="display:none"src="https://www.facebook.com/tr?id=1482115145333251&ev=PageView&noscript=1"/></noscript><!-- End Facebook Pixel Code -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NMJPT23"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php
hello_elementor_body_open();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	get_template_part( 'template-parts/header' );
}
