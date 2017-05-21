<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package sparkling
 */
?>

<script>
	var SMT_price = <?php the_field('SMT_price'); ?>;
	var Basic_price = <?php the_field('Basic_price'); ?>;
	var PRO_price = <?php the_field('PRO_price'); ?>;
	var ChooseLanguages = '<?php _e('Choose language(s)', 'TBU'); ?>';	
	var ChooseLanguage = '<?php _e('Choose language', 'TBU'); ?>';	
	var SelectedText = '<?php _e('Selected', 'TBU'); ?>';	
	var SelectText = '<?php _e('Select', 'TBU'); ?>';
	var CountingWords = '<?php _e('Counting words', 'TBU'); ?>';
	var StillCountingWords = '<?php _e('Still counting words - please hold on', 'TBU'); ?>';
	var curLang = '<?php echo ICL_LANGUAGE_CODE; ?>';	
	var removeFile = '<?php _e('Remove file', 'TBU'); ?>';
	var cancelUpload = '<?php _e('Cancel upload', 'TBU'); ?>';
	var noMoreFiles = '<?php _e('You can not upload any more files', 'TBU'); ?>';
	var noText = '<?php _e('Please insert text or upload file(s)', 'TBU'); ?>';
</script>




<div class="container">


<div class="row landing-content">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<!-- <h1 class="page-title"><?php the_title(); ?></h1> -->
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</article>
</div>

<?php if( have_rows('landing_sections') ): ?>
<div class="landing_sections">
	<?php while ( have_rows('landing_sections') ) : the_row(); ?>
	<div class="row">
		<div class="col-md-4 section-title">
			<?php $ls_title = get_sub_field('lp_section_title'); ?>
			<?php if ( $ls_title != '' ): ?>
				<h2><?php echo $ls_title; ?></h2>
			<?php endif; ?>
			<img src="<?php  the_sub_field('lp_section_icon') ?>" alt="">
		</div>
		<div class="col-md-8 section-desc">
			<?php the_sub_field('lp_section_description'); ?>
		</div>
	</div>
	<?php endwhile; ?>
</div>
<?php endif; ?>

<?php if( have_rows('landing_icons') ): ?>
	<?php $icons_section_title = get_field('landing_icons_title'); ?>
	<?php if ( $icons_section_title != '' ): ?>
		<h2 style="padding-left: 20px;"><?php echo $icons_section_title ?></h2>
	<?php endif ?>
	
	<div class="row landing-icons">
		<?php while ( have_rows('landing_icons') ) : the_row();
			$img_url = get_sub_field('li_image'); ?>
			<div class="col-md-4 landing-item">
				<?php if ($img_url != ''): ?>
					<div class="landing-image">
						<img src="<?php echo $img_url;?>" alt="Landing Icon">
					</div>
				<?php endif; ?>
				<div class="landing-description">
					<?php the_sub_field('li_description'); ?>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
<?php endif; ?>

