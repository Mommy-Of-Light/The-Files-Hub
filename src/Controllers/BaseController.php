<?php

namespace TheFileHub\Controllers;

use TheFileHub\Services\UserService;
use Slim\Views\PhpRenderer;

abstract class BaseController
{
    /**
     * @var PhpRenderer
     */
    protected PhpRenderer $view;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->view = new PhpRenderer(__DIR__ . '/../../views', [
            'title' => 'The TheFileHub',
            'withMenu' => true,
        ]);

        $this->view->setLayout("layout.php");
    }
}
