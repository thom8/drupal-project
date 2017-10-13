<?php

/**
 * @file
 * Contains \DrupalProject\composer\WebComposer.
 */

namespace DrupalProject\composer;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;

class WebComposer {

  /**
   * @var string
   */
  private $env = 'COMPOSER_CACHE_DIR=./.composer/cache';

  /**
   * @var string
   */
  private $phpMemoryLimit = '-1';

  /**
   * @var int
   */
  private $phpTimeLimit = -1;

  /**
   * Constructor.
   *
   * @param Request $request
   *
   */
  public function __construct(Request $request) {
    $this->request = $request;
    $this->composer = new Application();
    $this->output = new BufferedOutput();
    $this->command = $this->getCommand();
    $this->argument = $this->getArgument();
    $this->options = $this->getOptions();
  }

  /**
   * Invoke composer command based on request.
   *
   * @return string
   *   Stream output from the console command.
   */
  public function run() {
    $this->setEnvironment();
    $this->composer->setAutoExit(false);

    $input = new ArrayInput($this->buildInput());
    $this->composer->run($input, $this->output);

    return $this->output->fetch();
  }

  /**
   *  Builds input array for composer app.
   *
   * @return array
   *   Array containing command, arguments and options.
   */
  public function buildInput() {
    $input['command'] = $this->command;
    if ($this->argument) {
      $input['packages'] = [$this->argument];
    }
    if ($this->options) {
      foreach ($this->options as $option) {
        $input['--' .$option] = TRUE;
      }
    }
    return $input;
  }

  /**
   * Set environment configuration.
   */
  public function setEnvironment() {
    putenv($this->env);
    ini_set('memory_limit', $this->phpMemoryLimit);
    set_time_limit((int) $this->phpTimeLimit);
  }

  /**
   * Get composer command.
   *
   * @return string
   *  A valid composer command to run or validate by default.
   */
  public function getCommand() {
    $args = explode('/', $this->getPath());
    return ((isset($args[1]) && $this->allowedCommand($args[1]))) ? $args[1] : 'validate';
  }

  /**
   * Get composer arguments.
   *
   * @return string
   *  Argument string for composer.
   */
  public function getArgument() {
    if ($this->getPath()) {
      $args = explode('/--/', $this->getPath());
      $args = str_replace('/' . $this->command, '', $args[0]);
    }

    return (isset($args) && !empty($args)) ? ltrim($args, '/') : '';
  }

  /**
   * Get composer options.
   *
   * @return array
   *  Array of options to include for composer.
   */
  public function getOptions() {
    $args = explode('/--/', $this->getPath());
    return (isset($args[1])) ? explode('/', $args[1]) : [];
  }

  /**
   * @param $command string
   *  Composer command.
   *
   * @return bool
   *  True if command is allowed.
   */
  public function allowedCommand($command) {
    return in_array($command, [
      'install',
      'require',
      'show',
      'update',
      'validate',
    ]);
  }

  /**
   * @return string
   *  Request URI without the composer prefix.
   */
  public function getPath() {
    return str_replace('/composer', '', $this->request->getPathInfo());
  }

}
