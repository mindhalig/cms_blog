<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Testimoni Terpilih</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/edit_testimoni',$attributes); 
              
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                    <tbody>
                      <input type='hidden' name='id' value='$rows[id_testimoni]'>
                      <tr><th width='120px' scope='row'>Nama</th>   <td><input type='text' class='form-control' name='a' value='$rows[nama_testimoni]'></td></tr>
                      <tr><th scope='row'>Deskripsi</th>           <td><textarea class='ckeditor form-control' name='b' style='height:260px'>$rows[deskripsi_testimoni]</textarea></td></tr>
                     
                      <tr><th scope='row'>Job</th>               <td><input type='text' class='form-control' name='d' value='$rows[pekerjaan_testimoni]'></td></tr>
             
                  </table>
                </div>
              
              <div class='box-footer'>
                  <button type='submit' name='submit' class='btn btn-info'>Update</button>
                  <a href='index.php'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                </div>
            </div></div></div>";
            echo form_close();
