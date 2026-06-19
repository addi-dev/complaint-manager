<?php
class Helpers
{
    public static function generer_motdePasse(): string
    {
        $lettres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $chiffres = '0123456789';

        $partie_lettres = '';
        for ($i = 0; $i < 4; $i++) {
            $partie_lettres .= $lettres[random_int(0, strlen($lettres) - 1)];
        }

        $partie_chiffres = '';
        for ($i = 0; $i < 4; $i++) {
            $partie_chiffres .= $chiffres[random_int(0, 9)];
        }

        return $partie_lettres . $partie_chiffres;
    }
}
