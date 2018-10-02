<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/dropzone/css/dropzone.min.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/dropzone/css/basic.min.css') ?>" />
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

<section class="content">
  <!-- <div class="text-center">
  <?php //$supplier_id = $this->session->userdata('user_id'); ?>
  <a href="<?php //echo site_url('supplier/public_supplier_detail_view?supplier_id=').$supplier_id ?>" class="text-center btn btn-info">
  <span class="glyphicon glyphicon-eye-open"></span> Preview Mini Site</a>
</div> -->

<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
      <div class="box-header">
        <h2 class="box-title">Account</h2>
      </div>

      <?php //echo $error;?>
      <div class="box-body">

        <!-- <a href="">                                                </a> -->









        <div class="row">
          <div class="col-sm-3">
            <!--left col-->


            <div class="text-center">
              <img src="<?php if (empty($user[0]->ProfileImage)) {
                echo base_url().'assets/icon/upload-icon.png';
              }else{
                echo base_url().'assets/supplier_upload/'.$user[0]->ProfileImage;
              }?>" alt="" class="img-thumbnail image img-circle" alt="Cinque Terre" width="175" height="175">
              <?php if ($user[0]->IsVerifiedSupplier == 1): ?>
                <p><img src="<?php echo base_url().'assets/supplier_upload/'.'verified.png' ?>" alt="" width="25"></p>
                <p style="font-style: oblique;font-weight: bold; margin-top: -12px; margin-bottom: 3px;">Verified Supplier</p>
              <?php else: ?>
                <p><img src="<?php echo base_url().'assets/supplier_upload/'.'unverified.png' ?>" alt="" width="25"></p>
                <p style="font-style: oblique;font-weight: bold; margin-top: -12px; margin-bottom: 3px;">Unverified Supplier
                  <a href="#" data-toggle="popover"  title="How to be verified supplier" data-content=" to become a verified supplier You can contact Dinilaku Admin on the following email dinilaku@gmail.com ">
                    <span class="glyphicon glyphicon-info-sign">
                    </span>
                  </a>
                </p>
              <?php endif; ?>
              <p>
                <?php $supplier_id = $this->session->userdata('user_id'); ?>
                <a href="<?php echo site_url('User/supplier_mini_site_view?')."supplier_id=".$supplier_id ?>" class="text-center ">
                  <span class="glyphicon glyphicon-eye-open"></span> Preview Mini Site</a>
                </p>

              </div>
            </hr>
            <br>

            <div class="panel panel-default">
              <div class="panel-heading">
                Company Profile
              </div>
              <div class="panel-body" style="display:block;height: unset;">
                <h5><b>Member level</b></h5>
                <p>
                  <i>
                    <?php
                    if ($user[0]->UserLevel == 1) {
                      echo "Supplier Only";
                    }elseif ($user[0]->UserLevel == 3){
                      echo "Supplier & Buyer";
                    }
                    ?>

                  </i>
                  <a href="#" data-toggle="popover"  title="Member Level" data-content=" To change member level you can contact Dinilaku Admin in the following email dinilaku@gmail.com">
                    <span class="glyphicon glyphicon-info-sign"></span>
                  </a>
                </p>
                <h5><b>First Name</b></h5>
                <p><?php echo $user[0]->FirstName; ?></p>
                <h5><b>Last Name</b></h5>
                <p><?php echo $user[0]->LastName; ?></p>
                <h5><b>Company Name</b></h5>
                <p><?php echo $user[0]->CompanyName; ?></p>
                <h5><b>Zip Code</b></h5>
                <p><?php echo $user[0]->ZipCode; ?></p>
                <h5><b>Company Address</b></h5>
                <p><?php echo $user[0]->Address; ?></p>
                <h5><b>City</b></h5>
                <p><?php echo $user[0]->City; ?></p>
                <h5><b>Province</b></h5>
                <p><?php echo $user[0]->Province; ?></p>
                <h5><b>State</b></h5>
                <p><?php echo $user[0]->State; ?></p>
                <h5><b>Phone Number</b></h5>
                <p><?php echo $user[0]->Phone; ?></p>
                <h5><b>Company Description</b></h5>
                <p><?php echo $user[0]->CompanyDescription; ?></p>
              </div>
            </div>

          </div>
          <!--/col-3-->
          <div class="col-sm-9">
            <ul class="nav nav-tabs">
              <li class="active">
                <a data-toggle="tab" href="#home">Company Profile</a>
              </li>
              <li>
                <a data-toggle="tab" href="#messages">Certificate & License</a>
              </li>
              <li>
                <a data-toggle="tab" href="#settings">Company Gallery</a>
              </li>
            </ul>


            <div class="tab-content">
              <div class="tab-pane active" id="home">

                <form method="post" id="SimpanCompanyProfile" action="<?php echo base_url().'User/update_company_profile'; ?>" enctype="multipart/form-data">
                  <!-- <div class="form-group text-center">
                  <label  for="profile_image">Profil Image</label> <br>
                  <img src="<?php //echo base_url().'assets/suplier_upload/'.$user[0]->Gambar; ?>" id = "fotoview" alt="" class="img-thumbnail" alt="Cinque Terre" width="304" height="236"><br>
                  <a href="#" data-target="#myModal" data-toggle='modal' class="btn btn-default text-center">Change</a>
                </div> -->
                <div class="form-group col-md-12 text-center">
                  <label class="control-label">Profile Image</label>
                  <br>

                  <div class="form-group text-center" >
                    <!-- <label for="profile_image" class="btn"> -->
                    <img src="<?php if (empty($user[0]->ProfileImage)) {
                      echo base_url().'assets/icon/upload-icon.png';
                    }else{
                      echo base_url().'assets/supplier_upload/'.$user[0]->ProfileImage;
                    }?>" id="fotoprofile" alt="" class="img-thumbnail" alt="Cinque Terre" width="175" height="175">
                    <!-- </label> -->


                    <!-- _________________dropdown______________ -->
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-camera"></span> <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="margin-left: 50%; margin-right: 50%;">
                        <li><a href="#"><label for="profile_image" class="" style="font-weight:400">Change Profile Image</label></a></li>



                        <?php if (!empty($user[0]->ProfileImage)): ?>
                          <li>
                            <a href="#" data-toggle="modal" data-target="#lightbox">
                              <img src="<?php echo base_url().'assets/supplier_upload/'.$user[0]->ProfileImage;?>" style="display: none;" alt="">
                              View Profile Image
                            </a>
                          </li>
                        <?php endif; ?>

                      </ul>
                    </div>
                    <!-- _________________dropdown______________ -->



                    <br>
                  </div>

                  <?php if (empty($user[0]->ProfileImage)): ?>
                    <div class="form-group">
                      <input type="file" name="profile_image" value="" id="profile_image" size='20' onchange="readUrlProfileImage(this);" data-validation="mime size required"
                      data-validation-allowing="jpg, png" data-validation-max-size="300kb" data-validation-error-msg-required="Gambar Belum Dipilih..."
                      style="visibility:hidden;">

                    </div>
                  <?php else: ?>
                    <div class="form-group">
                      <input type="file" name="profile_image" value="" id="profile_image" size='20' onchange="readUrlProfileImage(this);" data-validation="mime size required"
                      data-validation-allowing="jpg, png" data-validation-max-size="300kb" data-validation-error-msg-required="Gambar Belum Dipilih..."
                      style="visibility:hidden;">
                      <input type="hidden" name="profile_image_lama" id="profile_image_lama" value="<?php echo $user[0]->ProfileImage; ?>">
                      <!-- <button type="submit" class="btn btn-danger" id="tambah_tdp">Delete</button> -->
                    </div>
                  <?php endif; ?>
                  <!--  -->

                </div>







                <div class="form-group col-lg-6">
                  <label class="control-label">First Name</label>
                  <input type="text" name="first_name" id="first_name" value="<?php echo $user[0]->FirstName; ?>" data-validation="length" data-validation-length="min1"
                  data-validation-error-msg="Please fill out first name..." class="form-control " placeholder="" required>
                </div>
                <div class="form-group col-lg-6">
                  <label class="control-label">Last Name</label>
                  <input type="text" name="last_name" id="last_name" value="<?php echo $user[0]->LastName; ?>" data-validation="length" data-validation-length="min1"
                  data-validation-error-msg="Please fill out last name..." class="form-control" placeholder="" required>
                </div>
                <div class="form-group col-lg-12">
                  <label class="control-label">Zip Code</label>
                  <input type="text" name="zip_code" id="zip_code" value="<?php echo $user[0]->ZipCode; ?>" data-validation="length" data-validation-length="min1"
                  data-validation-error-msg="Please fill out zip code..." class="form-control" placeholder="" required>
                </div>
                <div class="form-group col-lg-12">
                  <label class="control-label">Company Address</label>
                  <input type="text" name="address" id="address" value="<?php echo $user[0]->Address; ?>" data-validation="length" data-validation-length="min1"
                  data-validation-error-msg="Please fill out company address..." class="form-control" placeholder="" required>
                </div>
                <div class="form-group col-lg-12">
                  <label class="control-label">City</label>
                  <input type="text" name="city" id="city" value="<?php echo $user[0]->City; ?>" data-validation="length" data-validation-length="min1"
                  data-validation-error-msg="Please fill out city..." class="form-control" placeholder="" required>
                </div>
                <div class="form-group col-lg-12">
                  <label class="control-label">Province</label>
                  <input type="text" name="province" id="province" value="<?php echo $user[0]->Province; ?>" data-validation="length" data-validation-length="min1"
                  data-validation-error-msg="Please fill out province..." class="form-control" placeholder="" required>
                </div>
                <div class="form-group col-lg-12">
                  <label class="control-label">State</label>
                  <select name="state" id="state" class="form-control select2">
                    <option selected value="<?php echo $user[0]->State; ?>"><?php echo $user[0]->State; ?></option>
                  </select>
                </div>
                <div class="form-group col-lg-12">
                  <label class="control-label">Company Name</label>
                  <input type="text" name="company_name" id="company_name" value="<?php echo $user[0]->CompanyName; ?>" data-validation="length"
                  data-validation-length="min1" data-validation-error-msg="Please fill out company name..." class="form-control"
                  placeholder="" required>
                </div>

                <div class="form-group col-lg-12">
                  <label class="control-label">Phone Number</label>
                  <input type="text" name="phone" id="phone" value="<?php echo $user[0]->Phone; ?>" data-validation="length" data-validation-length="min1"
                  data-validation-error-msg="Please fill out phone number..." class="form-control" placeholder="" required>
                </div>
                <div class="form-group col-lg-12">
                  <label class="control-label">Company Description</label>
                  <textarea name="company_description" rows="6" class="form-control" data-validation="length" data-validation-length="min4"
                  data-validation-error-msg="Please fill out product description..." required><?php echo $user[0]->CompanyDescription; ?></textarea>
                </div>
                <div class="form-group ">
                  <button  class="btn btn-primary col-md-12" type="submit" value="Validate">Save</button>
                </div>
              </form>

              <hr>

            </div>
            <!--/tab-pane-->
            <div class="tab-pane" id="messages">

              <h2></h2>

              <hr>
              <form id="SimpanCertificateLicense" class="form" action="<?php echo base_url().'User/update_certificate_license'; ?>" enctype="multipart/form-data" method="post">
                <div class="form-group col-lg-12">
                  <label class="control-label">Taxpayer Registration Number</label>
                  <input type="text" name="npwp" id="description" value="<?php echo $user[0]->Npwp; ?>" data-validation="length" data-validation-length="min1"
                  data-validation-error-msg="Please fill out category description..." class="form-control" placeholder="">
                </div>
                <div class="form-group col-lg-6 text-center">
                  <label class="control-label">Trade Business License</label>
                  <br>
                  <!-- <img src="<?php //echo base_url().'assets/icon/upload-icon.png'?>" alt="" style="width: 100px"> -->
                  <div class="form-group text-center">
                    <!-- <label for="siup" class="btn"> -->
                    <img src="<?php if (empty($user[0]->Siup)) {
                      echo base_url().'assets/icon/upload-icon.png';
                    }else{
                      echo base_url().'assets/supplier_upload/'.$user[0]->Siup;
                    }?>" id="fotoview" alt="" class="img-thumbnail" alt="Cinque Terre" width="175" height="175">
                    <!-- </label> -->

                    <!-- _________________dropdown______________ -->
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-camera"></span> <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="margin-left: 50%; margin-right: 50%;">
                        <li><a href="#"><label for="siup" class="" style="font-weight:400">Change Profile Image</label></a></li>



                        <?php if (!empty($user[0]->Siup)): ?>
                          <li>
                            <a href="#" data-toggle="modal" data-target="#lightbox">
                              <img src="<?php echo base_url().'assets/supplier_upload/'.$user[0]->Siup;?>" style="display: none;" alt="">
                              View Profile Image
                            </a>
                          </li>
                        <?php endif; ?>

                      </ul>
                    </div>
                    <!-- _________________dropdown______________ -->
                  </div>
                  <?php if (empty($user[0]->Siup)): ?>
                    <div class="form-group">
                      <input type="file" name="siup" value="" id="siup" size='20' onchange="readURL(this);" data-validation="mime size required"
                      data-validation-allowing="jpg, png" data-validation-max-size="300kb" data-validation-error-msg-required="Gambar Belum Dipilih..."
                      style="visibility:hidden;">
                    </div>
                  <?php else: ?>
                    <div class="form-group">
                      <input type="file" name="siup" value="" id="siup" size='20' onchange="readURL(this);" data-validation="mime size required"
                      data-validation-allowing="jpg, png" data-validation-max-size="300kb" data-validation-error-msg-required="Gambar Belum Dipilih..."
                      style="visibility:hidden;">
                      <input type="hidden" name="siup_lama" id="siup_lama" value="<?php echo $user[0]->Siup; ?>">
                      <!-- <button type="submit" class="btn btn-danger" id="tambah_siup">Delete</button> -->
                    </div>
                  <?php endif; ?>

                  <!--  -->

                </div>

                <div class="form-group col-lg-6 text-center">
                  <label class="control-label">Company Registration Certificate</label>
                  <br>

                  <div class="form-group text-center">
                    <!-- <label for="tdp" class="btn"> -->
                    <img src="<?php if (empty($user[0]->Tdp)) {
                      echo base_url().'assets/icon/upload-icon.png';
                    }else{
                      echo base_url().'assets/supplier_upload/'.$user[0]->Tdp;
                    }?>" id="fotoedit" alt="" class="img-thumbnail" alt="Cinque Terre" width="175" height="175">
                    <!-- </label> -->
                    <!-- _________________dropdown______________ -->
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-camera"></span> <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="margin-left: 50%; margin-right: 50%;">
                        <li><a href="#"><label for="tdp" class="" style="font-weight:400">Change Profile Image</label></a></li>



                        <?php if (!empty($user[0]->Tdp)): ?>
                          <li>
                            <a href="#" data-toggle="modal" data-target="#lightbox">
                              <img src="<?php echo base_url().'assets/supplier_upload/'.$user[0]->Tdp;?>" style="display: none;" alt="">
                              View Profile Image
                            </a>
                          </li>
                        <?php endif; ?>

                      </ul>
                    </div>
                    <!-- _________________dropdown______________ -->
                    <br>
                  </div>

                  <?php if (empty($user[0]->Tdp)): ?>
                    <div class="form-group">
                      <input type="file" name="tdp" value="" id="tdp" size='20' onchange="readUrlTdp(this);" data-validation="mime size required"
                      data-validation-allowing="jpg, png" data-validation-max-size="300kb" data-validation-error-msg-required="Gambar Belum Dipilih..."
                      style="visibility:hidden;">

                    </div>
                  <?php else: ?>
                    <div class="form-group">
                      <input type="file" name="tdp" value="" id="tdp" size='20' onchange="readUrlTdp(this);" data-validation="mime size required"
                      data-validation-allowing="jpg, png" data-validation-max-size="300kb" data-validation-error-msg-required="Gambar Belum Dipilih..."
                      style="visibility:hidden;">
                      <input type="hidden" name="tdp_lama" id="tdp_lama" value="<?php echo $user[0]->Tdp; ?>">
                      <!-- <button type="submit" class="btn btn-danger" id="tambah_tdp">Delete</button> -->
                    </div>
                  <?php endif; ?>
                  <!--  -->

                </div>
                <div class="form-group ">
                  <button class="btn btn-primary col-md-12" type="submit">Save</button>
                </div>
              </form>

            </div>
            <!--/tab-pane-->
            <div class="tab-pane" id="settings">


              <hr>
              <form class="form" action="<?php echo base_url().'User/update_supplier_gallery'; ?>" enctype="multipart/form-data" method="post" id="SimpanCompanyGallery">

                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Company Gallery</label>
                  </div>
                  <?php foreach ($supplier_gallery_pic as $sgp): ?>

                    <div id="<?php echo "div".$sgp->Id; ?>" class="form-group col-lg-3 text-center">
                      <!-- <img src="<?php //echo base_url().'assets/icon/upload-icon.png'?>" alt="" style="width: 100px"> -->
                      <div class="form-group text-center">

                        <img src="<?php if (empty($sgp->FileName)) {
                          echo base_url().'assets/icon/upload-icon.png';
                        }else{
                          echo base_url().'assets/supplier_upload/'.$sgp->FileName;
                        }?>" alt="" class="img-thumbnail" alt="Cinque Terre" height="300">
                        <div class="dropdown">
                          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-camera"></span> <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="margin-right: 50%;">
                            <li>
                              <a href="#" data-toggle="modal" data-target="#lightbox">
                                <img src="<?php echo base_url().'assets/supplier_upload/'.$sgp->FileName;?>" style="display: none;" alt="">
                                View Profile Image
                              </a>
                            </li>
                            <li><a href="#" id="delete_pic" class="<?php echo $sgp->Id; ?>">Delete</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>

                  <?php endforeach; ?>

                </div>
                <div class="form-group">
                  <label class="control-label"></label>
                  <div class="dropzone">
                    <div class="dz-message">
                      <h4> Click or Drop gallery picture here..
                        <br>Max File Size 1,8 MB</h4>
                      </div>
                    </div>
                  </div>
                  <div class="form-group ">
                    <button class="btn btn-primary col-md-12" type="submit">Save</button>
                  </div>
                </form>
              </div>

            </div>
            <!--/tab-pane-->
          </div>
          <!--/tab-content-->

        </div>
        <!--/col-9-->



        <!-- ______________________lightbox_____________ -->
        <div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog ">
            <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="modal-content">
              <div class="modal-body">
                <img src="" alt="" />
              </div>
            </div>
          </div>
        </div>
        <!-- ______________________lightbox_____________ -->



      </div>
    </div>
  </div>
