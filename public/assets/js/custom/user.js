$(document).ready(function() {
  $('#userForm').validate({
    rules: {
      password: {
        required: true,
        minlength: 6
      },
      password_confirmation: {
        required: true,
        equalTo: '#password'
      }
    },
    messages: {
      password: {
        required: 'Please enter a password',
        minlength: 'Your password must be at least 6 characters long'
      },
      password_confirmation: {
        required: 'Please confirm your password',
        equalTo: 'Your passwords do not match'
      }
    },
    errorElement: 'div',
    errorPlacement: function(error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function(element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
