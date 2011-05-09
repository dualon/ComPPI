<?php

namespace Comppi\LoaderBundle\Command;

use Comppi\LoaderBundle\Service\EntityGenerator\EntityGenerator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class EntityGeneratorCommand extends Command
{
    private $container;
    private $generator;
    private $databases;
    private $parser;

    private $database_dir;
    private $output_dir;
    
    protected function configure()
    {
        $this
            ->setName('comppi:load:entities')
            ->setDescription('Generates model entities from plaintext db headers')
            ->setHelp('All option paths are relative to the LoaderBundle')
            ->addOption('database_dir', null, InputArgument::OPTIONAL, 'Path to the plaintext databases')
            ->addOption('output_dir', null, InputArgument::OPTIONAL, 'Path to the dir of generated entities')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        //parse databases
        foreach ($this->databases as $database) {
            $filename = basename($database);
            $cur_parser = $this->parser->getMatchingParser($filename);

            if ($cur_parser) {
                //matching parser found
                $handle = fopen($database, "r");
                if ($handle) {
                    $fields = $cur_parser->getFieldArray($handle);
                    fclose($handle);
                    $this->generateEntity($filename, $fields);
                } else {
                    throw new \Exception("Can't open file '" . $database . "'");
                }
                
            } else {
                throw new \UnexpectedValueException("Can't parse database: '" . $database . "'");
            }
        }
        
    }
    
    protected function initialize(InputInterface $input, OutputInterface $output) {
        $this->container = $this->getApplication()->getKernel()->getContainer();
        $this->generator = $this->container->get('loader.entity_generator');
        $this->parser = $this->container->get('loader.database_parser');
        
        $this->loadOptions($input);        
        
        $this->loadDatabaseFiles(__DIR__ . '/../' . $this->database_dir);
    }
    
    private function loadOptions(InputInterface $input) {
        
        $keys = array(
            'database_dir',
            'output_dir'
        );
        
        foreach ($keys as $key) {
            if (!$value = $input->getOption($key)) {
                $value = $this->container->getParameter('loader.' . $key);
            }
            
            $this->$key = $value;
        }
    }
    
    private function loadDatabaseFiles($database_dir) {
        $dbs = new Finder();
        $dbs->files()->in($database_dir);
        
        $this->databases = $dbs;     
    }
    
    private function generateEntity($name, array $fields) {
        file_put_contents(
            __DIR__ . '/../' . $this->output_dir . '/' . ucfirst($name) . '.php',
            $this->generator->generate($name, $fields)
        );
    }
}
