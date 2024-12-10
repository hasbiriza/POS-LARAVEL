<select {{ $attributes->merge(['class' => 'select2-multi block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm', 'multiple' => 'multiple']) }}>
    {{ $slot }}
</select>

<!-- Pastikan untuk menyertakan CSS dan JS Select2 -->
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script> -->

<!-- Custom CSS for Select2 -->
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: none;
        min-height: calc(2.25rem + 2px);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        padding: 0.25rem;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        margin: 0.25rem 0.125rem;
        /* padding: 0.25rem; */
        background-color: #ecf6ff;
        /* border: 1px solid #ced4da; */
        /* border-radius: 0.25rem; */
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        margin-left: 0.25rem;
        color: #e3342f;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__clear {
        margin-top: 5px;
    }

    .input-group .select2-container {
        width: auto !important;
        flex: 1 1 auto;
    }
</style>

<!-- Inisialisasi Select2 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2-multi').select2({
            width: 'resolve', // Agar lebar sesuai dengan input-group
            placeholder: 'Pilih beberapa opsi', // Placeholder untuk multi-select
            allowClear: true, // Izinkan pengguna untuk menghapus pilihan
        });
    });
</script>