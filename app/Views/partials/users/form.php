<?php if (isset($errors)): ?>
  <?php foreach($errors as $error): ?>
    <div class="alert alert-danger">
      <?= $error ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<?= form_open($action, array('novalidate' => true, 'class' => 'needs-validation', 'id' => $formId )); ?>
  <div class="form-group">
    <?= form_label('First Name', 'firstname', array('class' => 'form-label')); ?>
    <?= form_input(['type' => 'text', 'name' => 'firstname', 'class' => 'form-control', 'id' => 'firstname', 'aria-describedby' => 'First name', 'placeholder' => "First name here", 'required' => true], $data['firstname'] ?? '') ?>
    <div class="invalid-feedback">Please enter a first name</div>
  </div>
  <div class="form-group">
    <?= form_label('Last Name', 'lastname'); ?>
    <?= form_input(['type' => 'text', 'name' => 'lastname', 'class' => 'form-control', 'id' => 'lastname', 'aria-describedby' => 'Last name', 'placeholder' => "Last name here", 'required' => true], $data['lastname'] ?? '') ?>
    <div class="invalid-feedback">Please enter a last name</div>
  </div>
  <div class="form-group">
    <?= form_label('Email Address', 'email'); ?>
    <?= form_input(['type' => 'email', 'name' => 'email', 'class' => 'form-control', 'id' => 'email', 'aria-describedby' => 'Email address', 'placeholder' => "Email address here", 'required' => true], $data['email'] ?? '') ?>
    <div class="invalid-feedback">Please enter a valid email address</div>
  </div>
  <div class="form-group">
    <?= form_label('Mobile Phone Number', 'mobile'); ?>
    <?= form_input(['type' => 'number', 'name' => 'mobile', 'class' => 'form-control', 'id' => 'mobile', 'minlength' => "11", 'maxlength' => "11", 'aria-describedby' => 'Mobile Number', 'placeholder' => "Mobile number here", 'required' => true], $data['mobile'] ?? '') ?>
    <div class="invalid-feedback">Please enter a valid mobile number</div>
  </div>
  <div class="form-group">
    <?= form_label('Username', 'username'); ?>
    <?= form_input(['type' => 'text', 'name' => 'username', 'class' => 'form-control', 'id' => 'username', 'aria-describedby' => 'Username', 'minlength' => "6", 'placeholder' => "Username here", 'required' => true], $data['username'] ?? '') ?>
    <div class="invalid-feedback">Please enter a username</div>
  </div>
  <div class="form-group">
    <?= form_label('Password', 'password'); ?>
    <?= form_password(['class' => 'form-control', 'name' => 'password', 'id' => 'password', 'aria-describedby' => 'Password', 'minlength' => "6", 'placeholder' => "Password here", 'required' => true]) ?>
    <div class="invalid-feedback">Please enter a password with minimum 6 length</div>
  </div>
  <button class="btn btn-primary ml-auto" type="submit">Submit</button>
<?= form_close(); ?>
