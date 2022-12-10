<?php

declare(strict_types=1);

namespace Redislabs\Module\RedisJson\Command;

use Redislabs\Interfaces\CommandInterface;
use Redislabs\Command\CommandAbstract;
use Redislabs\Module\RedisJson\Path;

final class ObjectKeys extends CommandAbstract implements CommandInterface
{
    protected static $command = 'JSON.OBJKEYS';

    private function __construct(string $key, Path $path)
    {
        $this->arguments = [$key,  $path->getPath()];
        $this->responseCallback = static function ($result) use ($path) {
            if (!empty($result)) {
                if ($path->isLegacyPath() === false && (is_countable($result) ? count($result) : 0) === 1) {
                    return $result[0][0];
                }
                if ($path->isLegacyPath() === false && (is_countable($result) ? count($result) : 0) > 1) {
                    return $result;
                }
                return $result;
            }
            return null;
        };
    }

    public static function createCommandWithArguments(string $key, string $path): CommandInterface
    {
        return new self(
            $key,
            new Path($path)
        );
    }
}
