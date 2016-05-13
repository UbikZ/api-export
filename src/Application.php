<?php

namespace ApiExport;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Silex\Provider\MonologServiceProvider;
use SMS\Core;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use SMS\Core\Silex\Layout as SilexLayout;

/**
 * Class Application.
 */
class Application extends Core\Application
{
    public function run()
    {
        try {
            $this->registerErrorHandler();
            $this->registerLogger();
            $this->registerRoutes();
            $this->registerDatabase();
            $this->registerMailer();
            $this->registerTemplateEngine();

            Core\Silex\Layout::getInstance()->run();
        } catch (Core\Exception\ErrorSQLStatementException $e) {
            $this->handleException($e, 'sql_errors');
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /***
     * Turn fatal errors into exception.
     */
    protected function registerErrorHandler()
    {
        return true;
    }

    public function registerLogger()
    {
        $confApp = $this->conf['application'];
        $modules = isset($confApp['modules']) && is_array($confApp['modules']) ? $confApp['modules'] : [];

        if (!isset($confApp['logger'])) {
            throw new InvalidConfigurationException('Configuration for `application.logger` does not exist');
        }
        if (!isset($confApp['logger']['path'])) {
            throw new InvalidConfigurationException('Configuration for `application.logger.path` does not exist');
        }
        if (!isset($confApp['logger']['channels'])) {
            throw new InvalidConfigurationException('Configuration for `application.logger.channels` does not exist');
        }

        $channels = is_array($confApp['logger']['channels']) ? $confApp['logger']['channels'] : [];
        $logPath = ROOT_PATH.'/'.$confApp['logger']['path'];

        // Default configuration
        SilexLayout::getInstance()->register(new MonologServiceProvider(), [
            'monolog.logfile' => $logPath.'/'.APPLICATION_ENV.'.log',
            'monolog.level' => Logger::NOTICE,
        ]);

        // Register new channels (specific channels + modules one)
        foreach ($channels + $modules as $channel) {
            SilexLayout::setService('monolog.'.$channel, SilexLayout::getInstance()->share(
                function ($app) use ($logPath, $channel) {
                    /** @var Logger $log */
                    $log = new $app['monolog.logger.class']($channel);
                    $handler = new StreamHandler($logPath.'/'.APPLICATION_ENV.'.'.$channel.'.log');
                    $log->pushHandler($handler);

                    return $log;
                }
            ));
        }
    }
}
