<div class="login-container">
  <div class="login-block">
    <div class="logo-container">
      <?php echo \Asset::img('logo-login.png')?>
    </div>
    <div class="form-container">
      <form method="POST" action="<?php echo \Uri::create('store/auth/login')?>">
        <?php if(!empty($errors)):?>
              <?php foreach($errors as $error):?>
                <span class="help-block" style="color: red"><?php echo $error;?><span>
              <?php endforeach;?>
        <?php endif;?>
        <div class="form-group">
          <input type="text" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div class="">
          <button class="btn btn-default btn-primary">Send</button>
        </div><hr>
        <div class="form-group forget-password">
          <a href="#">パスワードを忘れる、ここをクリック</a>
        </div>
      </form>
    </div>
  </div>
</div>