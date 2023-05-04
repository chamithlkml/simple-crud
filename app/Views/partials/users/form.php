<form class="needs-validation" novalidate>
  <div class="form-group">
    <label for="firstname">First Name</label>
    <input type="text" class="form-control" id="firstname" aria-describedby="First name" placeholder="First name here" required>
    <div class="invalid-feedback">Please enter a first name</div>
  </div>
  <div class="form-group">
    <label for="lastname">Last Name</label>
    <input type="text" class="form-control" id="lastname" aria-describedby="Last name" placeholder="Last name here" required>
    <div class="invalid-feedback">Please enter a last name</div>
  </div>
  <div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" class="form-control" id="email" aria-describedby="Email address" placeholder="Enter email address" required>
    <div class="invalid-feedback">Please enter a valid email address</div>
  </div>
  <div class="form-group">
    <label for="mobile">Mobile Phone Number</label>
    <input type="number" class="form-control" id="mobile" aria-describedby="Mobile phone number" placeholder="Mobile phone number" required>
    <div class="invalid-feedback">Please enter a valid mobile number</div>
  </div>
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" aria-describedby="Username" placeholder="Username" required>
    <div class="invalid-feedback">Please enter valid username</div>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password" required>
    <div class="invalid-feedback">Password is required</div>
  </div>
  <div class="form-group">
    <label for="password_confirmation">Password confirmation</label>
    <input type="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter password again" required>
    <div class="invalid-feedback">Please enter valid username</div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>