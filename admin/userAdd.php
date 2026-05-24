<?php
  session_start();
  require '../config/config.php';
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  if($_POST){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare(
      "INSERT INTO users(name,email,role)
       VALUES(:name,:email,:role)"
    );

    $result = $stmt->execute(
      array(
        ':name'=>$name,
        ':email'=>$email,
        ':role'=>$role
      )
    );

    if($result){
      echo "<script>alert('Successfully Created');window.location.href='user.php';</script>";
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
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" name="name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label><br>
                        <input type="email" class="form-control" name="email" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label><br>
                        <input type="password" class="form-control" name="password" value="" required>
                    </div>
                    <div class="form-group">
                            <label for="">Role</label>

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
