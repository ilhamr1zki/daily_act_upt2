<?php $search=str_replace("_", " ", $_GET['q']); $c=mysqli_query($con,"SELECT * FROM siswa where nisn='$search' or nama like'%$search%' ");$hcs=mysqli_fetch_array($c); $bul=date('m'); //echo $cnisn['nisn']; ?>
<div class="row">
  <div class="col-xs-12 col-md-12 col-lg-12">
    <div class="box box-info">
      <div class="box-body">
       <i class="glyphicon glyphicon-search"></i> Hasil Pencarian NISN Atau NAMA : <?php echo $search; ?>
      <span style="float:right;text-transform: uppercase;"><?php echo $hcs['nama']; ?></span>
      </div>
    </div>
  </div>
</div>
<?php if($hcs==NULL){ ?>
  <div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">
      <div class="box box-danger">
        <div class="box-body"> <i class="glyphicon glyphicon-search"></i> Tidak Ada Siswa Yang Sesuai Dengan NISN Atau NAMA <?php echo $search; ?></div>
      </div>
    </div>
  </div>
<?php }else { ?>
  <div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">
      <div class="box box-danger">
        <div class="box-header">
           <i class="glyphicon glyphicon-search"></i> <span style="text-transform: uppercase;"> <?php echo $hcs['nama']; ?></span>
        </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover">
              <thead>
               <tr>
                  <th width="5%">NO</th>
                  <th>MATA PELAJARAN</th>
                  <th width="10%">KKM</th>
                   <?php $lihtip=mysqli_query($con,"SELECT * FROM tipenilai order by p asc ");while($hlihtip=mysqli_fetch_array($lihtip)){echo '<th width="10%">'.$hlihtip['tipe'].'</th>';
                    }?>
                </tr>
              </thead>
              <tbody>
<?php 
  $pel=mysqli_query($con,"SELECT * FROM mapel order by mapel asc "); $no=1;
    while($hpel=mysqli_fetch_array($pel)){
    //tipe nilai
?>
               <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $hpel['mapel']; ?></td>
                <td><b><?php echo $hpel['sl']; ?></b></td>
<?php
    $lihtip=mysqli_query($con,"SELECT * FROM tipenilai order by p asc ");
    while($hlihtip=mysqli_fetch_array($lihtip)){ 
      $nil=mysqli_fetch_array(mysqli_query($con,"SELECT sum(nilai) as nilai FROM nilai where c_ta='$c_ta' and c_tipenilai='$hlihtip[c_tipenilai]' and c_siswa='$hcs[c_siswa]' and c_kelas='$hcs[c_kelas]' and c_mapel='$hpel[c_mapel]' "));
        if($nil['nilai']<1){echo '<td><b></b></td>';}else{
          echo '<td><b>'.$nil['nilai'].'</b></td>';
        }                    
    }
?>
              </tr>
<?php } ?>
            </tbody>
              </table>
            </div>    
      </div>
    </div>
  </div>
<?php } ?>