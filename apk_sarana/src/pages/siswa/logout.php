<?php

route('POST', '/siswa/logout', function () {
    require_siswa();
    csrf_abort();
    
    session_start_safe();
    unset($_SESSION['siswa']);
    
    session_flash('status', 'Anda telah berhasil log out.');
    redirect('/siswa/login');
});
