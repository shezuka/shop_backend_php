<?php

namespace App\Services;

class Hasher
{
    private string $alg;
    private array $lastUpdateValues = [];
    private $context = null;

    public function __construct(string $alg)
    {
        $this->alg = $alg;
    }

    public function update($value)
    {
        if (!isset($this->context)) {
            $this->context = hash_init($this->alg);
        }
        array_push($this->lastUpdateValues, $value);
        hash_update($this->context, $value);
    }

    public function digest()
    {
        $context_copy = $this->create_copy_context();
        return hash_final($context_copy, true);
    }

    public function hexdigest()
    {
        return bin2hex($this->digest());
    }

    public function close($binary = false) {
        $value = hash_final($this->context, $binary);
        $this->context = null;
        $this->lastUpdateValues = [];
        return $value;
    }

    private function create_copy_context()
    {
        $copy = hash_init($this->alg);
        for ($i = 0; $i < count($this->lastUpdateValues); ++$i) {
            hash_update($copy, $this->lastUpdateValues[$i]);
        }
        return $copy;
    }
}

class CustomHasher
{
    public function hash($value)
    {
        $sha256 = new Hasher('sha256');
        $sha256->update($value);

        $md5 = new Hasher('md5');
        $md5->update($sha256->digest());
        for ($i = 0; $i < 128; ++$i) {
            $md5->update($md5->digest());
        }
        $sha256->update($md5->close(true));
        return $sha256->close();
    }

    public function check($value, $hashedValue)
    {
        return $this->hash($value) === $hashedValue;
    }
}
