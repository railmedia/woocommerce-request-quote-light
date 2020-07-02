<?php
$files = array(
    'admin/views/admin.views.class.php',
    'admin/controllers/admin.class.php',
    'frontend/frontend.class.php',
    'frontend/rest.class.php'
);
foreach( $files as $file ) {
    require_once( $file );
}
?>
