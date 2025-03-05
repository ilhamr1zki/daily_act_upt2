<?php 
	
	$timeOut        = $_SESSION['expire_paud'];
    
	$timeRunningOut = time() + 5;

	$timeIsOut      = 0;

	error_reporting(1);

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    // error_reporting(1);

	} else {

    if (isset($_POST['ins_guru'])) {
      
      $c_guru         = random(9); 
      $nip            = htmlspecialchars($_POST['nip']);
      $nama           = mysqli_real_escape_string($con, htmlspecialchars($_POST['nama']));
      $alamat         = mysqli_real_escape_string($con, htmlspecialchars($_POST['alamat']));
      $tl             = date('Y-m-d',strtotime($_POST['tl'])); 
      $username       = htmlspecialchars($_POST['username']);
      $password       = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
      $tgl_join       = date('Y-m-d',strtotime($_POST['tgljoin'] ?? "1900-01-01"));
      $c_jabatan      = htmlspecialchars($_POST['jabatan']);
      $jkel           = htmlspecialchars($_POST['jk']); 
      $alamatlengkap  = mysqli_real_escape_string($con, htmlspecialchars($_POST['_alamatrumah'])); 
      $pendidikan     = htmlspecialchars($_POST['_pendidikan']); 
      $jurusan        = mysqli_real_escape_string($con, htmlspecialchars($_POST['_jurusan'])); 
      $email          = htmlspecialchars($_POST['_email']);
      $no_hp          = htmlspecialchars($_POST['nohp']);

      $queryInsGuru = mysqli_query($con, "
        INSERT INTO guru
        SET
        c_guru      = '$c_guru',
        nip         = '$nip',
        nama        = '$nama',
        temlahir    = '$alamat',
        tanglahir   = '$tl',
        tgl_join    = '$tgl_join',
        c_jabatan   = '$c_jabatan',
        jkel        = '$jkel',
        alamat      = '$alamatlengkap',
        pendidikan  = '$pendidikan',
        jurusan     = '$jurusan',
        email       = '$email',
        username    = '$username',
        password    = '$password',
        no_hp       = '$no_hp'
      ");

      if ($queryInsGuru) {
        $_SESSION['pesan'] = 'tambah';
      } else {
        $_SESSION['pesan'] = 'gagal';
      }

    }

  }

?>

<div class="row">
        <!-- left column -->
        <div class="col-md-12">

        <?php if(isset($_SESSION['pesan']) && $_SESSION['pesan']=='tambah'){?>
          <div style="display: none;" class="alert alert-warning alert-dismissable"> GURU BARU BERHASIL DI TAMBAHKAN !
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        <?php } $_SESSION['pesan'] = '';?>

        <?php if(isset($_SESSION['pesan']) && $_SESSION['pesan']=='tambah'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable">Guru Gagal Ditambahkan
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        <?php } $_SESSION['pesan'] = '';?>

        <?php if(isset($_SESSION['pesanx']) && $_SESSION['pesanx'] == 'jabatan'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Penulisan Di Kolom Jabatan Invalid 
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        <?php } $_SESSION['pesanx'] = '';?>

          <!-- general form elements -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> <i class="glyphicon glyphicon-edit"></i> Tambah Data Guru</h3><span style="float: right;"> <a href="<?= $basead; ?>teacher" class="btn btn-circle btn-primary"> <i class="glyphicon glyphicon-list-alt"></i> Daftar Guru </a> </span> 
            </div>
            <!-- form start -->
            <form method="post">
              <div class="box-body">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                          <label>NIP<sup style="color: red;">*</sup></label>
                          <input type="text" id="inp_nip" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="7" placeholder="Hanya Boleh Angka" name="nip" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                          <label>NAMA</label>
                          <input type="text" name="nama" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                          <label>TANGGAL LAHIR</label>
                          <div class="controls input-append date form_date" data-date="1998-10-14" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                              <input class="form-control" type="text" name="tl" value="" required="">
                              <span class="add-on"><i class="icon-th"></i></span>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ALAMAT LAHIR</label>
                      <input type="text" required="" name="alamat" class="form-control">
                    </div>
                  </div>
                  <div class="col-sm-6">
                        <div class="form-group">
                          <label>JENIS KELAMIN</label>&nbsp;&nbsp;&nbsp;
                          <label><input type="radio" name="jk" value="L"> Laki-Laki</label>&nbsp;&nbsp;
                          <label><input type="radio" name="jk" value="P"> Perempuan</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                        <div class="form-group">
                          <label>ALAMAT</label>
                          <input type="text" class="form-control" id="_alamatrumah" name="_alamatrumah">
                      </div>
                  </div>
                  <div class="col-sm-2">
                      <div class="form-group">
                          <label>PENDIDIKAN</label>
                          <select id="_pendidikan" name="_pendidikan"  class="form-control form-select">
                          <option value="">--Pilih--</option>
                              <?php 
                                  foreach($pendidikans as $item){
                                    echo "<option value='$item'>$item</option>";
                                }
                              ?>
                          </select>
                      </div>
                  </div>
                  <div class="col-sm-4">
                        <div class="form-group">
                          <label>JURUSAN</label>
                          <input type="text" class="form-control" id="_jurusan" name="_jurusan">
                      </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>TANGGAL JOIN</label>
                        <div class="controls input-append date form_date" data-date="14-10-1998" data-date-format="dd-mm-yyyy" data-link-field="dtp_input1">
                            <input class="form-control" type="text" name="tgljoin" required="">
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="form-group">
                        <label>JABATAN</label>
                        <input type="text" placeholder="Contoh Penulisan : Guru SD/PAUD, Kepala Sekolah SD/PAUD, Kepala Unit Poros Iman SD/PAUD" name="jabatan" class="form-control" onkeydown = "return /[a-zA-Z0-9 ]/i.test(event.key)">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>EMAIL</label>
                        <input type="email"  name="_email" class="form-control">
                      </div>
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>NO.HP / WA YANG AKTIF<sup style="color: red;">*</sup></label>
                      <input type="text" required="" placeholder="628xxx / 08xxx" name="nohp" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="12" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>USERNAME</label>
                      <input type="text" required="" name="username" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>PASSWORD</label>
                      <input type="password" required="" id="password" name="password" class="form-control">
                      <div class="checkbox" id="swp1" onmouseover="mouseOver1()">
                        <i class="glyphicon glyphicon-eye-open" id="icnEye1"></i> <span id="said1"> Show </span> Password
                      </div>  
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" name="ins_guru" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-ok"></i> Simpan</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
</div>
<!-- jQuery 2.2.3 -->
<script src="<?php echo $base; ?>theme/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo $base; ?>theme/datetime/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo $base; ?>theme/datetime/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0,
        showMeridian: 1
    });
  $('.form_date').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0
    });
  $('.form_time').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 1,
    minView: 0,
    maxView: 1,
    forceParse: 0
  });

  $("#inp_nip").focus();

  $("#swp1").click(function(){
    let x = document.getElementById("password");
    if (x.type === "password") {
        $("#icnEye1").removeClass("glyphicon-eye-open");
        $("#icnEye1").addClass("glyphicon-eye-close");
        $("#said1").text('Close')
        x.type = "text";
    } else {
        x.type = "password";
        $("#icnEye1").removeClass("glyphicon-eye-close");
        $("#icnEye1").addClass("glyphicon-eye-open");
        $("#said1").text('Show')
    }
  })

  $('#inp_nip').keypress(function (e) {
    if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
  });

  function mouseOver1() {
    document.getElementById("swp1").style.cursor = "pointer";
  }

  function onlyNumberKey(evt) {
    // Only ASCII character in that range allowed
    let ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
  }

  $(document).ready(function() {
  	let newIcon = document.getElementById("addIcon");
	    newIcon.classList.remove("fa");
	    newIcon.classList.add("glyphicon");
	    newIcon.classList.add("glyphicon-user");

	    let getTitleList2 = document.getElementById('addtc').innerHTML;

	    $("#isiMenu").css({
	      "margin-left" 	: "3px",
	      "font-weight" 	: "bold",
	      "text-transform"	: "uppercase"
	    });

	    document.getElementById('isiMenu').innerHTML = getTitleList2
  })

</script>