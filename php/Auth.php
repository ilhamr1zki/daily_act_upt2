<?php

/**
 * Class Auth untuk melakukan login dan registrasi user baru
 */
class Auth {
    /**
     * @var
     * Menyimpan Koneksi database
     */
    private $db;

    /**
     * @var
     * Menyimpan Error Message
     */
    private $error;

    /**
     * @var
     * Menyimpan Email
     */
    private $emailUser;

    /**
     * @var
     * Menyimpan Nama
     */
    private $nameUser;

    /**
     * @var
     * Menyimpan Kode Message
     */
    private $kode;

    /**
     * @param $db_conn
     * Contructor untuk class Auth, membutuhkan satu parameter yaitu koneksi ke database
     */
    public function __construct($db_conn) {
        $this->db = $db_conn;

        // Mulai session
        session_start();
    }
    /**
     * @param $name
     * @param $role
     * @param $password
     * @return bool

    /**
     * @param $email
     * @param $password
     * @return bool
     *
     * fungsi login user
     */
    public function loginAdmin($username, $password) {

        try {
            // Ambil data dari database
            // echo $password;exit;
            $login = $this->db->prepare("
                SELECT 
                `admin`.c_admin as id_admin, `admin`.nama as level_user, `admin`.username as username, `admin`.password as password
                FROM admin
                WHERE username = :username  
            ");
            $login->bindParam(":username", $username);
            $login->execute();
            $data = $login->fetch();
            // Jika jumlah baris > 0
            if ($login->rowCount() > 0) {
                // jika password yang dimasukkan sesuai dengan yg ada di database
                if (password_verify($password, $data['password'])) {
                    $_SESSION['key_admin_paud']  = $data['id_admin'];
                    $_SESSION['name_user_paud']  = $data['username'];
                    $_SESSION['start_sess_paud'] = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']     = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;
                } else {    
                    // echo "Salah";exit;
                    $this->error = "Wrong Password !";
                    $this->kode = 2;

                    return false;
                }

            } else {

                $this->error = "fail_login!";
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    public function loginHeadMaster($username, $password) {

        try {
            // Ambil data dari database
            // echo $password;exit;
            $login = $this->db->prepare("
                SELECT 
                `kepala_sekolah`.id as id_kepsek, `guru`.c_jabatan as c_jabatan, `kepala_sekolah`.nip as nip_kepsek, `kepala_sekolah`.nama as nama_kepsek, `kepala_sekolah`.username as username, `kepala_sekolah`.password as password
                FROM kepala_sekolah
                LEFT JOIN guru
                ON kepala_sekolah.nip = guru.nip
                WHERE kepala_sekolah.username = :username  
            ");
            $login->bindParam(":username", $username);
            $login->execute();
            $data = $login->fetch();
            // Jika jumlah baris > 0
            if ($login->rowCount() > 0) {
                // jika password yang dimasukkan sesuai dengan yg ada di database
                if (password_verify($password, $data['password'])) {
                    $_SESSION['id_kepsek_paud']          = $data['id_kepsek'];
                    $_SESSION['name_kepsek_paud']        = $data['nama_kepsek'];
                    $_SESSION['nip_kepsek_paud']         = $data['nip_kepsek'];
                    $_SESSION['c_kepsek_paud']           = $data['c_jabatan'];
                    $_SESSION['username_kepsek_paud']    = $data['username'];
                    $_SESSION['start_sess_paud']         = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']     = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;
                } else {    
                    // echo "Salah";exit;
                    $this->error = "Wrong Password !";
                    $this->kode = 2;

                    return false;
                }

            } else {

                $this->error = "fail_login!";
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    public function loginNotifHeadMaster($username, $password, $rkey) {

        try {
            // Ambil data dari database
            // echo $password;exit;
            $login = $this->db->prepare("
                SELECT 
                `kepala_sekolah`.id as id_kepsek, `guru`.c_jabatan as c_jabatan, `kepala_sekolah`.nip as nip_kepsek, `kepala_sekolah`.nama as nama_kepsek, `kepala_sekolah`.username as username, `kepala_sekolah`.password as password
                FROM kepala_sekolah
                LEFT JOIN guru
                ON kepala_sekolah.nip = guru.nip
                WHERE kepala_sekolah.username = :username  
            ");
            $login->bindParam(":username", $username);
            $login->execute();
            $data = $login->fetch();
            // Jika jumlah baris > 0
            if ($login->rowCount() > 0) {
                // jika password yang dimasukkan sesuai dengan yg ada di database
                if (password_verify($password, $data['password'])) {
                    $_SESSION['id_kepsek_paud']          = $data['id_kepsek'];
                    $_SESSION['name_kepsek_paud']        = $data['nama_kepsek'];
                    $_SESSION['nip_kepsek_paud']         = $data['nip_kepsek'];
                    $_SESSION['c_kepsek_paud']           = $data['c_jabatan'];
                    $_SESSION['username_kepsek_paud']    = $data['username'];
                    $_SESSION['roomkeys_paud']           = $rkey;
                    $_SESSION['start_sess_paud']         = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']     = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;
                } else {    
                    // echo "Salah";exit;
                    $this->error = "Wrong Password !";
                    $this->kode = 2;

                    return false;
                }

            } else {

                $this->error = "fail_login!";
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    public function loginGuru($username, $password) {

        try {
            // Ambil data dari database
            // echo $password;exit;
            $login = $this->db->prepare("
                SELECT 
                `guru`.nip as nip_guru, `guru`.c_guru as c_guru, `guru`.username as username, `guru`.c_jabatan as c_jabatan, `guru`.password as password,
                `guru`.nama as nama
                FROM guru
                WHERE username = :username  
            ");
            $login->bindParam(":username", $username);
            $login->execute();
            $data = $login->fetch();
            // Jika jumlah baris > 0
            if ($login->rowCount() > 0) {
                // jika password yang dimasukkan sesuai dengan yg ada di database
                if (password_verify($password, $data['password'])) {
                    $_SESSION['c_guru_paud']         = $data['c_jabatan'];
                    $_SESSION['nama_guru_paud']      = $data['nama'];
                    $_SESSION['nip_guru_paud']       = $data['nip_guru'];
                    $_SESSION['jabatan_paud']        = "guru";
                    $_SESSION['username_guru_paud']  = $data['username'];
                    $_SESSION['key_guru_paud']       = $data['c_guru'];
                    $_SESSION['start_sess_paud']     = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']         = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;
                } else {    
                    // echo "Salah";exit;
                    $this->error = "Wrong Password !";
                    $this->kode = 2;

                    return false;
                }

            } else {

                $this->error = "fail_login!";
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    public function loginNotifGuru($username, $password, $rkey) {

        try {
            // Ambil data dari database
            // echo $password;exit;
            $login = $this->db->prepare("
                SELECT 
                `guru`.nip as nip_guru, `guru`.c_guru as c_guru, `guru`.username as username, `guru`.c_jabatan as c_jabatan, `guru`.password as password,
                `guru`.nama as nama
                FROM guru
                WHERE username = :username  
            ");
            $login->bindParam(":username", $username);
            $login->execute();
            $data = $login->fetch();
            // Jika jumlah baris > 0
            if ($login->rowCount() > 0) {
                // jika password yang dimasukkan sesuai dengan yg ada di database
                if (password_verify($password, $data['password'])) {
                    $_SESSION['c_guru_paud']         = $data['c_jabatan'];
                    $_SESSION['nama_guru_paud']      = $data['nama'];
                    $_SESSION['nip_guru_paud']       = $data['nip_guru'];
                    $_SESSION['jabatan_paud']        = "guru";
                    $_SESSION['username_guru_paud']  = $data['username'];
                    $_SESSION['key_guru_paud']       = $data['c_guru'];
                    $_SESSION['roomkeys_paud']       = $rkey;
                    $_SESSION['start_sess_paud']     = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']         = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;
                } else {    
                    // echo "Salah";exit;
                    $this->error = "Wrong Password !";
                    $this->kode = 2;

                    return false;
                }

            } else {

                $this->error = "fail_login!";
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    public function loginOtm($username, $password) {

        try {
            // Ambil data dari database
            // echo $password;exit;

            $tampungPassword = [];

            // $queryGetDataPasswordAwal = mysqli_query($con, "SELECT password FROM akses_otm");
            $getAllPassword = $this->db->prepare("
                SELECT password FROM akses_otm
            ");

            $getAllPassword->execute();
            $allDataPw = $getAllPassword->fetchAll();

            for ($i=0; $i < count($allDataPw); $i++) {
                $tampungPassword[] = $allDataPw[$i]['password'];
                // echo $allDataPw[$i]['password'] . "<br>";
            }

            // exit;

            $getPasswordOTM = $this->db->prepare("
                SELECT password FROM akses_otm WHERE nis_siswa = :nis_siswa
            ");

            $getPasswordOTM->bindParam(":nis_siswa", $username);
            $getPasswordOTM->execute();
            $allDataPwOTM = $getPasswordOTM->fetch();

            $login = $this->db->prepare("
                SELECT 
                `akses_otm`.nis_siswa as nis_siswa, `siswa`.c_siswa as c_siswa, `siswa`.c_kelas as kelas_siswa, `siswa`.nama as nama_siswa, `akses_otm`.password as password
                FROM akses_otm
                LEFT JOIN
                siswa
                ON `akses_otm`.nis_siswa = `siswa`.nis
                WHERE nis_siswa = :nis_siswa  
            ");
            $login->bindParam(":nis_siswa", $username);
            $login->execute();
            $data = $login->fetch();
            // Jika jumlah baris > 0
            if ($login->rowCount() > 0) {

                if (in_array($password,$tampungPassword)) {
                    // echo $password . " Terdaftar";exit;
                
                    $_SESSION['c_otm_paud']          = $data['nis_siswa'];
                    $_SESSION['bag_siswa_paud']      = $data['c_siswa'];
                    $_SESSION['get_pw_paud']         = $password;
                    $_SESSION['username_otm_paud']   = strtoupper($data['nama_siswa']);
                    $_SESSION['kls_siswa_paud']      = $data['kelas_siswa']; 
                    $_SESSION['start_sess_paud']     = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']         = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;

                } else if (password_verify($password, $allDataPwOTM['password'])) {
                    
                    $_SESSION['c_otm_paud']          = $data['nis_siswa'];
                    $_SESSION['bag_siswa_paud']      = $data['c_siswa'];
                    $_SESSION['get_pw_paud']         = $password;
                    $_SESSION['username_otm_paud']   = strtoupper($data['nama_siswa']);
                    $_SESSION['kls_siswa_paud']      = $data['kelas_siswa']; 
                    $_SESSION['start_sess_paud']     = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']         = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;

                } else {

                    $this->error = "Wrong Password !";
                    $this->kode = 2;

                    return false;

                  }

            } else {

                $this->error = "fail_login!";
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    public function loginNotifOtm($username, $password, $rkey) {

        try {
            

            $tampungPassword = [];

            // $queryGetDataPasswordAwal = mysqli_query($con, "SELECT password FROM akses_otm");
            $getAllPassword = $this->db->prepare("
                SELECT password FROM akses_otm
            ");

            $getAllPassword->execute();
            $allDataPw = $getAllPassword->fetchAll();

            for ($i=0; $i < count($allDataPw); $i++) {
                $tampungPassword[] = $allDataPw[$i]['password'];
                // echo $allDataPw[$i]['password'] . "<br>";
            }

            // exit;

            $getPasswordOTM = $this->db->prepare("
                SELECT password FROM akses_otm WHERE nis_siswa = :nis_siswa
            ");

            $getPasswordOTM->bindParam(":nis_siswa", $username);
            $getPasswordOTM->execute();
            $allDataPwOTM = $getPasswordOTM->fetch();

            $login = $this->db->prepare("
                SELECT 
                `akses_otm`.nis_siswa as nis_siswa, `siswa`.c_siswa as c_siswa, `siswa`.c_kelas as kelas_siswa, `siswa`.nama as nama_siswa, `akses_otm`.password as password
                FROM akses_otm
                LEFT JOIN
                siswa
                ON `akses_otm`.nis_siswa = `siswa`.nis
                WHERE nis_siswa = :nis_siswa  
            ");
            $login->bindParam(":nis_siswa", $username);
            $login->execute();
            $data = $login->fetch();
            // Jika jumlah baris > 0
            if ($login->rowCount() > 0) {

                if (in_array($password,$tampungPassword)) {
                    // echo $password . " Terdaftar";exit;
                
                    $_SESSION['c_otm_paud']          = $data['nis_siswa'];
                    $_SESSION['bag_siswa_paud']      = $data['c_siswa'];
                    $_SESSION['get_pw_paud']         = $password;
                    $_SESSION['username_otm_paud']   = strtoupper($data['nama_siswa']);
                    $_SESSION['kls_siswa_paud']      = $data['kelas_siswa']; 
                    $_SESSION['roomkeys_paud']       = $rkey;
                    $_SESSION['start_sess_paud']     = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']         = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;

                } else if (password_verify($password, $allDataPwOTM['password'])) {
                    
                    $_SESSION['c_otm_paud']          = $data['nis_siswa'];
                    $_SESSION['bag_siswa_paud']      = $data['c_siswa'];
                    $_SESSION['get_pw_paud']         = $password;
                    $_SESSION['username_otm_paud']   = strtoupper($data['nama_siswa']);
                    $_SESSION['kls_siswa_paud']      = $data['kelas_siswa']; 
                    $_SESSION['roomkeys_paud']       = $rkey;
                    $_SESSION['start_sess_paud']     = time();
                    // Session Will Be Expired after 30 Minute
                    $_SESSION['expire_paud']         = $_SESSION['start_sess_paud'] + (30 * 60);
                    return true;

                } else {

                    $this->error = "Wrong Password !";
                    $this->kode = 2;

                    return false;

                  }

            } else {

                $this->error = "fail_login!";
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    /**
     * @return true|void
     *
     * fungsi cek login user
     */
    public function isLoggedInAdmin() {
        // Apakah user_session sudah ada di session

        if (isset($_SESSION['key_admin_paud']) ) {
            return true;
        } else {
            return false;
        }

    }

    public function isLoggedInHeadMaster() {
        // Apakah user_session sudah ada di session

        if (isset($_SESSION['id_kepsek_paud']) ) {
            return true;
        } else {
            return false;
        }

    }

    public function isLoggedInGuru() {
        // Apakah user_session sudah ada di session

        if (isset($_SESSION['c_guru_paud']) ) {
            return true;
        } else {
            return false;
        }

    }

    public function isLoggedInOTM() {

        if (isset($_SESSION['c_otm_paud']) ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @return false
     *
     * fungsi ambil data user yang sudah login
     */
    public function getUserAdmin() {
        // Cek apakah sudah login
        if (!$this->isLoggedIn()) {
            return false;
        }

        try {
            // Ambil data user dari database
            $stmt = $this->db->prepare("
                SELECT 
                `admin`.c_admin as id_admin, `admin`.nama as level_user, `admin`.username as username, `admin`.password as password
                FROM admin
                WHERE c_admin = :c_admin");
            $stmt->bindParam(":c_admin", $_SESSION['c_admin_paud']);
            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }

    }

    /**
     * @return true
     *
     * fungsi Logout user
     */
    public function logout() {

        // Hapus session
        session_destroy();

        return true;
    }

    /**
     * @return mixed
     *
     * fungsi ambil error terakhir yg disimpan di variable error
     */
    public function getLastError() {
        return $this->error;
    }

    public function getNameUser() {
        return $this->nameUser;
    }

    public function getEmailUser() {
        return $this->emailUser;
    }

    public function getCodeUser() {
        return $this->kode;
    }

    public function countDataMessage($status_approve = 'kosong') {
        try {

            $user_id = $_SESSION['user_id'];

            if ($status_approve == 'kosong' || $status_approve == 0 || $status_approve == 1) {
                
                // echo "Masuk Ke if";
                $getDataNotif   = $this->db->prepare("SELECT * FROM message_approve WHERE status_approve = '1'");
                // $getDataNotif->bindParam(":stat_approve", $status_approve);
                $getDataNotif->execute();
                $getDataNotif->rowCount();
                $data = $getDataNotif->fetchAll();
                $hitungDataNotif = $getDataNotif->rowCount();
                return $hitungDataNotif;

            } else if ($status_approve !== 'kosong' || $status_approve !== 0) {

                // echo "Masuk Ke else if ";

                $getDataNotif   = $this->db->prepare("SELECT * FROM message_approve WHERE user_id = '$user_id' AND status_approve = 2 OR status_approve = 3");
                // $getDataNotif->bindParam(":stat_approve", 2);
                // $getDataNotif->bindParam(":stat_approve_2", 3);
                $getDataNotif->execute();
                $getDataNotif->rowCount();
                $data = $getDataNotif->fetchAll();
                $hitungDataNotif = $getDataNotif->rowCount();
                return $hitungDataNotif;

            }


        } catch (Exception $e) {
            
            echo $e->getMessage();

            return false;

        }
    }

}