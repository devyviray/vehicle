<?php 
    return [
        'api' => [
            //admin@lafilgroup.com
            // 'gps_hash'=> '$2y$10$AfIsvH9nfXgNDRxMnJR9Hewa5R9ZSTId/c9pXssd0nJjjcvGyYwoq', 
            //arjay.lumagdong@lafilgroup.com
            'gps_hash'=> '$2y$10$cgtB39dh3RjWI411T6MwjuYUHrRTv/4iUC.A7RSTktZQrjJ5UWl1W',
            'gps_headers' => [
                'cache-control' => 'no-cache',
                'Connection' => 'keep-alive',
                'Content-Length' => '961',
                'Accept-Encoding' => 'gzip, deflate',
                'Host' => 'gpstracker.lafilgroup.com',
                'Cache-Control' => 'no-cache',
                'Accept' => '*/*',
                'content-type' => 'application/x-www-form-urlencoded',
            ]
        ],
        'sap_api' => [
            'connection_lfug' => [
                'ashost' => '172.17.2.36',
                'sysnr' => '00',
                'client' => '888',
                'user' => 'rfidproject',
                'passwd' => 'P@ssw0rd4'
            ],
            'connection_pfmc' => [
                'ashost' => '172.17.1.35',
                'sysnr' => '02',
                'client' => '888',
                'user' => 'rfidproject',
                'passwd' => 'P@ssw0rd4',
            ],
            'table' => [
                'table' => ['LFA1' => 'vendors'],
                'fields' => [
                'LIFNR' => 'vendor_number',
                'NAME1' => 'vendor_name',
                'NAME2' => 'vendor_name2',
                ]
            ]
        ]
    ];
