<h1>dashboard, yo</h1>

<button class="success" data-reveal-id="modal-create-problem">pose a problem</a>
<button class="success">create a solution</a>
<button id="more" name="more">MORE</button>

<div class="row" id="temp">
	<div class="small-12 column text-center">
		<p>Every problem is an opportunity</p>
	</div>
</div>

<!-- pose-a-problem modal -->
<div id="modal-create-problem" class="reveal-modal" data-reveal>
  <h2>Pose a problem</h2>
  <p class="lead">Your couch.  It is mine.</p>

	<form data-abide="ajax" id="submit-problem">

	    <div class="statement-field">
	    	<label>problem statement
	        	<input id="form_problem_statement" type="text" placeholder="Why can't I type with my mind yet?" required/>
	     	</label>
	     	<small class="error">What's your problem?</small>
	    </div>

	    <div class="description-field">
	     	<label>description
	        	<textarea id="form_problem_desc" placeholder="Keyboards have been around since like the 1930s..." required></textarea>
	     	</label>
	     	<small class="error">Please elaborate on your problem.</small>
	    </div>

		<!-- tags -->
		<div id="tag-container" class="row">
			<div class="medium-6 columns">
				<input id="tag_field" type="text" placeholder="tag your problem"/>

			</div>
			<div class="medium-6 column">
				<ul class="inline-list">
					<li><a class="tag" href="#">Walt Disney's fucking shitface</a></li>
					<li><a class="tag" href="#">Onomatopoeia</a></li>
					<li><a class="tag" href="#">Link 3</a></li>
					<li><a class="tag" href="#">Link 4</a></li>
					<li><a class="tag" href="#">Link 5</a></li>
				</ul>

			</div>
		</div>
		<!-- end tags -->

	   	<button id="test" type="submit">Submit</button>
	</form>
  <button class="close-reveal-modal">&#215;</button>
</div> <!-- end pose-a-problem modal -->

<div id="container">

<div>