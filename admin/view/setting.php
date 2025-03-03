<div class="row">
        <!-- left column -->
        <div class="col-md-12">
        <?php if(isset($_SESSION['pesan']) && $_SESSION['pesan']=='edit'){?>
          <div style="display: none;" class="alert alert-success alert-dismissable">Pengaturan Berhasil Diubah
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        <?php } $_SESSION['pesan'] = '';?>
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> <i class="glyphicon glyphicon-cog"></i> Pengaturan Aplikasi</h3>
            </div>
            <!-- form start -->
            <form role="form" method="post" enctype="multipart/form-data" action="<?php echo $basead; ?>a-control/<?php echo md5('aplikasi'); ?>/access">
              <div class="box-body">
                <div class="form-group">
                  <label>NAMA SEKOLAH</label>
                  <input type="text" name="namasek" class="form-control" value="<?php echo $aplikasi['namasek']; ?>">
                </div>
                <div class="form-group">
                  <label>ALAMAT SEKOLAH</label>
                  <input type="text" name="alamat" class="form-control" value="<?php echo $aplikasi['alamat']; ?>">
                </div>
                <div class="row">
                  <div class="col-xs-6">
                    <label>KEPALA SEKOLAH</label>
                    <input type="text" name="kepsek" class="form-control" value="<?php echo $aplikasi['kepsek']; ?>">
                  </div>
                  <div class="col-xs-6">
                    <label>NIP KEPALA SEKOLAH</label>
                    <input type="text" name="nipkepsek" class="form-control" value="<?php echo $aplikasi['nipkepsek']; ?>">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-ok"></i> Simpan Perubahan</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
</div>