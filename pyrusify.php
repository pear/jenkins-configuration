<?php
$dir = new DirectoryIterator(dirname(__FILE__) . '/jobs/');

foreach ($dir as $file) {
    if ($file->isDir() && (string)$file != '..') {
        foreach (new DirectoryIterator($file->getRealPath()) as $child) {
            if ((string)$child != 'config.xml' ) { continue; }

            $config = simplexml_load_file($child->getRealPath());

            foreach ($config->xpath('//hudson.tasks.Shell/command') as $cmd) {
                if ((string)$cmd == 'pear package') {
                    $cmd[0] = 'php ~/pyrus.phar package';

                    file_put_contents($child->getRealPath(), $config->asXML());
                }
            }


        }
    }
}
