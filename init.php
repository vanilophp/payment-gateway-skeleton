<?php

const COPY_FILES = ['.gitignore', '.styleci.yml', 'composer.json', 'LICENSE', 'phpunit.xml.dist'];
const COPY_FOLDERS = ['src', 'docs', 'tests'];
const SKEL_MAP = [
    'README.md' => 'README.md',
    'tests.yml' => '.github/workflows/tests.yml'
];

class Configuration
{
    public $author;
    public $gateway;
    public $namespace;
    public $pkgVendor;
    public $pkgName;
    public $githubPath;
    public $styleCI;
    public $directory;

    public $gatewayStudly;
    public $gatewaySlug;
}

function ask($question, $example, $explanation, $echo = null): string
{

    echo "╾───────────────────────────────────────────────────╼\n";
    echo "(i) $explanation\n";

    $answer = readline("$question (eg. $example): ");
    if (null !== $echo) {
        echo sprintf("$echo\n", $answer);
    }

    return $answer;
}

function studly($str): string
{
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $str)));
}

function slug($str, $separator = '-'): string
{
    $flip = $separator === '-' ? '_' : '-';
    $str = preg_replace('!['.preg_quote($flip).']+!u', $separator, $str);
    $str = str_replace('@', $separator.'at'.$separator, $str);
    $str = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($str));
    $str = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $str);

    return trim($str, $separator);
}

function starts_with($haystack, $needles)
{
    foreach ((array) $needles as $needle) {
        if ((string) $needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0) {
            return true;
        }
    }

    return false;
}

function summarize(Configuration $cfg)
{
    echo "\n\n";
    echo "> Payment Gateway Code will be generated with the following data:\n";
    echo "┌─────────────────────────────────────────────────────────────────────┐\n";
    echo '│ Author     : ' . str_pad($cfg->author, 55) . "│\n";
    echo '│ Gateway    : ' . str_pad($cfg->gateway, 55) . "│\n";
    echo '│ Namespace  : ' . str_pad($cfg->namespace, 55) . "│\n";
    echo '│ Package    : ' . str_pad("$cfg->pkgVendor/$cfg->pkgName", 55) . "│\n";
    echo '│ Github     : ' . str_pad("https://github.com/$cfg->githubPath", 55) . "│\n";
    echo '│ StyleCI ID : ' . str_pad($cfg->styleCI, 55) . "│\n";
    echo '│ Output dir : ' . str_pad($cfg->directory, 55) . "│\n";
    echo "└─────────────────────────────────────────────────────────────────────┘\n\n";

    $yes = readline('Type "y" + ENTER to proceed with code generation ');
    if (!in_array(strtolower($yes), ['y', 'yes'])) {
        exit(0);
    }
}

function generate_code(Configuration $cfg)
{
    echo "Generating $cfg->gateway code...\n";
    mkdir($cfg->directory);

    foreach (COPY_FILES as $file) {
        copy_file(
            __DIR__ . "/$file",
            __DIR__ . "/{$cfg->directory}/$file",
            $cfg
        );
    }

    foreach (SKEL_MAP as $source => $dest) {
        $targetFolder = $cfg->directory . '/' . dirname($dest);
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        copy_file(
            __DIR__ . "/_skel/$source",
            __DIR__ . "/{$cfg->directory}/$dest",
            $cfg
        );
    }

    copy_folders($cfg);

    rename(
        $cfg->directory . '/tests/migrations/2021_03_17_095014_create_test_orders_table.php',
        $cfg->directory . '/tests/migrations/' . date('Y_m_d_His') . '_create_test_orders_table.php'
    );
}

