<?php
  session_start();
  require '../config/config.php';
  require '../config/common.php';
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  if($_POST){
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['role']) || strlen($_POST['password']) <4 ){
      if(empty($_POST['name'])){
        $nameError = "Name cannot be null";
      }
      if(empty($_POST['email'])){
        $emailError = "Email cannot be null";
      }
      if(empty($_POST['password'])){
        $passwordError = "Password cannot be null";
      }
      if(strlen($_POST['password']) <4 ){
        $passwordError = "Password should be at least 4 characters";
      }
      if(empty($_POST['role'])){
        $roleError = "Role cannot be null";
      }
      }else{
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $stmt = $pdo->prepare(
          "INSERT INTO users(name,email,password,role)
          VALUES(:name,:email,:password,:role)"
        );

        $result = $stmt->execute(
          array(
            ':name'=>$name,
            ':email'=>$email,
            ':password'=>$password,
            ':role'=>$role
          )
        );

        if($result){
          echo "<script>alert('Successfully Created');window.location.href='user.php';</script>";
        } 
      } 
}
  
?>
<?php
      include('header.php');
?>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->

    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <form class="" action="userAdd.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
                    <div class="form-group">
                        <label for="">Name</label><p style='color:red'><?php echo empty($nameError) ? '':$nameError; ?></p>
                        <input type="text" class="form-control" name="name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label><p style='color:red'><?php echo empty($emailError) ? '':$emailError; ?></p>
                        <input type="email" class="form-control" name="email" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label><p style='color:red'><?php echo empty($passwordError) ? '':$passwordError; ?></p>
                        <input type="password" class="form-control" name="password" value="" required>
                    </div>
                    <div class="form-group">
                            <label for="">Role</label><p style='color:red'><?php echo empty($roleError) ? '':$roleError; ?></p>

                            <select name="role" class="form-control" required>
                                <option value="">Choose Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-success"name="" value="SUBMIT">
                        <a href="user.php" class="btn btn-warning">Back</a>
                    </div>

                </form>
                </div>
            </div> 
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <?php 
   include('footer.html');
  ?>
