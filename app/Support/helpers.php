<?php

if (!function_exists('statusKuota')) {
    function statusKuota($kuota)
    {
        if ($kuota >= 75) {
            return 'Besar';
        }

        return 'Terbatas';
    }
}