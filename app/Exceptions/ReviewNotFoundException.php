<?php

namespace App\Exceptions;


class ReviewNotFoundException extends \Exception {

    protected $message;

    protected $code = 404;


    public function __construct()
    {
        parent::__construct();

        $this->message = __('error.review.404');
    }
}
