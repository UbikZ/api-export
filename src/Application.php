<?php

namespace ApiExport;

use SMS\Core;

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
}
