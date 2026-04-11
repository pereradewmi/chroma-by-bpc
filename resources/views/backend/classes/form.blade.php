@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Class' : 'Create New Class' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('classes.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('classes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="class_id" value="{{ $class->cID }}">
                            @endif

                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="cName">Class Name</label>
                                            <input type="text" id="cName" name="cName"
                                                class="form-control form-control-alternative @error('cName') is-invalid @enderror"
                                                placeholder="Enter class name (e.g., Grade 10A, Mathematics Advanced)"
                                                value="{{ old('cName', $class->cName) }}" required>
                                            @error('cName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="classfee">Class Fee (Rs.)</label>
                                            <input type="number" id="classfee" name="classfee" step="0.01" min="0"
                                                class="form-control form-control-alternative @error('classfee') is-invalid @enderror"
                                                placeholder="Enter class fee"
                                                value="{{ old('classfee', $class->classfee) }}" required>
                                            @error('classfee')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="admission_amount">Admission Amount (Rs.)</label>
                                            <input type="number" id="admission_amount" name="admission_amount" step="0.01" min="0"
                                                class="form-control form-control-alternative @error('admission_amount') is-invalid @enderror"
                                                placeholder="Enter admission amount"
                                                value="{{ old('admission_amount', $class->admission_amount) }}">
                                            @error('admission_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="cVideo">Class Video</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('cVideo') is-invalid @enderror"
                                                    id="cVideo" name="cVideo" accept="video/mp4,video/webm,video/quicktime,video/x-msvideo,video/x-matroska">
                                                <label class="custom-file-label" for="cVideo">Choose video...</label>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle"></i> Supported formats: MP4, WebM, MOV, AVI, MKV (Max 50MB)
                                                @if($isEdit && $class->cVideo)
                                                    <br><strong>Current Video:</strong> {{ $class->cVideo }}
                                                @endif
                                            </small>
                                            @error('cVideo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="cDescription">Description</label>
                                            <!-- Rich Text Editor -->
                                            <div id="editor-toolbar" style="background-color: #f7f8fc; border: 1px solid #ddd; border-bottom: none; border-radius: 4px 4px 0 0; padding: 8px;">
                                                <button type="button" class="btn btn-sm" id="bold-btn" title="Bold" style="background: none; border: 1px solid #ddd; padding: 4px 8px; margin: 2px; cursor: pointer; font-weight: bold;">B</button>
                                                <button type="button" class="btn btn-sm" id="underline-btn" title="Underline" style="background: none; border: 1px solid #ddd; padding: 4px 8px; margin: 2px; cursor: pointer; text-decoration: underline;">U</button>
                                                <button type="button" class="btn btn-sm" id="bullet-btn" title="Bullet List" style="background: none; border: 1px solid #ddd; padding: 4px 8px; margin: 2px; cursor: pointer;">• List</button>
                                                <button type="button" class="btn btn-sm" id="italic-btn" title="Italic" style="background: none; border: 1px solid #ddd; padding: 4px 8px; margin: 2px; cursor: pointer; font-style: italic;">I</button>
                                            </div>
                                            <!-- Editor Container -->
                                            <div id="editor" style="background-color: white; border: 1px solid #ddd; border-radius: 0 0 4px 4px; min-height: 200px; padding: 12px; font-family: Arial, sans-serif;">
                                                {!! old('cDescription', $class->cDescription ?? '') !!}
                                            </div>
                                            <!-- Hidden textarea to store the content -->
                                            <textarea id="cDescription" name="cDescription"
                                                class="form-control form-control-alternative @error('cDescription') is-invalid @enderror"
                                                style="display: none;">{{ old('cDescription', $class->cDescription) }}</textarea>
                                            @error('cDescription')
                                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($isEdit && $class->cVideo && file_exists(storage_path('app/public/class-videos/' . $class->cVideo)))
                                    <div class="row mb-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Current Video Preview</label>
                                                <div class="mt-2">
                                                    <video style="max-width: 320px; max-height: 180px; border-radius: 4px;" controls muted>
                                                        <source src="{{ $class->getClassVideo() }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mt-4">
                                    <div class="col-lg-12 text-right">
                                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary ml-2">
                                            {{ $isEdit ? 'Update Class' : 'Create Class' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle file input label update
        const classVideoInput = document.getElementById('cVideo');
        if (classVideoInput) {
            classVideoInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose video...';
                document.querySelector('label[for="cVideo"]').textContent = fileName;
            });
        }

        // Rich Text Editor functionality
        const editor = document.getElementById('editor');
        const descriptionField = document.getElementById('cDescription');
        let isEditing = false;

        // Make editor contenteditable
        editor.contentEditable = true;
        editor.spellcheck = true;

        // Sync editor content to hidden textarea on input
        editor.addEventListener('input', function() {
            descriptionField.value = editor.innerHTML;
        });

        // Sync on form submit
        document.querySelector('form').addEventListener('submit', function() {
            descriptionField.value = editor.innerHTML;
        });

        // Bold button
        document.getElementById('bold-btn').addEventListener('click', function(e) {
            e.preventDefault();
            document.execCommand('bold', false, null);
            editor.focus();
        });

        // Italic button
        document.getElementById('italic-btn').addEventListener('click', function(e) {
            e.preventDefault();
            document.execCommand('italic', false, null);
            editor.focus();
        });

        // Underline button
        document.getElementById('underline-btn').addEventListener('click', function(e) {
            e.preventDefault();
            document.execCommand('underline', false, null);
            editor.focus();
        });

        // Bullet list button
        document.getElementById('bullet-btn').addEventListener('click', function(e) {
            e.preventDefault();
            document.execCommand('insertUnorderedList', false, null);
            editor.focus();
        });

        // Update button states based on selection
        document.addEventListener('mouseup', function() {
            updateButtonStates();
        });

        function updateButtonStates() {
            document.getElementById('bold-btn').style.backgroundColor = document.queryCommandState('bold') ? '#e9ecef' : 'transparent';
            document.getElementById('italic-btn').style.backgroundColor = document.queryCommandState('italic') ? '#e9ecef' : 'transparent';
            document.getElementById('underline-btn').style.backgroundColor = document.queryCommandState('underline') ? '#e9ecef' : 'transparent';
            document.getElementById('bullet-btn').style.backgroundColor = document.queryCommandState('insertUnorderedList') ? '#e9ecef' : 'transparent';
        }

        // Initialize button states
        updateButtonStates();

        // Prevent default paste behavior and clean up pasted content
        editor.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = e.clipboardData.getData('text/html') || e.clipboardData.getData('text/plain');
            document.execCommand('insertHTML', false, text);
        });
    </script>
@endsection
