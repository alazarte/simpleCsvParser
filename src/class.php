<?php
class ParseCsv
{
    private $timestamp, $header;

    function __construct()
    {
        $this->timestamp = array();
        $this->header = array();
    }

    private function adds($data)
    {
        if(isset($this->timestamp[$data[3]]["sums"][$data[0].",synthetic".$data[1]]))
            $this->timestamp[$data[3]]["sums"][$data[0].",synthetic".$data[1]] += $data[4];
        else
            $this->timestamp[$data[3]]["sums"][$data[0].",synthetic".$data[1]] = $data[4];
    }

    private function counts($data)
    {
        if(isset($this->timestamp[$data[3]]["count"][$data[0].",synthetic".$data[1]]))
            $this->timestamp[$data[3]]["count"][$data[0].",synthetic".$data[1]] ++;
        else
            $this->timestamp[$data[3]]["count"][$data[0].",synthetic".$data[1]] = 1;
    }

    private function averages()
    {
        foreach($this->timestamp as $tms => $tuple)
        {
            foreach($tuple["count"] as $dev_obj => $count)
            {
                $this->timestamp[$tms]["avg"][$dev_obj] = $this->timestamp[$tms]["sums"][$dev_obj] / $count;
            }
            unset($this->timestamp[$tms]["count"]);
        }
    }

    private function checkFile($file)
    {
        $last = array_values(array_slice(explode(".",$file),-1))[0];
        if($last == "csv")
            if (($handle = fopen($file,"r")) !== FALSE)
                if(fgetcsv($handle,50,",") !== NULL)
                    return TRUE;
        return FALSE;
    }

    public function readCsv($file)
    {
        if($this->checkFile($file))
        {
            if (($handle = fopen($file,"r")) !== FALSE)
            {
                $this->header = (($data = fgetcsv($handle,50,",")) !== FALSE) ? $data : array(null);
                while(($data = fgetcsv($handle,50,",")) !== FALSE)
                {
                    if(array(null) !== $data)
                    {
                        $this->adds($data);
                        $this->counts($data);
                    }
                    else
                    {
                        $this->csvlog("Found blank line");
                    }
                }
                $this->averages();
                fclose($handle);
            }
            else
            {
                csvlog("File does not exists");
            }
        }
        else
        {
            $this->csvlog("File provided is not csv");
        }
    }

    private function cleanFiles()
    {
        foreach(array_keys($this->timestamp) as $key)
        {
            file_put_contents("../data/results_$key.csv","");
        }
    }

    private function writeHeaders()
    {
        foreach(array_keys($this->timestamp) as $key)
        {
            foreach($this->header as $val)
            {
                file_put_contents("../data/results_$key.csv","$val ", FILE_APPEND);
            }
        }
    }

    public function write()
    {
        $this->cleanFiles();
        $this->writeHeaders();
        foreach($this->timestamp as $tms => $data)
        {
            foreach($data as $op_type => $tuple)
            {
                foreach($tuple as $dev_obj => $val)
                {
                    file_put_contents("../data/results_$tms.csv", "\n$dev_obj,$op_type,$tms,$val",FILE_APPEND);
                }
            }
        }
    }

    private function csvlog($msg)
    {
        file_put_contents("../data/log.out",date("d/m-G:i: ").$msg."\n",FILE_APPEND);
    }
}

$p = new ParseCsv();
$p->readCsv($argv[1]);
$p->write();
?>
