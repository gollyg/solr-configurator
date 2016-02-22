<?php

/*
 * This file is part of the Cilex framework.
 *
 * (c) Mike van Riel <mike.vanriel@naenius.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gollyg\SolrSyntaxParser\Command;

use Cilex\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use League\Csv\Reader;

/**
 * Example command for testing purposes.
 */
class SynonymCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('solr:synonym')
      ->setDescription('Converts files to Solr syntax')
      ->addArgument('input', InputArgument::REQUIRED, 'CSV file containing Solr values')
      ->addArgument('output', InputArgument::OPTIONAL, 'The name of the created file. If none is provided output will be printed to screen.')
      ->addOption('implicit', 'i', null, 'Use implicit Solr formatting.')
      ->addOption('no-header', 'H', NULL, 'Don\'t skip first row data.');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output_file = $input->getArgument('output');
    $expansive = $input->getOption('implicit');
    $no_header = $input->getOption('no-header');
    $items = array();

    //load source file
    $input_file = $input->getArgument('input');
    $csv = Reader::createFromPath($input_file);

    // Ignore header by default
    if (!$no_header) {
      $csv->setOffset(1);
    }

    $results = $csv->fetch();

    foreach ($results as $row) {
      // sanity check on the format
      if (empty($row[0]) || empty($row[1])) continue;
      // trim the items
      $row = array_map('trim', $row);
      // remove any special characters
      $row = preg_replace("/[^a-zA-Z0-9() ,\/]/", "", $row);

      if (!$expansive) {
        $line = $row[0] . ' => ' . $row[0] . ', ' . $row[1];
      }
      else {
        $line = implode(', ', $row);
      }
      $items[] = $line;
    }

    $text = implode(PHP_EOL, $items);

    if ($output_file) {
      $fs = new Filesystem();
      $fs->dumpFile($output_file, $text);
    }
    else {
      $output->write($text);
    }
  }
}