</div>


</section>

<script src="<?php echo base_url('assets/dropzone/js/dropzone.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dropzone/js/dropzone-amd-module.min.js') ?>"></script>
<script>
// $.validate({
//   lang: 'es',
//   form: '#SimpanCompanyProfile'
// });
</script>
<script type="text/javascript">

$(document).ready(function(){
  $('.select2').select2();
  let state = $('#state');
  //state.empty();
  //console.log(state.val());
  $.getJSON( "<?php echo base_url().'assets/country_json/state.json'; ?>", function( data ) {
    //console.log(data);
    $.each(data, function (key, entry) {
      if (state.val() != entry.name) {
        state.append($('<option></option>').attr('value', entry.name).text(entry.name));
      }

    })

  });
});
</script>
<script type="text/javascript">
$("#SimpanCompanyProfile").submit(function() {
  console.log('masuk');

    event.preventDefault();
    $.confirm({
      title: 'Confirmation',
      content: 'Are You Sure to Save?',
      type: 'blue',
      buttons: {
        Save: function () {
          $.LoadingOverlay("show");
          $("#SimpanCompanyProfile").submit();
        },
        Cancel: function () {

          $.alert('Data not saved...');
        },
      }
    });


});
</script>
<script type="text/javascript">
$("#SimpanCertificateLicense").submit(function() {
  console.log('masuk');

    event.preventDefault();
    $.confirm({
      title: 'Confirmation',
      content: 'Are You Sure to Save?',
      type: 'blue',
      buttons: {
        Save: function () {
          $.LoadingOverlay("show");
          $("#SimpanCertificateLicense").submit();
        },
        Cancel: function () {

          $.alert('Data not saved...');
        },
      }
    });


});
</script>
<script type="text/javascript">
$("#SimpanCompanyGallery").submit(function() {
  console.log('masuk');

    event.preventDefault();
    $.confirm({
      title: 'Confirmation',
      content: 'Are You Sure to Save?',
      type: 'blue',
      buttons: {
        Save: function () {
          $.LoadingOverlay("show");
          $("#SimpanCompanyGallery").submit();
        },
        Cancel: function () {

          $.alert('Data not saved...');
        },
      }
    });


});
</script>
<script type="text/javascript">
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#fotoview')
      .attr('src', e.target.result)
      .width(250);
    };

    reader.readAsDataURL(input.files[0]);
  }
}
</script>
<script type="text/javascript">
$(document).ready(function(){
  $("[data-toggle=popover]").popover();
});
</script>
<script type="text/javascript">
$(function () {
  $(document).click(function (event) {
    var value = $(event.target).attr('class');
    console.log(value);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url('User/remove_supplier_gallery_pic_button') ?>",
      data: {
        supplier_gallery_pic_id: value
      },
      success: function (respond) {
        var divPic = "#div" + value;
        $(divPic).remove();
      }
    })
  });
})
</script>
<script type="text/javascript">
function readUrlTdp(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#fotoedit')
      .attr('src', e.target.result)
      .width(300);
    };

    reader.readAsDataURL(input.files[0]);
  }
}
</script>
<script type="text/javascript">
function readUrlProfileImage(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#fotoprofile')
      .attr('src', e.target.result)
      .width(300);
    };

    reader.readAsDataURL(input.files[0]);
  }
}
</script>
<!-- <script type="text/javascript">
function edit(e){
e.preventDefault();
// ambil url pada atribute form action
var url = $('#SimpanCompanyProfile').attr('action');
// ambil inputannya
var data = {
'first_name'              : $('input[name=first_name]').val(),
'last_name'              : $('input[name=last_name]').val(),
'company_name'             : $('input[name=company_name]').val(),
'company_address'    : $('input[name=company_address]').val(),
'city'          : $('input[name=city]').val(),
'zip_code'    : $('input[name=zip_code]').val(),
'location'           : $('textarea[name=location]:checked').val(),
'npwp'           : $('input[name=npwp]').val()
};
// lakukan proses ajax
$.ajax({
type        : 'POST',
url         : url,
data        :  data,
success: function(response) {
$('#SimpanCompanyProfile').find('input').val();
}

});

return false;
}
</script> -->

