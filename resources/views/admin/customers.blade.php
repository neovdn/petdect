@extends('layouts.app')

@section('header_title', 'Data Pelanggan')

@section('content')
    {{-- ALERT SUCCESS --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm relative" role="alert">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="block sm:inline">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3 text-emerald-500 hover:text-emerald-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 flex items-center justify-between hover:shadow-lg transition-all duration-300">
            <div>
                <h3 class="text-slate-400 text-sm font-medium">Total Pelanggan</h3>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalCustomers }}</p>
            </div>
            <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 flex items-center justify-between hover:shadow-lg transition-all duration-300">
            <div>
                <h3 class="text-slate-400 text-sm font-medium">Rata-rata Transaksi / User</h3>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $avgTransactions }} <span class="text-sm font-normal text-slate-400">trx</span></p>
            </div>
            <div class="h-12 w-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ACTION BAR & FILTERS --}}
    <div class="bg-white p-5 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 mb-6">
        <form action="{{ route('admin.customers') }}" method="GET" class="flex flex-col md:flex-row md:items-center gap-4 justify-between">
            
            <div class="flex flex-1 gap-4">
                <div class="relative w-full md:max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" 
                           placeholder="Cari Nama atau Kode...">
                </div>

                <div class="relative w-full md:max-w-[200px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                    </span>
                    <select name="sort" onchange="this.form.submit()" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-600 appearance-none cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                        <option value="trx_desc" {{ request('sort') == 'trx_desc' ? 'selected' : '' }}>Transaksi Terbanyak</option>
                        <option value="trx_asc" {{ request('sort') == 'trx_asc' ? 'selected' : '' }}>Transaksi Terendah</option>
                    </select>
                </div>
            </div>

            <button type="button" onclick="openModal('addCustomerModal')" class="shrink-0 inline-flex items-center justify-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pelanggan
            </button>
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 bg-slate-50/50">
                        <th class="px-6 py-4">ID Pelanggan</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4 text-center">Total Transaksi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                {{ $customer->customer_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold mr-3">
                                    {{ substr($customer->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700">{{ $customer->full_name }}</p>
                                    <p class="text-xs text-slate-400 truncate max-w-[150px]">{{ $customer->address ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600">{{ $customer->phone_number ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->transactions_count > 0 ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $customer->transactions_count }} Trx
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button 
                                    data-customer="{{ $customer }}"
                                    onclick="editCustomer(JSON.parse(this.dataset.customer))"
                                    class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" 
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                
                               <button 
                                    data-id="{{ $customer->id }}"
                                    onclick="deleteCustomer(this.dataset.id)"
                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" 
                                    title="Hapus">
                                    
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p>Belum ada data pelanggan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $customers->withQueryString()->links() }}
        </div>
    </div>

    {{-- MODAL STRUCTURE (FIXED WIDTH & CENTERED) --}}
    
    {{-- MODAL TAMBAH CUSTOMER --}}
    <div id="addCustomerModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('addCustomerModal')"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                {{-- Updated: max-w-md and mx-auto --}}
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full max-w-md mx-auto">
                    <form action="{{ route('admin.customers.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10 text-emerald-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-slate-900" id="modal-title">Tambah Pelanggan Baru</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                                            <input type="text" name="full_name" required class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm px-4 py-2 border">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Nomor Telepon</label>
                                            <input type="text" name="phone_number" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm px-4 py-2 border">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Alamat</label>
                                            <textarea name="address" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm px-4 py-2 border"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto">Simpan</button>
                            <button type="button" onclick="closeModal('addCustomerModal')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT CUSTOMER --}}
    <div id="editCustomerModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('editCustomerModal')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                {{-- Updated: max-w-md and mx-auto --}}
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full max-w-md mx-auto">
                    <form id="editCustomerForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10 text-blue-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-slate-900">Edit Pelanggan</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                                            <input type="text" id="edit_full_name" name="full_name" required class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Nomor Telepon</label>
                                            <input type="text" id="edit_phone_number" name="phone_number" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Alamat</label>
                                            <textarea id="edit_address" name="address" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Update</button>
                            <button type="button" onclick="closeModal('editCustomerModal')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DELETE CUSTOMER --}}
    <div id="deleteCustomerModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('deleteCustomerModal')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                {{-- Updated: max-w-sm and mx-auto --}}
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full max-w-sm mx-auto">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-slate-900" id="modal-title">Hapus Pelanggan</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500">Apakah Anda yakin ingin menghapus data pelanggan ini? Data transaksi yang terkait mungkin juga akan terhapus atau menjadi yatim.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="deleteCustomerForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Hapus Permanen</button>
                        </form>
                        <button type="button" onclick="closeModal('deleteCustomerModal')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Script untuk mengisi Modal Edit
        function editCustomer(customer) {
            const form = document.getElementById('editCustomerForm');
            form.action = `/admin/customers/${customer.id}`;

            document.getElementById('edit_full_name').value = customer.full_name;
            document.getElementById('edit_phone_number').value = customer.phone_number || '';
            document.getElementById('edit_address').value = customer.address || '';

            openModal('editCustomerModal');
        }

        // Script untuk mengisi Modal Delete
        function deleteCustomer(customerId) {
            const form = document.getElementById('deleteCustomerForm');
            form.action = `/admin/customers/${customerId}`;
            openModal('deleteCustomerModal');
        }
    </script>
@endsection