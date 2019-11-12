<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insert extends CI_Controller {

  public function index()
  {
    // $upload_data = $this->upload->data();
    include APPPATH . "third_party/spreadsheet-reader-master/php-excel-reader/excel_reader2.php";
    include APPPATH . "third_party/spreadsheet-reader-master/SpreadsheetReader.php";
    $reader = new SpreadsheetReader("assets/Indonesian_Family_Life_Survey_4_Longitude_and_Latitude.csv");
    $sheets = $reader -> Sheets();
    $temp = [];
    foreach ($sheets as $index => $name)
    {
      if ($index == 0) {
        foreach ($reader as $val) {
          $x = [];
          foreach ($val as $value) {
            if ($value != "") {
              array_push($x, $value);
            }
          }
          if (sizeof($x) != 0) {
            array_push($temp, $x);
          }
        }
      }
    }
    $no = 1;
    foreach ($temp as $val) {
     
      if ($no >= 2) {
        $data = [
          "kd_provinsi" => $val[0],
          "kd_kabupaten" => $val[1],
          "kd_kecamatan" => $val[2],
          "lat" => $val[3],
          "lng" => $val[4],
          "nm_provinsi" => $val[5],
          "nm_kabupaten" => $val[6],
          "nm_kecamatan" => $val[7],
        ];
        // echo json_encode($data, JSON_PRETTY_PRINT);
        // $this->db->insert("temp", $data);
      }
     
      $no++;
    }

    echo json_encode(["sukses"], JSON_PRETTY_PRINT);
  }

  public function provinsi()
  {
    $this->db->select("nm_provinsi");
    $this->db->from("temp");
    $this->db->group_by(["nm_provinsi"]);
    // $this->db->order_by( "ASC");
    $data = $this->db->get()->result();
    foreach ($data as $val) {
      $dt = [
        "nm_provinsi" => $val->nm_provinsi,
      ];
      // $this->db->insert("provinsi", $dt);
      
    }
    echo json_encode(["sukses"]);
  }

  public function kabupaten()
  {
    $this->db->select("nm_provinsi, nm_kabupaten");
    $this->db->from("temp");
    $this->db->group_by(["nm_kabupaten", "nm_kabupaten"]);
    $data = $this->db->get()->result();
    foreach ($data as $val) {
      $id_provinsi = $this->db->select("kd_provinsi")->from("provinsi")->where("nm_provinsi", $val->nm_provinsi)->get()->row()->kd_provinsi;
      $dt = [
        "kd_provinsi" => $id_provinsi,
        "nm_kabupaten" => $val->nm_kabupaten,
      ];
      // echo json_encode($dt, JSON_PRETTY_PRINT) . "<br>";
      // $this->db->insert("kabupaten", $dt);
    }
    echo json_encode(["sukses"], JSON_PRETTY_PRINT);
  }

  public function kecamatan()
  {
    $this->db->select("*");
    $this->db->from("temp");
    $data = $this->db->get()->result();
    foreach ($data as $val) {
      $kd_kabupaten = $this->db->select("*")->from("kabupaten as t1")->join("provinsi as t2","t2.kd_provinsi = t1.kd_provinsi","left")->where("t2.nm_provinsi",$val->nm_provinsi)->where("t1.nm_kabupaten", $val->nm_kabupaten)->get()->row()->kd_kabupaten;
      if ($kd_kabupaten == null) {
        $echo =  json_encode(["ERROR!!!!", $val->nm_kabupaten, $val->nm_provinsi]);
        dd($echo);
      } else {
        $dt = [
          "kd_kabupaten"=> $kd_kabupaten,
          "lat"         => $val->lat,
          "lng"         => $val->lng,
          "nm_kecamatan"=> $val->nm_kecamatan,
        ];
        // $this->db->insert("kecamatan", $dt);
        echo json_encode($dt, JSON_PRETTY_PRINT);
      }
    }
    echo json_encode(["sukses"]);
  }

}

/* End of file Insert.php */
/* Location: ./application/controllers/Insert.php */