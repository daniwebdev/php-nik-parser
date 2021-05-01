<?php
namespace DaniWebDev;

use Exception;

/**
 * Data yang dihasilkan merupakan dari extract no NIK.
 * Data regional merupakan tempat pertamakali KTP di buat.
 *
 */

class NIK {
    private $_noNIK;

    function __construct($NIK)
    {
        $this->_noNIK = $NIK;
    }

    function parse() {
        if(strlen($this->_noNIK) == 16) {

            $thisYear = substr(date('Y'), -2);
            $_provinsi      = substr($this->_noNIK, 0, 2);
            $_kota          = substr($this->_noNIK, 2, 2);
            $_kabupaten     = substr($this->_noNIK, 2, 2);
            $_kecamatan     = substr($this->_noNIK, 4, 2);

            $year = substr($this->_noNIK, 10, 2);
            $parse = [];
            $parse['nik']    = $this->_noNIK;
            $parse['unique'] = substr($this->_noNIK, -4);

            $parse["provinsi"]      = $this->get_data_region($_provinsi);
            $parse["kota"]          = $this->get_data_region($_provinsi.'.'.$_kota);
            $parse["kabupaten"]     = $this->get_data_region($_provinsi.'.'.$_kabupaten);
            $parse["kecamatan"]     = $this->get_data_region($_provinsi.'.'.$_kota.'.'.$_kecamatan);

            $date = [
                "day"   => (substr($this->_noNIK, 6,2) > 40) ? substr($this->_noNIK, 6, 2) - 40 : substr($this->_noNIK, 6, 2),
                "month" => substr($this->_noNIK, 8, 2),
                "year"  => $year > 1 && $year < $thisYear ? "20".$year:"19".$year,
            ];

            $parse['birthday'] = $date['day'].'/'.$date['month'].'/'.$date['year'];


            return $parse;
        } else {
            throw new Exception("NO KTP harus 16 digit");
        }
    }

    function isValid() : bool {
        $prov = substr($this->_noNIK, 0, 2);
        $kota = substr($this->_noNIK, 2, 2);
        $kec  = substr($this->_noNIK, 4, 2);

        $regional = $prov.'.'.$kota.'.'.$kec;

        $get_data_region = $this->get_data_region($regional);

        $length = strlen($this->_noNIK);

        if($get_data_region != false && $length == 16) {
            return true;
        }

        return false;
    }

    private function load_data() {
        return json_decode(file_get_contents('./data.txt'));
    }

    private function get_data_region($code) {
        $data = $this->load_data();
        $get   = array_filter($data, function($data) use($code) {
            return $data[0] == $code;
        });

        foreach($get as $item) {
            if($item[0] == $code) {
                return $item[1];
            }
        }
        return false;
    }

}
