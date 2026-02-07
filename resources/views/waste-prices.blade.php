@extends('layouts.app')

@section('title', 'Harga Sampah - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-[#2D5016]">💰 Harga Sampah</h1>
        <p class="text-gray-600">Kelola harga sampah per kilogram</p>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-lg border-2 border-[#7FB069] overflow-hidden">
        <table class="w-full">
            <thead class="bg-[#2D5016] text-white">
                <tr>
                    <th class="px-6 py-4 text-left">Jenis Sampah</th>
                    <th class="px-6 py-4 text-left">Harga per Kg</th>
                    <th class="px-6 py-4 text-left">Terakhir Update</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wastePrices as $price)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-[#2D5016]">{{ $price->waste_type }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.waste-prices.update', $price->waste_type) }}" class="flex items-center gap-2">
                            @csrf
                            <span class="text-gray-600">Rp</span>
                            <input type="number" 
                                   name="price_per_kg" 
                                   value="{{ $price->price_per_kg }}" 
                                   class="border-2 border-gray-300 rounded px-3 py-1 w-32 focus:outline-none focus:border-[#2D5016]"
                                   required>
                            <button type="submit" 
                                    class="bg-[#7FB069] hover:bg-[#6A9957] text-white px-4 py-1 rounded transition">
                                Update
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $price->updated_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            Aktif
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 p-4 bg-blue-50 border-2 border-blue-300 rounded-lg">
        <p class="text-sm text-blue-800">
            💡 <strong>Info:</strong> Harga yang diupdate akan langsung berlaku untuk transaksi baru. Transaksi lama tetap menggunakan harga saat transaksi dilakukan.
        </p>
    </div>
    
</div>
@endsection