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
                                <li class="breadcrumb-item active">Privacy Policy</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Privacy Policy</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('privacy.store') }}" method="POST" class="row g-3" id="privacyForm">
                                @csrf
                                <div class="col-md-12">
                                    <label for="privacyEditor">Privacy Policy</label>
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
                                    placeholder: 'Write Privacy Policy here...',
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

                                @if(!empty($privacy->content))
                                    quill.root.innerHTML = {!! json_encode($privacy->content) !!};
                                @endif

                                document.getElementById('privacyForm').addEventListener('submit', function(e) {
                                    var content = quill.root.innerHTML.trim();
                                    document.getElementById('content').value = content;
                                    if(content === '' || content === '<p><br></p>') {
                                        alert('Privacy Policy field is required.');
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
