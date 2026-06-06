<?php
// core/Validator.php

class Validator
{
    private array $errors = [];
    private array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    // ── Rules ────────────────────────────────────────────────────────────────

    public function required(string $field, string $label = ''): static
    {
        $label = $label ?: $field;
        if (empty(trim($this->data[$field] ?? ''))) {
            $this->errors[$field] = "$label est obligatoire.";
        }
        return $this;
    }

    public function email(string $field, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "$label doit être une adresse e-mail valide.";
        }
        return $this;
    }

    public function minLength(string $field, int $min, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "$label doit contenir au moins $min caractères.";
        }
        return $this;
    }

    public function maxLength(string $field, int $max, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "$label ne doit pas dépasser $max caractères.";
        }
        return $this;
    }

    public function numeric(string $field, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "$label doit être un nombre.";
        }
        return $this;
    }

    public function in(string $field, array $allowed, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $allowed)) {
            $this->errors[$field] = "$label must be one of: " . implode(', ', $allowed) . ".";
        }
        return $this;
    }
    public function fileTypes(string $field, array $allowed, string $label = ''): static
    {
        $label = $label ?: $field;
        if (empty($_FILES[$field]['name'][0])) return $this;

        $files = $_FILES[$field];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            if (!in_array($files['type'][$i], $allowed)) {
                $this->errors[$field] = "« {$files['name'][$i]} » : type non autorisé. ({$label} uniquement)";
                break;
            }
        }
        return $this;
    }

    public function fileMaxSize(string $field, int $maxBytes, string $label = ''): static
    {
        $label = $label ?: $field;
        if (empty($_FILES[$field]['name'][0])) return $this;

        $files = $_FILES[$field];
        $count = count($files['name']);
        $maxMb = round($maxBytes / 1024 / 1024);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            if ($files['size'][$i] > $maxBytes) {
                $this->errors[$field] = "« {$files['name'][$i]} » dépasse la taille maximale de {$maxMb} Mo.";
                break;
            }
        }
        return $this;
    }
    // ── Results ───────────────────────────────────────────────────────────────

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): string
    {
        return array_values($this->errors)[0] ?? '';
    }

    // ── Static shortcut ───────────────────────────────────────────────────────

    public static function make(array $data): static
    {
        return new static($data);
    }
}
