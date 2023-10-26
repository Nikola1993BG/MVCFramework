<?php

namespace App\Core;

class View{

    /**
     * Renders a Twig template with given arguments.
     *
     * @param string|\Twig\TemplateWrapper $template The path to the Twig template or a Twig TemplateWrapper object.
     * @param array $args An associative array of variables to be passed to the template.
     * @return void
     */
    public static function renderTwig(string|\Twig\TemplateWrapper $template, $args=[]) : void
    {

        $loader = new \Twig\Loader\FilesystemLoader('../src/Views');
        $twig = new \Twig\Environment($loader);

        echo $twig->render($template, $args);

    }
   
}