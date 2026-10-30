<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use Exception;

class WsController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        return $this->respond([
            'message' => 'web service home'
        ], 200);
    }

    public function getUsers()
    {
        $db = \Config\Database::connect();
        try {
            $res = $db->table('v_users')->get()->getResult();
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getClients()
    {
        $db = \Config\Database::connect();
        try {
            $sql = $db->table('client_account')->get()->getResult();
            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $tgl_lahir = date("d-m-Y", strtotime($x->tgl_lahir));
                    $tgl_daftar = date("d-m-Y", strtotime($x->tgl_daftar));
                    $res[] = array(
                        'no_rekening' => $x->no_rekening,
                        'nik' => $x->nik,
                        'nama' => ucwords($x->nama),
                        'tgl_lahir' => ucwords($x->tmp_lahir . ' / ' . $tgl_lahir),
                        'alamat' => ucwords($x->dsn . ' ' . $x->ds . ' RT/RW ' . $x->rt . '/' . $x->rw),
                        'kec_kab' => ucwords($x->kec . ' - ' . $x->kab),
                        'tgl_daftar' => $tgl_daftar
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getClientId($key)
    {
        $db = \Config\Database::connect();
        try {
            if (is_null($key) || empty($key)) {
                return $this->respond([], 200);
            }
            $sql = $db->table('client_account')->where('no_rekening', $key)->get()->getResult();

            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $tgl_lahir = date("d-m-Y", strtotime($x->tgl_lahir));
                    $tgl_daftar = date("d-m-Y", strtotime($x->tgl_daftar));
                    $res[] = array(
                        'no_rekening' => $x->no_rekening,
                        'nik' => $x->nik,
                        'nama' => ucwords($x->nama),
                        'tgl_lahir' => ucwords($x->tmp_lahir . ' / ' . $tgl_lahir),
                        'alamat' => ucwords($x->dsn . ' ' . $x->ds . ' RT/RW ' . $x->rt . '/' . $x->rw),
                        'kec_kab' => ucwords($x->kec . ' - ' . $x->kab),
                        'tgl_daftar' => $tgl_daftar
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getSaldoHarianId($key)
    {
        $db = \Config\Database::connect();
        try {
            if (is_null($key) || empty($key)) {
                return $this->respond([], 200);
            }
            $sql = $db->table('v_saldo_harian')->where([
                'no_rekening' => $key
            ])->orderBy('tahun', 'desc')->get()->getResult();

            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $res[] = array(
                        'tahun' => $x->tahun,
                        'nilai_investasi' => number_format($x->nilai_investasi, '2', ',', '.'),
                        'nilai_rata_harian' => number_format($x->rata_rata_harian, '2', ',', '.'),
                        'nilai_rata_tahun' => number_format($x->rata_rata_tahun, '2', ',', '.')
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getDevidenId($key)
    {
        $db = \Config\Database::connect();
        try {
            if (is_null($key) || empty($key)) {
                return $this->respond([], 200);
            }
            $sql = $db->table('v_bagi_hasil')->where([
                'no_rekening' => $key
            ])->orderBy('tahun', 'desc')->get()->getResult();

            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $res[] = array(
                        'tahun' => $x->tahun,
                        'saham_persero' => number_format($x->saham_persero, '2', ',', '.'),
                        'shu' => number_format($x->shu, '2', ',', '.'),
                        'bagi_hasil' => number_format($x->nisbah_bagi_hasil, '2', ',', '.')
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getTransaksiId($key)
    {
        $db = \Config\Database::connect();
        try {
            if (is_null($key) || empty($key)) {
                return $this->respond([], 200);
            }
            $sql = $db->table('v_transaksi')->where([
                'no_rekening' => $key,
                'year(tgl_transaksi) <=' => date('Y')
            ])->orderBy('tgl_transaksi', 'desc')->get()->getResult();

            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $tgl_lahir = date("d-m-Y", strtotime($x->tgl_lahir));
                    $tgl_transaksi = date("d-m-Y", strtotime($x->tgl_transaksi));
                    $res[] = array(
                        'no_rekening' => $x->no_rekening,
                        'nama' => ucwords($x->nama),
                        'nik' => $x->nik,
                        'tgl_lahir' => ucwords($x->tmp_lahir . ', ' . $tgl_lahir),
                        'tgl_transaksi' => $tgl_transaksi,
                        'jml_transaksi' => number_format($x->jml_transaksi, 2, ',', '.'),
                        'no_sertifikat' => $x->no_sertifikat
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getTransaksiSaham($key)
    {
        $db = \Config\Database::connect();
        try {
            if (is_null($key) || empty($key)) {
                return $this->respond([], 200);
            }
            $sql = $db->table('v_transaksi')->where([
                'no_sertifikat' => $key,
            ])->get()->getResult();

            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $tgl_lahir = date("d-m-Y", strtotime($x->tgl_lahir));
                    $tgl_transaksi = date("d-m-Y", strtotime($x->tgl_transaksi));
                    $res[] = array(
                        'no_rekening' => $x->no_rekening,
                        'nama' => ucwords($x->nama),
                        'nik' => $x->nik,
                        'tgl_lahir' => ucwords($x->tmp_lahir . ', ' . $tgl_lahir),
                        'tgl_transaksi' => $tgl_transaksi,
                        'jml_transaksi' => number_format($x->jml_transaksi, 2, ',', '.'),
                        'no_sertifikat' => $x->no_sertifikat
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getSaldoInvestasi($key, $year)
    {
        $db = \Config\Database::connect();
        $temp = 0;
        try {
            if (is_null($key) || empty($key) || is_null($year) || empty($year)) {
                return $this->respond([], 200);
            }
            $sql = $db->table('v_saldo_investasi')->where([
                'no_rekening' => $key,
                'year(tanggal)' => $year
            ])->orderBy('tanggal', 'asc')->get()->getResult();

            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $tanggal = date("d-m-Y", strtotime($x->tanggal));
                    $tgl = explode('-', $tanggal);

                    if ($tgl[0] == '01' && $tgl[1] == '01') {
                        $ket = "Saldo Awal";
                    } else {
                        $ket = "-";
                    }

                    $temp = $temp + $x->jml_transaksi - $x->jml_penarikan;

                    $res[] = array(
                        'no_rekening' => $x->no_rekening,
                        'tanggal' => $tanggal,
                        'jml_transaksi' => number_format($x->jml_transaksi, 2, ',', '.'),
                        'jml_penarikan' => number_format($x->jml_penarikan, 2, ',', '.'),
                        'saldo' => number_format($temp, 2, ',', '.'),
                        'ket' => $ket
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getDispen($key)
    {
        $db = \Config\Database::connect();
        try {
            if (is_null($key) || empty($key)) {
                return $this->respond([], 200);
            }
            $sql = $db->table('v_distribusi_porsi')->where([
                'tahun' => $key,
                'jenis' => 'dispen'
            ])->get()->getResult();

            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $res[] = array(
                        'porsi' => $x->porsi,
                        'uraian' => $x->uraian,
                        'nilai' => number_format($x->nilai, 2, ',', '.'),
                        'jumlah' => number_format($x->jumlah, 2, ',', '.'),
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }

    public function getDisda($key)
    {
        $db = \Config\Database::connect();
        try {
            if (is_null($key) || empty($key)) {
                return $this->respond([], 200);
            }
            $sql = $db->table('v_distribusi_porsi')->where([
                'tahun' => $key,
                'jenis' => 'disda'
            ])->get()->getResult();

            $res = [];
            if (count($sql) > 0 || $sql) {
                foreach ($sql as $x) {
                    $res[] = array(
                        'porsi' => $x->porsi,
                        'uraian' => $x->uraian,
                        'nilai' => number_format($x->nilai, 2, ',', '.'),
                        'jumlah' => number_format($x->jumlah, 2, ',', '.'),
                    );
                }
            }
            return $this->respond($res, 200);
        } catch (Exception $ex) {
            $this->failNotFound($ex);
        }
    }
}
