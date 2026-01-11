<?php

namespace Polirium\Impersonate\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Polirium\Impersonate\Services\ImpersonateManager;

class InjectImpersonationUI
{
    protected $manager;

    public function __construct(ImpersonateManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $this->manager->isImpersonating()) {
            return $response;
        }

        if (! $response instanceof Response || ! str_contains($response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        $content = $response->getContent();

        // Simple check to ensure we are injecting into a valid HTML page
        if (($bodyPos = strripos($content, '</body>')) === false) {
            return $response;
        }

        $html = <<<HTML
        <div class="position-fixed bottom-0 start-0 p-3" style="z-index: 2147483647; pointer-events: none;">
            <a href="{{ROUTE}}" class="btn btn-danger shadow-lg d-flex align-items-center gap-2" style="pointer-events: auto; font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ghost" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                   <path d="M5 11a7 7 0 0 1 14 0v7a1.78 1.78 0 0 1 -3.1 1.4a1.65 1.65 0 0 0 -2.6 0a1.65 1.65 0 0 1 -2.6 0a1.65 1.65 0 0 0 -2.6 0a1.78 1.78 0 0 1 -3.1 -1.4v-7"></path>
                   <path d="M10 10l.01 0"></path>
                   <path d="M14 10l.01 0"></path>
                   <path d="M10 14a3.5 3.5 0 0 0 4 0"></path>
                </svg>
                <span>Đang đăng nhập: {{NAME}} (Quay lại)</span>
            </a>
        </div>
HTML;

        // Replace placeholders
        $html = str_replace(
            ['{{ROUTE}}', '{{NAME}}'],
            [route('impersonate.leave'), e(auth()->user()->name ?? 'User')],
            $html
        );

        $content = substr_replace($content, $html, $bodyPos, 0);
        $response->setContent($content);

        return $response;
    }
}
