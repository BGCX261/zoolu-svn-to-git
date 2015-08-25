  <!-- Content Section -->
  <div class="wrapper content">
    <div class="subwrapper">
      <div class="inner overview">        
        <!-- Sub Navigation -->
        <?php include dirname(__FILE__).'/../includes/subnavigation.inc.php'; ?>
        
        <!-- Main Content -->
        <div class="divContentContainer">
          <h1><?php get_title(); ?></h1>
          <div class="divContentIntro">
            <?php get_image_main('220x', true, true, '660x', 'divImgLeft'); ?>            
            <?php get_description(); ?>
            <div class="clear"></div>
          </div>
          
          <div class="clear"></div>                   
          
          <!-- Overview -->
          
          <div class="divContentOverview">
            <?php get_overview(); ?> 
            <div class="clear"></div>
          </div>
          
          <!-- Content Sidebar -->
          <div class="divContentSidebar">
            <!-- Contact -->
            <?php get_contacts(); ?>
                        
            <!-- Sidebar Blocks -->
            <?php get_sidebar_blocks(); ?>                       
            <div class="clear"></div>
          </div>
          
          <div class="clear"></div>        
        </div>
        <!-- @end main content -->
        
        <div class="clear"></div>
      </div>  
    </div>
  </div>