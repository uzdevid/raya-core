<?php declare(strict_types=1);

namespace App\Exception;

use Exception;

class UnauthorizedException extends Exception implements ClientException { }
