<?php

function view(string $view, array $data = [])
{
    $data['currentPath'] = parse_url(
        $_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    );

    extract($data);

    require __DIR__ . '/../Views/layouts/main.php';
}

function chunkText(string $content): array
{
     $paragraphs = preg_split(
        '/\n\s*\n/',
        trim($content)
    );

    $chunks = [];

    foreach ($paragraphs as $paragraph) {

        $paragraph = trim($paragraph);

        if (strlen($paragraph) <= 1000) {

            $chunks[] = $paragraph;

            continue;
        }

        $parts = str_split($paragraph, 1000);

        foreach ($parts as $part) {
            $chunks[] = trim($part);
        }
    }

    return array_values(
        array_filter($chunks)
    );
}
