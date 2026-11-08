<?php

const TIPOS_CRAFT = [
    "bancada" => 9,
    "ferraria" => 3,
    "fornalha" => 2,
    "pocao" => 4
];

function normaliza_tipo_craft($tipo) {
    if (!is_string($tipo)) {
        return null;
    }
    return isset(TIPOS_CRAFT[$tipo]) ? $tipo : null;
}
