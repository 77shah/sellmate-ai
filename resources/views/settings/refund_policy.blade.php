<x-header/>
<x-sidebar/>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                            
                                <li class="breadcrumb-item active">Refund Policy</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Refund Policy</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('refund.store') }}" method="POST" class="row g-3" id="refundForm">
                                @csrf
                                <div class="col-md-12">
                                    <label for="refundEditor">Refund Policy</label>
                                    <div id="editor-container" style="height: 300px;"></div>
                                    <input type="hidden" name="content" id="content">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                            <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

                            <script>
                                var quill = new Quill('#editor-container', {
                                    theme: 'snow',
                                    placeholder: 'Write Refund Policy here...',
                                    modules: {
                                        toolbar: [
                                            [{ header: [1, 2, 3, 4, 5, 6, false] }],
                                            ['bold', 'italic', 'underline', 'strike'],
                                            [{ color: [] }, { background: [] }],
                                            [{ align: [] }],
                                            [{ list: 'ordered' }, { list: 'bullet' }],
                                            ['blockquote', 'code-block'],
                                            ['link', 'image', 'video'],
                                            ['clean']
                                        ]
                                    }
                                });

                                @if(!empty($refund->content))
                                    quill.root.innerHTML = {!! json_encode($refund->content) !!};
                                @endif

                                document.getElementById('refundForm').addEventListener('submit', function(e) {
                                    var content = quill.root.innerHTML.trim();
                                    document.getElementById('content').value = content;
                                    if(content === '' || content === '<p><br></p>') {
                                        alert('Refund Policy field is required.');
                                        e.preventDefault();
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<x-footer/>
