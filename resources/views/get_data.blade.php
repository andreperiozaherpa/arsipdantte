@extends('layout.master')
@section('content')
    <section class="pt-lg-2 pt-md-2 pt-3">
        <div class="page-detail-dokumen container">
            <div class="konten mt-lg-5 mt-md-4 mt-3">
                <div class="card border-0">
                    <div class="card-body">
                        <h5 class="card-title">Validasi Tanda Tangan Digital</h5>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="items_sig">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mt-lg-5 col-md-12 col-12 mt-md-4 mt-3 m-0 p-0">
                    <div class="col-lg-5">
                        <div class="card card-pdf p-1">
                            <iframe id="dokumen" src="" class="cont-pdf"></iframe>
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-12 col-12 ps-lg-5 ps-md-0 ps-0 mt-lg-0 mt-md-4 mt-3 m-0 p-0">
                        <div class="table-responsive"></div>
                        <table class="table ket">
                            <tbody>
                                <tr>
                                    <td>Nomor Surat</td>
                                    <td>:</td>
                                    <td id="nomorSurat"></td>
                                </tr>

                                <tr>
                                    <td>Tanggal Surat</td>
                                    <td>:</td>
                                    <td id="tglSurat"></td>
                                </tr>

                                <tr>
                                    <td>Asal Surat</td>
                                    <td>:</td>
                                    <td id="asalSurat"></td>
                                </tr>
                                <tr>
                                    <td>Hal</td>
                                    <td>:</td>
                                    <td id="perihal">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ditandatangani Oleh</td>
                                    <td>:</td>
                                    <td id="pejabatTTD"></td>
                                </tr>
                                <tr>
                                    <td>Metode Tanda Tangan</td>
                                    <td>:</td>
                                    <td id="metodeTTD"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- <div class="d-flex mt-lg-4">
                                                                                                                                                                                                                                                                                        <div class="flex-grow-1">
                                                                                                                                                                                                                                                                                          <a href="#">
                                                                                                                                                                                                                                                                                            <button type="button" class="btn btn-unduh ps-lg-4 pe-lg-4">
                                                                                                                                                                                                                                                                                              Download File
                                                                                                                                                                                                                                                                                              <i class="bi bi-arrow-right ms-3"></i>
                                                                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                                                                          </a>
                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                      </div> -->
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalFull">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-transparent">
                <div class="modal-body p-0 m-0">
                    <div class="row p-0 m-0 align-items-center vh-100">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="align-items-center">
                        <div class="spinner-border text-dark" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const index = urlParams.get('index')
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "get",
                url: "/get_data/show",
                data: {
                    index: index
                },
                beforeSend: function() {
                    $('#modalFull').modal('show')
                },
                success: function(response) {
                    response_verify(response)
                    $('#modalFull').modal('hide')
                },
                error: function(response) {
                    $('#modalFull').modal('hide')
                    Swal.fire({
                        title: "error",
                        text: "Data Yang Dicari Tidak Ada",
                        icon: "error"
                    });
                }
            });

            function response_verify(res) {
                // console.log(res[0]);
                // console.log(res.data_sig.signatureInformations);
                var i = 1;
                var text = [];
                // console.log(res.data_sig.description);
                var nameMerge;

                $.each(res.data_sig.signatureInformations, function(indexInArray, valueOfElement) {
                    // console.log(valueOfElement);
                    var options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    var tanggalSekarang = new Date(valueOfElement.signatureDate);
                    var formatLokalID = tanggalSekarang.toLocaleDateString('id-ID', options);

                    $('.items_sig').append(`
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading` + i + `">
                            <button style="background-color:#ead196 ; color:#680000;" class="accordion-button collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#flush_` + i + `">
                                ` + i + `. Tanda - Tangan Oleh ` + valueOfElement.signerName + `
                            </button>
                        </h2>
                        <div id="flush_` + i + `" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Validasi</td>
                                            <td>: ` + res.data_sig.description + `</td>
                                        </tr>
                                        <tr>
                                            <td>Nama</td>
                                            <td>: ` + valueOfElement.signerName + `</td>
                                        </tr>
                                        <tr>
                                            <td>Lokasi</td>
                                            <td>: ` + valueOfElement.location + `</td>
                                        </tr>
                                        <tr>
                                            <td>Alasan</td>
                                            <td>: ` + valueOfElement.reason + `</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Tanda - Tangan Elektronik</td>
                                            <td>: ` + formatLokalID + `</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `);
                    nameMerge = text.push(i + '. ' + valueOfElement.signerName + '<br>')
                    i++;
                    // text = valueOfElement.signerName;
                });
                console.log(nameMerge);
                $("#dokumen").attr("src", res[0].url_dokumen + '#toolbar=0');
                $('#nomorSurat').text(res[0].nomor_surat);
                $('#tglSurat').text(res[0].tgl_surat);
                $('#asalSurat').text(res[0].organisasi);
                $('#perihal').text(res[0].perihal);
                $('#pejabatTTD').html(text);
                $('#metodeTTD').text(res[0].metode_tanda_tangan);
            }
        });
    </script>
@endpush
