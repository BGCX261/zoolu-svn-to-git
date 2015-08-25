<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title><?php get_portal_title(); ?> - <?php get_main_navigation_title(); ?></title>
  <?php get_meta_description(); ?>    
  <?php get_meta_keywords(); ?>
  
  <link rel="stylesheet" type="text/css" media="screen" href="/website/themes/default/css/screen.css"></link>
  <%widget_link%>
<%widget_css%>  
  <?php if(Zend_Auth::getInstance()->hasIdentity()) : ?>
  <link rel="stylesheet" type="text/css" media="screen" href="/website/themes/default/css/modus.css"></link>
  <?php endif; ?>
  
  <script type="text/javascript" src="/website/themes/default/js_incs/prototype/prototype.js"></script>
  <script type="text/javascript" src="/website/themes/default/js_incs/script.aculous/scriptaculous.js"></script>
  <script type="text/javascript" src="/website/themes/default/lightbox/js/lightbox.js"></script>
  <%template_js%>
  <script type="text/javascript" src="/website/themes/default/js_incs/default.js"></script>
<%widget_js%>
  <script type="text/javascript">//<![CDATA[
    var myDefault = new Default();
    document.observe('dom:loaded', function() { 
        myDefault.init();
    });
    //]]>
  </script>
  
  <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'IE 6') !== false): ?>     
  <!-- IE PNG FIX 
  <style type="text/css">
    #divScrollLeft img,
    #divScrollRight img { 
      behavior: url(/website/themes/default/css/iepngfix/iepngfix.htc); 
    }
  </style>-->    
  <?php endif; ?>
 
</head>

<body>
  <?php get_zoolu_header(); ?>
  
  <!-- Header Section -->
  <?php include dirname(__FILE__).'/includes/header.inc.php'; ?>
  <!-- Template Content -->  
  <?php include get_template_file(dirname(__FILE__).'/templates/'); ?>  
  <!-- Footer Section -->  
  <?php include dirname(__FILE__).'/includes/footer.inc.php'; ?>
		
  <!-- Google Analytics -->
  <script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
  </script>
  <script type="text/javascript">
    try {
      var pageTracker = _gat._getTracker("UA-XXXXXXX-1");
      pageTracker._trackPageview();
    } catch(err) {}
  </script>
</body>
</html>
