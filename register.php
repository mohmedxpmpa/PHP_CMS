<?php
require 'includes/init.php';
$conn = require_once "includes/db.php";

if (isset($_REQUEST['btn_register'])) //button name "btn_register"
{
    $username = htmlspecialchars($_REQUEST['txt_username']); //textbox name "txt_UserName"
    $password = htmlspecialchars($_REQUEST['txt_password']); //textbox name "txt_password"

    if (empty($username)) {
        $errorMsg[] = "Please enter username"; //check username textbox not empty 
    } else if (empty($password)) {
        $errorMsg[] = "Please enter password"; //check passowrd textbox not empty
    } else if (strlen($password) < 6) {
        $errorMsg[] = "Password must be atleast 6 characters"; //check passowrd must be 6 characters
    } else {
        try {
            $stmt = $conn->prepare("SELECT username FROM user 
          WHERE username=:username"); // SQL select query

            $stmt->execute(array(':username' => $username)); //execute query 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row["username"] == $username) {
                $errorMsg[] = "Sorry username already exists"; //check condition username already exists 
            } else if (!isset($errorMsg)) //check no "$errorMsg" show then continue
            {
                $new_password = password_hash($password, PASSWORD_DEFAULT); //encrypt password using password_hash()

                $insert_stmt = $conn->prepare("INSERT INTO user (username,password) VALUES
                (:username,:password)");   //SQL insert query     

                if ($insert_stmt->execute(array(
                    ':username' => $username,
                    ':password' => $new_password
                ))) {

                    $registerMsg = "Register Successfully..... Please Click On Login Account Link"; //execute query success message
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>


<!--       The HTML form      -->

<?php require 'includes/header.php'; ?>

<?php
if (isset($errorMsg)) {
    foreach ($errorMsg as $error) {
?>
        <div class="alert alert-danger">
            <strong>WRONG ! <?php echo $error; ?></strong>
        </div>
    <?php
    }
}
if (isset($registerMsg)) {
    ?>
    <div class="alert alert-success">
        <strong><?php echo $registerMsg; ?></strong>
    </div>
<?php
}
?>
<form method="post" class="form-horizontal">

    <div class="form-group">
        <label class="col-sm-3 control-label">Username</label>
        <div class="col-sm-6">
            <input type="text" name="txt_username" class="form-control" placeholder="Enter Username" />
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">Password</label>
        <div class="col-sm-6">
            <input type="password" name="txt_password" class="form-control" placeholder="Enter Passowrd" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9 m-t-15">
            <input type="submit" name="btn_register" class="btn btn-primary " value="Register">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9 m-t-15">
            You have a account register here? <a href="index.php">
                <p class="text-info">Login Account</p>
            </a>
        </div>
    </div>

</form>

<?php require 'includes/footer.php'; ?>