<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location:login.php');
    exit();
}

/* ================= UPDATE PART ================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT) ?? '';
    $role = $_POST['role'] ?? '';

    // Only attempt update if we actually have an ID
    if (!empty($id)) {
        // Fix: Added missing comma, fixed structure, and secured using placeholders
        $stmt = $pdo->prepare("
            UPDATE users 
            SET name = :name,
                email = :email,
                password = :password,
                role = :role
            WHERE id = :id
        ");

        $result = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password, // Note: You should ideally hash this using password_hash()
            ':role' => $role,
            ':id' => $id
        ]);

        if ($result) {
            echo "<script>alert('Successfully Updated');window.location.href='user.php';</script>";
            exit();
        }
    }
}

/* ================= SELECT PART ================= */

$getid = $_GET['id'] ?? 0;

// Secured this query against SQL Injection too
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $getid]);
$result = $stmt->fetchAll();

// Fallback empty array structure if user isn't found to prevent frontend errors
$user = $result[0] ?? ['id' => '', 'name' => '', 'email' => '', 'password' => '', 'role' => ''];

?>

<?php include('header.php'); ?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <form action="userEdit.php?id=<?php echo htmlspecialchars($getid); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" >
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"> 
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" value="<?php echo htmlspecialchars($user['password']); ?>">   
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" class="form-control" >
                                    <option value="">Choose Role</option>
                                    <!-- Fix: Correctly handling selected attribute dynamically -->
                                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="SUBMIT">
                                <a href="index.php" class="btn btn-warning">Back</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.html'); ?>