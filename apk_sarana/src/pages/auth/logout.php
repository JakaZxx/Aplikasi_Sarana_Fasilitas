<?php
route('POST', '/logout', function () {
    require_auth();
    csrf_abort();

    session_start_safe();
    session_unset();
    session_destroy();

    redirect('/');
});
