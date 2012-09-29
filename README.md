# Slic

Slic is a micro framework build to help you execute actions on the command line.

## Installation

* Install [Composer](http://getcomposer.org/)
* Run composer.phar install
* Optional: run ./compile (This'll allow you to use a .phar package)

### Already have composer installed?

`git clone git@github.com:siphoc/Slic.git && composer.phar install`

## Usage

This is a demo example:

    ```php
    <?php

    require_once __DIR__ . '/slic.phar';

    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Input\InputOption;

    class MockCommand extends \Slic\Command\Command
    {
        public function configure()
        {
            $this->setDescription('Test command')
                ->addArgument(
                    'name', InputArgument::REQUIRED, 'What\'s your name?'
                )
                ->addOption(
                    'uppercase', null,
                    InputOption::VALUE_NONE, 'Show in uppercase?'
                )
            ;
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $outputText = 'Hello, ';

            $outputText .= $input->getArgument('name');

            if ($input->getOption('uppercase')) {
                $outputText = strtoupper($outputText);
            }

            $output->writeln($outputText);
        }
    }

    $app = new \Slic\Application('slic');
    $app->registerCommand(new MockCommand('slic:mock'));
    $app->run();
    ```

Here we'll create a command mock (you can create as much commands as you want.)
and configure it's description and arguments/options/actions.

Then you only need to register these commands so that they'll be available
trough your script.

## Todo

* Services via config
* Extra default services
    * Doctrine
    * Monolog
* Commands via config
