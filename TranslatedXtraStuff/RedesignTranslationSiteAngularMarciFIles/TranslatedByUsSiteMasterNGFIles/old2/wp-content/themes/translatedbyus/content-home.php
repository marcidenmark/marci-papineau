<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package sparkling
 */
?>




	
	
	<div id="summary">
		
		
		
		<div class="row projectName border">
			<div class="col-md-12">
				<h2 class="text-center">Summary:</h2>
				<p class="uppercase text-center">Project name: </p>
				<h3 class="output text-center"></h3>
			</div>			
		</div>
		
				
		<div class="row wordCount border">
			<div class="col-md-4">Word count:</div>		
			<div class="col-md-8 output"></div>			
		</div>
		
		<div class="row languages">
			
			<div class="col-md-12">
			<h5>Selected languages:</h5>
			</div>
		</div>
		
		<div class="row fromLanguage">
			<div class="col-md-4">From:</div>		
			<div class="col-md-8 output"></div>			
		</div>
		
		
		<div class="row toLanguage border">
			<div class="col-md-4">To:</div>		
			<div class="col-md-8 output"></div>			
		</div>
		
		
		<div class="row name">
			<div class="col-md-4">Name:</div>		
			<div class="col-md-8 output"></div>			
		</div>
		
		<div class="row email">
			<div class="col-md-4">Email:</div>		
			<div class="col-md-8 output"></div>			
		</div>
		
		<div class="row company border">
			<div class="col-md-4">Company:</div>		
			<div class="col-md-8 output"></div>			
		</div>
		
		<div class="row pricing visible border">
			<div class="col-md-12"><h5>Pricing:</h5></div>
			<hr/>
			<div class="col-md-4 q1">Secure Machine Translation<br><strong>Free</strong></div>		
			<div class="col-md-4 q2 active">Basic<br><strong>$0.05 / word</strong></div>	
			<div class="col-md-4 q3">Professional<br><strong>$0.17 / word</strong></div>			
		</div>
		
		<div class="row total">
			<div class="col-md-12"><h2 class="text-center">Total: $<span class="output"></span></h2></div>
		</div>		
		
		
	</div>
	
	<?php the_post_thumbnail( 'sparkling-featured', array( 'class' => 'single-featured' )); ?>
	
	
	<div id="orderformwrapper" >
	
	<form name="translation-order" class="container" id="translation-order" action="http://54.213.157.92/projectt/Admin/Order/Add" method="POST">
		
		
		
		<input type="hidden" name="returnUrl" value="http://www2.translatedbyus.com/?ordered">
			
				
				<h3>Order a translation</h3>
                <section class="order-block step1">
	                   <h1 class="text-center">Order a translation</h1> 
	                    
	                    
	                    
	                    <input name="Notes" id="projectName" type="text" placeholder="Optional: Name your project" value="" />
	                    
	                   
						<h4 class="or">* Write or upload text</h4>
						
	                    <div class="textarea-ins">
		                    <div class="clear"><span class="dashicons dashicons-no-alt"></span></div>
                            <textarea placeholder="Type text here" rows="6" id="ordertext"></textarea>
                        </div>
	                    
	                                        
						<div class="dropzone dz-clickable" id="my-drop">
						    <div class="dz-default dz-message">
						        <span class="dashicons dashicons-welcome-add-page" style="font-size: 40px; width: 40px;"></span> <span>or click or drop files here to upload</span>
						    </div>
						    
						    <div class="dropzone-previews"></div>
						    
						</div>
						
						<div class="alert alert-danger"></div>
					
                
						
						
						<div id="languages" class="row">
							<div class="col-md-12">
							<h4>Select languages</h4>
							</div>
							<div class="col-md-6">
								<h5 class="fromLang">* From language:</h5>
								<div id="fromLanguage" class="languageSelector"></div>
							</div>
							<div class="col-md-6">
								<h5 class="toLang">* To language(s):</h5>
								<div id="toLanguage" class="languageSelector"></div>
							</div>	
						</div>
								
								                   
                    	
                    </section>
                    
                     <h3>Fill in your details</h3>
					 <section class="order-block step2">
	                 <h1 class="text-center">Fill in your details</h1>
	                 <input type="text" name="name" placeholder="Name" id="name" /> 
	                 <input type="email" name="email" placeholder="* Email" id="email" class="required" />   
                     <input type="text" name="company" placeholder="Company" id="company" />
                     <textarea placeholder="Comment/brief" rows="6"></textarea>                                
                     </section>
                        
                    <h3>Select translation quality</h3>
                    <section class="order-block step3">
	                    <h1 class="text-center">Select translation quality</h1>
	                    
	                    <div class="row flex">
		                    <div class="col-md-4 selectable" id="q1" rel="free">
			                    <div class="well">
				                <span class="dashicons dashicons-yes"></span>
			                    <h3>Secure machine translation</h3>
			                    <hr>
			                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin varius purus sit amet erat finibus cursus. Class aptent taciti sociosqu ad litora torquent per conubia nostra</p>
			                    
			                    <div class="btn btn-block">Free</div>
			                    
			                    </div>
		                    </div>
		                    
		                    <div class="col-md-4  selectable active" id="q2" rel="basic">
			                    <div class="well">
				                <span class="dashicons dashicons-yes"></span>
			                    <h3>Basic translation</h3>
			                    <hr>
			                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin varius purus sit amet erat finibus cursus. Class aptent taciti sociosqu ad litora torquent per conubia nostra</p>
			                    <div class="btn btn-block">$0.05 / word</div>
			                    </div>
		                    </div>
		                    
		                    <div class="col-md-4  selectable" id="q3" rel="pro">
			                    <div class="well">
				                <span class="dashicons dashicons-yes"></span>
			                    <h3>Professional translation</h3>
			                    <hr>
			                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin varius purus sit amet erat finibus cursus. Class aptent taciti sociosqu ad litora torquent per conubia nostra</p>
			                    <div class="btn btn-block">$0.17 / word</div>
			                    </div>
		                    </div>
			                    
	                    </div>
                           
                    </section>
                    
                                           
                    
                    
                </form>	
	</div>
                    
<div class="post-inner-content container">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>                 

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
	<?php edit_post_link( esc_html__( 'Edit', 'sparkling' ), '<footer class="entry-footer"><i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->
</div>