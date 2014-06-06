<?php header('X-UA-Compatible: IE=edge'); ?>
<!DOCTYPE html>

<!--
Template Name: Admin Lab Dashboard build with Bootstrap v2.3.1
Template Version: 1.2
Author: Mosaddek Hossain
Website: http://thevectorlab.net/
-->

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login page</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  <link href="<?=base_url();?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?=base_url();?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="<?=base_url();?>css/style.css" rel="stylesheet" />
  <link href="<?=base_url();?>css/style_responsive.css" rel="stylesheet" />
  <link href="<?=base_url();?>css/style_default.css" rel="stylesheet" id="style_color" />
  <link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/uniform/css/uniform.default.css">
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body id="login-body">
  <div class="login-header">
      <!-- BEGIN LOGO -->
      <div id="logo2" class="center">
          <img src="<?=base_url();?>img/logo-login.png" alt="logo" class="center" />
      </div>
      <!-- END LOGO -->
  </div>
  <!-- BEGIN LOGIN -->
  <div id="login">
    <!-- BEGIN LOGIN FORM -->
    <form action="<?=site_url();?>/user/login" method="post" class="form-vertical no-padding no-margin" id="loginform">
      <div class="lock">
          <i class="icon-lock"></i>
      </div>
      <div class="control-wrap">
          <h4>Login
		  <label class="radio inline"><input name="status" type="radio" value="user" checked="checked"/>一般使用者</label>
		  <label class="radio inline"><input name="status" type="radio" value="admin"/>中心管理員</label>
		  </h4>
		  
		  
          <div class="control-group">
              <div class="controls">
                  <div class="input-prepend">
                      <span class="add-on"><i class="icon-user"></i></span><input name="ID" type="text" id="input-username" placeholder="Username" />
                  </div>
              </div>
          </div>
          <div class="control-group">
              <div class="controls">
                  <div class="input-prepend">
                      <span class="add-on"><i class="icon-key"></i></span><input name="passwd" type="password" id="input-password" placeholder="Password" />
                  </div>
                  <div class="mtop10">
                      <div class="block-hint pull-left small">
                          <!--<input type="checkbox" id=""> Remember Me-->
						  <a href="<?=site_url();?>/user/form">註冊新帳號</a>
                      </div>
                      <div class="block-hint pull-right">
                          <a href="javascript:;" class="" id="forget-password">忘記密碼?</a>
                      </div>
                  </div>

                  <div class="clearfix space5"></div>

              </div>

          </div>
      </div>

      <input type="submit" id="login-btn" class="btn btn-block login-btn" value="Login" />
    </form>
    <!-- END LOGIN FORM -->   
	<!-- BEGIN FORGOT PASSWORD FORM -->
    <form id="forgotform" class="form-vertical no-padding no-margin hide" action="<?=site_url();?>/user/forget">
      <p class="center">請輸入您註冊時所填寫的 E-mail 地址</p>
      <div class="control-group">
        <div class="controls">
          <div class="input-prepend">
            <span class="add-on"><i class="icon-envelope"></i></span><input id="input-email" type="text" placeholder="Email"  />
          </div>
        </div>
        <div class="space20"></div>
      </div>
      <input type="button" id="forget-btn" class="btn btn-block login-btn" value="Submit" />
    </form>
    <!-- END FORGOT PASSWORD FORM -->
     
  </div>
  <!-- END LOGIN -->
  <!-- BEGIN COPYRIGHT -->
  <div id="login-copyright">
      2013 &copy; CMNST
  </div>
  <!-- END COPYRIGHT -->
  <!-- BEGIN JAVASCRIPTS -->
  <script src="<?=base_url();?>js/jquery-1.8.3.min.js"></script>
  <script src="<?=base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?=base_url();?>js/jquery.blockui.js"></script>
  <script type="text/javascript" src="<?=base_url();?>assets/uniform/jquery.uniform.min.js"></script>
  <script src="<?=base_url();?>js/scripts.js"></script>
  <script>
    jQuery(document).ready(function() {     
      App.initLogin();
    });
  </script>
  <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
