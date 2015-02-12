<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foundation</title>
    <link rel="stylesheet" href="assets/css/login.css" />
    <script src="bower_components/modernizr/modernizr.js"></script>

    <script src="assets/js/include/APICaller.js"></script>
  </head>
  <body>

    <!-- top bar -->
    <div class="fixed">
      <nav class="top-bar" data-topbar role="navigation">
        <ul class="title-area">
          <li class="name">
              <h1><a href="#"><img class="logo-mini" src="/web/content/shoptimize_logo_transp.png"></a></h1>
          </li>
          <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
          <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
        </ul>

      <section class="top-bar-section">
          <!-- Right Nav Section -->
          <ul class="right">
              <li class=""><a href="#">what is this?</a></li>
              <li class="has-dropdown">
                <a href="#">username</a>
                <ul class="dropdown">
                  <li><a href="#">First link in dropdown</a></li>
                  <li class="active"><a href="#">Active link in dropdown</a></li>
                </ul>
            </li>
          </ul>
        </section>
      </nav>
    </div><!-- end top bar -->

    <a href="#" class="button success" data-reveal-id="modal-create-problem">pose a problem</a>
    <a href="#" class="button success">create a solution</a>
    <button id="more" name="more">MORE</button>

    <div class="row">
      <div id="curator" class="small-12 columns">


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

        <button id="test" type="submit">Submit</button>
      </form>
      <a class="close-reveal-modal">&#215;</a>
    </div> <!-- end pose-a-problem modal -->

    

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/foundation/js/foundation.min.js"></script>
    <script src="assets/js/include/masonry.pkgd.min.js"></script>
    <script src="assets/js/testing.js"></script>
  </body>
</html>