<?php

namespace AbmmHasan\InterMix\DI\Invoker;

use AbmmHasan\InterMix\DI\Resolver\Repository;
use Closure;
use Exception;
use Error;

final class GenericCall
{
    /**
     * @param Repository $repository
     */
    public function __construct(
        private Repository $repository
    ) {
    }

    /**
     * Call the class
     *
     * @param string $class
     * @param string|null $method
     * @return array
     */
    public function classSettler(string $class, string $method = null): array
    {
        $asset = [
            'instance' => $instance = new $class(
                ...($this->repository->classResource[$class]['constructor']['params'] ?? [])
            ),
            'returned' => null
        ];

        if (!empty($this->repository->classResource[$class]['property'])) {
            foreach ($this->repository->classResource[$class]['property'] as $item => $value) {
                try {
                    $instance->$item = $value;
                } catch (Exception|Error $e) {
                    $class::$$item = $value;
                }
            }
        }

        $method ??= $this->repository->classResource[$class]['method']['on']
            ?? $this->repository->defaultMethod;

        if (!empty($method) && method_exists($instance, $method)) {
            $asset['returned'] = $instance->$method(
                ...($this->repository->classResource[$class]['method']['params'] ?? [])
            );
        }

        return $asset;
    }

    /**
     * call the closure
     *
     * @param string|Closure $closure
     * @param array $params
     * @return mixed
     */
    public function closureSettler(string|Closure $closure, array $params): mixed
    {
        return $closure(...$params);
    }
}