function copy_folders(Configuration $cfg)
{
    foreach (COPY_FOLDERS as $folder) {
        echo "Copying folder $folder/...\n";
        mkdir($cfg->directory . "/$folder", 0777, true);
        $iterator = new RecursiveDirectoryIterator(__DIR__ . '/' . $folder);
        foreach(new RecursiveIteratorIterator($iterator) as $file) {
            $targetFolder = $cfg->directory . '/' . str_replace(__DIR__, '', $file->getPath());
            if (!file_exists($targetFolder)) {
                mkdir($targetFolder, 0777, true);
            }
            if ($file->isFile()) {
                $target = __DIR__ . "/{$cfg->directory}/" . str_replace(__DIR__, '', $file);
                copy_file($file->getRealPath(), $target, $cfg);
            }
        }
    }
}

function copy_file($source, $destination, Configuration $cfg)
{
    $relativeFileName = str_replace(__DIR__, '', $source);
    echo "Copying $relativeFileName...";
    file_put_contents($destination,
        replace_vars(file_get_contents($source), $cfg)
    );
    echo "OK\n";

    if (starts_with(basename($destination), 'XXX')) {
        rename(
            $destination,
            dirname($destination) . '/' . str_replace('XXX', $cfg->gatewayStudly, basename($destination))
        );
    }
}

function replace_vars($string, Configuration $cfg): string
{
    $map = [
        '{:author_name:}' => $cfg->author,
        '{:payment_gateway_name|slug|toupper:}' => mb_strtoupper(slug($cfg->gateway, '_')),
        '{:payment_gateway_name|slug:}' => $cfg->gatewaySlug,
        '{:payment_gateway_name|studly:}' => $cfg->gatewayStudly,
        '{:payment_gateway_name:}' => $cfg->gateway,
        '{:composer_vendor:}' => $cfg->pkgVendor,
        '{:composer_package_name:}' => $cfg->pkgName,
        '{:name_space_root|double_backslash:}' => str_replace('\\', '\\\\', $cfg->namespace),
        '{:name_space_root|dotnotation:}' => str_replace('\\', '.', mb_strtolower($cfg->namespace)),
        '{:name_space_root:}' => $cfg->namespace,
        '{:github_repo_path:}' => $cfg->githubPath,
        '{:style_ci_id:}' => $cfg->styleCI,
        'XXX' => $cfg->gatewayStudly,
        '{:year:}' => date('Y'),
    ];

    $result = $string;
    foreach ($map as $var => $replacement) {
        $result = str_replace($var, $replacement, $result);
    }

    return $result;
}

echo "┌───────────────────────────────────────────────────┐\n";
echo "│ This script will initialize some boilerplate code │\n";
echo "│ for a new Vanilo Payment Gateway implementation.  │\n";
echo "└───────────────────────────────────────────────────┘\n";

$cfg = new Configuration();

$cfg->author = ask('Enter your name', 'John Smith', 'Your own name. Will be used in LICENSE, composer.json', 'Hello %s!');
$cfg->gateway = ask('Enter the Gateway name', 'Paypal, Mollie, etc', 'The name of the payment gateway you\'re implementing support for', 'Vanilo + %s, sweet!');
$cfg->gatewayStudly = studly($cfg->gateway);
$cfg->gatewaySlug = slug($cfg->gateway);

$cfg->namespace = ask('Enter the PHP namespace', "Acme\\$cfg->gatewayStudly", 'The root PHP namespace of your package');
$cfg->pkgVendor = ask('Enter the package vendor', slug(explode('\\', $cfg->namespace)[0]), 'The composer vendor name your package will be using');
$cfg->pkgName = ask('Enter the package name', $cfg->gatewaySlug, 'The composer package name of your package');

$cfg->githubPath = ask('Enter the GitHub repo path', "$cfg->pkgVendor/$cfg->gatewaySlug", 'The path of the repo at Github. If it is hosted somewhere else, change later manually in the README');

$cfg->styleCI = ask('Enter the StyleCI id', '348627499', 'The StyleCI id connected to your repo. If you are not using StyleCI just enter anything and remove it from README later');

$cfg->directory = "./{$cfg->gatewaySlug}" . date('dHis') . '/';

summarize($cfg);

generate_code($cfg);
