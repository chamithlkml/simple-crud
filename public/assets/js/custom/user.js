$(document).ready(function() {
  
  // User table loading data using ajax
  $('#userTable').DataTable({
    ajax: '/users',
    processing: true,
    serverSide: true,
    columns: [
      { data: 'firstname' },
      { data: 'lastname' },
      { data: 'email' },
      { data: 'mobile' },
      { data: 'username' },
      { data: 'action', orderable: false },
    ]
  })

  // Handles delete confirmation of the user
  $(document).on('click', '.delete-user-btn', function(){
    const userId = $(this).attr('data-user-id');
    $('#confirmationModalTitle').html('Delete confirmation')
    $('#modalContent').html('Do you really want to delete the user..?')
    $('#confirmationForm').attr('action', '/users/'+userId+'/delete')
    $('#confirmationModal').modal()
  });

});
