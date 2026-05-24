<?php
  session_start();
  require 'config/config.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
    exit();
  }

  if(isset($_GET['id'])) {

    $blogId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
    $stmt->execute([
      ':id' => $blogId
    ]);

    $result = $stmt->fetchAll();

    if(!$result){
      echo "Post not found";
      exit();
    }

  } else {

    echo "Post ID missing.";
    exit();

  }

  $stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=:post_id ORDER BY id DESC");

  $stmtcmt->execute([
    ':post_id' => $blogId
  ]);

  $cmtResult = $stmtcmt->fetchAll();

  if($_POST){

    $comment = $_POST['comment'];

    $stmt = $pdo->prepare("INSERT INTO comments (content, author_id, post_id) VALUES (:content, :author_id, :post_id)");

    $insertResult = $stmt->execute(
      array(
        ':content'=>$comment,
        ':author_id'=>$_SESSION['user_id'],
        ':post_id'=>$blogId
      )
    );

    if($insertResult){
      header('Location: blogdetail.php?id='.$blogId);
      exit();
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog Site</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href="../dist/css/adminlte.min.css">

  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">

<div class="content-wrapper" style="margin-left:0px !important">

  <section class="content">

    <div class="row">

      <div class="col-md-12">

        <div class="card card-widget">

          <div class="card-header">

            <div class="card-title" style="text-align:center !important;float:none">

              <h4><?php echo $result[0]['title']?></h4>

            </div>

          </div>

          <div class="card-body">

            <img class="img-fluid pad"
              src="admin/images/<?php echo $result[0]['images'];?>">

            <br>

            <p><?php echo $result[0]['content']?></p>

            <h3>Comments</h3>
            <hr>

            <a href="index.php" type="button" class="btn btn-default">
              Go Back
            </a>

          </div>

          <div class="card-footer card-comments">

            <?php
              if($cmtResult){

                foreach($cmtResult as $comment){

                  $stmtau = $pdo->prepare("SELECT * FROM users WHERE id=:id");

                  $stmtau->execute([
                    ':id' => $comment['author_id']
                  ]);

                  $auResult = $stmtau->fetch(PDO::FETCH_ASSOC);
            ?>

            <div class="card-comment">

              <div class="comment-text" style="margin-left:0px !important">

                <span class="username">

                  <?php echo $auResult['name'];?>

                  <span class="text-muted float-right">
                    <?php echo $comment['created_at'];?>
                  </span>

                </span>

                <?php echo $comment['content'];?>

              </div>

            </div>

            <?php
                }
              }else{
                echo "<p>No comments yet.</p>";
              }
            ?>

          </div>

          <div class="card-footer">

            <form action="" method="post">

              <div class="img-push">

                <input
                  type="text"
                  name="comment"
                  class="form-control form-control-sm"
                  placeholder="Press enter to post comment"
                >

              </div>

            </form>

          </div>

        </div>

      </div>

    </div>

  </section>

</div>

<a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
  <i class="fas fa-chevron-up"></i>
</a>

<footer class="main-footer" style="margin-left:0px !important; ">

  <div class="float-right d-none d-sm-inline">

    <a href="logout.php" type="button" class="btn btn-default">
      LogOut
    </a>

  </div>

  <strong>
    Copyright &copy; 2026
    <a href="https://adminlte.io">A Programmer</a>.
  </strong>

  All rights reserved.

</footer>

<aside class="control-sidebar control-sidebar-dark">
</aside>

<script src="../plugins/jquery/jquery.min.js"></script>

<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../dist/js/adminlte.min.js"></script>

<script src="../dist/js/demo.js"></script>

</body>
</html>