<script>
// $(document).on('click', '#tambah_siup', function (e) {
//   e.preventDefault();
//   var file_data = $('#siup').prop('files')[0];
//   var form_data = new FormData();
//
//   form_data.append('siup', file_data);
//   $.ajax({
//    url: '<?php //echo base_url().'
//    index.php / Suplier / suplier_upload_siup '; ?>', // point to server-side PHP script
//    dataType: 'json', // what to expect back from the PHP script, if anything
    // cache: false,
    // contentType: false,
    // processData: false,
    // data: form_data,
    // type: 'post',
    // success: function (data, status) {
    //  alert(php_script_response); // display response from the PHP script, if any
//       if (data.status != 'error') {
//         $('#siup').val('');
//         alert(data.msg);
//       } else {
//         alert(data.msg);
//       }
//     }
//   });
// })
</script>

<script>
// $(document).on('click', '#tambah_tdp', function (e) {
//   e.preventDefault();
//   var file_data = $('#tdp').prop('files')[0];
//   var form_data = new FormData();
//
//   form_data.append('tdp', file_data);
//   $.ajax({
//     url: '<?php //echo base_url().'
//    index.php / Suplier / suplier_upload_tdp '; ?>', // point to server-side PHP script
//    dataType: 'json', // what to expect back from the PHP script, if anything
    // cache: false,
    // contentType: false,
    // processData: false,
  //  data: form_data,
  //  type: 'post',
  //  success: function (data, status) {
