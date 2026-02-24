<?php

// config for ZarulIzham/Fail2Ban
return [
    'fail2ban_client_path' => env('FAIL2BAN_CLIENT_PATH', 'fail2ban-client'),
    'use_sudo' => env('FAIL2BAN_USE_SUDO', true),
    'sudo_path' => env('FAIL2BAN_SUDO_PATH', 'sudo'),
];
