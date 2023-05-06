$(document).ready(function() {
  
  // User table loading data using ajax
  $('#userTable').DataTable({
    ajax: '/users',
    processing: true,
    serverSide: true,
    // dataSrc: 'data',
    columns: [
      { data: 'firstname' },
      { data: 'lastname' },
      { data: 'email' },
      { data: 'mobile' },
      { data: 'username' },
      { data: 'action' },
    ]
  })

  // Handles delete confirmation of the user
  $(document).on('click', '.delete-user-btn', function(){
    
  });

});
