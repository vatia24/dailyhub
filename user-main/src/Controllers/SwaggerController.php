<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Utils\Request;
use App\Utils\Response;

final class SwaggerController
{
    public function __construct(private Request $req, private Response $res)
    {
    }

    public function ui(): void
    {
        header('Content-Type: text/html; charset=utf-8');
        // Uses Swagger UI CDN and points to a swagger.yaml under the configured BASE_PATH if provided
        // Read BASE_PATH robustly from multiple sources
        $rawBase = getenv('BASE_PATH');
        if ($rawBase === false || $rawBase === '') {
            $rawBase = $_ENV['BASE_PATH'] ?? ($_SERVER['BASE_PATH'] ?? '');
        }
        $basePath = rtrim((string)$rawBase, '/');
        echo '<!doctype html><html><head><meta charset="utf-8"/><title>API Docs</title>' .
            '<link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui.css"/>' .
            '</head><body>' .
            '<div id="swagger-ui"></div>' .
            '<script src="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui-bundle.js"></script>' .
            '<script>' .
            '  (function(){' .
            '    var cfgBase = ' . json_encode($basePath) . ';' .
            '    var base = cfgBase && cfgBase.length ? cfgBase : window.location.pathname.replace(/\/$/, "");' .
            '    var specUrl = base + "/swagger.yaml";' .
            '    window.ui = SwaggerUIBundle({' .
            '      url: specUrl,' .
            '      dom_id: "#swagger-ui",' .
            '      requestInterceptor: function(req) {' .
            '        try {' .
            '          var u = new URL(req.url, window.location.origin);' .
            '          if (u.pathname.indexOf("/api/") === 0) {' .
            '            if (base && u.pathname.indexOf(base + "/api/") !== 0) {' .
            '              u.pathname = base + u.pathname;' .
            '              req.url = u.toString();' .
            '            }' .
            '          }' .
            '        } catch (e) {}' .
            '        return req;' .
            '      }' .
            '    });' .
            '  })();' .
            '</script>' .
            '</body></html>';
    }

    public function spec(): void
    {
        $path = dirname(__DIR__, 2) . '/docs/openapi.yaml';
        if (!is_file($path)) {
            http_response_code(404);
            echo 'openapi.yaml not found';
            return;
        }
        header('Content-Type: application/yaml; charset=utf-8');
        readfile($path);
    }
}

?>


