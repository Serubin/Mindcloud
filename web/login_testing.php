<!-- interim page for logging during feature testing -->
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foundation</title>
    <link rel="stylesheet" href="assets/css/app.css" />
    <script src="bower_components/modernizr/modernizr.js"></script>
    <!--<script src="bower_components/masonry/masonry.js"></script>-->
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
              <li class=""><a href="#" data-reveal-id="register_modal">sign up</a></li>
              <li class=""><a href="#">announcements</a></li>
              <li class=""><a href="#">about</a></li>
              <li class=""><a href="#">help</a></li>
              <li class="show-for-small-only"><a href="#" >terms of service</a></li>
              <li class="show-for-small-only"><a href="#">privacy policy</a></li>
              <li class="show-for-small-only"><a href="#">contact</a></li>
          </ul>
        </section>
      </nav>
    </div><!-- end top bar -->

    <!-- title -->
    <div id="title"> 
      <p class="subtitle">welcome to</p>
      <!-- <h1 class="title">mindcloud</h1> -->
    </div>
        
    <div class="row">
      <div class="small-10 medium-4 column small-offset-1 medium-offset-4" id="login-pane">
        <p>Please log in.</p>

        <div class="alert-box success round" style="display:none">
          Registration successful! Please log in.
          <a href="#" class="close">&times;</a>
        </div>
        <form id="login_form" data-abide="ajax">
          <!-- email -->
          <div class="email-field">
            <span class="label">email</span>
              <input type="email" required id="login_email" name="login_email" placeholder="you@example.com" />
              <small class="error">email invalid</small>
          </div>

          <!-- password -->
          <div class="password-field">
            <label><span class="label">password</span>
              <input type="password" id="login_password" name="login_password" required pattern="password" placeholder="password" />
              </label>
              <small id="login_pass_err" class="error">Please enter a valid password</small>
          </div>

      <button type="submit" class="btn-login">login</button>
      <button id="login_forgot" class="button btn-login secondary">help</button>
    </form>



      </div> <!-- end login pane -->
    </div>

    <!-- Register modal -->
    <div id="register_modal" class="reveal-modal" data-reveal>
      <h2>Welcome to Mindcloud!</h2>
      <p class="lead">Please fill all of these fields</p>
      <div data-alert id="reg_error_alert" class="alert-box alert round">
        <a href="#" class="close">&times;</a>
      </div>


      <form id="registration_form" data-abide="ajax">

        <!-- first name -->
        <div class="name-fields">
          <label><label for="register_first">first name    <small>required</small></label>
          <input type="text" required id="register_firstname" name="register_firstname" placeholder="Joshua" />
          </label>
          <small class="error">Please provide your first name</small>
        </div>     

        <!-- last name -->
        <div class="name-fields">
          <label><span class="label">last name</span><small>required</small>
          <input type="text" required id="register_lastname" name="register_lastname" placeholder="Schooner" />
          </label>
          <small class="error">Please provide your last name</small>
        </div>  

        <!-- email -->
        <div class="email-field">
          <label><span class="label">email</span><small>required</small>
            <input type="email" required id="register_email" name="register_email" placeholder="you@example.com" />
          </label>
          <small class="error">Email invalid</small>
        </div>

        <!-- password -->
        <div class="password-field">
          <label><span class="label">password</span><small>required</small>
            <input type="password" required id="register_password" name="register_password" pattern="password" placeholder="password" />
            </label>
            <small id="login_pass_err" class="error">Please enter a valid password</small>
        </div>

        <!-- password confirm -->
        <div class="password-confirmation-field">
          <label><span class="label">confirm password</span><small>required</small>
            <input type="password" required data-equalto="register_password" pattern="password" placeholder="password (again...)">
          </label>
          <small class="error">The password did not match</small>
        </div>

        <!-- year of birth -->
        <div class="birthdate-field">
          <label><span class="label">year of birth</span><small>required</small>
            <select id="register_year" required>
              <option value ="">Select</option>
            </select>
          </label>
          <small class="error">please provide your birthday</small>
        </div>


        <!-- gender -->
        <div class="gender-field">
            <label>gender    <small>required</small></label>
            <input type="radio" name="register_gender" value="M" id="register_gender" required><label for="male">Male</label>
            <input type="radio" name="register_gender" value="F" id="register_gender" required><label for="female">Female</label>
            <input type="radio" name="register_gender" value="O" id="register_gender" required><label for="other">Other</label>
        </div>
        <br/>
        <button type="submit">register</button>
        <button class="secondary" id="reg_to_login">Already have an account?</button>
      </form> 
    </div> <!-- end regiser modal -->

    <nav class="top-bar bottom-bar show-for-medium-up" data-topbar role="navigation">

      <section class="top-bar-section" >
          <!-- Right Nav Section -->
          <ul class="right">
              <li class="show-for-small-up"><a href="#" >terms of service</a></li>
              <li class="show-for-small-up"><a href="#">privacy policy</a></li>
              <li class=""><a href="#">contact</a></li>
              
          </ul>
        </section>
      </nav>

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/foundation/js/foundation.min.js"></script>
    <script src="assets/js/include/APICaller.js"></script>
    <script src="assets/js/include/sha512.js"></script>
    <script src="assets/js/login.js"></script>
  </body>
</html>