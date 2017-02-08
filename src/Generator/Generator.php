<?php

namespace Afosto\ApiClient\Generator;

use Afosto\ApiClient\App;
use Symfony\Component\Console\Command\Command;

class Generator extends Command {

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
     * @var \Mustache_Engine
     */
    private $_mustache;

    /**
     * Current dir
     * @var string
     */
    private $_rootDir;

    /**
     * The source
     * @var string
     */
    private $_swaggerSource;

    /**
     * Controllermap for api
     * @var array
     */
    private $_paths;

    /**
     *
     * @var Definition[]
     */
    private $_definitions;

    /**
     * Constructor, used for Command
     *
     * @param string $name
     */
    public function __construct($name = null) {
        parent::__construct($name);
        $this->_mustache = new \Mustache_Engine();
        $this->_rootDir = dirname(__FILE__);
        $this->_swaggerSource = json_decode(file_get_contents(App::ENDPOINT . App::VERSION . self::SOURCE_URI), true);
    }

    /**
     * Called by Command
     */
    protected function configure() {
        $this->setName('generate')->setDescription('Generate the models');
    }

    /**
     * Called by Command
     */
    protected function execute($input, $output) {
        $this->_gatherPaths();
        $this->_formatDefinitions();
        $this->_generateModels();
    }

    /**
     * Format the data into definitions
     */
    private function _formatDefinitions() {
        foreach ($this->_swaggerSource['definitions'] as $modelName => $properties) {
            if (in_array($modelName, ['Link', 'Patch', 'Timestamp', 'Count', 'ErrorResponse'])) {
                continue;
            }
            $defintion = new Definition($modelName, $properties, $this->_paths);
            $this->_definitions[] = $defintion;
        }
    }

    /**
     * Render the templates and store the files in the model directory
     */
    private function _generateModels() {

        $model = file_get_contents($this->_rootDir . '/' . self::TEMPLATE_DIR . '/model.mustache');
        $apiModel = file_get_contents($this->_rootDir . '/' . self::TEMPLATE_DIR . '/api_model.mustache');
        $link = file_get_contents($this->_rootDir . '/' . self::TEMPLATE_DIR . '/link.mustache');

        $baseModel = file_get_contents($this->_rootDir . '/' . self::TEMPLATE_DIR . '/base_model.mustache');
        $baseApiModel = file_get_contents($this->_rootDir . '/' . self::TEMPLATE_DIR . '/base_api_model.mustache');
        $baseLink = file_get_contents($this->_rootDir . '/' . self::TEMPLATE_DIR . '/base_link.mustache');

        $modelDir = $this->_rootDir . '/' . self::TARGET_PATH . '/';
        if (is_dir($modelDir)) {
            shell_exec('rm -rf ' . $modelDir);
        }

        foreach ($this->_definitions as $definition) {

            //Model files
            $filePath = $definition->namespace . '/';
            $dir = $modelDir . $filePath;
            $file = $definition->name . '.php';
            $path = $dir . $file;

            //Base model files
            $baseFilePath = '_Base/' . $definition->namespace . '/';
            $baseDir = $modelDir . $baseFilePath;
            $baseFile = 'Base' . $definition->name . '.php';
            $basePath = $baseDir . $baseFile;


            if (in_array(strtolower($definition->name), $this->_paths)) {
                //ApiModel
                $content = $this->_mustache->render($apiModel, $definition->getArray());
                $baseContent = $this->_mustache->render($baseApiModel, $definition->getArray());
            } else if (substr($definition->name, -3) == 'Rel') {
                //Link
                $content = $this->_mustache->render($link, $definition->getArray());
                $baseContent = $this->_mustache->render($baseLink, $definition->getArray());
            } else {
                //Model
                $content = $this->_mustache->render($model, $definition->getArray());
                $baseContent = $this->_mustache->render($baseModel, $definition->getArray());
            }

            $currentFile = null;
            @$currentFile = file_get_contents(realpath(dirname(__FILE__) . '/../Models') . '/' . $filePath . $file);

            $currentBaseFile = null;
            @$currentBaseFile = file_get_contents(realpath(dirname(__FILE__) . '/../Models') . '/' . $baseFilePath . $baseFile);

            if ($content !== $currentFile) {
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                if (file_exists($path)) {
                    unlink($path);
                }

                file_put_contents($path, $content);
            }

            if ($baseContent !== $currentBaseFile) {
                if (!is_dir($baseDir)) {
                    mkdir($baseDir, 0777, true);
                }
                if (file_exists($basePath)) {
                    unlink($basePath);
                }
                file_put_contents($basePath, $baseContent);
            }
        }
    }

    /**
     * Build the controllermap
     */
    private function _gatherPaths() {
        $this->_paths = [];
        foreach ($this->_swaggerSource['paths'] as $path => $pathData) {
            $path = current(explode('/', substr($path, 1)));
            $this->_paths[] = \Doctrine\Common\Inflector\Inflector::singularize($path);
        }
        $this->_paths = array_unique($this->_paths);
    }

}
