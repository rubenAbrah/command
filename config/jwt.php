<?php

return [
    'secret' => env('JWT_SECRET', '8Zz5tw0Ionm3XPZZfN0NOml3z9FMfmpgXwovR9fp6ryDIoGRM8EPHAB6iHsc0fb'),
    'algo' => 'HS256',
    'issuer' => 'game-auth-server',
    'expire' => 3600, // 1 час
];