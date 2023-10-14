<?php

declare(strict_types=1);

namespace axy\posix;

class PosixResourceLimits
{
    public readonly PosixResourceListLimits $soft;
    public readonly PosixResourceListLimits $hard;

    public function __construct(public readonly array $data)
    {
        $parts = [
            'hard' => [],
            'soft' => [],
        ];
        foreach ($this->data as $k => $v) {
            $k = explode(' ', $k, 2);
            if (count($k) !== 2) {
                continue;
            }
            if (isset($parts[$k[0]])) {
                $parts[$k[0]][$k[1]] = $v;
            }
        }
        $this->soft = new PosixResourceListLimits('soft', $parts['soft']);
        $this->hard = new PosixResourceListLimits('hard', $parts['hard']);
    }
}
