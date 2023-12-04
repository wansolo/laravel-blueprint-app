<?php
namespace App\CustomBluePrint;
use \Blueprint\Generators\Statements\ViewGenerator as BaseViewGenerator;
use Illuminate\Support\Str;

class CustomViewGenerator extends BaseViewGenerator
{
    protected array $types = ['controllers', 'views'];

    protected function getStatementPath(string $view): string
    {
       return 'resources/js/Pages/' . str_replace('.', '/', Str::ucfirst($view)) . '.jsx';
    }

}