//      alert(php_script_response); // display response from the PHP script, if any
//       if (data.status != 'error') {
//         $('#tdp').val('');
//         alert(data.msg);
//       } else {
//         alert(data.msg);
//       }
//     }
//   });
// })
</script>
<script type="text/javascript">
$(document).ready(function () {
  var i = 1;
  Dropzone.autoDiscover = false;
  autoProcessQueue: false;
  var accept = ".png,.jpg,.JPEG";
  var foto_upload = new Dropzone(".dropzone", {
    url: "<?php echo base_url('User/add_supplier_gallery_pic') ?>",
    maxFilesize: 2000,
    method: "post",
    acceptedFiles: accept,
    paramName: "userfiles",
    maxFiles: 5,
    dictInvalidFileType: "Type file ini tidak dizinkan",
    addRemoveLinks: true,

    success: function (file, data) {

      var data_array = data.split(',');
      var nama = data_array[0];
      var namafile = nama.replace('"', '');
      var token = data_array[1];
      var tokenfile = token.replace('"', '');
      var str = String(tokenfile);
      var res = str.substring(3, 10);
      $('<input>').attr({
        type: 'hidden',
        id: res, //a.token,
        class: tokenfile,
        name: 'file[' + i + ']',
        value: $.trim(namafile)
      }).appendTo('form');
      i++;
    }
  });

  foto_upload.on("addedfile", function (file) {
    if (!file.type.match(/image.*/)) {
      foto_upload.emit("thumbnail", file, "<?php echo base_url('assets/img/pdf.png') ?>");
    }
  });

  // mengupload
  foto_upload.on("sending", function (a, b, c) {
    a.token = Math.random();
    var str = String(a.token);
    var res = str.substring(3, 10);
    c.append("token_foto", a.token); //Menmpersiapkan token untuk masing masing foto
  });

  //hapus
  foto_upload.on("removedfile", function (a) {
    var token = a.token;
    var str = String(a.token);
    var res = str.substring(3, 10);
    var namafile = $('#' + res).val();
    $('#' + res).remove();
    $.ajax({
      type: "post",
      data: {
        nama: namafile
      },
      url: "<?php echo base_url('User/remove_supplier_gallery_pic') ?>",
      cache: false,
      dataType: 'json',
      success: function () {
        console.log("Foto terhapus");
      },
      error: function () {
        console.log("Error");

      }
    });
  });
});
</script>
<script>
  $(document).ready(function() {
    var $lightbox = $('#lightbox');

    $('[data-target="#lightbox"]').on('click', function(event) {
      var $img = $(this).find('img'),
      src = $img.attr('src'),
      alt = $img.attr('alt'),
      css = {
        'maxWidth': $(window).width() - 100,
        'maxHeight': $(window).height() - 100
      };

      $lightbox.find('.close').addClass('hidden');
      $lightbox.find('img').attr('src', src);
      $lightbox.find('img').attr('alt', alt);
      $lightbox.find('img').css(css);
    });

    $lightbox.on('shown.bs.modal', function (e) {
      var $img = $lightbox.find('img');

      $lightbox.find('.modal-dialog').css({'width': $img.width()});
      $lightbox.find('.close').removeClass('hidden');
    });
  });
</script>
<style>
#lightbox .modal-content {
  display: inline-block;
  text-align: center;
}

#lightbox .close {
  opacity: 1;
  color: rgb(255, 255, 255);
  background-color: rgb(25, 25, 25);
  padding: 5px 8px;
  border-radius: 30px;
  border: 2px solid rgb(255, 255, 255);
  position: absolute;
  top: -15px;
  right: -55px;

  z-index:1032;
}
</style>