<div class="row orderform" style="top:0;">
	

	
	<div id="summary">
		
		<div class="data" id="summarydata">
			
			<h2><?php _e('Summary', 'TBU'); ?>:</h2>
			<div class="row projectName border">
				<div class="col-md-12">
					<p class="uppercase"><?php _e('Project name', 'TBU'); ?>: </p>
					<h3 class="output"></h3>
				</div>			
			</div>
			
					
			<div class="row wordCount border">
				<div class="col-md-4 col-sm-4 col-xs-4"><?php _e('Word count', 'TBU'); ?>:</div>		
				<div class="col-md-8 col-sm-8 col-xs-8 output"></div>			
			</div>
			
			<div class="row languages">
				
				<div class="col-md-12">
				<h5><?php _e('Selected languages', 'TBU'); ?>:</h5>
				</div>
			</div>
			
			<div class="row fromLanguage">
				<div class="col-md-4 col-sm-4 col-xs-4"><?php _e('From', 'TBU'); ?>:</div>		
				<div class="col-md-8 col-sm-8 col-xs-8 output"></div>			
			</div>
			
			
			<div class="row toLanguage border">
				<div class="col-md-4 col-sm-4 col-xs-4"><?php _e('To', 'TBU'); ?>:</div>		
				<div class="col-md-8 col-sm-8 col-xs-8 output"></div>			
			</div>
			
			
			<div class="row name">
				<div class="col-md-4 col-sm-4 col-xs-4"><?php _e('Name', 'TBU'); ?>:</div>		
				<div class="col-md-8 col-sm-8 col-xs-8 output"></div>			
			</div>
			
			<div class="row email">
				<div class="col-md-4 col-sm-4 col-xs-4"><?php _e('Email', 'TBU'); ?>:</div>		
				<div class="col-md-8 col-sm-8 col-xs-8 output"></div>			
			</div>
			
			<div class="row company border">
				<div class="col-md-4 col-sm-4 col-xs-4"><?php _e('Company', 'TBU'); ?>:</div>		
				<div class="col-md-8 col-sm-8 col-xs-8 output"></div>			
			</div>
			
			<div class="row quality visible border">
				<div class="col-md-12"><h5><?php _e('Selected quality', 'TBU'); ?>:</h5></div>
	
				<div class="col-md-12 free hidden q"><?php _e('Machine Translation', 'TBU'); ?><br><strong><?php _e('Free', 'TBU'); ?></strong></div>		
				<div class="col-md-12 basic q"><?php _e('Basic', 'TBU'); ?><br><strong><?php the_field('Currency'); ?> <?php echo str_replace(".", ",", get_field('Basic_price')); ?>/<?php _e('word', 'TBU'); ?></strong></div>	
				<div class="col-md-12 pro hidden q"><?php _e('Business', 'TBU'); ?><br><strong><?php the_field('Currency'); ?> <?php echo str_replace(".", ",", get_field('PRO_price')); ?>/<?php _e('word', 'TBU'); ?></strong></div>			
			</div>
			
			<div class="row total">
				<div class="col-md-12"><h2><?php _e('Total', 'TBU'); ?>: <?php _e('$', 'TBU'); ?> <span class="output"></span></h2></div>
			</div>		
			
		</div>
		<div id="response" class=" text-center">
            <?php the_field('order_confirmation_text'); ?>
            <div class="row">
				 <div class="col-md-6">
					 <div class="btn btn-block" id="order-confirmation"><i class="fa fa-print"></i> &nbsp;&nbsp;&nbsp;<?php _e('Click here to print order confirmation.', 'TBU'); ?></div>
				 </div>
				 
				  <div class="col-md-6">
					 <div class="btn btn-block" id="reset-form"><i class="fa fa-refresh"></i> &nbsp;&nbsp;&nbsp;<?php _e('Click here to start over.', 'TBU'); ?></div>
				 </div>
            </div>
        </div>
		
	</div>
	
	<?php the_post_thumbnail( 'sparkling-featured', array( 'class' => 'single-featured' )); ?>
	
	
	<div class="col-md-4 no-conf"> </div>
	
	<div id="orderformwrapper" class="col-md-8">
	
	<form name="translation-order" id="translation-order" action="#" method="POST">
		
		
		
		<input type="hidden" name="TranslationPackageId" id="TranslationPackageId" value="2">
		<input type="hidden" name="SourceLang" id="SourceLang" value="">
		<!--input type="hidden" name="TargetLanguages" id="TargetLanguages" value=""-->
		<!--input type="hidden" name="FileToUpload" id="FileToUpload" value=""-->
			
			
				
                    <h1><span>1</span> <?php _e('Select translation quality', 'TBU'); ?></h1> 
                    <section class="order-block">
	                    
	                    <div class="row flex">
		                    <div class="col-md-4 selectable" id="q0" rel="free">
			                    <div class="well">
				                <span class="dashicons dashicons-yes"></span>
			                    <h3><?php _e('Machine Translation', 'TBU'); ?></h3>
			                    <h4><?php _e('Free', 'TBU'); ?></h4>
			                    <hr>
			                    <p><?php the_field('SMT_desc'); ?></p>
			                    
			                    <div class="btn btn-block"><?php _e('Select', 'TBU'); ?></div>
			                    
			                    </div>
		                    </div>
		                    
		                    <div class="col-md-4  selectable active" id="q1" rel="basic">
			                    <div class="well">
				                <span class="dashicons dashicons-yes"></span>
			                    <h3><?php _e('Basic translation', 'TBU'); ?></h3>
			                    <h4><?php the_field('Currency'); ?> <?php echo str_replace(".", ",", get_field('Basic_price')); ?>/<?php _e('word', 'TBU'); ?></h4>
			                    
			                    <hr>
			                    <p><?php the_field('Basic_desc'); ?></p>
			                    <div class="btn btn-block"><?php _e('Selected', 'TBU'); ?></div>
			                    </div>
		                    </div>
		                    
		                    <div class="col-md-4  selectable" id="q2" rel="pro">
			                    <div class="well">
				                <span class="dashicons dashicons-yes"></span>
			                    <h3><?php _e('Business translation', 'TBU'); ?></h3>
			                    <h4><?php the_field('Currency'); ?> <?php echo str_replace(".", ",", get_field('PRO_price')); ?>/<?php _e('word', 'TBU'); ?></h4>
			                    <hr>
			                    <p><?php the_field('PRO_desc'); ?></p>
			                    <div class="btn btn-block"><?php _e('Select', 'TBU'); ?></div>
			                    </div>
		                    </div>
			                    
	                    </div>
                           
                    </section>
			
                <h1><span>2</span> <?php _e('Write or upload text', 'TBU'); ?>:</h1> 
                <section class="order-block">
	                
	                                       
	                    
	                    <input name="Name" id="projectName" type="text" placeholder="<?php _e('Optional: Name your project', 'TBU'); ?>" value="" />
	                                      
						
	                    <div class="textarea-ins">
		                    <div class="clear"><span class="dashicons dashicons-no-alt"></span></div>
                            <textarea placeholder="<?php _e('Type text here', 'TBU'); ?>" rows="6" name="Text" id="ordertext"></textarea>
                        </div>
	                    
	                                        
						<div class="dropzone dz-clickable" id="my-drop">
							
							<div class="fallback">
						        <input name="file" type="file" />
						    </div>
							
						    <div class="dz-default dz-message">
						        <span class="dashicons dashicons-welcome-add-page" style="font-size: 40px; width: 40px;"></span> <span><?php _e('or click or drop files here to upload', 'TBU'); ?></span>
						    </div>
						    
						    <div class="dropzone-previews"></div>
						    
						</div>
						
						<div class="alert alert-danger dropzone-danger"></div>
						
						<div class="alert alert-info dropzone-info">
							<i class="fa fa-info-circle"></i>
							<span class="txt"></span>
						</div>
					
                
						
						
						<div id="languages" class="row">
							<div class="col-md-12">
							<h4><?php _e('Select languages', 'TBU'); ?></h4>
							</div>
							<div class="col-md-6">
								<h5 class="fromLang">* <?php _e('From language', 'TBU'); ?>:</h5>
								<div id="fromLanguage" class="languageSelector"></div>
							</div>
							<div class="col-md-6">
								<h5 class="toLang">* <?php _e('To language(s)', 'TBU'); ?>:</h5>
								<div id="toLanguage" class="languageSelector"></div>
							</div>	
						</div>
								
								                   
                    	
                    </section>
                    
                     <h1><span>3</span> <?php _e('Fill in your details', 'TBU'); ?></h1> 
                     
					 <section class="order-block">
						 <div class="row">
							 <div class="col-md-4"><input type="text" name="ClientName" placeholder="* <?php _e('Name', 'TBU'); ?>" id="name" class="required" /> </div>
							 <div class="col-md-4"><input type="email" name="ClientEmail" placeholder="* <?php _e('Email', 'TBU'); ?>" id="email" class="required" />   </div>
							 <div class="col-md-4"><input type="text" name="ClientCompany" placeholder="<?php _e('Company', 'TBU'); ?>" id="company" /></div>
						 </div>
                     <textarea placeholder="<?php _e('Comment / brief', 'TBU'); ?>" name="Notes" rows="2" id="brief"></textarea>             
                     
                      <input type="submit" class="btn disabledz" value="<?php _e('Place order', 'TBU'); ?>"/>                      
                     </section>
                        
                    
                    
                      
                    
                    
                </form>	
                
                
                <img id="sendLoader" src="<?php echo get_stylesheet_directory_uri(); ?>/images/loader.gif" />
                
                
                
	</div>

</div>