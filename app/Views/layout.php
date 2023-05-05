<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Sample App</title>
      <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
      <!-- Adding any additional CSS file passed by the controller -->
      <?php if(isset($additionalStylesheets)){
        foreach($additionalStylesheets as $additionalStylesheet){
          ?>
            <link rel="stylesheet" href="<?= $additionalStylesheet ?>">
          <?php
        }
      } ?>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="#">Sample App</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="/users/create">Create</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Read</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Update</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Delete</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container">
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-12 text-center mt-1">
              <?php
                $session = session();
                $message = '';
                $alert = '';
                $success_message = $session->getFlashdata('success');
                $warning_message = $session->getFlashdata('error');

                if($success_message != ''){
                  $alert = 'alert-success';
                  $message = $success_message;
                }else if($warning_message != ''){
                  $alert = 'alert-warning';
                  $message = $warning_message;
                }
              ?>
              <?php if($message != ''):  ?>
                <div class="alert alert-dismissible fade show <?= $alert ?>" role="alert">
                  <strong>Attention</strong> <?= $message ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>

    <!-- Loading the content of the page named by the controller -->
    <?php if(isset($page)) echo $this->include($page) ?>
    
    <script src="<?php echo base_url('assets/js/jquery-3.6.4.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/custom/app.js') ?>"></script>
    <!-- Adding any additional javascript file passed by the Controller -->
    <?php if(isset($additionalScripts)){
      foreach($additionalScripts as $additionalScript){
        ?>
          <script src="<?= $additionalScript ?>"></script>
        <?php
      }
    } ?>
  </body>
</html>
