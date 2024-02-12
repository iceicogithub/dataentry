<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
      'store_new_act',
      'update_main_act/*',
      'store_act/*',
      'update_all_section/*',
      'add_new_section',
      'edit-section/*',
      'update_all_regulation/*',
      'update_all_rule/*',
      'add_new_rule',
      'update_all_article/*',
      'add_new_article',
    ];
}
