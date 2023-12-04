<?php

namespace Blueprint\Generators\Statements;

use Blueprint\Contracts\Generator;
use Blueprint\Generators\StatementGenerator;
use Blueprint\Models\Statements\RenderStatement;
use Blueprint\Tree;
use Illuminate\Support\Str;

class ViewGenerator extends StatementGenerator implements Generator
{
    protected array $types = ['controllers', 'views'];

    public function output(Tree $tree): array
    {
        $stub = $this->filesystem->stub('view.stub');

        /**
         * @var \Blueprint\Models\Controller $controller
         */
        foreach ($tree->controllers() as $controller) {
            foreach ($controller->methods() as $method => $statements) {
                foreach ($statements as $statement) {
                    if (!$statement instanceof RenderStatement) {
                        continue;
                    }

                    $path = $this->getStatementPath($statement->view());

                    if ($this->filesystem->exists($path)) {
                        $this->output['skipped'][] = $path;
                        continue;
                    }

                    $this->create($path, $this->populateStub($stub, $statement));
                }
            }
        }

        return $this->output;
    }

    protected function getStatementPath(string $view): string
    {
        return 'resources/views/' . str_replace('.', '/', $view) . '.blade.php';
//        return 'resources/js/Pages/' . str_replace('.', '/', Str::ucfirst($view)) . '.jsx';
    }

    protected function populateStub(string $stub, RenderStatement $renderStatement): string
    {
        return str_replace('{{ view }}', $renderStatement->view(), $stub);
    }
}
