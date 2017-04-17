<?php

namespace Afosto\ApiClient\Generator;

use Afosto\ApiClient\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorCommand extends Command
{
    /**
     * The uri for the swagger json
     */
    const SOURCE_URI = '/swagger';
    /**
     * Target for the generated models
     */
    const TARGET_PATH = 'Models';
    /**
     * Sourcedirecotry for the templates
     */
    const TEMPLATE_DIR = 'templates';

    /**
     * Template renderer
     *
     * @var \Mustache_Engine
     */
    private $_mustache;

    /**
     * Current dir
     *
     * @var string
     */
    private $_rootDir;

    /**
     * The source
     *
     * @var string
     */
    private $_swaggerSource;

    /**
     * Controllermap for api
     *
     * @var array
     */
    private $_paths;

    /**
     *
     * @var Definition[]
     */
    private $_definitions;

    /**
     * @var OutputInterface
     */
    private $outputInterface;

    /**
     * @var bool
     */
    private $isDryRun = false;

    /**
     * Constructor, used for Command
     *
     * @param string $rootDirectory
     */
    public function __construct($rootDirectory = null)
    {
        parent::__construct('generate');
        $this->_mustache = new \Mustache_Engine();
        $this->_swaggerSource =
            json_decode(file_get_contents(App::ENDPOINT . App::VERSION . self::SOURCE_URI), true);
    }

    /**
     * Called by Command
     */
    protected function configure()
    {
        $this->setName('generate:models')
            ->setDescription('Generate the models')
            ->addArgument('outputDirectory', InputArgument::REQUIRED, 'The output directory')
            ->addOption(
                'dry-run',
                'd',
                InputOption::VALUE_NONE,
                'Perform dry run (do not create files and / or directories)'
            );
    }

    /**
     * Called by Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->outputInterface = $output;
        $this->isDryRun = $input->getOption('dry-run');

        $this->_rootDir = rtrim($input->getArgument('outputDirectory'), '/') . '/';
        $this->_gatherPaths();
        $this->_formatDefinitions();
        $this->_generateModels();
    }

    /**
     * Format the data into definitions
     */
    private function _formatDefinitions()
    {
        foreach ($this->_swaggerSource['definitions'] as $modelName => $properties) {
            if (in_array($modelName,
                         [
                             'InternalShipment',
                             'InternalShipmentItem',
                             'Me',
                             'Link',
                             'Patch',
                             'Timestamp',
                             'Count',
                             'ErrorResponse',
                         ]
            )) {
                continue;
            }
            $definition = new Definition($modelName, $properties, $this->_paths);
            $this->_definitions[] = $definition;
        }
    }

    private function getModelTemplates()
    {
        $names = ['model', 'api_model', 'link', 'base_model', 'base_api_model', 'base_link'];
        $templates = [];
        foreach ($names as $name) {
            $templates[$name] = file_get_contents(__DIR__ . "/templates/$name.mustache");
        }
        return $templates;
    }

    /**
     * @param string[]|string $messages
     */
    private function writeMessage($messages)
    {
        $this->outputInterface->writeln(
            $messages,
            $this->isDryRun ? OutputInterface::VERBOSITY_NORMAL : OutputInterface::VERBOSITY_VERBOSE
        );
    }

    /**
     * Render the templates and store the files in the model directory
     */
    private function _generateModels()
    {
        $outputBaseDirectory = $this->_rootDir . self::TARGET_PATH . '/';
        if (file_exists($this->_rootDir) && !is_dir($this->_rootDir)) {
            throw new RuntimeException('Invalid output directory', -1);
        }

        if (file_exists($outputBaseDirectory) && !is_dir($outputBaseDirectory)) {
            throw new RuntimeException('Invalid output directory', -1);
        }

        if (!is_dir($this->_rootDir)) {
            $this->writeMessage("<info>Creating {$this->_rootDir}</info>");
            if (!$this->isDryRun) {
                mkdir($this->_rootDir);
            }
        }

        if (!file_exists($outputBaseDirectory)) {
            $this->writeMessage("<info>Creating directory {$outputBaseDirectory}</info>");
            if (!$this->isDryRun) {
                mkdir($outputBaseDirectory);
            }
        }

        $templates = $this->getModelTemplates();
        foreach ($this->_definitions as $definition) {
            $outputDirectory = $outputBaseDirectory . $definition->namespace . '/';
            $baseDirectory = $outputBaseDirectory . '_Base/' . $definition->namespace . '/';
            $baseFilePath = $baseDirectory . 'Base' . $definition->name . '.php';
            $filePath = $outputDirectory . $definition->name . '.php';

            if (!is_dir($outputDirectory)) {
                $this->writeMessage("<info>Creating {$outputDirectory}</info>");
                if (!$this->isDryRun) {
                    mkdir($outputDirectory, 0777, true);
                }
            }

            if (!is_dir($baseDirectory)) {
                $this->writeMessage("<info>Creating {$baseDirectory}</info>");
                if (!$this->isDryRun) {
                    mkdir($baseDirectory, 0777, true);
                }
            }

            $customFileAlreadyExists = false;
            if (file_exists($filePath)) {
                $this->writeMessage("<info>Not performing action on {$filePath}.</info>");
                $customFileAlreadyExists = true;
            }

            if (file_exists($baseFilePath)) {
                if (!$this->isDryRun) {
                    unlink($baseFilePath);
                }
            }

            $templateToUse = null;
            if (in_array(strtolower($definition->name), $this->_paths)) {
                //ApiModel
                $templateToUse = 'api_model';
            } else if (substr($definition->name, -3) == 'Rel') {
                $templateToUse = 'link';
            } else {
                $templateToUse = 'model';
            }

            //Model
            $content = $this->_mustache->render(
                $templates[$templateToUse],
                $definition->getArray()
            );
            $baseContent = $this->_mustache->render(
                $templates["base_$templateToUse"],
                $definition->getArray()
            );

            if (!$customFileAlreadyExists) {
                $this->writeMessage(
                    "<info>Generating {$filePath} using template {$templateToUse}</info>"
                );

                if (!$this->isDryRun) {
                    file_put_contents($filePath, $content);
                }
            }

            $this->writeMessage(
                "<info>Generating {$baseFilePath} using template base_{$templateToUse}</info>"
            );

            if (!$this->isDryRun) {
                file_put_contents($baseFilePath, $baseContent);
            }
        }
    }

    /**
     * Build the controllermap
     */
    private function _gatherPaths()
    {
        $this->_paths = [];
        foreach ($this->_swaggerSource['paths'] as $path => $pathData) {
            $path = current(explode('/', substr($path, 1)));
            $this->_paths[] = \Doctrine\Common\Inflector\Inflector::singularize($path);
        }
        $this->_paths = array_unique($this->_paths);
    }
}
