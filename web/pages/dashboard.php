<h1>dashboard, yo</h1>

<button class="success" data-reveal-id="modal-create-problem">pose a problem</a>
<button class="success">create a solution</a>
<button id="more" name="more">MORE</button>


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

	   	<button id="test" type="submit">Submit</button>
	</form>
  <button class="close-reveal-modal">&#215;</button>
</div> <!-- end pose-a-problem modal -->

<div id="container" class="text-center">

<div>