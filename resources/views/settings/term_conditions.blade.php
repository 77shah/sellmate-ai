<x-header/>

<x-sidebar/>

<!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Settings</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item active">Terms & Conditions</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-4">Terms & Conditions</h4>

                                        @if(session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        <form action="{{ route('terms.store') }}" method="POST" class="row g-3" id="termsForm">
                                            @csrf
                                            <div class="col-md-12">
                                                <label for="termsEditor">Terms & Conditions</label>
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
                                                placeholder: 'Write Terms & Conditions here...',
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

                                            // Set value from database
                                            @if(!empty($term->content))
                                                quill.root.innerHTML = {!! json_encode($term->content) !!};
                                            @endif

                                            document.getElementById('termsForm').addEventListener('submit', function (e) {
                                                var quillContent = quill.root.innerHTML.trim();
                                                document.getElementById('content').value = quillContent;

                                                if (quillContent === '' || quillContent === '<p><br></p>') {
                                                    alert('Terms & Conditions field is required.');
                                                    e.preventDefault();
                                                }
                                            });
                                        </script>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

<x-footer/>