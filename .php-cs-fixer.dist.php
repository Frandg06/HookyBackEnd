<?php

$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PSR2' => true,
        'single_quote' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)  // Esto buscará archivos en todo el proyecto
            ->exclude('vendor')  // No formatear los archivos dentro de 'vendor'
    )
;