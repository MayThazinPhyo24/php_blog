<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
    exit();
}

if($_SESSION['role'] != 1){
    header('Location: login.php');
    exit();
}

if(isset($_POST['search']) && $_POST['search'] != ''){
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
}else{
    if(empty($_GET['pageno'])){
        unset($_COOKIE['search']);
        setcookie('search', null, -1, '/');
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

          <div class="card-header">
            <h3 class="card-title">User Listing</h3>
          </div>

<?php

if(!empty($_GET['pageno'])){
    $pageno = $_GET['pageno'];
}else{
    $pageno = 1;
}

$numofRec = 5;
$offset = ($pageno - 1) * $numofRec;

if(empty($_POST['search']) && empty($_COOKIE['search'])){

    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
    $rawresult = $stmt->fetchAll();

    $total_pages = ceil(count($rawresult) / $numofRec);

    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numofRec");
    $stmt->execute();

    $result = $stmt->fetchAll();

}else{

    if(isset($_POST['search']) && $_POST['search'] != ''){
        $searchKey = $_POST['search'];
    }else{
        $searchKey = $_COOKIE['search'];
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :search ORDER BY id DESC");
    $stmt->execute([
        ':search' => "%$searchKey%"
    ]);

    $rawresult = $stmt->fetchAll();

    $total_pages = ceil(count($rawresult) / $numofRec);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :search ORDER BY id DESC LIMIT $offset,$numofRec");

    $stmt->execute([
        ':search' => "%$searchKey%"
    ]);

    $result = $stmt->fetchAll();
}

?>

          <div class="card-body">
            <a href="userAdd.php" type="button" class="btn btn-success">Create User</a>
          </div>

          <!-- /.card-header -->

          <div class="card-body">

            <table class="table table-bordered">

              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th style="width: 40px">Actions</th>
                </tr>
              </thead>

              <tbody>

              <?php
              if($result){

                  $i = 1;

                  foreach($result as $value){ ?>

                    <tr>

                      <td><?php echo $i; ?></td>

                      <td><?php echo escape( $value['name']); ?></td>

                      <td><?php echo escape($value['email']); ?></td>

                      <td>

                        <div class="btn-group">

                          <div class="container">
                            <a href="userEdit.php?id=<?php echo $value['id']; ?>"
                               type="button"
                               class="btn btn-warning">
                               Edit
                            </a>
                          </div>

                          <div class="container">
                            <a href="userDelete.php?id=<?php echo $value['id']; ?>"
                               onclick="return confirm('Are you sure you want to delete this item')"
                               type="button"
                               class="btn btn-danger">
                               Delete
                            </a>
                          </div>

                        </div>

                      </td>

                    </tr>

              <?php
                    $i++;
                  }
              }
              ?>

              </tbody>

            </table>

            <br>

            <nav aria-label="Page navigation example" style="float:right">

              <ul class="pagination">

                <li class="page-item">
                  <a class="page-link" href="?pageno=1">First</a>
                </li>

                <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">

                  <a class="page-link"
                     href="<?php
                     if($pageno <= 1){
                        echo '#';
                     }else{
                        echo '?pageno='.($pageno-1);
                     }
                     ?>">
                     Previous
                  </a>

                </li>

                <li class="page-item">
                  <a class="page-link" href="#">
                    <?php echo $pageno; ?>
                  </a>
                </li>

                <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">

                  <a class="page-link"
                     href="<?php
                     if($pageno >= $total_pages){
                        echo '#';
                     }else{
                        echo '?pageno='.($pageno+1);
                     }
                     ?>">
                     Next
                  </a>

                </li>

                <li class="page-item">
                  <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">
                    Last
                  </a>
                </li>

              </ul>

            </nav>

          </div>

          <!-- /.card-body -->

        </div>

        <!-- /.row -->

      </div>

      <!-- /.container-fluid -->

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