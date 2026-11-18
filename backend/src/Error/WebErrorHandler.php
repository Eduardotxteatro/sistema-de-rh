<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Error\ErrorHandler;

class WebErrorHandler extends ErrorHandler
{
    public function handleError(
        int $code,
        string $description,
        ?string $file = null,
        ?int $line = null,
        ?array $context = null
    ): bool {
        // PHP 8 retains fatal error bits in error_reporting() under @.
        if ((error_reporting() & $code) === 0) {
            return false;
        }

        return parent::handleError($code, $description, $file, $line, $context);
    }
}
