<?php

// src/Service/TokenGenerator.php
namespace App\Service;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;

class TokenGenerator
{
private $jwtManager;

public function __construct(JWTManager $jwtManager)
{
$this->jwtManager = $jwtManager;
}

public function generateToken(User $user): string
{
// Utilisez le JWTManager pour générer le token
$payload = ['email' => $user->getEmail()]; // Ajoutez d'autres données si nécessaire
return $this->jwtManager->create($user, $payload);
}
}