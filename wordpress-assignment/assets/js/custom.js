jQuery(document).ready(function($) {
  $('#searchform').submit(function(e) {
      e.preventDefault();
      var searchQuery = $('#s').val();
      $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
              action: 'search_posts',
              search_query: searchQuery
          },
          success: function(response) {
              console.log(response.data); // Do something with the response
          },
          error: function(xhr, status, error) {
              console.log(error); // Handle error
          }
      });
  });
});
