<!DOCTYPE html>
<?php
require_once("../config/constants.php");
//require_once("../utils/ensure_session.php");
//require_once("../da/data_access.php");
require_once('../service/data_service.php');

$navMarkup = "";
$navMarkup = $navMarkup . "<li><a href=\"" . APPLICATION_ROOT . "/views/home.php\">Tasks</a></li>";
$navMarkup = $navMarkup . "<li><a href=\"" . APPLICATION_ROOT . "/controller/auth.php\">Logout</a></li>";
require_once("header.php");
//session_start();
$userId = $_SESSION[CURRENT_USER];
if (!isset($_SESSION["taskId"])) {
    redirect(VIEWS . "/home.php");
}

$taskId = $_SESSION["taskId"];
$task = get_todo($taskId);
print_r($task);
unset($_SESSION["taskId"]);

?>
<div class="container">
<form action="<?php echo CONTROLLER ?>/todo.php" method="POST">
<div class="row" style="margin-top:20px">
    <div class="col-xs-12">
        <h4>Update Task</h4>
    </div>                
</div>
<div class="clearfix" />
<div class="row">
    <div class="col-xs-2">
        <label>Description</label>
    </div>
    <div class="col-xs-10">
        <input type="hidden" name="taskId" value="<?php echo $taskId; ?>" />
        <input type="text" name="description" size="100" value="<?php echo $task["desc"] ?>" />
    </div>
</div>
<div class="clearfix" />
<div class="row" style="margin-top: 5px">
    <div class="col-xs-2">
        <label>Status</label>
    </div>
    <div class="col-xs-10">
        <select name="status">
            <option value="Not Started" <?php if ($task["status"] == "Not Started") echo "selected"; ?>>Not Started</option>
            <option value="Started" <?php if ($task["status"] == "Started") echo "selected"; ?>>Started</option>
            <option value="Midway" <?php if ($task["status"] == "Midway") echo "selected"; ?>>Midway</option>
            <option value="Complete" <?php if ($task["status"] == "Complete") echo "selected"; ?>>Complete</option>
        </select>
        <input type="submit" name="action" value="Update" />
    </div>
</div>
<div class="clearfix" />
</form>
</div><!-- /.container -->
<?php
require_once("footer.php");
?>
