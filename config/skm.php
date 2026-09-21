<?php

return [

    /*
    |--------------------------------------------------------------------
    | Link Survei SKM Eksternal
    |--------------------------------------------------------------------
    |
    | Fitur pengisian survei kepuasan masyarakat (SKM) secara internal
    | sudah dimatikan (per koordinasi dengan tim lain, supaya datanya
    | satu pintu). Semua titik pengisian survei di aplikasi ini
    | (per-tiket, per-layanan pemohon, maupun survei umum publik)
    | diarahkan ke link SKM resmi di bawah ini.
    |
    */

    'external_url' => env('SKM_EXTERNAL_URL', 'https://gandem.id/page/survey/skm/'),

];
