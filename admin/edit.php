<?php
session_start();
require '../config/config.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location:login.php');
    exit();
}

/* ================= UPDATE PART ================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if ($id != null) {

        // IMAGE EXISTS
        if (!empty($_FILES['image']['name'])) {

            $file = 'images/' . $_FILES['image']['name'];
            $imageType = pathinfo($file, PATHINFO_EXTENSION);

            if (
                $imageType != 'png' &&
                $imageType != 'jpg' &&
                $imageType != 'jpeg'
            ) {

                echo "<script>alert('Image must be png, jpg, jpeg')</script>";

            } else {

                move_uploaded_file($_FILES['image']['tmp_name'], $file);

                $image = $_FILES['image']['name'];

                // IMPORTANT:
                // If your database column name is "images"
                // keep images='$image'
                // If your column name is "image"
                // change it to image='$image'

                $stmt = $pdo->prepare("
                    UPDATE posts 
                    SET title='$title',
                        content='$content',
                        images='$image'
                    WHERE id='$id'
                ");

                $result = $stmt->execute();
            }

        } else {

            $stmt = $pdo->prepare("
                UPDATE posts 
                SET title='$title',
                    content='$content'
                WHERE id='$id'
            ");

            $result = $stmt->execute();
        }

        if (!empty($result)) {
            echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
        }
    }
}

/* ================= SELECT PART ================= */

$getid = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id='$getid'");
$stmt->execute();

$result = $stmt->fetchAll();

?>

<?php include('header.php'); ?>

<div class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">

                        <form action="edit.php?id=<?php echo $getid; ?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">

                                <input type="hidden" name="id"
                                    value="<?php echo $result[0]['id'] ?? ''; ?>">

                                <label>Title</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="title"
                                    value="<?php echo $result[0]['title'] ?? ''; ?>"
                                    required>

                            </div>

                            <div class="form-group">

                                <label>Content</label><br>

                                <textarea
                                    class="form-control"
                                    name="content"
                                    rows="8"
                                    cols="80"><?php echo $result[0]['content'] ?? ''; ?></textarea>

                            </div>

                            <div class="form-group">

                                <label>Image</label><br>

                                <?php if (!empty($result[0]['images'])) : ?>

                                    <img
                                        src="images/<?php echo $result[0]['images']; ?>"
                                        width="150"
                                        height="150"
                                        alt=""><br><br>

                                <?php endif; ?>

                                <input type="file" name="image">

                            </div>

                            <div class="form-group">

                                <input
                                    type="submit"
                                    class="btn btn-success"
                                    value="SUBMIT">

                                <a href="index.php" class="btn btn-warning">
                                    Back
                                </a>

                            </div>

                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include('footer.html'); ?>