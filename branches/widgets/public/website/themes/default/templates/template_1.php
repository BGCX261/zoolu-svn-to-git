    <!-- Content Section -->
  <div class="wrapper content">
    <div class="subwrapper">
      <div class="inner detail">        
        <!-- Sub Navigation -->
        <?php include dirname(__FILE__).'/../includes/subnavigation.inc.php'; ?>
        
        <!-- Main Content -->
        <div class="divContentContainer">
          <h1><?php get_title(); ?></h1>          
          <?php get_image_main('220x', true, false, '660x', 'divImgLeft'); ?>
          <?php get_description(); ?>
          <div class="clear"></div>
          
          <?php get_text_blocks('220x', true, false, '660x'); ?>          
          
          <!-- <div class="divTextBlock">
            <h2>Textblock 1</h2>
            <div class="divImgLeft">
              <img title="" alt="" src="/website/themes/default/images/tmp/blank220x.gif"/>
            </div>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. <br/>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
            <div class="clear"></div>
          </div>
          
          <div class="divTextBlock">
            <h2>Textblock 2</h2>
            <div class="divImgLeft">
              <img title="" alt="" src="/website/themes/default/images/tmp/blank220x.gif"/>
            </div>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. <br/>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
            <div class="clear"></div>
          </div> -->
          
          <?php get_video(); ?>
          
          <?php if(has_image_gallery()) : ?>
            <div class="divImageGallery">
              <?php get_image_gallery_title('h3'); ?>
              <?php get_image_gallery(10, '80x80', true, false, '660x'); ?>
              <div class="clear"></div>
            </div>
          <?php endif; ?>
              
          <?php if(has_documents()) : ?>
            <div class="divDocuments">
              <?php get_documents_title('h3'); ?>
              <?php get_documents(); ?>               
              <div class="clear"></div>
            </div>
          <?php endif; ?>
          
          <!--<div class="divImageGallery">
            <h3>Bildergalerie</h3>                
            <div class="divGallery">
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif" title="">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif" title="">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="divGalleryItem">
                <a rel="lightbox[pics]" href="/website/themes/default/images/tmp/blank80x80.gif">
                  <img title="" alt="" src="/website/themes/default/images/tmp/blank80x80.gif"/>
                </a>
              </div>
              <div class="clear"></div>
            </div>                
            <div class="clear"></div>
          </div> -->
          
          <!--<div class="divDocuments">
            <h3>Dokumente</h3>                
            <div class="divDocItem">
              <div class="divDocIcon"><img title="Adressen der Trainer" alt="Adressen der Trainer" src="/website/themes/default/images/icons/icon_xls.gif"/></div>
              <div class="divDocInfos">
                <a target="_blank" href="#">Adressen der Trainer</a><br/>
                <span>Format:</span> <span>xls</span> <span>Größe:</span> <span>21 KB</span>
              </div>
              <div class="clear"></div>
            </div>
            <div class="divDocItem">
              <div class="divDocIcon"><img title="Ausschreibung" alt="Ausschreibung" src="/website/themes/default/images/icons/icon_doc.gif"/></div>
              <div class="divDocInfos">
                <a target="_blank" href="#">Ausschreibung</a><br/>
                <span>Format:</span> <span>doc</span> <span>Größe:</span> <span>29184 KB</span>
              </div>
              <div class="clear"></div>
            </div>
            <div class="divDocItem">
              <div class="divDocIcon"><img title="Vorträge online" alt="Vorträge online" src="/website/themes/default/images/icons/icon_pdf.gif"/></div>
              <div class="divDocInfos">
                <a target="_blank" href="#">Vorträge online</a><br/>
                <span>Format:</span> <span>pdf</span> <span>Größe:</span> <span>43082 KB</span>
              </div>
              <div class="clear"></div>
            </div>               
            <div class="clear"></div>
          </div> -->
          
          <div class="clear"></div>        
        </div>
        <!-- @end main content -->
        
        <!-- Content Sidebar --> 
        <div class="divContentSidebar">           
          <?php get_contacts(); ?>
          
          <?php if(has_categories()) : ?>
            <h3>Kategorie</h3>
            <ul>
              <?php get_categories('li', false); ?> 
            </ul>
          <?php endif; ?>                
                
          <?php if(has_tags()) : ?>
            <h3>Tags</h3>
            <ul>
              <?php get_tags('li', false); ?>
            </ul>
          <?php endif; ?>
        
          <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </div>  
    </div>
  </div>