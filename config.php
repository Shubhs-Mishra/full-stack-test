<?php

function loadDotEnvFile(string $filePath): void
{
    if (!is_readable($filePath)) {
        return;
    }

    $fileContents = file_get_contents($filePath);
    $lines = preg_split('/\r\n|\n|\r/', $fileContents);

    foreach ($lines as $line) {
        $trimmedLine = trim($line);
        if ($trimmedLine === '' || str_starts_with($trimmedLine, '#')) {
            continue;
        }

        if (!str_contains($trimmedLine, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $trimmedLine, 2);
        $name = trim($name);
        $value = trim($value);

        if ($value !== '' && str_starts_with($value, '"') && str_ends_with($value, '"')) {
            $value = stripcslashes(substr($value, 1, -1));
        }

        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

loadDotEnvFile(__DIR__ . '/.env');

function getEnvironmentValue(string $key, string $default = ''): string
{
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }

    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
}

function getDatabaseDsn(): string
{
    return sprintf(
        '%s:host=%s;port=%s;dbname=%s;charset=%s',
        getEnvironmentValue('DB_CONNECTION', 'mysql'),
        getEnvironmentValue('DB_HOST', '127.0.0.1'),
        getEnvironmentValue('DB_PORT', '3306'),
        getEnvironmentValue('DB_DATABASE', 'wpoets_test'),
        getEnvironmentValue('DB_CHARSET', 'utf8mb4')
    );
}

function getDatabaseUser(): string
{
    return getEnvironmentValue('DB_USERNAME', 'root');
}

function getDatabasePassword(): string
{
    return getEnvironmentValue('DB_PASSWORD', '');
}
