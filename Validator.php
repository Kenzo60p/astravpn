<?php

namespace App\Core;

class Validator
{
    protected array $errors = [];

    public function required(string $key, $value, string $message): self
    {
        if ($value === null || $value === '' || (is_array($value) && empty($value))) {
            $this->errors[$key] = $message;
        }

        return $this;
    }

    public function email(string $key, $value, string $message): self
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$key] = $message;
        }

        return $this;
    }

    public function minLength(string $key, string $value, int $length, string $message): self
    {
        if (mb_strlen($value) < $length) {
            $this->errors[$key] = $message;
        }

        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
