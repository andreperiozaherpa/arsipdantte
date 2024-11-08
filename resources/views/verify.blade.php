@extends('layout.master')
@section('content')
    <section class="pt-lg-2 pt-md-2 pt-3">
        <div class="page-dash d-flex align-items-center container">
            <div class="mt-lg-5 mt-md-4 mt-3">

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12">
                        <h5>Tanda Tangan Elektronik</h5>
                        <h5>Pemerintah Kabupaten Tulang Bawang Barat</h5>
                        <p class="mt-md-4 mt-3">Pemerintah Kabupaten Tulang Bawang Barat bekerjasama dengan Badan Siber dan
                            Sandi
                            Negara (BSSN)
                            mengimplemtasikan penerapan tanda tangan digital di lingukungan kerja Pemerintah Kabupaten
                            Tulang Bawanag Barat.</p>
                        <p>Didukung oleh :</p>
                        <div class="d-flex logo">
                            <img class="img-fluid p-md-2 p-1" src="/assets/logo/logopemda.svg" alt="">
                            <img class="img-fluid p-md-2 p-1" src="/assets/logo/logo-bssn.png" alt="">
                            <img class="img-fluid p-md-2 p-1" src="/assets/logo/logo-bsre.jpg" alt="">
                        </div>
                    </div>

                    {{-- unggah file --}}
                    <div class="col-lg-6 col-md-6 col-12 ps-md-5 mt-md-0 mt-4">
                        <div class="card-ver p-md-5 p-4 text-center mt-md-4 mt-3">
                            <h6>Cek Validasi File Tanda Tangan Elektronik</h6>
                            <div class="row d-flex justify-content-center mt-md-2 mt-3">
                                <div class="col-auto">
                                    <div class="mt-md-4 drop">
                                        {{-- <div class="drop-zone">
                                            <span class="drop-zone__prompt">Drop file here or click to upload</span>
                                            <input accept="application/pdf" name="myFile" id="myFile" class="drop-zone__input">
                                        </div> --}}
                                        <form class="dropzone" id="myFile"></form>
                                    </div>

                                    {{-- button cek file --}}
                                    {{-- <div class="but d-flex justify-content-center mt-md-4 mt-3">
                                        <button style="background-color: #680000;color:white;" id="buttonVerify" type="button" class="btn btn-sm btn-atas ps-lg-4 pe-lg-4">
                                            Cek dokumen
                                            <i class="bi bi-arrow-right ms-3"></i>
                                        </button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>





                {{-- <div class="card border-0">
                    <div class="card-body">
                        <h5 class="card-title">Validasi Tanda Tangan Digital</h5>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="items_sig">
                            </div>
                        </div>
                    </div>
                </div> --}}




                <div class="row mt-lg-5 col-md-12 col-12 mt-md-4 mt-3 m-0 p-0">

                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" tabindex="-1" id="modalVerify">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dokumen Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalBox">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Status</td>
                                    <td></td>
                                    <td id="validStatus"></td>
                                </tr>
                                <tr>
                                    <td>Deskripsi</td>
                                    <td></td>
                                    <td id="validDeskripsi"></td>
                                </tr>
                                <tr>
                                    <th scope="row">TTE</th>
                                    <td></td>
                                    <td>
                                        <ul id="validName">
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        Dropzone.options.myFile = {
            // Configuration options go here
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/verify",
            acceptedFiles: 'application/pdf',
            maxFiles: 1,
            init: function() {
                this.on("sending", function(file, xhr, formData) {
                    formData.append("param", "uploads");
                });

                this.on('success', function(file, responseText) {
                    $('.dz-filename span').text(responseText.name)
                    $('#modalVerify').modal('show')

                    $.each(responseText.data.signatureInformations, function(index, value) {
                        $('#validName').append('<li>' + value.signerName + '</li>')
                    });

                    $('#validStatus').text(responseText.data.conclusion)
                    $('#validDeskripsi').text(responseText.data.description)
                });
                this.on('resetFiles', function() {
                    this.removeAllFiles();
                });
            },
        };


        // arif
        // document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
        //     const dropZoneElement = inputElement.closest(".drop-zone");

        //     dropZoneElement.addEventListener("click", (e) => {
        //         inputElement.click();
        //     });

        //     dropZoneElement.addEventListener("change", (e) => {
        //         if (inputElement.files.length) {
        //             updateThumbnail(dropZoneElement, inputElement.files[0]);
        //         }
        //     });

        //     dropZoneElement.addEventListener("dragover", (e) => {
        //         e.preventDefault();
        //         dropZoneElement.classList.add("drop-zone--over");
        //     });

        //     ["dragleave", "dragend"].forEach((type) => {
        //         dropZoneElement.addEventListener(type, (e) => {
        //             dropZoneElement.classList.remove("drop-zone--over");
        //         });
        //     });

        //     dropZoneElement.addEventListener("drop", (e) => {
        //         e.preventDefault();

        //         if (e.dataTransfer.files.length) {
        //             inputElement.files = e.dataTransfer.files;
        //             updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
        //         }

        //         dropZoneElement.classList.remove("drop-zone--over");
        //     });
        // });

        // /**
        //  * Updates the thumbnail on a drop zone element.
        //  *
        //  * @param {HTMLElement} dropZoneElement
        //  * @param {File} file
        //  */
        // function updateThumbnail(dropZoneElement, file) {
        //     let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

        //     // First time - remove the prompt
        //     if (dropZoneElement.querySelector(".drop-zone__prompt")) {
        //         dropZoneElement.querySelector(".drop-zone__prompt").remove();
        //     }

        //     // First time - there is no thumbnail element, so lets create it
        //     if (!thumbnailElement) {
        //         thumbnailElement = document.createElement("div");
        //         thumbnailElement.classList.add("drop-zone__thumb");
        //         dropZoneElement.appendChild(thumbnailElement);
        //     }

        //     thumbnailElement.dataset.label = file.name;

        //     // Show thumbnail for image files
        //     if (file.type.startsWith("image/")) {
        //         const reader = new FileReader();

        //         reader.readAsDataURL(file);
        //         reader.onload = () => {
        //             thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
        //         };
        //     } else {
        //         thumbnailElement.style.backgroundImage = null;
        //     }
        // }
    </script>
@endpush
