<?php

declare(strict_types=1);

namespace AbmmHasan\InterMix\DI\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Ink
{
    private array|string|null $data = null;
    private string $type = 'name';

    public function __construct(...$parameters)
    {
        if (!empty($parameters)) {
            $type = array_key_first($parameters);
            $this->data = $parameters[$type];

            if (!is_int($type)) {
                $this->type = $type;
            }
        }
    }

    /**
     * Get resource of the entry to inject
     *
     * @param string|null $key
     * @return array|string|null
     */
    public function getData(string $key = null): mixed
    {
        $returnable = [
            'type' => $this->type,
            'data' => $this->data
        ];
        return $key ? ($returnable[$key] ?? null) : $returnable;
    }
}
