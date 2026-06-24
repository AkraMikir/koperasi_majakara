@extends('layouts.admin')

@section('title', 'Master Syarat & Ketentuan Layanan Gadai')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}" class="hover:text-gray-900">Master Data</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Syarat & Ketentuan Layanan Gadai</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Syarat & Ketentuan Layanan Gadai</h1>
            <p class="text-gray-600 mt-1 font-sans">Kelola isi syarat & ketentuan penggunaan gadai barang nasabah (tampil pada pop-up persetujuan nasabah)</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-center shadow-xs">
        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <p class="text-green-700 font-medium text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Form & Editor Card -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 md:p-8">
        @canCrudMasterData
        <form id="form-syarat-layanan" action="{{ route('admin.master-data.syarat-ketentuan-layanan-gadai.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <input type="hidden" name="konten" id="syarat_layanan_input">

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700">Konten Syarat & Ketentuan Gadai</label>
                
                <div id="editor-container" class="rounded-xl overflow-hidden border border-gray-300">
                    {!! $data->konten ?? '' !!}
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-[#674c1d] hover:bg-[#8b6f2f] text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md hover:shadow-lg">
                    Simpan Perubahan Syarat Gadai
                </button>
            </div>
        </form>
        @else
        <!-- View Only Mode for Operasional Admins -->
        <div class="space-y-4">
            <div class="p-4 bg-yellow-50 text-yellow-800 rounded-xl text-sm border border-yellow-100">
                Mode lihat saja. Hanya Admin Utama (Owner) yang berwenang mengubah data syarat & ketentuan layanan gadai.
            </div>
            <div class="prose max-w-none p-6 border border-gray-200 rounded-2xl bg-gray-50 max-h-[500px] overflow-y-auto">
                {!! $data->konten ?? 'Belum ada konten.' !!}
            </div>
        </div>
        @endcanCrudMasterData
    </div>
</div>
@endsection

@push('styles')
    @canCrudMasterData
    <!-- Include stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <style>
        .ql-editor {
            min-height: 400px;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        .ql-toolbar.ql-snow {
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            border-color: #d1d5db;
            background-color: #f9fafb;
        }
        .ql-container.ql-snow {
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            border-color: #d1d5db;
            background-color: #ffffff;
        }
    </style>
    @endcanCrudMasterData
@endpush

@push('scripts')
    @canCrudMasterData
    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <!-- Initialize Quill editor -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initQuillEditor();
        });

        document.addEventListener('turbo:load', function () {
            initQuillEditor();
        });

        function initQuillEditor() {
            const container = document.getElementById('editor-container');
            const input = document.getElementById('syarat_layanan_input');
            const form = document.getElementById('form-syarat-layanan');
            
            if (!container || !input || !form || container.classList.contains('ql-container')) {
                return;
            }

            const quill = new Quill('#editor-container', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1' }, { 'indent': '+1' }],
                        ['clean']
                    ]
                }
            });

            form.addEventListener('submit', function (e) {
                const html = quill.root.innerHTML;
                if (html === '<p><br></p>') {
                    input.value = '';
                } else {
                    input.value = html;
                }
            });
        }
    </script>
    @endcanCrudMasterData
@endpush
