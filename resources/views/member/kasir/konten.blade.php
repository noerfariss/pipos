 <div class="container-xxl flex-grow-1 container-p-y">
     <div class="row">
         <div class="col-sm-3">
             <div class="card">
                 <h5 class="card-header">Customer</h5>
                 <div class="card-body">
                     {{-- member --}}
                     <div class="row">
                         <div class="col-sm-12 text-center">
                             <div class="btn-group btn-group-sm" role="group"
                                 aria-label="Basic radio toggle button group">
                                 <input type="radio" class="btn-check2 member" name="btnradio" id="member_umum"
                                     value="0" autocomplete="off">
                                 <label class="btn btn-outline-primary" for="member_umum">Umum</label>

                                 <input type="radio" class="btn-check2 member" name="btnradio" id="member_member"
                                     value="1" autocomplete="off">
                                 <label class="btn btn-outline-primary" for="member_member">Member</label>

                             </div>
                         </div>
                         <div class="col-sm-12 mt-3">
                             <form id="member_cari_form">
                                 <input type="text" name="member_cari" id="member_cari" class="form-control"
                                     placeholder="Nama / nomor whatsapp / kode member" disabled>
                             </form>
                         </div>

                         <div class="col-sm-12" style="display:none;" id="member_detail">
                             <table class="table table-sm table-hover">
                                 <tr>
                                     <td width="20%" class="col-form-label">Nama <input type="hidden"
                                             id="member_uuid"></td>
                                     <td width="5%">:</td>
                                     <td width="75%" id="member_nama"></td>
                                 </tr>
                                 <tr>
                                     <td width="20%" class="col-form-label">Whatsapp</td>
                                     <td width="5%">:</td>
                                     <td width="75%" id="member_phone"></td>
                                 </tr>
                                 <tr>
                                     <td width="20%" class="col-form-label">Alamat</td>
                                     <td width="5%">:</td>
                                     <td width="75%" id="member_alamat"></td>
                                 </tr>
                             </table>
                         </div>
                     </div>
                     {{-- end member --}}
                 </div>
             </div>

             {{-- cari produk --}}
             <div class="card mt-3">
                 <div class="card-body">
                     <form id="member_item_cari_form">
                         <div class="row">
                             <div class="col-sm-8">
                                 <label for="" class="col-form-label">Kode Produk</label>
                                 <input type="text" id="member_item_cari" class="form-control"
                                     placeholder="Kode Produk ...">
                             </div>
                             <div class="col-sm-4">
                                 <label for="" class="col-form-label">Jumlah</label>
                                 <input type="text" id="member_item_qty" class="form-control">
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
             {{-- end cari produk --}}

             <a href="{{ route('auth.index') }}" class="mt-2 btn btn-default"><i
                     class="menu-icon tf-icons bx bx-pie-chart"></i> Dashboard</a>

             @include('member.layouts.footer')

         </div>

         <div class="col-sm-9">
             <div class="card" style="height: 83vh;">
                 <div class="card-body">
                     <section>
                         <h1>Rp <span class="float-end totalAll">0</span></h1>
                     </section>
                 </div>


                 <div class="divider">
                     <hr>
                 </div>
                 {{-- items --}}

                 <table class="table table-hover" id="myTable">
                     <thead>
                         <tr>
                             <th class="table_nomor">no</th>
                             <th class="table_produk">produk</th>
                             <th class="table_harga">harga</th>
                             <th class="table_qty">qty</th>
                             <th class="table_harga">subtotal</th>
                             <th class="table_delete"></th>
                         </tr>
                     </thead>
                     <tbody>

                     </tbody>
                 </table>

                 {{-- end items --}}


                 <div style="position: absolute; bottom: 0; display:block; width:100%;">
                     <section class="mb-3" style="width: 100%;">
                         <button type="button" class="btn btn-large btn-outline-danger ms-3">Reset</button>
                         <button type="button" class="btn btn-large btn-primary float-end me-4" id="btn-bayar"
                             onclick="modalBayar()">Bayar</button>
                     </section>
                 </div>
             </div>
         </div>

     </div>
 </div>


 @include('member.kasir.modalBayar')

 @include('member.kasir.script')
 @include('member.kasir.simpleMoney')

