<?php
$hash = password_hash("asd", PASSWORD_BCRYPT, ['cost' => 10]);
echo "Hashed password: " . $hash;
