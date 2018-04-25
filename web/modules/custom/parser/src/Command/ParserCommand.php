<?php

namespace Drupal\parser\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Core\Command\Command;
use Drupal\parser\Handler\ParserHandler;

/**
 * Class ParserCommand.
 *
 * @Drupal\Console\Annotations\DrupalCommand (
 *     extension="parser",
 *     extensionType="module"
 * )
 */
class ParserCommand extends Command {

  protected $parser;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('parser')
      ->setDescription($this->trans('commands.parser.description'))
      ->addOption(
        'file',
        NULL,
        InputOption::VALUE_REQUIRED,
        $this->trans('commands.parser.options.file')
        );
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    if (!$input->getOption('file')) {
      $file = $this->getIo()->choiceNoList(
        $this->trans('commands.parser.questions.file'),
        $this->files()
      );
      $input->setOption('file', $file);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $file = $input->getOption('file');
    $path = "/var/www/html/lib/data";
    $this->$parser = new ParserHandler($path, $file, $this->getIo());
    $this->$parser->execute();
    $this->getIo()->info($this->trans('commands.parser.messages.success'));
  }

  /**
   * Returns supported files to be parsed.
   */
  private function files() {
    return [
      'zip3',
      'zip5',
      '2017-400NG',
      '2018-400NG',
      'entitlements',
    ];
  }

}
