<?php

declare(strict_types=1);

namespace Auth\Domain;

final class EmailCanonicalizer
{
    public static function canonicalize(string $email): string
    {
        $canonicalEmail = strtolower($email);

        if (strpos($canonicalEmail, '@google.com') || strpos($canonicalEmail, '@gmail.com')) {
            $canonicalEmail = self::canonicalizeGoogleEmail($canonicalEmail);
        }

        return $canonicalEmail;
    }

    private static function canonicalizeGoogleEmail(string $googleEmail): string
    {
        list($localPart, $domain) = explode('@', $googleEmail, 2);
        $localPart = str_replace('.', '', $localPart);
        $localPart = strstr($localPart, '+', true) ?: $localPart;

        return $localPart . '@' . $domain;
    }
}