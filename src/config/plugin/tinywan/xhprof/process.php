<?php
/**
 * @desc process.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2024/2/20 14:51
 */
declare(strict_types=1);

return [
    // 自定义Http Xhprof 访问协议
    'http.xhprof' => [
        'handler'=> \Tinywan\Xhprof\HttpProxy::class,
        'listen' => 'http://0.0.0.0:8686',
        'count'  => 1,
    ]
];