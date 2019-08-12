<?php /** @noinspection PhpUnusedLocalVariableInspection PhpUnusedParameterInspection */
declare(strict_types=1);
namespace App\Controllers\API;

use App\Formatters\SqlFormatter;
use App\Settings;
use MVQN\Data\Database;
use MVQN\Data\Exceptions\DatabaseConnectionException;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use UCRM\Common\Plugin;


/**
 * Class PermissionsController
 *
 * An controller for interacting with the Plugin's log files.
 *
 * @package App\Controllers
 * @author Ryan Spaeth <rspaeth@mvqn.net>
 * @final
 */
final class PermissionsController
{

    /**
     * PsqlController constructor.
     *
     * @param App $app The Slim Application for which to configure routing.
     */
    public function __construct(App $app)
    {
        // Get a local reference to the Slim Application's DI Container.
        $container = $app->getContainer();


        // Handle GET queries to the file logs...
        $app->get(
            "/permissions/groups[/]",

            function (Request $request, Response $response, array $args) use ($container)
            {
                /** @noinspection PhpUndefinedClassConstantInspection */
                Database::connect(
                    getenv("POSTGRES_HOST") ?: Settings::UCRM_DB_HOST,
                    (int)getenv("POSTGRES_PORT") ?: Settings::UCRM_DB_PORT,
                    getenv("POSTGRES_DB") ?: Settings::UCRM_DB_NAME,
                    getenv("POSTGRES_USER") ?: Settings::UCRM_DB_USER,
                    getenv("POSTGRES_PASSWORD") ?: Settings::UCRM_DB_PASSWORD
                );

                $results = Database::query(
                    "
                    SELECT * FROM user_group;                
                    "
                );

                return $response->withJson($results);
            }
        );

        $app->get(
            "/permissions/groups/allowed",

            function (Request $request, Response $response, array $args) use ($container)
            {
                Plugin::dbQuery(
                    "
                    CREATE TABLE IF NOT EXISTS permissions (
                        id INTEGER PRIMARY KEY,
                        \"group\" TEXT NOT NULL,
                        allowed INTEGER NOT NULL DEFAULT 0
                    );
                    "
                );

                $results = Plugin::dbQuery(
                    "
                    SELECT * FROM permissions;
                    "
                );

                if(count($results) === 0)
                {
                    $results = Plugin::dbQuery(
                        "
                        INSERT INTO permissions
                            ( \"group\", allowed )
                        VALUES
                            ( \"Admin Group\", 1 )
                        ;
                        "
                    );
                }

                $results = Plugin::dbQuery(
                    "
                    SELECT * FROM permissions;
                    "
                );

                return $response->withJson($results);
            }
        );

        // Handle GET queries to the file logs...
        $app->post(
            "/permissions/groups/allowed",

            function (Request $request, Response $response, array $args) use ($container)
            {
                $body = $request->getBody()->getContents();

                $data = json_decode($body, true);

                $group = $data["group"];
                $allowed = $data["allowed"];

                $results = Plugin::dbQuery(
                    "
                    SELECT * FROM permissions
                    WHERE \"group\" = \"$group\";
                    "
                );

                return $response->withJson($results);
            }
        );






    }

}
