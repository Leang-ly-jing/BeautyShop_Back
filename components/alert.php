<?php
if (isset($success_msg) && is_array($success_msg)) {
    foreach ($success_msg as $msg) {
        echo '<script>swal("' . htmlspecialchars($msg) . '", "", "success");</script>';
    }
}
if (isset($warning_msg) && is_array($warning_msg)) {
    foreach ($warning_msg as $msg) {
        echo '<script>swal("' . htmlspecialchars($msg) . '", "", "warning");</script>';
    }
}
if (isset($info_msg) && is_array($info_msg)) {
    foreach ($info_msg as $msg) {
        echo '<script>swal("' . htmlspecialchars($msg) . '", "", "info");</script>';
    }
}
if (isset($error_msg) && is_array($error_msg)) {
    foreach ($error_msg as $msg) {
        echo '<script>swal("' . htmlspecialchars($msg) . '", "", "error");</script>';
    }
}
?>
