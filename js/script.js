$(function() {
/**
 * jQuery functionality
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0.0 April 2020
 * @version 2.0.0 September 2021
 *                Input is now escaped to prevent HTML and JS injection
 * @version 2.0.1 December 2024
 *                Code convention updated
 */

    const searchMovieText = 'Please enter a movie ID';
    const newMovieText = 'Please enter the name of the new movie';
    const noMovieText = 'No movie';
    const searchMovieButtonText = 'Get the movie name';
    const newMovieButtonText = 'Add the new movie';

    // Initialisation of labels for movie option buttons
    $('#lblSearch').addClass('show');
    $('#lblNew').addClass('hide');
    $('#divMovie').html(noMovieText);
    $('#btnMovie').html(searchMovieButtonText);

    // Behaviour of labels for movie option buttons
    $('#lblSearch').click(function() { FSelectSearchMovie(); });
    $('#lblNew').click(function() { FSelectNewMovie(); });

    function FSelectSearchMovie() {
        $('#lblSearch').addClass('show');
        $('#lblSearch').removeClass('hide');
        $('#lblNew').addClass('hide');    
        $('#lblNew').removeClass('show');    
        $('#lblMovie').html(searchMovieText);
        $('#divMovie').html(noMovieText);
        $('#btnMovie').html(searchMovieButtonText);
        $('#txtMovie').val('');
    }

    function FSelectNewMovie() {
        $('#lblSearch').addClass('hide');
        $('#lblSearch').removeClass('show');
        $('#lblNew').addClass('show');    
        $('#lblNew').removeClass('hide');    
        $('#lblMovie').html(newMovieText);
        $('#divMovie').html(noMovieText);
        $('#btnMovie').html(newMovieButtonText);
        $('#txtMovie').val('');
    }

    // Ok button clicked
    $('#frmName').submit(function(e) {
        e.preventDefault();
        $('#divName').text('Your name is ' + $('#txtName').val());
    });

    // Get movie by ID
    $('#frmMovie').click(function(e) {
        e.preventDefault();
        if ($('#btnMovie').html() == searchMovieButtonText) {
            $.ajax({
                url: 'src/api.php',
                type: 'GET',
                data: {
                    action: 'get_movie',
                    movie_id: $('#txtMovie').val()
                    }
                }).
                done(function(data) {
                    const movie = JSON.parse(data);
                    console.log(movie);
                    
                    if (movie == '')
                        $('#divMovie').html('No movie with this ID exists');
                    else
                        $('#divMovie').html(movie);
                })
                .fail(function() {
                    $('#divMovie').html('There was an error while processing the request');
                })
        } else {
            $.ajax({
                url: 'src/api.php',
                type: 'POST',
                data: {
                    action: 'new_movie',
                    movie_name: $('#txtMovie').val()
                }
            }).
            done(function(data) {
                $('#divMovie').html(JSON.parse(data));
            })
            .fail(function() {
                $('#divMovie').html('There was an error while trying to add the new movie');
            })
        }
    });   
});