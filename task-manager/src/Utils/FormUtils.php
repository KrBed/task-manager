<?php

namespace App\Utils;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class FormUtils
{

    /**
     * Metoda generuje unikalny token antyduplikacyjny dla formularza.
     * Ten token jest przechowywany w sesji użytkownika,
     * aby zapobiec wielokrotnemu przesyłaniu tego samego formularza.
     * @param Request $request
     * @return string
     */
    public static function generateAntiDuplicateToken(Request $request): string
    {
        $session = $request->getSession();

        if (!$session->has('form_token')) {
            $token = Uuid::uuid4()->toString();
            $session->set('form_token', $token);
        } else {
            $token = $session->get('form_token');
        }

        return $token;
    }
}