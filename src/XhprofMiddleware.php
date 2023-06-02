<?php
/**
 * @desc XhprofMiddleware
 * @author Tinywan(ShaoBo Wan)
 * @date 2023/4/28 9:26
 */
declare(strict_types=1);


namespace Tinywan\Xhprof;


use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class XhprofMiddleware implements MiddlewareInterface
{
    /**
     * @desc: process 描述
     * @param Request $request
     * @param callable $handler
     * @return Response
     * @author Tinywan(ShaoBo Wan)
     */
    public function process(Request $request, callable $handler): Response
    {
        $xhprof = $request->get('xhprof', 0);
        $extension = extension_loaded('xhprof');
        if ($xhprof && $extension) {
            include_once "xhprof/xhprof_lib/utils/xhprof_lib.php";
            include_once "xhprof/xhprof_lib/utils/xhprof_runs.php";
            xhprof_enable(XHPROF_FLAGS_NO_BUILTINS + XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        }

        $response = $handler($request);
        if ($xhprof && $extension) {
            $data = xhprof_disable();
            $objXhprofRun = new \XHProfRuns_Default();
            $objXhprofRun->save_run($data, sprintf('%s', date("YmdHis")));
        }

        return $response;
    }
}