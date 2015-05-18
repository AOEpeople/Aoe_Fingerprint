<?php

require_once 'abstract.php';

class Aoe_Fingerprint_Shell_Fingerprint extends Mage_Shell_Abstract
{
    /**
     * Run script
     *
     * @return void
     */
    public function run()
    {
        try {
            $action = $this->getArg('action');
            if (empty($action)) {
                echo $this->usageHelp();
            } else {
                $actionMethodName = $action . 'Action';
                if (method_exists($this, $actionMethodName)) {
                    $this->$actionMethodName();
                } else {
                    echo "Action $action not found!\n";
                    echo $this->usageHelp();
                    exit(1);
                }
            }
        } catch (Exception $e) {
            $fh = fopen('php://stderr', 'w');
            fputs($fh, $e->__toString());
            fclose($fh);
            exit(255);
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     * @return string
     */
    public function usageHelp()
    {
        $help = 'Available actions: ' . "\n";
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (substr($method, -6) == 'Action') {
                $help .= '    --action ' . substr($method, 0, -6);
                $helpMethod = $method . 'Help';
                if (method_exists($this, $helpMethod)) {
                    $help .= ' ' . $this->$helpMethod();
                }
                $help .= "\n";
            }
        }
        return $help;
    }

    public function createAction()
    {
        exec('cd ' . Mage::getBaseDir() . '; find -L . -type f -not -path "./media/*" -not -path "./var/*" -not -path "*/.sass-cache/*" -not -path "./export/*" -exec md5sum {} \;', $output);
        // file_put_contents('/tmp/ser', serialize($output));
        // $output = unserialize(file_get_contents('/tmp/ser'));

        $data = array();
        foreach ($output as $line) {
            $data[substr($line, 34)] = substr($line, 0, 32);
        }

        $fingerprint = Mage::getModel('aoe_fingerprint/fingerprint'); /* @var $fingerprint Aoe_Fingerprint_Model_Fingerprint */
        $fingerprint->setChecksums(json_encode($data));
        $fingerprint->save();

        // var_dump(json_encode($data));
    }

}

$shell = new Aoe_Fingerprint_Shell_Fingerprint();
$shell->run();
