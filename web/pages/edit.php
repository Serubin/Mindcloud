<div id="edit-page">
	<div class="row">
		<form data-abide="ajax" id="edit-form" class="small-12 columns">
		<h2><small>Editing</small><span id="edit-title"></span></h2>
			<!-- title -->
		    <div class="statement-field">
		    	<label>statement
		        	<input id="form_edit_statement" type="text" maxlength="200" required/>
		     	</label>
		     	<small class="error">Blank statement?</small>
		    </div>

		    <!-- description -->
		    <div class="description-field clearfix">
		     	<label>description</label>
		 		<!-- tabs for edit and preview-->
		 		<ul class="tabs right" data-tab role="tablist">
					<li class="tab-title active" role="presentational" >
						<a href="#edit-editing" role="tab" tabindex="0" aria-selected="true" controls="edit-editing">Editing</a>
					</li>
					<li class="tab-title" role="presentational">
						<a href="#edit-preview" id="edit-preview-button" role="tab" tabindex="0" aria-selected="false" controls="edit-preview">Preview</a>
					</li>
				</ul>
				<!-- editing and preview tab content -->
				<div class="tabs-content">
					<div class="content active" id="edit-editing">
					<textarea id="form_edit_desc" class="small-12 columns" rows="8" required></textarea>
					</div>
					<div class="content" id="edit-preview">
						<div id="edit-text-preview">
						</div>
					</div>
				</div>
		     	<small class="error">Please elaborate</small>
		    </div>

		    <div class="hide-field">
		    	<input id="form_edit_hide" type="checkbox"><label for="form_edit_hide"><span data-tooltip aria-haspopup="true" class="has-tip" title="Hiding a post is a way to prevent users from viewing it without a link. This works for both Problems and Solutions.">Hide post?</span></label>
		    </div>

			<button type="submit" class="button btn-login">save</button>

		   	<!--<a role="button" type="submit" aria-label="submit form" href="#" class="button keep-native">submit</a>-->
		</form>
	</div>
</div>