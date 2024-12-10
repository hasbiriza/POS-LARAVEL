   <select {{ $attributes->merge(['class' => 'select2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) }}>
       {{ $slot }}
   </select>

   <!-- Pastikan untuk menyertakan CSS dan JS Select2 -->
   <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script> -->

   <!-- Custom CSS for Select2 -->
   <style>
       .select2-container--default .select2-selection--single {
           border: 1px solid #ced4da;
           border-radius: 0 0.375rem 0.375rem 0;
           box-shadow: none;
           height: calc(2.25rem + 2px);
           /* Sesuaikan dengan tinggi input-group */
           line-height: 2.25rem;
       }

       .select2-container--default .select2-selection--single .select2-selection__rendered {
           padding: 0 0.75rem;
           line-height: 2.25rem;
       }

       .select2-container--default .select2-selection--single .select2-selection__arrow {
           height: calc(2.25rem + 2px);
           /* Sesuaikan dengan tinggi input-group */
           top: 0;
       }

       .input-group .select2-container {
           width: auto !important;
           flex: 1 1 auto;
       }
   </style>

   <!-- Inisialisasi Select2 -->
   <script>
       document.addEventListener('DOMContentLoaded', function() {
           $('.select2').select2({
               width: 'resolve' // Agar lebar sesuai dengan input-group
           });
       });
   </script>