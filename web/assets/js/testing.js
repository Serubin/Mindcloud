/******************************************************************************
 * testing.js
 * @author Michael Shullick, Solomon Rubin
 * 13 Febuary 2015
 * Javascript for main app
 *****************************************************************************/
$( function() {

	$container = $('#curator');

	// initialize masonry curation
    $container.masonry({
        columnWidth: 20,
        itemSelector: '.item'
    });

    $('#more').click(function (e) {
        var types = ['w1', 'w2', 'w3', 'w4'];
        var elems;
        for (var i = 0; i < 3; i++) {
            var elem = $("<div></div>").addClass('item ' + types[Math.floor(Math.random() * types.length)]); 
            elems = elems ? elems.add( elem ) : elem;
        }        

        $container.append( elems );
        $container.masonry('appended',elems);

        $(document).foundation();
    });


	// Login form submission, validation done by Foundation form-abide
	$('#submit-problem').on('valid', function() {
		var req = new APICaller('problem', 'create');
		var params = {statement: $("#form_problem_statement").val(), description:$("#form_problem_desc").val()};
		req.send(params, function(result) {
				if (result) {
					$("#register_modal").foundation('reveal', 'close');
					alert("good job");

				}
			});
	});

	function createMasonryProblem(stmt) {
		return "<div class='item'>" + stmt + "</div>";
	}
});