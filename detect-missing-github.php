<?php
// Detects any configurations with a github source location, but no build trigger
$dir = new DirectoryIterator(dirname(__FILE__) . '/jobs/');

foreach ($dir as $file) {
    if ($file->isDir() && (string)$file != '..') {
        foreach (new DirectoryIterator($file->getRealPath()) as $child) {
            if ((string)$child != 'config.xml' ) { continue; }

            $config = simplexml_load_file($child->getRealPath());

            foreach ($config->xpath('//scm[@class="hudson.plugins.git.GitSCM"]/../triggers') as $triggers) {
                if (!$triggers->xpath('com.cloudbees.jenkins.GitHubPushTrigger')) {
                    $triggers->addChild('com.cloudbees.jenkins.GitHubPushTrigger')
                              ->addChild('spec');

                    file_put_contents($child->getRealPath(), $config->asXML());
                }
            }

        }
    }
}
