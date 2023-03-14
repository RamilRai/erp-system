<style>
    #documents {
        display: flex;
        flex-wrap: wrap;
    }

    #imgPreview img {
        width: 70px;
        height: 70px;
        margin: 5px;
        border: 1px solid gray;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">Documents</h5>
    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        <em class="icon ni ni-cross"></em>
    </a>
</div>
<div class="modal-body">
    <form  class="form-validate is-alter"enctype="multipart/form-data" id="taskDocumentsForm">
        @csrf
        <div class="row">
            <input type="hidden" name="id" value="{{$taskId}}">
            <div class="form-group col-12">
                <label class="form-label" for="documents">Documents <code>*</code></label>
                <div class="form-control-wrap">
                    <input type="file" class="form-control" id="documents" name="documents[]" multiple>
                </div>
                <p class="form-text text-danger documents"></p>
                <div id="imgPreview"></div>
            </div>
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-lg btn-primary" id="saveTaskDocuments"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Information</button>
        </div>
    </form>
</div>

<script>
    //uploaded image preview start
    $('#documents').change(function() {
        var file = this.files;
        var fileType = file[0].type;

        $('#imgPreview').empty();
        if (fileType.indexOf('image') !== -1) {
            if (file) {
                for (var i = 0; i < file.length; i++) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imgPreview').append('<img src="' + e.target.result + '">');
                    }
                    reader.readAsDataURL(file[i]);
                }
            }
        }
    });
    // uploaded image preview end

    //================== store data start ==================
    $("#saveTaskDocuments").on('click', function(e){
        e.preventDefault();
        $('#taskDocumentsForm').validate({
            rules: {
                "documents[]": 'required'
            },
            messages: {
                "documents[]": {
                    required: "Please upload document."
                }
            }
        });
        if ($('#taskDocumentsForm').valid()) {
            var token = "{{csrf_token()}}";
            $('#taskDocumentsForm').ajaxSubmit({
                url: '{{route('task-management-documents.submit')}}',
                type: 'POST',
                data: {'_token':token},
                beforeSend: function() {
                    $('#saveTaskDocuments').html(
                        "<svg width='25' viewBox='-2 -2 42 42' xmlns='http://www.w3.org/2000/svg' stroke='rgb(30, 41, 59)' class='w-8 h-8'><g fill='none' fill-rule='evenodd'><g transform='translate(1 1)' stroke-width='4'><circle stroke-opacity='.5' cx='18' cy='18' r='18'></circle><path d='M36 18c0-9.94-8.06-18-18-18'><animateTransform attributeName='transform' type='rotate' from='0 18 18' to='360 18 18' dur='1s' repeatCount='indefinite'></animateTransform></path></g></g></svg> Submitting..."
                    ).attr('disabled', 'disabled');
                },
                complete: function() {
                    $('#saveTaskDocuments').html("<i class='fa-solid fa-floppy-disk' data-lucide='refresh-cw'></i>&nbsp; Submit").removeAttr('disabled');
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.type=='success') {
                        Swal.fire({
                            title: 'Success!',
                            icon: 'success',
                            text: result.message
                        });
                        $('#taskDocumentsForm')[0].reset();
                        $('#taskManagementModalShow').hide();
                        taskManagementTable.fnDraw();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            icon: 'error',
                            text: result.message
                        });
                    }
                },
                error: function(response) {
                    var errors =  response.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        $('.'+key).html('');
                        $('.'+key).append(val);
                        $('#'+key).addClass('border-danger');
                        $('#'+key).change(function(){
                            $('.'+key).html('');
                            $('#'+key).removeClass('border-danger');
                        });
                    });
                }
            });
        }
    });
    //================== store data end ==================
</script>